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

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');
require_once JPATH_ADMINISTRATOR.'/components/com_costbenefitprojection/helpers/vdm.php';

/**
*	Calculation Class
**/

class Sum{
	
	protected 	$ages;
	protected 	$genders;
	protected 	$user;
	protected 	$country;
	protected 	$totals;
	protected 	$items;
	protected 	$interventions;
	public		$vdm;
	public		$open_vdm;
		
	public function __construct()
	{
		// set the age groups
		$this->ages 					= array("15-19","20-24","25-29","30-34","35-39","40-44","45-49","50-54","55-59","60-64");
		// set the genders
		$this->genders 					= array("Males","Females");	
		// set the vault
		$this->vdm						= new Vault();
		$this->open_vdm					= new Vault(false);
	}
	
	/**
	 * Check if data is found and retrun id.
	 *
	 * @return boolean flase if not  found
	 * @return int if found
	 *
	 */
	protected function getDataId()
	{
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		
		// Order it by the ordering field.
		$query->select($db->quoteName(array('id')));
		$query->from($db->quoteName('#__costbenefitprojection_memberdata'));
		$query->where($db->quoteName('owner') . ' = '. $db->quote($this->user['id']));
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();
		if($db->getNumRows()){
			return $db->loadResult();
		} else {
			return false;
		}
	}
	
	/**
	 * Calculation based on data.
	 *
	 * @return boolean
	 *
	 */
	public function save($userId, $userProfile = false)
	{
		// set the user id
		$this->user['id'] 				= $userId;
		// set user profile data
		if(!$userProfile){
			$this->user['profile'] 		= $this->setProfile($this->user['id']);
		} else {
			$this->user['profile'] 		= $userProfile;
		}
		// get Country user id
		$this->country['user_id'] 		= $this->setCountryUserId();
		$country_gizprofile = JUserHelper::getProfile($this->country['user_id'])->gizprofile;
		
		// get country data if basic/public member
		if(!$this->user['profile']['diseases'] && !$this->user['profile']['percent_Males_15-19']){
			
			$this->user['profile']['diseases']	= $country_gizprofile['diseases'];
			
			$this->user['profile']['percent_Males_15-19']	= $country_gizprofile['percent_Males_15-19'];
			$this->user['profile']['percent_Females_15-19']	= $country_gizprofile['percent_Females_15-19'];
			$this->user['profile']['percent_Males_20-24']	= $country_gizprofile['percent_Males_20-24'];
			$this->user['profile']['percent_Females_20-24']	= $country_gizprofile['percent_Females_20-24'];
			$this->user['profile']['percent_Males_25-29']	= $country_gizprofile['percent_Males_25-29'];
			$this->user['profile']['percent_Females_25-29']	= $country_gizprofile['percent_Females_25-29'];
			$this->user['profile']['percent_Males_30-34']	= $country_gizprofile['percent_Males_30-34'];
			$this->user['profile']['percent_Females_30-34']	= $country_gizprofile['percent_Females_30-34'];
			$this->user['profile']['percent_Males_35-39']	= $country_gizprofile['percent_Males_35-39'];
			$this->user['profile']['percent_Females_35-39']	= $country_gizprofile['percent_Females_35-39'];
			$this->user['profile']['percent_Males_40-44']	= $country_gizprofile['percent_Males_40-44'];
			$this->user['profile']['percent_Females_40-44']	= $country_gizprofile['percent_Females_40-44'];
			$this->user['profile']['percent_Males_45-49']	= $country_gizprofile['percent_Males_45-49'];
			$this->user['profile']['percent_Females_45-49']	= $country_gizprofile['percent_Females_45-49'];
			$this->user['profile']['percent_Males_50-54']	= $country_gizprofile['percent_Males_50-54'];
			$this->user['profile']['percent_Females_50-54']	= $country_gizprofile['percent_Females_50-54'];
			$this->user['profile']['percent_Males_55-59']	= $country_gizprofile['percent_Males_55-59'];
			$this->user['profile']['percent_Females_55-59']	= $country_gizprofile['percent_Females_55-59'];
			$this->user['profile']['percent_Males_60-6']	= $country_gizprofile['percent_Males_60-64'];
			$this->user['profile']['percent_Females_60-64']	= $country_gizprofile['percent_Females_60-64'];
						
			$this->user['profile']['medical_turnovers_Males'] 	= $this->user['profile']['Males'] * $country_gizprofile['medical_turnovers_country']/100000;
			$this->user['profile']['medical_turnovers_Females'] = $this->user['profile']['Females'] * $country_gizprofile['medical_turnovers_country']/100000;		
			$this->user['profile']['sick_leave_Males'] 			= $this->user['profile']['Males'] * $country_gizprofile['sick_leave_country'];
			$this->user['profile']['sick_leave_Females'] 		= $this->user['profile']['Females'] * $country_gizprofile['sick_leave_country'];
			$this->user['profile']['currency'] 					= $country_gizprofile['currency'];
			$this->user['profile']['datayear'] 					= $country_gizprofile['datayear'];
			$this->user['profile']['total_healthcare'] 			= ($country_gizprofile['healthcare_country']/100) * $this->user['profile']['total_salary'];
			$this->user['profile']['working_days'] 				= $country_gizprofile['working_days'];
			$this->user['profile']['productivity_losses'] 		= $country_gizprofile['productivity_losses_country'];
			
			$basic_user = true;
		}
		if($basic_user){
			$this->user['scaling']			= false;
		} else {
			// get User Scaling Factors
			$this->user['scaling'] 			= $this->setUserScalingFactors();
		}
		// get global country totals
		$this->country['totals'] 		= $this->setCountryTotals();
		// get country default data
		$this->country['defaults'] 		= $this->setCountryDefaults();
		// get country presenteeism
		$this->country['presenteeism']	= $country_gizprofile['presenteeism_country'];
		
		// do the calculation for days lost
		if($this->setDaysLost()){
			// do the calculation for cost
			if($this->setCost()){
				// do the calculation Intervention
				$this->setInterventions();
				// load results set
				$results 								= array();
				$results['user'] 						= $this->user['profile'];
				$results['user']['id'] 					= $this->user['id'];
				$results['user']['country_user_id'] 	= $this->country['user_id'];
				$currency_details						= $this->getCurrency($this->user['profile']['currency']);
				$results['user']['currency_name']		= $currency_details['currency_name'];
				
				// sort the items now by subtotal_cost_unscaled
				usort($this->items, function($b, $a) {
					return $a['subtotal_cost_unscaled'] - $b['subtotal_cost_unscaled'];
				});
				
				$results['items'] 			= $this->items;
				$results['totals'] 			= $this->totals;
				$results['interventions'] 	= $this->interventions;
				$params 					= json_encode($results);
				$params						= $this->vdm->the($params);
				// get data id
				$data_id = $this->getDataId();
				
				// set members data
				if($data_id){
					// Create an object for the record we are going to update.
					$object = new stdClass();
					 
					// Must be a valid primary key value.
					$object->id 		= $data_id;
					$object->params 	= $params;
					 
					// Update their details in the users table using id as the primary key.
					$result = JFactory::getDbo()->updateObject('#__costbenefitprojection_memberdata', $object, 'id');
				} else {
					// Get a db connection.
					$db = JFactory::getDbo();
					 
					// Create a new query object.
					$query = $db->getQuery(true);
					 
					// Insert columns.
					$columns = array('owner', 'params');
					 
					// Insert values.
					$values = array($this->user['id'], $db->quote($params));
					 
					// Prepare the insert query.
					$query
						->insert($db->quoteName('#__costbenefitprojection_memberdata'))
						->columns($db->quoteName($columns))
						->values(implode(',', $values));
					 
					// Set the query using our newly populated query object and execute it.
					$db->setQuery($query);
					$db->query();
					
					return true;
				}
			}
		} else {
							
			// load results set
			$results 								= array();
			$results['user'] 						= $this->user['profile'];
			$results['user']['id'] 					= $this->user['id'];
			$results['user']['country_user_id'] 	= $this->country['user_id'];
			$currency_details						= $this->getCurrency($this->user['profile']['currency']);
			$results['user']['currency_name']		= $currency_details['currency_name'];
			
			// sort the items now by subtotal_cost_unscaled
			usort($this->items, function($b, $a) {
				return $a['subtotal_cost_unscaled'] - $b['subtotal_cost_unscaled'];
			});
			
			$results['items'] 						= 0;
			$results['totals'] 						= 0;
			$results['interventions'] 				= 0;
			$params 								= json_encode($results);
			$params									= $this->vdm->the($params);
			// get data id
			$data_id = $this->getDataId();
			
			// set members data
			if($data_id){
				// Create an object for the record we are going to update.
				$object = new stdClass();
				 
				// Must be a valid primary key value.
				$object->id 		= $data_id;
				$object->params 	= $params;
				 
				// Update their details in the users table using id as the primary key.
				$result = JFactory::getDbo()->updateObject('#__costbenefitprojection_memberdata', $object, 'id');
			} else {
				// Get a db connection.
				$db = JFactory::getDbo();
				 
				// Create a new query object.
				$query = $db->getQuery(true);
				 
				// Insert columns.
				$columns = array('owner', 'params');
				 
				// Insert values.
				$values = array($this->user['id'], $db->quote($params));
				 
				// Prepare the insert query.
				$query
					->insert($db->quoteName('#__costbenefitprojection_memberdata'))
					->columns($db->quoteName($columns))
					->values(implode(',', $values));
				 
				// Set the query using our newly populated query object and execute it.
				$db->setQuery($query);
				$db->query();
				
				return true;
			}
		}
		
	}
	
