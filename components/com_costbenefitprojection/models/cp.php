<?php
/**
* 
* 	@version 	2.0.0 March 13, 2014
* 	@package 	Staff Health Cost Benefit Projection
* 	@author  	Vast Development Method <http://www.vdm.io>
* 	@copyright	Copyright (C) 2014 German Development Cooperation <http://www.giz.de>
* 	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
*
**/
defined( '_JEXEC' ) or die;

// Base this model on the backend version.
require_once JPATH_ADMINISTRATOR.'/components/com_costbenefitprojection/models/chart.php';
//Load the Excel module
require_once JPATH_ADMINISTRATOR.'/components/com_costbenefitprojection/helpers/PHPExcel.php';

class CostbenefitprojectionModelCp extends CostbenefitprojectionModelChart
{
	protected function populateState() 
	{
		parent::populateState();
		// Get the user id
		$user_id = JFactory::getUser()->id;
		$this->setState('user.id', $user_id);
	}
	
	public function getPdf()
	{
		$jinput = JFactory::getApplication()->input;
		$data = $jinput->post->get('key', NULL, 'BASE64');
		$item['text'] = $jinput->post->get('text', NULL, 'SAFE_HTML');
		$data = str_replace('dataimage/pngbase64', '', $data);
		$imageName = $this->getHash(10);
		// check point
		if ( base64_encode(base64_decode($data)) === $data){
			// get current date
			$item['date'] =& JFactory::getDate()->toFormat($format= '%d %b %G');
					
			// great tmp image to use in PDF
			file_put_contents('tmp/'.$imageName.'.png', base64_decode($data));
			
			// get current user
			$user = JFactory::getUser();
			
			// set Name of user
			$item['user'] = $user->name;
			
			// Include the JLog class.
			jimport('joomla.log.log');
			
			// get ip for log
			$ip = $this->getUserIP();
			
			// Add the logger.
			JLog::addLogger(
				 // Pass an array of configuration options
				array(
						// Set the name of the log file
						'text_file' => 'costbenefitprojection_downloads.php',
						// (optional) you can change the directory
						//'text_file_path' => 'logs'
				 ),
				 JLog::NOTICE
			);
			
			// start logging...
			JLog::add('Chart->['.$item['text'].'] Downloaded by ' . $user->name. '->['. $user->id.'] ip->['.$ip.']', JLog::NOTICE, 'PDF');
		
		} else {
			throw new Exception('ERROR! value not correct!');
			return false;
		}
		$item['image'] = 'tmp/'.$imageName.'.png';
		
		return $item;
	}
	
	public function getExcel()
	{
		$jinput = JFactory::getApplication()->input;
		$item['title'] = $jinput->post->get('title', NULL, 'SAFE_HTML');
		$item['csv_text'] = $jinput->post->get('csv_text', NULL, 'SAFE_HTML');
		
		// get current date
		$item['date'] =& JFactory::getDate()->toFormat($format= '%d %b %G');

		// set each row
		$table = explode(" ####BR#### ", $item['csv_text']);
		foreach ($table as $line){
			$rows[] = explode(" ##br## ", $line);
		}
		
		// get current user
		$user = JFactory::getUser();
		
		// set Name of user
		$item['user'] = $user->name;
		
		// Include the JLog class.
		jimport('joomla.log.log');
		
		// get ip for log
		$ip = $this->getUserIP();
		
		// Add the logger.
		JLog::addLogger(
			 // Pass an array of configuration options
			array(
					// Set the name of the log file
					'text_file' => 'costbenefitprojection_downloads.php',
					// (optional) you can change the directory
					//'text_file_path' => 'logs'
			 ),
			 JLog::NOTICE
		);
			
		// start logging...
		JLog::add('Table->['.$item['title'].'] Downloaded by ' . $user->name. '->['. $user->id.'] ip->['.$ip.']', JLog::NOTICE, 'EXCEL');
			
		$FileName = str_replace(array( '(', ')'), '', $item['title']);
		$FileName = preg_replace('/\s+/', '_', $FileName).'_'.preg_replace('/\s+/', '_', $item['date']);	
		
		//set all data
		$data['rows'] 		= $rows;
		$data['fileName'] 	= $FileName;
		$data['tabName']	= 'GIZ Table';
		$data['user']		= $item['user'];
		$data['title']		= $item['title'];
		
		return $data;
	}
		
	protected function getHash($length)
	{
		$pool = 'ABCDEFGHIJKLMOPQRSTUVXWYZabcdefghijklmnopqrstuvwxyz0123456789';
		$strlenPool = strlen($pool);
		$strlenPool--;
		
		$hash=NULL;
		for($i=1;$i<=$length;$i++){
			$rand = rand(0,$strlenPool);
			$hash .= substr($pool,$rand,1);
		}
		
		return $hash;
	}
		
	public function getResult()
	{
		if (!isset($this->result)) 
                {
					$user_id = $this->getState('user.id');
					
					// Add Data
					$this->result = $this->getMembersData($user_id);					
					
				}
		 return $this->result;
	}
	
	protected function getUserIP()
	{
		$ip = "";
		
		if (isset($_SERVER)) {
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
				$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
				$ip = $_SERVER["HTTP_CLIENT_IP"];
			} else {
				$ip = $_SERVER["REMOTE_ADDR"];
			}
		} else {
			if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
				$ip = getenv( 'HTTP_X_FORWARDED_FOR' );
			} elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
				$ip = getenv( 'HTTP_CLIENT_IP' );
			} else {
				$ip = getenv( 'REMOTE_ADDR' );
			}
		}
		return $ip;
    }
	
}