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

class CostbenefitprojectionTableIntervention extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__costbenefitprojection_interventions', 'intervention_id', $db);
	}
	
	public function bind($array, $ignore = '') 
	{
		//echo '<pre>';var_dump($array);die;
		if (isset($array['params']) && is_array($array['params'])) {
			// Convert the params field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($array['params']);
			$array['params'] = (string)$parameter;
		} elseif (!isset($array['risk_id'])) {
			$array['params'] = '';
		}
		
		if (isset($array['risk_id']) && is_array($array['risk_id'])) {
			$array['risk_id'] = json_encode($array['risk_id']);
		} elseif (!isset($array['risk_id'])) {
			$array['risk_id'] = '';
		}
		
		if (isset($array['cluster_id']) && is_array($array['cluster_id'])) {
			$array['cluster_id'] = json_encode($array['cluster_id']);
		} elseif (!isset($array['cluster_id'])) {
			$array['cluster_id'] = '';
		}
		
		if (isset($array['disease_id']) && is_array($array['disease_id'])) {
			$array['disease_id'] = json_encode($array['disease_id']);
		} elseif (!isset($array['disease_id'])) {
			$array['disease_id'] = '';
		}
		
		return parent::bind($array, $ignore);
	}
 
	public function load($pk = null, $reset = true) 
	{
		if (parent::load($pk, $reset)) 
		{
			// Convert the params field to a registry.
			$params = new JRegistry;
			$params->loadJSON($this->params);
						
			$this->params = $params;
			
			$this->disease_id = json_decode($this->disease_id);
			$this->risk_id = json_decode($this->risk_id);
			$this->cluster_id = json_decode($this->cluster_id);
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function check()
	{
		// get current user
		$user = JFactory::getUser();
		
		// set owner if not set for front end
		if(!$this->owner){
			$this->owner = $user->id;
		}
		
		// Check and create alias, if necessary
		if (trim($this->intervention_alias) == '' || !$this->intervention_id) {
			$this->intervention_alias = $this->owner.'-'.$this->intervention_name;
		}
		
		$this->intervention_alias = JApplication::stringURLSafe($this->intervention_alias);
		
		// To clear old selection data(Disease or Risk) from Params that now is unselected
		
		if ($this->type == 1){
			$params = json_decode($this->params);
			$risks_id = json_decode($this->risk_id);
			$diseases_id = json_decode($this->disease_id);
			$i = 0;
			foreach ($params as $key => $value){
				$the = explode('_', $key);
				if ($the[0] == 'disease' && $the[1] == 'cpe'){
					$d_P[$i] = (int)$the[2];
				} elseif ($the[0] == 'risk' && $the[1] == 'cpe'){
					$r_P[$i] = (int)$the[2];
				}
				$i++;
			}
			$i = 0;
			foreach ($d_P as $theD_P){
				if (!in_array($theD_P,$diseases_id)){
					$Dnot[$i] = (int)$theD_P;
					$i++;
				}
			}
			$i = 0;
			foreach ($r_P as $theR_P){
				if (!in_array($theR_P,$risks_id)){
					$Rnot[$i] = (int)$theR_P;
					$i++;
				}
			}
			$i = 0;
			foreach ($params as $key => $value){
				$the = explode('_', $key);
				if ($the[0] == 'disease'){
					if (is_array($Dnot)){
						if (in_array($the[2],$Dnot)){
							unset($params->$key);
						}
					}
				} elseif ($the[0] == 'risk'){
					if (is_array($Rnot)){
						if (in_array($the[2],$Rnot)){
							unset($params->$key);
						}
					}
				}
				$i++;
			}
			$this->params =	json_encode($params);
		} else {
			
			$cluster_id = json_decode($this->cluster_id);
			$array["disease_id"] = array();
			$array["risk_id"] = array();
			$i = 0;
			foreach ($cluster_id as $c_id){
				$selected[$i]  = $this->getSelectedDiseaseRisk($c_id);
				$i++;
			}
			foreach ($selected as $key => $value){
				foreach ($selected as $key_i => $value_i){
					if (is_array($value["disease_id"]) && is_array($value_i["disease_id"])){
						$array["disease_id"] =  array_merge($value["disease_id"], $value_i["disease_id"],$array["disease_id"]);
					}
					if (is_array($value["risk_id"]) && is_array($value_i["risk_id"])){
						$array["risk_id"] =  array_merge($value["risk_id"], $value_i["risk_id"],$array["risk_id"]);
					}
				}
			}
			$array_risk = array_unique($array["risk_id"]);
			if(sort($array_risk)){
				$array["risk_id"]= array();
				foreach ($array_risk as $id){
					$risks_id[] = $id;
				}
			}
			$array_disease = array_unique($array["disease_id"]);
			if(sort($array_disease)){
				$array["disease_id"] = array();
				foreach ($array_disease as $id){
					$diseases_id[] = $id;
				}
			}
			// start cleaning the data
			$params = json_decode($this->params);
			$i = 0;
			if ($this->params){
				foreach ($params as $key => $value){
					$the = explode('_', $key);
					if ($the[0] == 'disease' && $the[1] == 'cpe'){
						$d_P[$i] = (int)$the[2];
					} elseif ($the[0] == 'risk' && $the[1] == 'cpe'){
						$r_P[$i] = (int)$the[2];
					}
					$i++;
				}
				$i = 0;
				foreach ($d_P as $theD_P){
					if (!in_array($theD_P,$diseases_id)){
						$Dnot[$i] = (int)$theD_P;
						$i++;
					}
				}
				$i = 0;
				foreach ($r_P as $theR_P){
					if (!in_array($theR_P,$risks_id)){
						$Rnot[$i] = (int)$theR_P;
						$i++;
					}
				}
				$i = 0;
				foreach ($params as $key => $value){
					$the = explode('_', $key);
					if ($the[0] == 'disease'){
						if (is_array($Dnot)){
							if (in_array($the[2],$Dnot)){
								unset($params->$key);
							}
						}
					} elseif ($the[0] == 'risk'){
						if (is_array($Rnot)){
							if (in_array($the[2],$Rnot)){
								unset($params->$key);
							}
						}
					}
					$i++;
				}
				$this->params =	json_encode($params);
			}
			
			//echo '<pre>';var_dump($this->params);die;
			if( $this->params ){
				if (isset($diseases_id) && is_array($diseases_id)) {
					$this->disease_id = json_encode($diseases_id);
				} elseif (!isset($diseases_id)) {
					$this->disease_id = '';
				}
				if (isset($risks_id) && is_array($risks_id)) {
					$this->risk_id = json_encode($risks_id);
				} elseif (!isset($risks_id)) {
					$this->risk_id = '';
				}
			} else {
				$this->disease_id = '';
				$this->risk_id = '';
			}
		}
		
		$this->country = JUserHelper::getProfile($this->owner)->gizprofile[country];
		
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
		JLog::add('id->['.$this->intervention_id.'] saved by ' . $user->name.'->['. $user->id.'] ip->['.$ip.']', JLog::NOTICE, 'Intervention');
		
		// get current date
		$date =& JFactory::getDate();
		
		if ($this->intervention_id){
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
	
	protected function getSelectedDiseaseRisk($id)
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('disease_id, risk_id');
		$query->from('#__costbenefitprojection_interventions');;
		$query->where('intervention_id = \''.$id.'\'');

		$db->setQuery($query);

		$result = $db->loadAssoc();
		
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		if (!empty($result["disease_id"])){
			$selectedResult["disease_id"] = json_decode($result["disease_id"]);
		}
		if (!empty($result["risk_id"])){
			$selectedResult["risk_id"] = json_decode($result["risk_id"]);
		}
				
		return $selectedResult;
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