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

class CostbenefitprojectionTableCurrency extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__costbenefitprojection_currencies', 'currency_id', $db);
	}
	
	public function check()
	{
		// get current user
		$user = JFactory::getUser();
		
		// Include the JLog class.
		jimport('joomla.log.log');
		
		// get ip for log
		$ip = $this->getUserIP();
		
		// Add the logger.
		JLog::addLogger(
			 // Pass an array of configuration options
			array(
					// Set the name of the log file
					'text_file' => 'costbenefitprojection_saves.php',
					// (optional) you can change the directory
					//'text_file_path' => 'logs'
			 ),
			 JLog::NOTICE
		);
		
		// start logging...
		JLog::add('id->['.$this->currency_id.'] saved by ' . $user->name.'->['. $user->id.'] ip->['.$ip.']', JLog::NOTICE, 'Currency');
		
		// get current date
		$date =& JFactory::getDate();
		
		if ($this->currency_id){
			// set modified data
			$this->modified_by = $user->id;
			$this->modified_on = $date->toFormat();;
		} else {
			// set creation data
			$this->created_by = $user->id;
			$this->created_on = $date->toFormat();
		}

		return true;
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