	/**
	 * Get the Totals for each age group in relation to the uses country.
	 *
	 * @return array
	 *
	 */
	protected function setProfile($userId)
	{
		// Load the profile data from the database.
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT profile_key, profile_value FROM #__user_profiles' .
			' WHERE user_id = '.(int) $userId." AND profile_key LIKE 'gizprofile.%'" .
			' ORDER BY ordering'
		);
		$results = $db->loadRowList();
		
		// Check for a database error.
		if ($db->getErrorNum())
		{
			$this->_subject->setError($db->getErrorMsg());
			return false;
		}
		
		// Merge the profile data.
		$gizprofile = array();
		
		foreach ($results as $v)
		{
			$k = str_replace('gizprofile.', '', $v[0]);
			$gizprofile[$k] = json_decode($v[1], true);
			if ($gizprofile[$k] === null)
			{
				$gizprofile[$k] = $v[1];
			}
		}
		
		foreach($gizprofile as $key => $value){
			$gizprofile[$key] = $this->open_vdm->the($value);
		}
		
		return $gizprofile;
	}
	
	/**
	 * Get the interventions related to this user.
	 *
	 * @return array
	 *
	 */
	protected function setInterventions()
	{
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		$interventiondata = NULL;
		
		if ($this->user['id']){
			$query
				->select('a.*')
				->from('#__costbenefitprojection_interventions AS a')
				->where('a.published = 1 AND a.owner = '.$this->user['id'].'');
		
			// echo nl2br(str_replace('#__','giz_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			$interventiondata = $db->loadAssocList();
		} 
		if ($interventiondata){
			// Convert the values from json to an array.
			foreach ($interventiondata as $key_int => $item){
				$params = json_decode($item['params']);
				$d = 0;
				$r =0;
				foreach ($params as $key => $p){
					$paramsName = explode("_", $key);
					if ($paramsName[0] == 'disease'){;
						if(is_array($interventiondata[$key_int]['data'])){
							$dname = $this->getDiseaseAlias($paramsName[2]);
							$found = false;
							foreach ($interventiondata[$key_int]['data'] as $alreadykey => $alreadyData) {
								if ($alreadyData['allias'] == $dname) {
									$location = $alreadykey;
									$found = true;
									break;
								}
							}
							
							if ($found === false) {
								$interventiondata[$key_int]['data'][$d] = array('id' => $paramsName[2], 'allias' => $this->getDiseaseAlias($paramsName[2]),$paramsName[1] => $p);
								$d++;
							} elseif ($found){
								$interventiondata[$key_int]['data'][$location] = array_merge($interventiondata[$key_int]['data'][$location], array($paramsName[1] => $p));
							}
							
						} elseif ($d == 0) {
							$interventiondata[$key_int]['data'][$d] = array('id' => $paramsName[2], 'allias' => $this->getDiseaseAlias($paramsName[2]),$paramsName[1] => $p);
							$d++;
						}
					}
				}		
			}
			// now set the result set
			if(is_array($this->user['profile']['diseases']) && count($this->user['profile']['diseases']) > 0){
				$i = 0;
				foreach ($interventiondata as $key => $item){
					$this->interventions[$i]["id"] 				= $item["intervention_id"];
					$this->interventions[$i]["name"] 			= $item["intervention_name"];
					$this->interventions[$i]["description"] 	= $item["intervention_description"];
					$this->interventions[$i]["duration"] 		= $item["duration"];
					$this->interventions[$i]["coverage"] 		= $item["coverage"];
					$this->interventions[$i]["type"] 			= $item["type"];
					$this->interventions[$i]['found']			= array();
					$this->interventions[$i]['not_found']		= array();
					// set totals
					$this->interventions[$i]['totals']['annual_cost_per_employee']				= 0;
					$this->interventions[$i]['totals']['annual_costmoney_per_employee']			= 0;
					$this->interventions[$i]['totals']['cost_of_problem_scaled'] 				= 0;
					$this->interventions[$i]['totals']['cost_of_problem_unscaled'] 				= 0;
					$this->interventions[$i]['totals']['costmoney_of_problem_scaled'] 			= 0;
					$this->interventions[$i]['totals']['costmoney_of_problem_unscaled'] 		= 0;
					$this->interventions[$i]['totals']['contribution_to_cost_scaled'] 			= 0;
					$this->interventions[$i]['totals']['contribution_to_cost_unscaled'] 		= 0;
					$this->interventions[$i]['totals']['annual_cost'] 							= 0;
					$this->interventions[$i]['totals']['annual_costmoney'] 						= 0;
					$this->interventions[$i]['totals']['annual_benefit_scaled'] 				= 0;
					$this->interventions[$i]['totals']['annual_benefit_unscaled']				= 0;
					$this->interventions[$i]['totals']['annualmoney_benefit_scaled'] 			= 0;
					$this->interventions[$i]['totals']['annualmoney_benefit_unscaled']			= 0;
					$this->interventions[$i]['totals']['net_benefit_scaled']					= 0;
					$this->interventions[$i]['totals']['net_benefit_unscaled']					= 0;
					$this->interventions[$i]['totals']['netmoney_benefit_scaled']				= 0;
					$this->interventions[$i]['totals']['netmoney_benefit_unscaled']				= 0;
					// now load the cause/risk that are linked to this user
					$a = 0;
					// set values
					foreach ($item["data"] as $key => $value){
						if(in_array($value['id'],$this->user['profile']['diseases'])){
							// set array of causes/risk ids
							$this->interventions[$i]['found'][$value['id']]							= $this->items[$value['id']]['details']['name'];
							// set local cause/risk values
							$this->interventions[$i]['items'][$a] 									= $value;
							$this->interventions[$i]['items'][$a]['name'] 							= $this->items[$value['id']]['details']['name'];
							$this->interventions[$i]['items'][$a]['cost_of_problem_unscaled'] 		= $this->items[$value['id']]['subtotal_cost_unscaled'];
							$this->interventions[$i]['items'][$a]['cost_of_problem_scaled'] 		= $this->items[$value['id']]['subtotal_cost_scaled'];
							$this->interventions[$i]['items'][$a]['costmoney_of_problem_unscaled'] 	= $this->items[$value['id']]['subtotal_costmoney_unscaled'];
							$this->interventions[$i]['items'][$a]['costmoney_of_problem_scaled'] 	= $this->items[$value['id']]['subtotal_costmoney_scaled'];
							
							$this->interventions[$i]['items'][$a]['annual_cost']	= ($this->interventions[$i]["coverage"] /100) * $value['cpe']
																					* ($this->user['profile']['Males'] + $this->user['profile']['Females']);
							// turn into money													
							$this->interventions[$i]['items'][$a]['annual_costmoney'] 	= $this->makeMoney((float)$this->interventions[$i]['items'][$a]['annual_cost']
																						, $this->user['profile']['currency']);
																							
							$this->interventions[$i]['items'][$a]['annual_benefit_unscaled']	= ($this->interventions[$i]["coverage"] /100) * ($value['mbr'] /100)
																								* ($this->items[$value['id']]['subtotal_cost_morbidity_unscaled'] 
																								+ $this->items[$value['id']]['subtotal_cost_presenteeism_unscaled'])
																								+ ($value['mtr']/100) * $this->items[$value['id']]['subtotal_cost_mortality_unscaled'];
																						
							$this->interventions[$i]['items'][$a]['annual_benefit_scaled']	= ($this->interventions[$i]["coverage"] /100) * ($value['mbr'] /100) 
																							* ($this->items[$value['id']]['subtotal_cost_morbidity_scaled'] 
																							+ $this->items[$value['id']]['subtotal_cost_presenteeism_scaled'])
																							+ ($value['mtr']/100) * $this->items[$value['id']]['subtotal_cost_mortality_scaled'];
							// turn into money													
							$this->interventions[$i]['items'][$a]['annualmoney_benefit_unscaled'] 	= $this->makeMoney((float)$this->interventions[$i]['items'][$a]['annual_benefit_unscaled']
																									, $this->user['profile']['currency']);
							$this->interventions[$i]['items'][$a]['annualmoney_benefit_scaled']		= $this->makeMoney((float)$this->interventions[$i]['items'][$a]['annual_benefit_scaled']
																									, $this->user['profile']['currency']);
																						
							$this->interventions[$i]['items'][$a]['net_benefit_unscaled'] 			= $this->interventions[$i]['items'][$a]['annual_benefit_unscaled'] 
																									- $this->interventions[$i]['items'][$a]['annual_cost'];
							$this->interventions[$i]['items'][$a]['net_benefit_scaled']				= $this->interventions[$i]['items'][$a]['annual_benefit_scaled'] 
																									- $this->interventions[$i]['items'][$a]['annual_cost'];
							// turn into money		
							$this->interventions[$i]['items'][$a]['netmoney_benefit_unscaled']		= $this->makeMoney((float)$this->interventions[$i]['items'][$a]['net_benefit_unscaled']
																									, $this->user['profile']['currency']);
							$this->interventions[$i]['items'][$a]['netmoney_benefit_scaled']		= $this->makeMoney((float)$this->interventions[$i]['items'][$a]['net_benefit_scaled']
																									, $this->user['profile']['currency']);
							// set ratio
							$this->interventions[$i]['items'][$a]['benefit_ratio_unscaled'] 		= $this->interventions[$i]['items'][$a]['annual_benefit_unscaled'] 
																									/ $this->interventions[$i]['items'][$a]['cost_of_problem_unscaled'];	
							$this->interventions[$i]['items'][$a]['benefit_ratio_scaled']			= $this->interventions[$i]['items'][$a]['annual_benefit_scaled'] 
																									/ $this->interventions[$i]['items'][$a]['cost_of_problem_scaled'];
																									
							$this->interventions[$i]['items'][$a]['annual_costmoney_per_employee']	= $this->makeMoney((float)$value['cpe'], $this->user['profile']['currency']);
							
							// set totals
							$this->interventions[$i]['totals']['annual_cost']						= $this->interventions[$i]['totals']['annual_cost']
																									+ $this->interventions[$i]['items'][$a]['annual_cost'];
							$this->interventions[$i]['totals']['annual_benefit_scaled'] 			= $this->interventions[$i]['totals']['annual_benefit_scaled'] 
																									+ $this->interventions[$i]['items'][$a]['annual_benefit_scaled'];
							$this->interventions[$i]['totals']['annual_benefit_unscaled'] 			= $this->interventions[$i]['totals']['annual_benefit_unscaled'] 
																									+ $this->interventions[$i]['items'][$a]['annual_benefit_unscaled'];
							$this->interventions[$i]['totals']['net_benefit_scaled'] 				= $this->interventions[$i]['totals']['net_benefit_scaled'] 
																									+ $this->interventions[$i]['items'][$a]['net_benefit_scaled'];
							$this->interventions[$i]['totals']['net_benefit_unscaled'] 				= $this->interventions[$i]['totals']['net_benefit_unscaled'] 
																									+ $this->interventions[$i]['items'][$a]['net_benefit_unscaled'];
							$this->interventions[$i]['totals']['annual_cost_per_employee'] 			= $this->interventions[$i]['totals']['annual_cost_per_employee'] + $value['cpe'];
							
							$this->interventions[$i]['totals']['cost_of_problem_scaled'] 			= $this->interventions[$i]['totals']['cost_of_problem_scaled'] 
																									+ $this->items[$value['id']]['subtotal_cost_scaled'];
							$this->interventions[$i]['totals']['cost_of_problem_unscaled'] 			= $this->interventions[$i]['totals']['cost_of_problem_unscaled'] 
																									+ $this->items[$value['id']]['subtotal_cost_unscaled'];
						} else {
							$this->interventions[$i]['not_found'][$value['id']] 					= $this->getDiseaseName($value['id']);
						}
						$a++;
					}
					// contribution_to_cost
					$a = 0;
					foreach ($item["data"] as $key => $value){
						if(in_array($value['id'],$this->user['profile']['diseases'])){
							
							$this->interventions[$i]['items'][$a]['contribution_to_cost_unscaled']		= ($this->interventions[$i]['items'][$a]['cost_of_problem_unscaled'] 
																										/ $this->interventions[$i]['totals']['cost_of_problem_unscaled']) * 100;
							$this->interventions[$i]['items'][$a]['contribution_to_cost_scaled']		= ($this->interventions[$i]['items'][$a]['cost_of_problem_scaled'] 
																										/ $this->interventions[$i]['totals']['cost_of_problem_scaled']) * 100;
							// set totals
							$this->interventions[$i]['totals']['contribution_to_cost_scaled'] 			= $this->interventions[$i]['totals']['contribution_to_cost_scaled'] 
																										+ $this->interventions[$i]['items'][$a]['contribution_to_cost_scaled'];
							$this->interventions[$i]['totals']['contribution_to_cost_unscaled'] 		= $this->interventions[$i]['totals']['contribution_to_cost_unscaled'] 
																										+ $this->interventions[$i]['items'][$a]['contribution_to_cost_unscaled'];
						}
						$a++;
					}
					// set total money
					$this->interventions[$i]['totals']['annual_costmoney_per_employee']			= $this->makeMoney((float)$this->interventions[$i]['totals']['annual_cost_per_employee']
																								, $this->user['profile']['currency']);
					$this->interventions[$i]['totals']['costmoney_of_problem_scaled'] 			= $this->makeMoney((float)$this->interventions[$i]['totals']['cost_of_problem_scaled']
																								, $this->user['profile']['currency']);
					$this->interventions[$i]['totals']['costmoney_of_problem_unscaled'] 		= $this->makeMoney((float)$this->interventions[$i]['totals']['cost_of_problem_unscaled']
																								, $this->user['profile']['currency']);
					$this->interventions[$i]['totals']['annual_costmoney'] 						= $this->makeMoney((float)$this->interventions[$i]['totals']['annual_cost']
																								, $this->user['profile']['currency']);
					$this->interventions[$i]['totals']['annualmoney_benefit_scaled'] 			= $this->makeMoney((float)$this->interventions[$i]['totals']['annual_benefit_scaled']
																								, $this->user['profile']['currency']);
					$this->interventions[$i]['totals']['annualmoney_benefit_unscaled']			= $this->makeMoney((float)$this->interventions[$i]['totals']['annual_benefit_unscaled']
																								, $this->user['profile']['currency']);
					$this->interventions[$i]['totals']['netmoney_benefit_scaled']				= $this->makeMoney((float)$this->interventions[$i]['totals']['net_benefit_scaled']
																								, $this->user['profile']['currency']);
					$this->interventions[$i]['totals']['netmoney_benefit_unscaled']				= $this->makeMoney((float)$this->interventions[$i]['totals']['net_benefit_unscaled']
																								, $this->user['profile']['currency']);
					$this->interventions[$i]['nr_found'] 		= sizeof($this->interventions[$i]['items']);
					$this->interventions[$i]['nr_not_found'] 	= sizeof($this->interventions[$i]['not_found']);
					$i++;
				} return true;
			} $this->interventions = false;
		} $this->interventions = false;
	}
	
	protected function getDiseaseAlias($id)
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('disease_alias');
		$query->from('#__costbenefitprojection_diseases');;
		$query->where('disease_id = \''.$id.'\'');

		$db->setQuery($query);

		$name = $db->loadResult();

		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		return $name;
	}
	
	protected function getDiseaseName($id)
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('disease_name');
		$query->from('#__costbenefitprojection_diseases');;
		$query->where('disease_id = \''.$id.'\'');

		$db->setQuery($query);

		$name = $db->loadResult();

		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		return $name;
	}
	
	/**
	 * Get the Totals for each age group in relation to the uses country.
	 *
	 * @return array
	 *
	 */
	protected function setCountryTotals()
	{
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT params FROM #__costbenefitprojection_countries' .
			' WHERE country_id = '.(int)$this->user['profile']["country"]
		);
		$totals = json_decode($db->loadResult(), true);
		
		// Check for a database error.
		if ($db->getErrorNum())
		{
			$this->_subject->setError($db->getErrorMsg());
			return false;
		}
		if (isset($totals["totals"][$this->user['profile']["datayear"]])){
			return $totals["totals"][$this->user['profile']["datayear"]];
		} 
		return false;
	}
	
	/**
	 * Get ID of country member.
	 *
	 * @return an int
	 *
	 */
	protected function setCountryUserId()
	{
		if ($this->user['profile']["country"]){
			// Get a db connection.
			$db = JFactory::getDbo();
			
			// get only country
			$groups = &JComponentHelper::getParams('com_costbenefitprojection')->get('country');
			
			$query = $db->getQuery(true);
		
			$query
				->select('map2.user_id')
				->from('#__user_usergroup_map AS map2')
				->where('map2.group_id IN ('.implode(',', $groups).')');
				// echo nl2br(str_replace('#__','giz_',$query)); die; 
				// Reset the query using our newly populated query object.
				$db->setQuery($query);
				
				$countryIds = $db->loadColumn();
				foreach($countryIds as $countryId){
					$country = $this->setProfile($countryId);
					if ($country["country"] == $this->user['profile']["country"]){
						return (int)$countryId;
					}
				}
		} 
		return false;
	}
	
	/**
	 * Get the Totals for each age group in relation to the uses country.
	 *
	 * @return array
	 *
	 */
	protected function setUserScalingFactors()
	{
		$db = JFactory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('disease_id', 'params')));
		$query->from($db->quoteName('#__costbenefitprojection_diseasedata'));
		$query->where($db->quoteName('owner') . ' = '. (int)$this->user['id']);
		$query->where($db->quoteName('published') . ' = 1');
		
		$db->setQuery($query);
		$results = $db->loadAssocList();
		
		// Check for a database error.
		if ($db->getErrorNum())
		{
			$this->_subject->setError($db->getErrorMsg());
			return false;
		}
		if(is_array($results)){
			foreach($results as $result){
				$ScalingFactors[$result["disease_id"]] = json_decode($result["params"], true);

			}
			return $ScalingFactors;
		}
		return false;
	}
	
	/**
	 * Get the default data of this country.
	 *
	 * @return array
	 *
	 */
	protected function setCountryDefaults()
	{
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('params', 'ref_id')));
		$query->from($db->quoteName('#__costbenefitprojection_defaultdata'));
		$query->where($db->quoteName('year') . ' LIKE '. (int)$this->user['profile']["datayear"]);
		$query->where($db->quoteName('country_id') . ' LIKE '. (int)$this->user['profile']["country"]);
		$query->where($db->quoteName('ref_id') . ' IN (' . implode(',', $this->user['profile']['diseases']) . ')');  
		
		$db->setQuery($query);
		$results = $db->loadObjectList();
		if($results){
			foreach ($results as $default){
				$defaults[$default->ref_id] = json_decode($default->params, true); 
			}
			return $defaults;
		}
		return $results;
	}
	
	/**
	 * Get disease data that belongs to this disease id.
	 *
	 * @return an string
	 *
	 */
	protected function getItemDetails($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		if ($id){
			$query
				->select('a.disease_id AS id, a.ref, a.disease_name AS name, a.disease_alias AS allias, b.diseasecategory_name AS category')
				->from('#__costbenefitprojection_diseases AS a')
				->join('INNER', $db->quoteName('#__costbenefitprojection_diseasecategories', 'b') . ' ON (' . $db->quoteName('a.diseasecategory_id') . ' = ' . $db->quoteName('b.diseasecategory_id') . ')')
				->where('a.disease_id = \''.$id.'\'');
		} 
		 
		// echo nl2br(str_replace('#__','giz_',$query)); die; 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		
		return $db->loadAssoc();
	}
	
	/**
	 * Do calculation for days lost.
	 *
	 * @return array
	 *
	 */
	protected function setDaysLost()
	{
		if(is_array($this->user['profile']['diseases']) && count($this->user['profile']['diseases']) > 0){
			// set global totals morbidity
			$this->totals['total_morbidity_raw'] 					= 0;
			$this->totals['total_morbidity_interim'] 				= 0;
			$this->totals['total_morbidity_scaled'] 				= 0;
			$this->totals['total_morbidity_unscaled'] 				= 0;
			// set global totals mortality
			$this->totals['total_mortality_raw'] 					= 0;
			$this->totals['total_mortality_interim']				= 0;
			$this->totals['total_mortality_scaled'] 				= 0;
			$this->totals['total_mortality_unscaled'] 				= 0;
			// set global totals presenteesim		
			$this->totals['total_presenteeism_scaled'] 				= 0;
			$this->totals['total_presenteeism_unscaled'] 			= 0;
			// set global totals day_lost_mortality
			$this->totals['total_days_lost_mortality_scaled'] 		= 0;
			$this->totals['total_days_lost_mortality_unscaled']		= 0;
			// set global totals day_lost
			$this->totals['total_days_lost_scaled']					= 0;
			$this->totals['total_days_lost_unscaled']				= 0;
			// gender totals morbidity raw
			$this->totals['Males_morbidity_raw'] 					= 0;
			$this->totals['Females_morbidity_raw'] 					= 0;
			$this->totals['Males_mortality_raw'] 					= 0;
			$this->totals['Females_mortality_raw'] 					= 0;
			$this->totals['Males_morbidity_interim'] 				= 0;
			$this->totals['Females_morbidity_interim'] 				= 0;
			$this->totals['Males_mortality_interim'] 				= 0;
			$this->totals['Females_mortality_interim'] 				= 0;
			// gender totals morbidity
			$this->totals['Males_morbidity_scaled'] 				= 0;
			$this->totals['Males_morbidity_unscaled'] 				= 0;
			$this->totals['Females_morbidity_unscaled']				= 0;
			$this->totals['Females_morbidity_scaled'] 				= 0;
			// gender totals mortality
			$this->totals['Males_mortality_scaled'] 				= 0;
			$this->totals['Males_mortality_unscaled'] 				= 0;
			$this->totals['Females_mortality_scaled'] 				= 0;
			$this->totals['Females_mortality_unscaled']				= 0;
			// gender totals presenteeism
			$this->totals['Males_presenteeism_scaled'] 				= 0;
			$this->totals['Males_presenteeism_unscaled'] 			= 0;
			$this->totals['Females_presenteeism_scaled']			= 0;
			$this->totals['Females_presenteeism_unscaled']			= 0;
			// gender totals day_lost_mortality
			$this->totals['Males_days_lost_mortality_scaled'] 		= 0;
			$this->totals['Males_days_lost_mortality_unscaled'] 	= 0;
			$this->totals['Females_days_lost_mortality_scaled']		= 0;
			$this->totals['Females_days_lost_mortality_unscaled']	= 0;
			// gender totals day_lost
			$this->totals['Males_days_lost_scaled'] 				= 0;
			$this->totals['Males_days_lost_unscaled'] 				= 0;
			$this->totals['Females_days_lost_scaled']				= 0;
			$this->totals['Females_days_lost_unscaled']				= 0;
			
			///////////////////////////////////////////////////////
			//  ------ set morbidity for each cause/risk ------  //
			///////////////////////////////////////////////////////
		
			// set morbidity_raw
			foreach($this->user['profile']['diseases'] as $id){
				
				// set caues/risk name and id to array set
				$this->items[$id]['details'] = $this->getItemDetails($id);
				
				// work with each gender
				foreach($this->genders as $gender){
					// work with each age group
					foreach($this->ages as $age){
						// set each gender and age group
						// fix missing values
						if(!isset($this->country['defaults'][$id][$gender]['yld'][$age])){
							$this->country['defaults'][$id][$gender]['yld'][$age] = 0;
						}
						$this->items[$id][$gender][$age]['yld']				= $this->country['defaults'][$id][$gender]['yld'][$age];
						$this->items[$id][$gender][$age]['morbidity_raw'] 	= ( $this->country['defaults'][$id][$gender]['yld'][$age]/100000 ) 
																			* $this->user['profile'][$gender] * ($this->user['profile']['percent_'.$gender.'_'.$age]/100);
						// set total for morbidity_raw
						$this->totals['total_morbidity_raw'] 				= $this->totals['total_morbidity_raw'] + $this->items[$id][$gender][$age]['morbidity_raw'];
						$this->totals[$gender.'_morbidity_raw'] 			= $this->totals[$gender.'_morbidity_raw'] + $this->items[$id][$gender][$age]['morbidity_raw'];
					}
				}
			}
			// set morbidity_unscaled & morbidity_interim
			foreach($this->user['profile']['diseases'] as $id){
				// work with each gender
				foreach($this->genders as $gender){
					// work with each age group
					foreach($this->ages as $age){
						// set each gender and age group morbidity_unscaled
						$this->items[$id][$gender][$age]['morbidity_unscaled'] 	= $this->items[$id][$gender][$age]['morbidity_raw'] 
																				* $this->user['profile']['sick_leave_'.$gender] / $this->totals[$gender.'_morbidity_raw'];
						// set total each cause/risk morbidity_unscaled
						$this->items[$id]['subtotal_morbidity_unscaled'] 		+= $this->items[$id][$gender][$age]['morbidity_unscaled'];
						// set total each cause/risk per gender morbidity_unscaled
						$this->items[$id][$gender.'_morbidity_unscaled'] 		+= $this->items[$id][$gender][$age]['morbidity_unscaled'];					
												
						// set total for morbidity_unscaled per gender
						$this->totals[$gender.'_morbidity_unscaled'] 			= $this->totals[$gender.'_morbidity_unscaled'] + $this->items[$id][$gender][$age]['morbidity_unscaled'];
						// set total for morbidity_unscaled
						$this->totals['total_morbidity_unscaled'] 				= $this->totals['total_morbidity_unscaled'] + $this->items[$id][$gender][$age]['morbidity_unscaled'];
						if($this->user['scaling']){
							// set each gender and age group
							$this->items[$id][$gender][$age]['morbidity_interim'] 	= $this->items[$id][$gender][$age]['morbidity_unscaled'] * $this->user['scaling'][$id]['yld_scaling_factor_'.$gender];
						} else {
							// set each gender and age group
							$this->items[$id][$gender][$age]['morbidity_interim'] 	= $this->items[$id][$gender][$age]['morbidity_unscaled'] * 1;
						}
						// set total for morbidity_interim
						$this->totals['total_morbidity_interim'] 				= $this->totals['total_morbidity_interim'] + $this->items[$id][$gender][$age]['morbidity_interim'];
						$this->totals[$gender.'_morbidity_interim'] 			= $this->totals[$gender.'_morbidity_interim'] + $this->items[$id][$gender][$age]['morbidity_interim'];
						if($this->user['scaling']){
							// set each gender and age group
							$this->items[$id][$gender][$age]['presenteeism_unscaled'] 	= $this->items[$id][$gender][$age]['morbidity_unscaled'] 
																						* $this->country['presenteeism'] * $this->user['scaling'][$id]['presenteeism_scaling_factor_'.$gender];
						} else {
							// set each gender and age group
							$this->items[$id][$gender][$age]['presenteeism_unscaled'] 	= $this->items[$id][$gender][$age]['morbidity_unscaled'] 
																						* $this->country['presenteeism'] * 1;
						}
						// set total each cause/risk presenteeism_unscaled
						$this->items[$id]['subtotal_presenteeism_unscaled'] 		+= $this->items[$id][$gender][$age]['presenteeism_unscaled'];
						// set total each cause/risk per gender morbidity_unscaled
						$this->items[$id][$gender.'_presenteeism_unscaled'] 		+= $this->items[$id][$gender][$age]['presenteeism_unscaled'];					
												
						// set total for presenteeism_unscaled per gender
						$this->totals[$gender.'_presenteeism_unscaled'] 			= $this->totals[$gender.'_presenteeism_unscaled'] + $this->items[$id][$gender][$age]['presenteeism_unscaled'];
						// set total for presenteeism_unscaled
						$this->totals['total_presenteeism_unscaled'] 				= $this->totals['total_presenteeism_unscaled'] + $this->items[$id][$gender][$age]['presenteeism_unscaled'];
					}
				}
			}
			// set morbidity_scaled
			foreach($this->user['profile']['diseases'] as $id){
				// work with each gender
				foreach($this->genders as $gender){
					// work with each age group
					foreach($this->ages as $age){
						// set each gender and age group
						$this->items[$id][$gender][$age]['morbidity_scaled'] 	= $this->items[$id][$gender][$age]['morbidity_interim'] 
																				* $this->user['profile']['sick_leave_'.$gender] / $this->totals[$gender.'_morbidity_interim'];
						// set total each cause/risk
						$this->items[$id]['subtotal_morbidity_scaled'] 			+= $this->items[$id][$gender][$age]['morbidity_scaled'];
						// set total each cause/risk per gender
						$this->items[$id][$gender.'_morbidity_scaled'] 			+= $this->items[$id][$gender][$age]['morbidity_scaled'];
						
						// set total for morbidity_scaled per gender
						$this->totals[$gender.'_morbidity_scaled'] 				= $this->totals[$gender.'_morbidity_scaled'] + $this->items[$id][$gender][$age]['morbidity_scaled'];
						// set total for morbidity_scaled
						$this->totals['total_morbidity_scaled'] 				= $this->totals['total_morbidity_scaled'] + $this->items[$id][$gender][$age]['morbidity_scaled'];
						
						if($this->user['scaling']){
							// set each gender and age group
							$this->items[$id][$gender][$age]['presenteeism_scaled'] 	= $this->items[$id][$gender][$age]['morbidity_scaled'] 
																						* $this->country['presenteeism'] * $this->user['scaling'][$id]['presenteeism_scaling_factor_'.$gender];
						} else {
							// set each gender and age group
							$this->items[$id][$gender][$age]['presenteeism_scaled'] 	= $this->items[$id][$gender][$age]['morbidity_scaled'] 
																						* $this->country['presenteeism'] * 1;
						}
						// set total each cause/risk presenteeism_scaled
						$this->items[$id]['subtotal_presenteeism_scaled'] 			+= $this->items[$id][$gender][$age]['presenteeism_scaled'];
						// set total each cause/risk per gender morbidity_scaled
						$this->items[$id][$gender.'_presenteeism_scaled'] 			+= $this->items[$id][$gender][$age]['presenteeism_scaled'];					
												
						// set total for presenteeism_scaled per gender
						$this->totals[$gender.'_presenteeism_scaled'] 				= $this->totals[$gender.'_presenteeism_scaled'] + $this->items[$id][$gender][$age]['presenteeism_scaled'];
						// set total for presenteeism_scaled
						$this->totals['total_presenteeism_scaled'] 					= $this->totals['total_presenteeism_scaled'] + $this->items[$id][$gender][$age]['presenteeism_scaled'];
					}
				}
			}
			
			///////////////////////////////////////////////////////
			//  ------ set mortality for each cause/risk ------  //
			///////////////////////////////////////////////////////
			
			// set mortality_raw
			foreach($this->user['profile']['diseases'] as $id){
				// work with each gender
				foreach($this->genders as $gender){
					// work with each age group
					foreach($this->ages as $age){
						// set each gender and age group
						// fix missing values
						if(!isset($this->country['defaults'][$id][$gender]['death'][$age])){
							$this->country['defaults'][$id][$gender]['death'][$age] = 0;
						}
						$this->items[$id][$gender][$age]['death'] 			= $this->country['defaults'][$id][$gender]['death'][$age];
						$this->items[$id][$gender][$age]['mortality_raw'] 	= ( $this->country['defaults'][$id][$gender]['death'][$age]/100000 ) 
																			* $this->user['profile'][$gender] * ($this->user['profile']['percent_'.$gender.'_'.$age]/100);
						// set total for mortality_raw
						$this->totals['total_mortality_raw'] 				= $this->totals['total_mortality_raw'] + $this->items[$id][$gender][$age]['mortality_raw'];
						$this->totals[$gender.'_mortality_raw'] 			= $this->totals[$gender.'_mortality_raw'] + $this->items[$id][$gender][$age]['mortality_raw'];
					}
				}
			}
			// set mortality_unscaled & mortality_interim
			foreach($this->user['profile']['diseases'] as $id){
				// work with each gender
				foreach($this->genders as $gender){
					// work with each age group
					foreach($this->ages as $age){
						// set each gender and age group
						$this->items[$id][$gender][$age]['mortality_unscaled'] 	= $this->items[$id][$gender][$age]['mortality_raw'] 
																				*  $this->user['profile']['medical_turnovers_'.$gender] / $this->totals[$gender.'_mortality_raw'];
						// set total each cause/risk
						$this->items[$id]['subtotal_mortality_unscaled'] 		+= $this->items[$id][$gender][$age]['mortality_unscaled'];
						// set total each cause/risk per gender
						$this->items[$id][$gender.'_mortality_unscaled'] 		+= $this->items[$id][$gender][$age]['mortality_unscaled'];
						// set total for mortality_unscaled per gender
						$this->totals[$gender.'_mortality_unscaled'] 			= $this->totals[$gender.'_mortality_unscaled'] + $this->items[$id][$gender][$age]['mortality_unscaled'];
						// set total for mortality_unscaled
						$this->totals['total_mortality_unscaled'] 				= $this->totals['total_mortality_unscaled']  + $this->items[$id][$gender][$age]['mortality_unscaled'];
						
						if($this->user['scaling']){
							// set each gender and age group
							$this->items[$id][$gender][$age]['mortality_interim'] 	= $this->items[$id][$gender][$age]['mortality_unscaled'] * $this->user['scaling'][$id]['mortality_scaling_factor_'.$gender];
						} else {
							// set each gender and age group
							$this->items[$id][$gender][$age]['mortality_interim'] 	= $this->items[$id][$gender][$age]['mortality_unscaled'] * 1;
						}
						// set total for mortality_interim
						$this->totals['total_mortality_interim'] 				= $this->totals['total_mortality_interim'] + $this->items[$id][$gender][$age]['mortality_interim'];
						$this->totals[$gender.'_mortality_interim'] 			= $this->totals[$gender.'_mortality_interim'] + $this->items[$id][$gender][$age]['mortality_interim'];
						
						// set each gender and age group days_lost_mortality_unscaled
						$this->items[$id][$gender][$age]['days_lost_mortality_unscaled'] 	= $this->items[$id][$gender][$age]['mortality_unscaled'] 
																							* $this->user['profile']['working_days'] * $this->user['profile']['productivity_losses'];
						// set total each cause/risk days_lost_mortality_unscaled
						$this->items[$id]['subtotal_days_lost_mortality_unscaled'] 		+= $this->items[$id][$gender][$age]['days_lost_mortality_unscaled'];
						// set total each cause/risk per gender days_lost_mortality_unscaled
						$this->items[$id][$gender.'_days_lost_mortality_unscaled'] 		+= $this->items[$id][$gender][$age]['days_lost_mortality_unscaled'];
						
						// set total for days_lost_mortality_unscaled per gender
						$this->totals[$gender.'_days_lost_mortality_unscaled'] 	= $this->totals[$gender.'_days_lost_mortality_unscaled'] + $this->items[$id][$gender][$age]['days_lost_mortality_unscaled'];
						// set total for days_lost_mortality_unscaled
						$this->totals['total_days_lost_mortality_unscaled'] 	= $this->totals['total_days_lost_mortality_unscaled'] + $this->items[$id][$gender][$age]['days_lost_mortality_unscaled'];
					}
				}
			}
			// set mortality_scaled
			foreach($this->user['profile']['diseases'] as $id){
				// work with each gender
				foreach($this->genders as $gender){
					// work with each age group
					foreach($this->ages as $age){
						// set each gender and age group
						$this->items[$id][$gender][$age]['mortality_scaled'] 	= $this->items[$id][$gender][$age]['mortality_interim'] 
																				* $this->user['profile']['medical_turnovers_'.$gender] / $this->totals[$gender.'_mortality_interim'];
						// set total each cause/risk
						$this->items[$id]['subtotal_mortality_scaled'] 	+= $this->items[$id][$gender][$age]['mortality_scaled'];
						// set total each cause/risk per gender
						$this->items[$id][$gender.'_mortality_scaled'] 	+= $this->items[$id][$gender][$age]['mortality_scaled'];
						// set total for mortality_scaled per gender
						$this->totals[$gender.'_mortality_scaled'] 		= $this->totals[$gender.'_mortality_scaled'] + $this->items[$id][$gender][$age]['mortality_scaled'];
						// set total for mortality_scaled
						$this->totals['total_mortality_scaled'] 		= $this->totals['total_mortality_scaled'] + $this->items[$id][$gender][$age]['mortality_scaled'];
						
						// set each gender and age group days_lost_mortality_scaled
						$this->items[$id][$gender][$age]['days_lost_mortality_scaled'] 	= $this->items[$id][$gender][$age]['mortality_scaled'] 
																						* $this->user['profile']['working_days'] * $this->user['profile']['productivity_losses'];
						// set total each cause/risk days_lost_mortality_scaled
						$this->items[$id]['subtotal_days_lost_mortality_scaled'] 		+= $this->items[$id][$gender][$age]['days_lost_mortality_scaled'];
						// set total each cause/risk per gender days_lost_mortality_scaled
						$this->items[$id][$gender.'_days_lost_mortality_scaled'] 		+= $this->items[$id][$gender][$age]['days_lost_mortality_scaled'];
						
						// set total for days_lost_mortality_scaled per gender
						$this->totals[$gender.'_days_lost_mortality_scaled'] 	= $this->totals[$gender.'_days_lost_mortality_scaled'] + $this->items[$id][$gender][$age]['days_lost_mortality_scaled'];
						// set total for days_lost_mortality_scaled
						$this->totals['total_days_lost_mortality_scaled'] 		= $this->totals['total_days_lost_mortality_scaled'] + $this->items[$id][$gender][$age]['days_lost_mortality_scaled'];
					}
				
					// set gender days lost unscaled per cause/risk
					$this->items[$id][$gender.'_days_lost_unscaled'] 	= $this->items[$id][$gender.'_morbidity_unscaled']
																		+ $this->items[$id][$gender.'_days_lost_mortality_unscaled'] 
																		+ $this->items[$id][$gender.'_presenteeism_unscaled'];
					// set gender days lost scaled per cause/risk
					$this->items[$id][$gender.'_days_lost_scaled'] 		= $this->items[$id][$gender.'_morbidity_scaled']
																		+ $this->items[$id][$gender.'_days_lost_mortality_scaled'] 
																		+ $this->items[$id][$gender.'_presenteeism_scaled'];
					// set gender global total days lost unscaled
					$this->totals[$gender.'_days_lost_unscaled'] 	= $this->totals[$gender.'_days_lost_unscaled']
																	+ $this->items[$id][$gender.'_days_lost_unscaled'];
					// set gender global total days lost scaled
					$this->totals[$gender.'_days_lost_scaled'] 	= $this->totals[$gender.'_days_lost_scaled']
																+ $this->items[$id][$gender.'_days_lost_scaled'];
				}
				
				// set subtotal days lost unscaled per cause/risk
				$this->items[$id]['subtotal_days_lost_unscaled'] 	= $this->items[$id]['subtotal_morbidity_unscaled']
																	+ $this->items[$id]['subtotal_days_lost_mortality_unscaled'] 
																	+ $this->items[$id]['subtotal_presenteeism_unscaled'];
				// set subtotal days lost scaled per cause/risk
				$this->items[$id]['subtotal_days_lost_scaled'] 		= $this->items[$id]['subtotal_morbidity_scaled']
																	+ $this->items[$id]['subtotal_days_lost_mortality_scaled'] 
																	+ $this->items[$id]['subtotal_presenteeism_scaled'];
																	
				// set global total days lost unscaled
				$this->totals['total_days_lost_unscaled'] 	= $this->totals['total_days_lost_scaled']
															+ $this->items[$id]['subtotal_days_lost_unscaled'];
				// set global total days lost scaled
				$this->totals['total_days_lost_scaled'] 	= $this->totals['total_days_lost_scaled']
															+ $this->items[$id]['subtotal_days_lost_scaled'];
			} return true;
		} return false;
	}
	
	/**
	 * Do calculation for cost.
	 *
	 * @return array
	 *
	 */
	protected function setCost()
	{
		if(is_array($this->user['profile']['diseases']) && count($this->user['profile']['diseases']) > 0){
			// the total cost in Money
			$this->totals['total_costmoney_scaled']					= 0;
			$this->totals['total_costmoney_unscaled']				= 0;
			// set global totals morbidity
			$this->totals['total_costmoney_morbidity_scaled'] 		= 0;
			$this->totals['total_costmoney_morbidity_unscaled'] 	= 0;
			// set global totals mortality
			$this->totals['total_costmoney_mortality_scaled'] 		= 0;
			$this->totals['total_costmoney_mortality_unscaled'] 	= 0;
			// set global totals presenteesim		
			$this->totals['total_costmoney_presenteeism_scaled'] 	= 0;
			$this->totals['total_costmoney_presenteeism_unscaled'] 	= 0;
			// gender totals morbidity
			$this->totals['Males_costmoney_morbidity_scaled'] 		= 0;
			$this->totals['Males_costmoney_morbidity_unscaled'] 	= 0;
			$this->totals['Females_costmoney_morbidity_unscaled']	= 0;
			$this->totals['Females_costmoney_morbidity_scaled'] 	= 0;
			// gender totals mortality
			$this->totals['Males_costmoney_mortality_scaled'] 		= 0;
			$this->totals['Males_costmoney_mortality_unscaled'] 	= 0;
			$this->totals['Females_costmoney_mortality_scaled'] 	= 0;
			$this->totals['Females_costmoney_mortality_unscaled']	= 0;
			// gender totals presenteeism
			$this->totals['Males_costmoney_presenteeism_scaled'] 	= 0;
			$this->totals['Males_costmoney_presenteeism_unscaled'] 	= 0;
			$this->totals['Females_costmoney_presenteeism_scaled']	= 0;
			$this->totals['Females_costmoney_presenteeism_unscaled']= 0;
			// gender totals
			$this->totals['Males_costmoney_scaled'] 				= 0;
			$this->totals['Males_costmoney_unscaled'] 				= 0;
			$this->totals['Females_costmoney_scaled']				= 0;
			$this->totals['Females_costmoney_unscaled']				= 0;
			
			// the total cost in Values only
			$this->totals['total_cost_scaled']					= 0;
			$this->totals['total_cost_unscaled']				= 0;
			// set global totals morbidity
			$this->totals['total_cost_morbidity_scaled'] 		= 0;
			$this->totals['total_cost_morbidity_unscaled'] 		= 0;
			// set global totals mortality
			$this->totals['total_cost_mortality_scaled'] 		= 0;
			$this->totals['total_cost_mortality_unscaled'] 		= 0;
			// set global totals presenteesim		
			$this->totals['total_cost_presenteeism_scaled'] 	= 0;
			$this->totals['total_cost_presenteeism_unscaled'] 	= 0;
			// gender totals morbidity
			$this->totals['Males_cost_morbidity_scaled'] 		= 0;
			$this->totals['Males_cost_morbidity_unscaled'] 		= 0;
			$this->totals['Females_cost_morbidity_unscaled']	= 0;
			$this->totals['Females_cost_morbidity_scaled'] 		= 0;
			// gender totals mortality
			$this->totals['Males_cost_mortality_scaled'] 		= 0;
			$this->totals['Males_cost_mortality_unscaled'] 		= 0;
			$this->totals['Females_cost_mortality_scaled'] 		= 0;
			$this->totals['Females_cost_mortality_unscaled']	= 0;
			// gender totals presenteeism
			$this->totals['Males_cost_presenteeism_scaled'] 	= 0;
			$this->totals['Males_cost_presenteeism_unscaled'] 	= 0;
			$this->totals['Females_cost_presenteeism_scaled']	= 0;
			$this->totals['Females_cost_presenteeism_unscaled']	= 0;
			// gender totals
			$this->totals['Males_cost_scaled'] 					= 0;
			$this->totals['Males_cost_unscaled'] 				= 0;
			$this->totals['Females_cost_scaled']				= 0;
			$this->totals['Females_cost_unscaled']				= 0;
			
			//////////////////////////////////////////////////
			//  ------ set cost for each cause/risk ------  //
			//////////////////////////////////////////////////
			
			// set cost_morbidity
			foreach($this->user['profile']['diseases'] as $id){
				// work with each gender
				foreach($this->genders as $gender){
					// work with each age group
					foreach($this->ages as $age){
						// unscaled ///////////////
						// set each gender and age group morbidity_unscaled
						$this->items[$id][$gender][$age]['cost_morbidity_unscaled'] 	= $this->items[$id][$gender][$age]['morbidity_unscaled']
																						* (( $this->user['profile']['total_salary'] / ($this->user['profile']['Males'] + $this->user['profile']['Females']) 
																						/ $this->user['profile']['working_days'] )
																						+ $this->user['profile']['total_healthcare'] / $this->totals['total_morbidity_unscaled']);
						// set total each cause/risk cost_morbidity_unscaled
						$this->items[$id]['subtotal_cost_morbidity_unscaled'] 		+= $this->items[$id][$gender][$age]['cost_morbidity_unscaled'];
						// set total each cause/risk per gender cost_morbidity_unscaled
						$this->items[$id][$gender.'_cost_morbidity_unscaled'] 		+= $this->items[$id][$gender][$age]['cost_morbidity_unscaled'];
																							
						// set total for cost_morbidity_unscaled
						$this->totals['total_cost_morbidity_unscaled'] 				= $this->totals['total_cost_morbidity_unscaled'] + $this->items[$id][$gender][$age]['cost_morbidity_unscaled'];
						// set total for cost_morbidity_unscaled per gender
						$this->totals[$gender.'_cost_morbidity_unscaled'] 			= $this->totals[$gender.'_cost_morbidity_unscaled'] + $this->items[$id][$gender][$age]['cost_morbidity_unscaled'];
																							
						// scaled /////////////////
						// set each gender and age group morbidity_scaled
						$this->items[$id][$gender][$age]['cost_morbidity_scaled'] 	= $this->items[$id][$gender][$age]['morbidity_scaled']
																					* (( $this->user['profile']['total_salary'] / ($this->user['profile']['Males'] + $this->user['profile']['Females']) 
																					/ $this->user['profile']['working_days'] )
																					+ $this->user['profile']['total_healthcare'] / $this->totals['total_morbidity_scaled']);
						// set total each cause/risk cost_morbidity_scaled
						$this->items[$id]['subtotal_cost_morbidity_scaled'] 			+= $this->items[$id][$gender][$age]['cost_morbidity_scaled'];
						// set total each cause/risk per gender cost_morbidity_scaled
						$this->items[$id][$gender.'_cost_morbidity_scaled'] 			+= $this->items[$id][$gender][$age]['cost_morbidity_scaled'];
						
						// set total for cost_morbidity_scaled
						$this->totals['total_cost_morbidity_scaled'] 				= $this->totals['total_cost_morbidity_scaled'] + $this->items[$id][$gender][$age]['cost_morbidity_scaled'];
						// set total for cost_morbidity_scaled per gender
						$this->totals[$gender.'_cost_morbidity_scaled'] 			= $this->totals[$gender.'_cost_morbidity_scaled'] + $this->items[$id][$gender][$age]['cost_morbidity_scaled'];
						
					}
				}
			}
			// set cost_presenteeism
			foreach($this->user['profile']['diseases'] as $id){
				// work with each gender
				foreach($this->genders as $gender){
					// work with each age group
					foreach($this->ages as $age){
						// unscaled ///////////////
						// set each gender and age group cost_presenteeism_unscaled
						$this->items[$id][$gender][$age]['cost_presenteeism_unscaled'] 	= $this->items[$id][$gender][$age]['presenteeism_unscaled']
																						* ( $this->user['profile']['total_salary'] / (( $this->user['profile']['Males'] + $this->user['profile']['Females']) 
																						* $this->user['profile']['working_days']));
						// set total each cause/risk cost_presenteeism_unscaled
						$this->items[$id]['subtotal_cost_presenteeism_unscaled'] 		+= $this->items[$id][$gender][$age]['cost_presenteeism_unscaled'];
						// set total each cause/risk per gender cost_presenteeism_unscaled
						$this->items[$id][$gender.'_cost_presenteeism_unscaled'] 		+= $this->items[$id][$gender][$age]['cost_presenteeism_unscaled'];
																							
						// set total for cost_presenteeism_unscaled
						$this->totals['total_cost_presenteeism_unscaled'] 				= $this->totals['total_cost_presenteeism_unscaled'] 
																						+ $this->items[$id][$gender][$age]['cost_presenteeism_unscaled'];
						// set total for cost_presenteeism_unscaled per gender
						$this->totals[$gender.'_cost_presenteeism_unscaled'] 			= $this->totals[$gender.'_cost_presenteeism_unscaled'] 
																						+ $this->items[$id][$gender][$age]['cost_presenteeism_unscaled'];
																							
						// scaled /////////////////
						// set each gender and age group cost_presenteeism_scaled
						$this->items[$id][$gender][$age]['cost_presenteeism_scaled'] 	= $this->items[$id][$gender][$age]['presenteeism_scaled']
																						* ( $this->user['profile']['total_salary'] / (( $this->user['profile']['Males'] + $this->user['profile']['Females']) 
																						* $this->user['profile']['working_days']));
						// set total each cause/risk cost_presenteeism_scaled
						$this->items[$id]['subtotal_cost_presenteeism_scaled'] 		+= $this->items[$id][$gender][$age]['cost_presenteeism_scaled'];
						// set total each cause/risk per gender cost_presenteeism_scaled
						$this->items[$id][$gender.'_cost_presenteeism_scaled'] 		+= $this->items[$id][$gender][$age]['cost_presenteeism_scaled'];
																							
						// set total for cost_presenteeism_scaled
						$this->totals['total_cost_presenteeism_scaled'] 			= $this->totals['total_cost_presenteeism_scaled'] 
																					+ $this->items[$id][$gender][$age]['cost_presenteeism_scaled'];
						// set total for cost_presenteeism_scaled per gender
						$this->totals[$gender.'_cost_presenteeism_scaled'] 			= $this->totals[$gender.'_cost_presenteeism_scaled'] 
																					+ $this->items[$id][$gender][$age]['cost_presenteeism_scaled'];
					}
				}
			}
			// set Cost_mortality
			foreach($this->user['profile']['diseases'] as $id){
				// work with each gender
				foreach($this->genders as $gender){
					// work with each age group
					foreach($this->ages as $age){
						// unscaled ///////////////
						// set each gender and age group cost_mortality_unscaled
						$this->items[$id][$gender][$age]['cost_mortality_unscaled'] = $this->items[$id][$gender][$age]['days_lost_mortality_unscaled']
																					* ( $this->user['profile']['total_salary'] / (( $this->user['profile']['Males'] + $this->user['profile']['Females']) 
																					* $this->user['profile']['working_days']));
						// set total each cause/risk cost_mortality_unscaled
						$this->items[$id]['subtotal_cost_mortality_unscaled'] 		+= $this->items[$id][$gender][$age]['cost_mortality_unscaled'];
						// set total each cause/risk per gender cost_mortality_unscaled
						$this->items[$id][$gender.'_cost_mortality_unscaled'] 		+= $this->items[$id][$gender][$age]['cost_mortality_unscaled'];
																							
						// set total for cost_mortality_unscaled
						$this->totals['total_cost_mortality_unscaled'] 			= $this->totals['total_cost_mortality_unscaled'] 
																				+ $this->items[$id][$gender][$age]['cost_mortality_unscaled'];
						// set total for cost_mortality_unscaled per gender
						$this->totals[$gender.'_cost_mortality_unscaled'] 		= $this->totals[$gender.'_cost_mortality_unscaled'] 
																				+ $this->items[$id][$gender][$age]['cost_mortality_unscaled'];
																							
						// scaled /////////////////
						// set each gender and age group cost_mortality_scaled
						$this->items[$id][$gender][$age]['cost_mortality_scaled'] 	= $this->items[$id][$gender][$age]['days_lost_mortality_scaled']
																					* ( $this->user['profile']['total_salary'] / (( $this->user['profile']['Males'] + $this->user['profile']['Females']) 
																					* $this->user['profile']['working_days']));
						// set total each cause/risk cost_mortality_scaled
						$this->items[$id]['subtotal_cost_mortality_scaled'] 		+= $this->items[$id][$gender][$age]['cost_mortality_scaled'];
						// set total each cause/risk per gender cost_mortality_scaled
						$this->items[$id][$gender.'_cost_mortality_scaled'] 		+= $this->items[$id][$gender][$age]['cost_mortality_scaled'];
																							
						// set total for cost_mortality_scaled
						$this->totals['total_cost_mortality_scaled'] 	= $this->totals['total_cost_mortality_scaled'] 
																		+ $this->items[$id][$gender][$age]['cost_mortality_scaled'];
						// set total for cost_mortality_scaled per gender
						$this->totals[$gender.'_cost_mortality_scaled'] = $this->totals[$gender.'_cost_mortality_scaled'] 
																		+ $this->items[$id][$gender][$age]['cost_mortality_scaled'];
					}
					
					// unscaled ///////////////
					$this->items[$id][$gender.'_cost_unscaled'] = $this->items[$id][$gender.'_cost_morbidity_unscaled']
																+ $this->items[$id][$gender.'_cost_mortality_unscaled'] 
																+ $this->items[$id][$gender.'_cost_presenteeism_unscaled'] ;
					// scaled ///////////////
					$this->items[$id][$gender.'_cost_scaled'] 	= $this->items[$id][$gender.'_cost_morbidity_scaled'] 
																+ $this->items[$id][$gender.'_cost_mortality_scaled'] 
																+ $this->items[$id][$gender.'_cost_presenteeism_scaled'];
																
					// set total for gender cost_unscaled
					$this->totals[$gender.'_cost_unscaled'] 	= $this->totals[$gender.'_cost_unscaled'] 
																+ $this->items[$id][$gender.'_cost_unscaled'];
																
					// set total for gender cost_scaled
					$this->totals[$gender.'_cost_scaled'] 	= $this->totals[$gender.'_cost_scaled'] 
															+ $this->items[$id][$gender.'_cost_scaled'];
				}
				// unscaled ///////////////
				$this->items[$id]['subtotal_cost_unscaled'] = $this->items[$id]['subtotal_cost_morbidity_unscaled'] 
															+ $this->items[$id]['subtotal_cost_mortality_unscaled'] 
															+ $this->items[$id]['subtotal_cost_presenteeism_unscaled'];
				// scaled ///////////////
				$this->items[$id]['subtotal_cost_scaled'] 	= $this->items[$id]['subtotal_cost_morbidity_scaled'] 
															+ $this->items[$id]['subtotal_cost_mortality_scaled'] 
															+ $this->items[$id]['subtotal_cost_presenteeism_scaled'];
				
				// set global total cost
				$this->totals['total_cost_unscaled']	= $this->totals['total_cost_unscaled'] + $this->items[$id]['subtotal_cost_unscaled'];
				$this->totals['total_cost_scaled']		= $this->totals['total_cost_scaled'] + $this->items[$id]['subtotal_cost_scaled'];
			}
			// turn values into money
			foreach($this->user['profile']['diseases'] as $id){
				// work with each gender
				foreach($this->genders as $gender){
					// work with each age group
					foreach($this->ages as $age){
						// set each gender and age group costmoney_morbidity_unscaled
						$this->items[$id][$gender][$age]['costmoney_morbidity_unscaled'] 	= $this->makeMoney((float)$this->items[$id][$gender][$age]['cost_morbidity_unscaled'], $this->user['profile']['currency']);
						// set each gender and age group costmoney_morbidity_scaled
						$this->items[$id][$gender][$age]['costmoney_morbidity_scaled'] 		= $this->makeMoney((float)$this->items[$id][$gender][$age]['cost_morbidity_scaled'], $this->user['profile']['currency']);
						
						// set each gender and age group costmoney_presenteeism_unscaled
						$this->items[$id][$gender][$age]['costmoney_presenteeism_unscaled'] = $this->makeMoney((float)$this->items[$id][$gender][$age]['cost_presenteeism_unscaled'],$this->user['profile']['currency']);
						// set each gender and age group costmoney_presenteeism_scaled
						$this->items[$id][$gender][$age]['costmoney_presenteeism_scaled'] 	= $this->makeMoney((float)$this->items[$id][$gender][$age]['cost_presenteeism_scaled'], $this->user['profile']['currency']);
						
						// set each gender and age group costmoney_mortality_unscaled
						$this->items[$id][$gender][$age]['costmoney_mortality_unscaled'] 	= $this->makeMoney((float)$this->items[$id][$gender][$age]['cost_mortality_unscaled'], $this->user['profile']['currency']);
						// set each gender and age group costmoney_mortality_scaled
						$this->items[$id][$gender][$age]['costmoney_mortality_scaled'] 	= $this->makeMoney((float)$this->items[$id][$gender][$age]['cost_mortality_scaled'], $this->user['profile']['currency']);					
					}
					//// --- add all cost per gender --- ////						
					// set total each cause/risk per gender costmoney_morbidity_unscaled
					$this->items[$id][$gender.'_costmoney_morbidity_unscaled'] 			= $this->makeMoney((float)$this->items[$id][$gender.'_cost_morbidity_unscaled'], $this->user['profile']['currency']);
					// set total each cause/risk per gender costmoney_morbidity_scaled
					$this->items[$id][$gender.'_costmoney_morbidity_scaled'] 			= $this->makeMoney((float)$this->items[$id][$gender.'_cost_morbidity_scaled'], $this->user['profile']['currency']);
					
					// set total each cause/risk per gender costmoney_mortality_unscaled
					$this->items[$id][$gender.'_costmoney_mortality_unscaled'] 			= $this->makeMoney((float)$this->items[$id][$gender.'_cost_mortality_unscaled'], $this->user['profile']['currency']);
					// set total each cause/risk per gender costmoney_mortality_scaled
					$this->items[$id][$gender.'_costmoney_mortality_scaled'] 			= $this->makeMoney((float)$this->items[$id][$gender.'_cost_mortality_scaled'], $this->user['profile']['currency']);
					
					// set total each cause/risk per gender costmoney_presenteeism_unscaled
					$this->items[$id][$gender.'_costmoney_presenteeism_unscaled'] 		= $this->makeMoney((float)$this->items[$id][$gender.'_cost_presenteeism_unscaled'], $this->user['profile']['currency']);
					// set total each cause/risk per gender costmoney_presenteeism_scaled
					$this->items[$id][$gender.'_costmoney_presenteeism_scaled'] 		= $this->makeMoney((float)$this->items[$id][$gender.'_cost_presenteeism_scaled'], $this->user['profile']['currency']);										
																
					// set total each subtotal_costmoney_unscale
					$this->items[$id][$gender.'_costmoney_unscaled'] 	= $this->makeMoney((float)$this->items[$id][$gender.'_cost_unscaled'], $this->user['profile']['currency']);
					// set total each subtotal_costmoney_scaled
					$this->items[$id][$gender.'_costmoney_scaled'] 		= $this->makeMoney((float)$this->items[$id][$gender.'_cost_scaled'], $this->user['profile']['currency']);	
						
					// gender totals morbidity
					$this->totals[$gender.'_costmoney_morbidity_scaled'] 		= $this->makeMoney((float)$this->totals[$gender.'_cost_morbidity_scaled'], $this->user['profile']['currency']);
					$this->totals[$gender.'_costmoney_morbidity_unscaled'] 		= $this->makeMoney((float)$this->totals[$gender.'_cost_morbidity_unscaled'], $this->user['profile']['currency']);
					// gender totals mortality
					$this->totals[$gender.'_costmoney_mortality_scaled'] 		= $this->makeMoney((float)$this->totals[$gender.'_cost_mortality_scaled'], $this->user['profile']['currency']);
					$this->totals[$gender.'_costmoney_mortality_unscaled'] 		= $this->makeMoney((float)$this->totals[$gender.'_cost_mortality_unscaled'], $this->user['profile']['currency']);
					// gender total presenteeism
					$this->totals[$gender.'_costmoney_presenteeism_unscaled'] 	= $this->makeMoney((float)$this->totals[$gender.'_cost_presenteeism_unscaled'], $this->user['profile']['currency']);
					$this->totals[$gender.'_costmoney_presenteeism_scaled'] 	= $this->makeMoney((float)$this->totals[$gender.'_cost_presenteeism_scaled'], $this->user['profile']['currency']);										
																
					// set total gender costmoney
					$this->totals[$gender.'_costmoney_unscaled'] 	= $this->makeMoney((float)$this->totals[$gender.'_cost_unscaled'], $this->user['profile']['currency']);
					$this->totals[$gender.'_costmoney_scaled'] 		= $this->makeMoney((float)$this->totals[$gender.'_cost_scaled'], $this->user['profile']['currency']);				
				}
					
				// set total each cause/risk costmoney_morbidity_unscaled
				$this->items[$id]['subtotal_costmoney_morbidity_unscaled'] 		= $this->makeMoney((float)$this->items[$id]['subtotal_cost_morbidity_unscaled'], $this->user['profile']['currency']);
				// set total each cause/risk costmoney_morbidity_scaled
				$this->items[$id]['subtotal_costmoney_morbidity_scaled'] 		= $this->makeMoney((float)$this->items[$id]['subtotal_cost_morbidity_scaled'], $this->user['profile']['currency']);
				
				// set total each cause/risk costmoney_mortality_unscaled
				$this->items[$id]['subtotal_costmoney_mortality_unscaled'] 		= $this->makeMoney((float)$this->items[$id]['subtotal_cost_mortality_unscaled'], $this->user['profile']['currency']);
				// set total each cause/risk costmoney_mortality_scaled
				$this->items[$id]['subtotal_costmoney_mortality_scaled'] 		= $this->makeMoney((float)$this->items[$id]['subtotal_cost_mortality_scaled'], $this->user['profile']['currency']);
				
				// set total each cause/risk costmoney_presenteeism_unscaled
				$this->items[$id]['subtotal_costmoney_presenteeism_unscaled'] 	= $this->makeMoney((float)$this->items[$id]['subtotal_cost_presenteeism_unscaled'], $this->user['profile']['currency']);
				// set total each cause/risk costmoney_presenteeism_scaled
				$this->items[$id]['subtotal_costmoney_presenteeism_scaled'] 	= $this->makeMoney((float)$this->items[$id]['subtotal_cost_presenteeism_scaled'], $this->user['profile']['currency']);
																			
				// set total each subtotal_costmoney_unscale
				$this->items[$id]['subtotal_costmoney_unscaled'] 	= $this->makeMoney((float)$this->items[$id]['subtotal_cost_unscaled'], $this->user['profile']['currency']);
				// set total each subtotal_costmoney_scaled
				$this->items[$id]['subtotal_costmoney_scaled'] 		= $this->makeMoney((float)$this->items[$id]['subtotal_cost_scaled'], $this->user['profile']['currency']);
			}
			
			// set global totals morbidity
			$this->totals['total_costmoney_morbidity_scaled'] 		= $this->makeMoney((float)$this->totals['total_cost_morbidity_scaled'], $this->user['profile']['currency']);
			$this->totals['total_costmoney_morbidity_unscaled'] 	= $this->makeMoney((float)$this->totals['total_cost_morbidity_unscaled'], $this->user['profile']['currency']);
			// set global totals mortality
			$this->totals['total_costmoney_mortality_scaled'] 		= $this->makeMoney((float)$this->totals['total_cost_mortality_scaled'], $this->user['profile']['currency']);
			$this->totals['total_costmoney_mortality_unscaled'] 	= $this->makeMoney((float)$this->totals['total_cost_mortality_unscaled'], $this->user['profile']['currency']);
			// set global totals presenteesim		
			$this->totals['total_costmoney_presenteeism_scaled']	= $this->makeMoney((float)$this->totals['total_cost_presenteeism_scaled'], $this->user['profile']['currency']);
			$this->totals['total_costmoney_presenteeism_unscaled'] 	= $this->makeMoney((float)$this->totals['total_cost_presenteeism_unscaled'], $this->user['profile']['currency']);
			// set global totals COST	
			$this->totals['total_costmoney_scaled']					= $this->makeMoney((float)$this->totals['total_cost_scaled'], $this->user['profile']['currency']);
			$this->totals['total_costmoney_unscaled'] 				= $this->makeMoney((float)$this->totals['total_cost_unscaled'], $this->user['profile']['currency']);
			return true;
		} return false;
	}
	
	public function getCurrency($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('t.*');
		$query->from('#__costbenefitprojection_currencies AS t');
		$query->where('currency_id = \''.$id.'\'');

		$db->setQuery($query);

		$currency = $db->loadAssocList();

		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		return $currency[0];
	}
	
	public function makeMoney($number,$currency)
	{
		if (is_numeric($number)){
			$negativeFinderObj = new NegativeFinder(new Expression("$number"));
			$negative = $negativeFinderObj->isItNegative() ? TRUE : FALSE;
		} else {
			throw new Exception('ERROR! ('.$number.') is not a number!');
		}
		$currency = $this->getCurrency($currency);
		
		if (!$negative){
			$format = $currency['currency_positive_style'];
			$sign = '+';
		} else {
			$format = $currency['currency_negative_style'];
			$sign = '-';
			$number = abs($number);
		}
		$setupNumber = number_format((float)$number, (int)$currency['currency_decimal_place'], $currency['currency_decimal_symbol'], ' '); //$currency['currency_thousands']);
		$search = array('{sign}', '{number}', '{symbol}');
		$replace = array($sign, $setupNumber, $currency['currency_symbol']);
		$moneyMade = str_replace ($search,$replace,$format);
		
		return $moneyMade;
	}
}

// Detecting negative numbers

class Expression {
    protected $expression;
    protected $result;

    public function __construct($expression) {
        $this->expression = $expression;
    }

    public function evaluate() {
        $this->result = eval("return ".$this->expression.";");
        return $this;
    }

    public function getResult() {
        return $this->result;
    }
}

class NegativeFinder {
    protected $expressionObj;

    public function __construct(Expression $expressionObj) {
        $this->expressionObj = $expressionObj;
    }


    public function isItNegative() {
        $result = $this->expressionObj->evaluate()->getResult();

        if($this->hasMinusSign($result)) {
            return true;
        } else {
            return false;
        }
    }

    protected function hasMinusSign($value) {
        return (substr(strval($value), 0, 1) == "-");
    }
}
