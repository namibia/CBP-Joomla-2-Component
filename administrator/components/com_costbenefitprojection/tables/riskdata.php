<?php
/**
* 
* 	@version 	1.0.0 July 24, 2013
* 	@package 	Cost Benefit Projection Tool Application
* 	@author  	Vast Development Method <http://www.vdm.io>
* 	@copyright	Copyright (C) 2013 German Development Cooperation <http://www.giz.de>
* 	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
*
**/
defined( '_JEXEC' ) or die;

class CostbenefitprojectionTableRiskdata extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__costbenefitprojection_riskdata', 'id', $db);
	}

	public function check()
	{
		// get current user
		$user = JFactory::getUser();
		
		// set owner if not set for front end
		if(!$this->owner){
			$this->owner = $user->id;
		}
		
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
		JLog::add('id->['.$this->id.'] saved by ' . $user->name.'->['. $user->id.'] ip->['.$ip.']', JLog::NOTICE, 'Riskdata');
		
		// get current date
		$date =& JFactory::getDate();
		
		$this->country = JUserHelper::getProfile($this->owner)->gizprofile[country];
		
		if ($this->id){
			// set modified data
			$this->modified_by = $user->id;
			$this->modified_on = $date->toFormat();
			$this->checkRiskdata($this->risk_id, $this->owner, $this->id);
		} else {
			// set creation data
			$this->created_by = $user->id;
			$this->created_on = $date->toFormat();
			$this->checkRiskdata($this->risk_id, $this->owner, $this->id);
		}

		return true;
	}
	
	protected function checkRiskdata($risk_id, $owner, $id = NULL)
	{
		
		$db = JFactory::getDbo();
		
		// Get a new db connection.	
		$query = $db->getQuery(true);
		
		$query
			->select('d.id')
			->from('#__costbenefitprojection_riskdata AS d')
			->where('d.risk_id = '.$risk_id.'')
			->where('d.owner = '.$owner.'');
		 
		// echo nl2br(str_replace('#__','yvs9m_',$query)); die; 	
		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$db->query();
		$num_rows = $db->getNumRows();
		
		if($num_rows > 0){
			$result = $db->loadResult();
			if ($result != $id){
				throw new Exception(JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISKDATA_DUPLICATE_ERROR'));
			}
		}
		
		return;		
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