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

jimport('joomla.application.component.modelitem');
jimport('joomla.application.component.helper');

class CostbenefitprojectionModelChart extends JModelItem
{
	protected $item;
	protected $data;
	protected $noRiskData;
	protected $noDiseaseData;
	
	protected function populateState() 
	{
		// Get the user id
		$input = JFactory::getApplication()->input;
		$user_id = $input->getInt('id');
		$this->setState('user.id', $user_id);
		parent::populateState();
	}
	
	public function getChartTabs ()
		{
					
			// Work Days Lost
			$items[0] = array('name' => 'COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_TITLE', 'view' => 'wdl', 'img' => 'media/com_costbenefitprojection/images/charts-48.png');
			
			// Work days Lost Percent
			$items[1] = array('name' => 'COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_PERCENT', 'view' => 'wdlp', 'img' => 'media/com_costbenefitprojection/images/charts-48.png');
			
			// Cost
			$items[2] = array('name' => 'COM_COSTBENEFITPROJECTION_CHARTS_COST_TITLE', 'view' => 'c', 'img' => 'media/com_costbenefitprojection/images/charts-48.png');
			
			// Cost Percent
			$items[3] = array('name' => 'COM_COSTBENEFITPROJECTION_CHARTS_COST_PERCENT_TITLE', 'view' => 'cp', 'img' => 'media/com_costbenefitprojection/images/charts-48.png');
			
			// Intervention Cost Benefit
			$items[4] = array('name' => 'COM_COSTBENEFITPROJECTION_CHARTS_INTERVENTION_COST_BENEFIT_TITLE', 'view' => 'icb', 'img' => 'media/com_costbenefitprojection/images/charts-48.png');
			
			return $items;
		}
		
	public function getTableTabs ()
		{
					
			// Work Days Lost Summary
			$items[0] = array('name' => 'COM_COSTBENEFITPROJECTION_TABLES_WORK_DAYS_LOST_SUMMARY_TITLE', 'view' => 'wdls', 'img' => 'media/com_costbenefitprojection/images/tables-48.png');
			
			// Predicted Work Days Lost
			$items[1] = array('name' => 'COM_COSTBENEFITPROJECTION_TABLES_PERDICTED_WORK_DAYS_LOST_TITLE', 'view' => 'pwdl', 'img' => 'media/com_costbenefitprojection/images/tables-48.png');
			
			// Cost Summary
			$items[2] = array('name' => 'COM_COSTBENEFITPROJECTION_TABLES_COST_SUMMARY_TITLE', 'view' => 'cs', 'img' => 'media/com_costbenefitprojection/images/tables-48.png');
			
			// Calculated Costs in Detail
			$items[3] = array('name' => 'COM_COSTBENEFITPROJECTION_TABLES_CALCULATED_COST_IN_DETAIL_TITLE', 'view' => 'ccid', 'img' => 'media/com_costbenefitprojection/images/tables-48.png');
			
			// Intervention Net Benefit
			$items[4] = array('name' => 'COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NET_BENEFIT_TITLE', 'view' => 'inb', 'img' => 'media/com_costbenefitprojection/images/tables-48.png');
			
			return $items;
		}
		
	public function getItem()
	{
		if (!isset($this->item)) 
                {
					$id = $this->getState('user.id');
					// check point to see if the user is allowed to edit this item
					$user = $this->getUser();
					$itemOwner = $this->userIs($id);
					if($itemOwner['id']){
						if ($user['type'] != 'admin'){
							if ($user['country'] == $itemOwner['country']){
								if (($user['type'] == 'service') && ($itemOwner['service'] != $user['id'])){
									throw new Exception('ERROR! this item does not belong to you, so you may not view it. <a href="javascript:history.go(-1)">Go back</a>');
								} 
							} else {
								throw new Exception('ERROR! this item does not belong to you, so you may not view it. <a href="javascript:history.go(-1)">Go back</a>');
							}
						}
					}
					
					// Add Data
					$this->item['data']					= $this->getData();
					
					// Add Calculations Results
					$this->item['results']				= $this->getResults($this->data);
					
					// Add the items that has no data
					$this->item['noData']['disease'] 	= $this->noDiseaseData;
					$this->item['noData']['risk'] 		= $this->noRiskData;
					
					
				}
		 return $this->item;
	}
	
	protected function getData()
	{
		if (!isset($this->data)) {	
			$id = $this->getState('user.id');
			// set country user id to model item
			$this->data['countryUserId'] = $this->getCountryUserId(JUserHelper::getProfile($id)->gizprofile[country]);
			// set client id to model
			$this->data['clientId'] = (int)$id;
			// set profile results to model item
			$profileResults = JUserHelper::getProfile($id)->gizprofile;
			$this->data["diseases"] = array();
			$this->data["risks"] = array();
			foreach ($profileResults as $item => $value){
				$this->data[$item] = $value;
			}
			if($this->data["diseases"]){
				sort($this->data["diseases"]); 
			}
			if($this->data["risks"]){
				sort($this->data["risks"]); 
			}
			//set currency details
			$this->data['currency'] = $this->getCurrency($this->data['currency']);
			
			// set the data of the selected diseases
			$this->data['diseasedata'] = $this->getFinalDiseasedata($this->data['clientId'],$this->data['countryUserId'],$this->data["diseases"]);	
			
			// set the data of the selected diseases
			$this->data['riskdata'] = $this->getFinalRiskdata($this->data['clientId'],$this->data['countryUserId'],$this->data["risks"]);
			
			// set the data of the selected diseases
			$this->data['interventiondata'] = $this->getFinalInterventiondata($this->data['clientId']);				
       }
	   return $this->data;   
	}
	
	public function getUser()
	{
		// get user
		$user = JFactory::getUser();
		
		return $user = $this->userIs($user->id);
	}

	/**
	 * Get internal user info in relation to this application.
	 *
	 * @return an associative array
	 *
	 */
	protected function userIs($id)
	{
		$userIs = array();
		$userIs['id'] 	= $id;
		$userIs['country'] 	= JUserHelper::getProfile($id)->gizprofile[country];
		$userIs['groups'] 	= JUserHelper::getUserGroups($id);
		$userIs['name'] 	= JFactory::getUser($id)->name;
		
		$AppGroups['admin'] 	= &JComponentHelper::getParams('com_costbenefitprojection')->get('admin');
		$AppGroups['country'] 	= &JComponentHelper::getParams('com_costbenefitprojection')->get('country');
		$AppGroups['service'] 	= &JComponentHelper::getParams('com_costbenefitprojection')->get('service');
		$AppGroups['client'] 	= &JComponentHelper::getParams('com_costbenefitprojection')->get('client');

		$admin_user 	= (count(array_intersect($AppGroups['admin'], $userIs['groups']))) ? true : false;
		$country_user 	= (count(array_intersect($AppGroups['country'], $userIs['groups']))) ? true : false;
		$service_user 	= (count(array_intersect($AppGroups['service'], $userIs['groups']))) ? true : false;
		$client_user 	= (count(array_intersect($AppGroups['client'], $userIs['groups']))) ? true : false;
		
		if ($admin_user){
			$userIs['type'] 	= 'admin';
		} elseif ($country_user){
			$userIs['type'] 	= 'country';
		} elseif ($service_user){
			$userIs['type'] 	= 'service';
		} elseif ($client_user) {
			$userIs['type'] 	= 'client';
			$userIs['service'] 	= JUserHelper::getProfile($id)->gizprofile[serviceprovider];
		}
		
		return $userIs;
	}
	
	/**
	 * Get ID of country member.
	 *
	 * @return an int
	 *
	 */
	protected function getCountryUserId($country)
	{
		
		// Get a db connection.
		$db = JFactory::getDbo();
		
		// get only country
		$groups = &JComponentHelper::getParams('com_costbenefitprojection')->get('country');
		
		$query = $db->getQuery(true);
		
		if ($country){
			$query
				->select('a.user_id')
				->from('#__user_profiles AS a')
				->where('a.profile_key LIKE \'%gizprofile.country%\'')
				->where('a.profile_value = \'"'.$country.'"\'')
				->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = a.user_id')
				->where('map2.group_id IN ('.implode(',', $groups).')');
		} 
		// echo nl2br(str_replace('#__','yvs9m_',$query)); die; 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		
		$userId = $db->loadObject()->user_id;
		
		return (int)$userId;
	}
	
	/**
	 * Get disease data that belongs to this client or country.
	 *
	 * @return an associative array
	 *
	 */
	protected function getDiseasedata($id,$diseases)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		$diseasedata = NULL;
		
		if (is_array($diseases)){
			$query
				->select('d.disease_name, a.*')
				->from('#__costbenefitprojection_diseasedata AS a')
				->where('a.owner = \''.$id.'\'')
				->where('a.disease_id IN ('.implode(',', $diseases).')')
				->join('LEFT', '#__costbenefitprojection_diseases AS d ON d.disease_id = a.disease_id ')
				->where('a.published = 1');
		 
			// echo nl2br(str_replace('#__','yvs9m_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			$diseasedata = $db->loadAssocList();
		}
		
		return $diseasedata;
	}
	
	/**
	 * Get disease data that belongs to this disease id.
	 *
	 * @return an string
	 *
	 */
	protected function getDiseasename($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		if ($id){
			$query
				->select('a.disease_name')
				->from('#__costbenefitprojection_diseases AS a')
				->where('a.disease_id = \''.$id.'\'');
		} 
		
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		
		$disease_name = $db->loadObject()->disease_name;
		
		return $disease_name;
	}
	
	/**
	 * Get disease data that belongs to this client.
	 *
	 * @return an associative array
	 *
	 */
	protected function getFinalDiseasedata($id,$country,$diseases)
	{
		$clientdiseasedata = $this->getDiseasedata($id,$diseases);
		$countrydiseasedata = $this->getDiseasedata($country,$diseases);
		
		$i = 0;
		
		if (is_array($diseases)){
			foreach ($diseases as $disease){
	
				foreach ($clientdiseasedata as $data){
					if ($disease == $data['disease_id']){
						$resultA[$i] = $data;
						// if found remove from diseases array
						unset($diseases[array_search($data['disease_id'],$diseases)] );
					}
				}
				
				foreach ($countrydiseasedata as $data){
					if ($diseases[$i] == $data['disease_id']){
						$resultB[$i] = $data;
						// if found remove from diseases array
						unset($diseases[array_search($data['disease_id'],$diseases)] );
					}
				}
				
				$i++;	
			}
			
			if (!empty($diseases)){
				// set diseases that has no data
				$i = 0;
				$data = NULL;
				foreach ($diseases as $disease){
					if ($i == 0){
						$data['name'] .= $this->getDiseasename($disease);
						$data['nr'] =  $i + 1;
					} else {
						$data['name'] .= '; '.$this->getDiseasename($disease);
						$data['nr'] =  $i + 1;
					}
					$i++;
				}
				$this->noDiseaseData = $data;
			} else {
				$this->noDiseaseData = array( 'name' => 'none', 'nr' => 0 );
			}
		}
		 
		if(is_array($resultA)){
		   	$result_a = $resultA;
		} else {
			$result_a = array(); 
		}
		if(is_array($resultB)){
		   	$result_b = $resultB;
		} else {
			$result_b = array(); 
		}
		 
		$result = (array_merge($result_a, $result_b));

		return $result;
	}
	
	/**
	 * Get risk data that belongs to this client or country.
	 *
	 * @return an associative array
	 *
	 */
	protected function getRiskdata($id,$risks)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		$riskdata = NULL;
		
		if (is_array($risks)){
			$query
				->select('d.risk_name , a.*')
				->from('#__costbenefitprojection_riskdata AS a')
				->where('a.owner = \''.$id.'\'')
				->where('a.risk_id IN ('.implode(',', $risks).')')
				->join('LEFT', '#__costbenefitprojection_risk AS d ON d.risk_id = a.risk_id ')
				->where('a.published = 1');
		
			// echo nl2br(str_replace('#__','yvs9m_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			$riskdata = $db->loadAssocList();
		} 
		return $riskdata;
	}
	
	/**
	 * Get risk name that belongs to this risk id.
	 *
	 * @return an string
	 *
	 */
	protected function getRiskname($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		if ($id){
			$query
				->select('a.risk_name')
				->from('#__costbenefitprojection_risk AS a')
				->where('a.risk_id = \''.$id.'\'');
		} 
		
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		
		$risk_name = $db->loadObject()->risk_name;
		
		return $risk_name;
	}
	
	/**
	 * Get risk data that belongs to this client.
	 *
	 * @return an associative array
	 *
	 */
	protected function getFinalRiskdata($id,$country,$risks)
	{
		$clientriskdata = $this->getRiskdata($id,$risks);
		$countryriskdata = $this->getRiskdata($country,$risks);
		
		$i = 0;
		// $array = (array_merge($countrydiseasedata, $clientdiseasedata));
		if (is_array($risks)){
			foreach ($risks as $risk){
	
				foreach ($clientriskdata as $data){
					if ($risk == $data['risk_id']){
						$resultA[$i] = $data;
						// if found remove from diseases array
						unset($risks[array_search($data['risk_id'],$risks)] );
					}
				}
				
				foreach ($countryriskdata as $data){
					if ($risks[$i] == $data['risk_id']){
						$resultB[$i] = $data;
						// if found remove from diseases array
						unset($risks[array_search($data['risk_id'],$risks)] );
					}
				}
				
				$i++;	
			}
			
			if (!empty($risks)){
				// set risks that has no data
				$i = 0;
				$data = NULL;
				foreach ($risks as $risk){
					if ($i == 0){
						$data['name'] .= $this->getRiskname($risk);
						$data['nr'] =  $i + 1;
					} else {
						$data['name'] .= '; '.$this->getRiskname($risk);
						$data['nr'] =  $i + 1;
					}
					 $i++;
				}
				$this->noRiskData = $data;
			} else {
				$this->noRiskData = array( 'name' => 'none', 'nr' => 0 );
			}
		}
		 
		if(is_array($resultA)){
		   	$result_a = $resultA;
		} else {
			$result_a = array(); 
		}
		if(is_array($resultB)){
		   	$result_b = $resultB;
		} else {
			$result_b = array(); 
		}
		 
		$result = (array_merge($result_a, $result_b));

		return $result;
	}
	
	/**
	 * Get intervention data that belongs to this client.
	 *
	 * @return an associative array
	 *
	 */
	protected function getFinalInterventiondata($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		$interventiondata = NULL;
		
		if ($id){
			$query
				->select('a.*')
				->from('#__costbenefitprojection_interventions AS a')
				->where('a.published = 1 AND a.owner = '.$id.'');
		
			// echo nl2br(str_replace('#__','yvs9m_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			$interventiondata = $db->loadAssocList();
		} 
		if ($interventiondata){
			// Convert the vlues ftom jason to an array.
			foreach ($interventiondata as $key => &$item){
				$item['params'] = json_decode($item['params']);
				$d = 0;
				$r =0;
				foreach ($item['params'] as $key => $p){
					$paramsName = explode("_", $key);
					if ($paramsName[0] == 'disease'){
							$diseasedata = $item['diseasedata'];
							if(is_array($diseasedata)){
								$dname = $this->getDiseasenameAlias($paramsName[2]);
								$found = false;
								foreach ($diseasedata as $alreadykey => $alreadyData) {
									if ($alreadyData['name'] == $dname) {
										$loaction = $alreadykey;
										$found = true;
										break;
									}
								}
								
								if ($found === false) {
									$item['diseasedata'][$d] = array('id' => $paramsName[2], 'name' => $this->getDiseasenameAlias($paramsName[2]),$paramsName[1] => $p);
									$d++;
								} elseif ($found){
									$item['diseasedata'][$loaction] = array_merge($item['diseasedata'][$loaction], array($paramsName[1] => $p));
								}
								
							} elseif ($d == 0) {
								$item['diseasedata'][$d] = array('id' => $paramsName[2], 'name' => $this->getDiseasenameAlias($paramsName[2]),$paramsName[1] => $p);
								$d++;
							}
					} elseif ($paramsName[0] == 'risk'){
						$riskdata = $item['riskdata'];
						if(is_array($riskdata)){
							$rname = $this->getRisknameAlias($paramsName[2]);
							$found = false;
							foreach ($riskdata as $alreadykey => $alreadyData) {
								if ($alreadyData['name'] == $rname) {
									$loaction = $alreadykey;
									$found = true;
									break;
								}
							}
							
							if ($found === false) {
								$item['riskdata'][$d] = array('id' => $paramsName[2], 'name' => $this->getRisknameAlias($paramsName[2]),$paramsName[1] => $p);
								$d++;
							} elseif ($found){
								$item['riskdata'][$loaction] = array_merge($item['riskdata'][$loaction], array($paramsName[1] => $p));
							}
							
						} elseif ($r == 0) {
							$item['riskdata'][$d] = array('id' => $paramsName[2], 'name' => $this->getRisknameAlias($paramsName[2]),$paramsName[1] => $p);
							$d++;
						}
					}
				}
				$item['params'] = array( 'nr_diseases' => sizeof($item['diseasedata']), 'nr_risks' => sizeof($item['riskdata']) );			
			}
				
			return $interventiondata;
		}
	}
	
	protected function getDiseasenameAlias($id)
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
		$name = ucwords(str_replace('-',' ',$name));
		return $name;
	}
	
	protected function getRisknameAlias($id)
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('risk_alias');
		$query->from('#__costbenefitprojection_risk');
		$query->where('risk_id = \''.$id.'\'');

		$db->setQuery($query);

		$name = $db->loadResult();

		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		$name = ucwords(str_replace('-',' ',$name));
		return $name;
	}
	
	protected function getCurrency($id)
	{
		$options = array();

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
	
	protected function makeMoney($number)
	{
		if (is_numeric($number)){
			$negativeFinderObj = new NegativeFinder(new Expression("$number"));
			$negative = $negativeFinderObj->isItNegative() ? TRUE : FALSE;
		} else {
			throw new Exception('ERROR! ('.$number.') is not a number!');
		}
		$currency = $this->data['currency'];
		
		if (!$negative){
			$format = $currency['currency_positive_style'];
			$sign = '+';
		} else {
			$format = $currency['currency_negative_style'];
			$sign = '-';
			$number = abs($number);
		}
		$setupNumber = number_format((float)$number, (int)$currency['currency_decimal_place'], $currency['currency_decimal_symbol'], ' '); // $currency['currency_thousands']);
		$search = array('{sign}', '{number}', '{symbol}');
		$replace = array($sign, $setupNumber, $currency['currency_symbol']);
		$moneyMade = str_replace ($search,$replace,$format);
		
		return $moneyMade;
	}
	
	/////////////////////////////////////////////////////// CALCULATIONS //////////////////////////////////////////////////////////////
	
	protected function getResults($data)
	{
		// Calculations for Diseases
		
		$totalDaysLost 			= 0;
		$totalDaysLost_HAS_SF 	= 0;
		$totalDaysLost_HAS_OE 	= 0;
		
		$totalAbsenceDays 			= 0;
		$totalAbsenceDays_HAS_ISF 	= 0;
		$totalAbsenceDays_HAS_OE 	= 0;

		
		// days calculations
		$i = 0;
		if(!empty($data['diseasedata'])){
			foreach ($data['diseasedata'] as $disease)
			{
				$results['disease'][$i]['disease_name'] = $disease[disease_name];
				
				// male calculation values
				$number_of_episodes_m[$i] 			= (float)$data[number_male] * (float)$disease[incidence_rate_male] / 1000;
				$number_of_episodes_m_HAS_ISF[$i] 	= (((float)$data[number_male] * (float)$disease[incidence_rate_male]) / 1000) * (float)$disease[incidence_scaling_factor_male];
				$hospital_admissions_m[$i] 			= (float)$number_of_episodes_m[$i] * (float)$disease[relative_proportion];
				$hospital_admissions_m_HAS_ISF[$i] 	= (float)$number_of_episodes_m_HAS_ISF[$i] * (float)$disease[relative_proportion];
				$hospital_admissions_m_HAS_OE[$i] 	= 1 * (float)$disease[relative_proportion];
				
				// female calculation values
				$number_of_episodes_f[$i] 			= ((float)$data[number_female] * (float)$disease[incidence_rate_female]) / 1000;
				$number_of_episodes_f_HAS_ISF[$i] 	= (((float)$data[number_female] * (float)$disease[incidence_rate_female]) / 1000) * (float)$disease[incidence_scaling_factor_female];
				$hospital_admissions_f[$i]			= (float)$number_of_episodes_f[$i] * (float)$disease[relative_proportion];
				$hospital_admissions_f_HAS_ISF[$i] 	= (float)$number_of_episodes_f_HAS_ISF[$i] * (float)$disease[relative_proportion];
				
				// male
				$results['disease'][$i]['number_of_episodes_m'] 			= round((float)$number_of_episodes_m[$i] , 2);
				$results['disease'][$i]['number_of_episodes_m_HAS_ISF'] 	= round((float)$number_of_episodes_m_HAS_ISF[$i], 2);
				$results['disease'][$i]['number_of_episodes_m_HAS_OE'] 		= 1;
				$results['disease'][$i]['hospital_admissions_m'] 			= round((float)$hospital_admissions_m[$i], 2);
				$results['disease'][$i]['hospital_admissions_m_HAS_ISF'] 	= round((float)$hospital_admissions_m_HAS_ISF[$i], 2);
				$results['disease'][$i]['hospital_admissions_m_HAS_OE'] 	= round((float)$hospital_admissions_m_HAS_OE[$i], 2);
				
				// female
				$results['disease'][$i]['number_of_episodes_f'] 			= round((float)$number_of_episodes_f[$i], 2);
				$results['disease'][$i]['number_of_episodes_f_HAS_ISF'] 	= round((float)$number_of_episodes_f_HAS_ISF[$i], 2);
				$results['disease'][$i]['hospital_admissions_f'] 			= round((float)$hospital_admissions_f[$i], 2);
				$results['disease'][$i]['hospital_admissions_f_HAS_ISF'] 	= round((float)$hospital_admissions_f_HAS_ISF[$i], 2);
				
				// male
				if ($disease[disability_weights] <= 0 || $disease[duration] <= 0) {

					$absence_days_m[$i] 			= (float)$number_of_episodes_m[$i] * (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_male];
					$absence_days_m_HAS_ISF[$i] 	= (float)$number_of_episodes_m_HAS_ISF[$i] * (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_male];
					$absence_days_m_HAS_OE[$i] 		= 1 * (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_male];
					
					$results['disease'][$i]['absence_days_m'] 			= round((float)$number_of_episodes_m[$i] * (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_male], 2);
					$results['disease'][$i]['absence_days_m_HAS_ISF'] 	= round((float)$number_of_episodes_m_HAS_ISF[$i] * (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_male], 2);
					$results['disease'][$i]['absence_days_m_HAS_OE'] 	= round(1 * (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_male], 2);
				} else {
					$absence_days_m[$i] 			= (float)$disease[absenteeism_multiplication_factor_male] * (float)$disease[duration] * (float)$number_of_episodes_m[$i] ;
					$absence_days_m_HAS_ISF[$i] 	= (float)$disease[absenteeism_multiplication_factor_male] * (float)$disease[duration]* (float)$number_of_episodes_m_HAS_ISF[$i];
					$absence_days_m_HAS_OE[$i] 		= (float)$disease[absenteeism_multiplication_factor_male] * (float)$disease[duration] * 1;
					
					$results['disease'][$i]['absence_days_m'] 			= round((float)$disease[absenteeism_multiplication_factor_male] * (float)$disease[duration] * (float)$number_of_episodes_m[$i] , 2);
					$results['disease'][$i]['absence_days_m_HAS_ISF'] 	= round((float)$disease[absenteeism_multiplication_factor_male] * (float)$disease[duration]* (float)$number_of_episodes_m_HAS_ISF[$i], 2);
					$results['disease'][$i]['absence_days_m_HAS_OE'] 	= round((float)$disease[absenteeism_multiplication_factor_male] * (float)$disease[duration] * 1, 2);
				}
				
				// female
				if ($disease[disability_weights] <= 0 || $disease[duration] <= 0) {
					$absence_days_f[$i] 			= (float)$number_of_episodes_f[$i] * (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_female];
					$absence_days_f_HAS_ISF[$i] 	= (float)$number_of_episodes_f_HAS_ISF[$i] * (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_female];
					
					$results['disease'][$i]['absence_days_f'] 			= round((float)$number_of_episodes_f[$i] * (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_female], 2);
					$results['disease'][$i]['absence_days_f_HAS_ISF'] 	= round((float)$number_of_episodes_f_HAS_ISF[$i] * (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_female], 2);
				} else {
					$absence_days_f[$i] 			= (float)$disease[absenteeism_multiplication_factor_female] * (float)$disease[duration] * (float)$number_of_episodes_f[$i];
					$absence_days_f_HAS_ISF[$i] 	= (float)$disease[absenteeism_multiplication_factor_female] * (float)$disease[duration]* (float)$number_of_episodes_f_HAS_ISF[$i];
					
					$results['disease'][$i]['absence_days_f'] 			= round((float)$disease[absenteeism_multiplication_factor_female] * (float)$disease[duration] * (float)$number_of_episodes_f[$i], 2);
					$results['disease'][$i]['absence_days_f_HAS_ISF'] 	= round((float)$disease[absenteeism_multiplication_factor_female] * (float)$disease[duration]* (float)$number_of_episodes_f_HAS_ISF[$i], 2);
				}
				
				// totals
				$total_absence_days[$i] 			= (float)$absence_days_m[$i] + (float)$absence_days_f[$i];
				$total_absence_days_HAS_ISF[$i] 	= (float)$absence_days_m_HAS_ISF[$i] + (float)$absence_days_f_HAS_ISF[$i];
				$total_absence_days_HAS_OE[$i] 		= (float)$absence_days_m_HAS_OE[$i];
				
				$results['disease'][$i]['total_absence_days'] 			= round((float)$absence_days_m[$i] + (float)$absence_days_f[$i], 2);
				$results['disease'][$i]['total_absence_days_HAS_ISF'] 	= round((float)$absence_days_m_HAS_ISF[$i] + (float)$absence_days_f_HAS_ISF[$i], 2);
				$results['disease'][$i]['total_absence_days_HAS_OE'] 	= round((float)$absence_days_m_HAS_OE[$i], 2);
				
				// male
				if ($disease[disability_weights] <= 0 || $disease[duration] <= 0) {
					
					$unproductive_days_m[$i] 			= ((float)$number_of_episodes_m[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_m[$i])) 
														* (float)$disease[presenteeism_scaling_factor_male] * (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_male];
														
					$unproductive_days_m_HAS_ISF[$i] 	= ((float)$number_of_episodes_m_HAS_ISF[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_m_HAS_ISF[$i])) 
														* (float)$disease[presenteeism_scaling_factor_male] * (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_male];
														
					$unproductive_days_m_HAS_OE[$i] 	= (1 - ((float)$disease[relative_proportion] * 1)) 
														* (float)$disease[presenteeism_scaling_factor_male] * (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_male];
					
					
					$results['disease'][$i]['unproductive_days_m'] 			= round(((float)$number_of_episodes_m[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_m[$i] )) 
																			* (float)$disease[presenteeism_scaling_factor_male]* (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_male], 2);
																		
					$results['disease'][$i]['unproductive_days_m_HAS_ISF'] 	= round(((float)$number_of_episodes_m_HAS_ISF[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_m_HAS_ISF[$i])) 
																			* (float)$disease[presenteeism_scaling_factor_male]* (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_male], 2);
																			
					$results['disease'][$i]['unproductive_days_m_HAS_OE'] 	= round((1 - ((float)$disease[relative_proportion] * 1)) 
																			* (float)$disease[presenteeism_scaling_factor_male]* (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_male], 2);
					
				} else {
					
					$unproductive_days_m[$i] 			= ((float)$number_of_episodes_m[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_m[$i] )) 
														* (float)$disease[presenteeism_scaling_factor_male] * (float)$disease[absenteeism_multiplication_factor_male] * (float)$disease[duration];
														
					$unproductive_days_m_HAS_ISF[$i] 	= ((float)$number_of_episodes_m_HAS_ISF[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_m_HAS_ISF[$i])) 
														* (float)$disease[presenteeism_scaling_factor_male] * (float)$disease[absenteeism_multiplication_factor_male] * (float)$disease[duration];
														
					$unproductive_days_m_HAS_OE[$i] 	= (1 - ((float)$disease[relative_proportion] * 1)) 
														* (float)$disease[presenteeism_scaling_factor_male] * (float)$disease[absenteeism_multiplication_factor_male] * (float)$disease[duration];
								
					$results['disease'][$i]['unproductive_days_m'] 			= round(((float)$number_of_episodes_m[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_m[$i] )) 
																			* (float)$disease[presenteeism_scaling_factor_male] * (float)$disease[absenteeism_multiplication_factor_male] * (float)$disease[duration], 2);
																			
					$results['disease'][$i]['unproductive_days_m_HAS_ISF'] 	= round(((float)$number_of_episodes_m_HAS_ISF[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_m_HAS_ISF[$i])) 
																			* (float)$disease[presenteeism_scaling_factor_male] * (float)$disease[absenteeism_multiplication_factor_male] * (float)$disease[duration], 2);
																			
					$results['disease'][$i]['unproductive_days_m_HAS_OE'] 	= round((1 - ((float)$disease[relative_proportion] * 1)) 
																			* (float)$disease[presenteeism_scaling_factor_male] * (float)$disease[absenteeism_multiplication_factor_male] * (float)$disease[duration], 2);
																			
				}
				
				// female
				if ($disease[disability_weights] <= 0 || $disease[duration] <= 0) {
					
					$unproductive_days_f[$i] 			= ((float)$number_of_episodes_f[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_f[$i])) 
														* (float)$disease[presenteeism_scaling_factor_female]* (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_female];
					
					$unproductive_days_f_HAS_ISF[$i] 	= ((float)$number_of_episodes_f_HAS_ISF[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_f_HAS_ISF[$i])) 
														* (float)$disease[presenteeism_scaling_factor_female]* (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_female];
					
					$results['disease'][$i]['unproductive_days_f'] 			= round(((float)$number_of_episodes_f[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_f[$i])) 
																			* (float)$disease[presenteeism_scaling_factor_female]* (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_female], 2);
					
					$results['disease'][$i]['unproductive_days_f_HAS_ISF'] 	= round(((float)$number_of_episodes_f_HAS_ISF[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_f_HAS_ISF[$i])) 
																			* (float)$disease[presenteeism_scaling_factor_female]* (float)$disease[hospital_stay] * (float)$disease[hospital_scaling_factor_female], 2);
				} else {
					
					$unproductive_days_f[$i] 			= ((float)$number_of_episodes_f[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_f[$i])) 
														* (float)$disease[presenteeism_scaling_factor_female] * (float)$disease[absenteeism_multiplication_factor_female] 
														* (float)$disease[duration];
					
					$unproductive_days_f_HAS_ISF[$i] 	= ((float)$number_of_episodes_f_HAS_ISF[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_f_HAS_ISF[$i])) 
														* (float)$disease[presenteeism_scaling_factor_female] * (float)$disease[absenteeism_multiplication_factor_female] 
														* (float)$disease[duration];
																			
					$results['disease'][$i]['unproductive_days_f'] 			= round(((float)$number_of_episodes_f[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_f[$i])) 
																			* (float)$disease[presenteeism_scaling_factor_female] * (float)$disease[absenteeism_multiplication_factor_female] 
																			* (float)$disease[duration], 2);
					
					$results['disease'][$i]['unproductive_days_f_HAS_ISF'] 	= round(((float)$number_of_episodes_f_HAS_ISF[$i] - ((float)$disease[relative_proportion] * (float)$number_of_episodes_f_HAS_ISF[$i])) 
																			* (float)$disease[presenteeism_scaling_factor_female] * (float)$disease[absenteeism_multiplication_factor_female] 
																			* (float)$disease[duration], 2);
				}
				
				// totals
				$total_unproductive_days[$i] 			= (float)$unproductive_days_m[$i] + (float)$unproductive_days_f[$i];
				$total_unproductive_days_HAS_ISF[$i] 	= (float)$unproductive_days_m_HAS_ISF[$i] + (float)$unproductive_days_f_HAS_ISF[$i];
				$total_unproductive_days_HAS_OE[$i] 	= (float)$unproductive_days_m_HAS_OE[$i];
				
				$results['disease'][$i]['total_unproductive_days'] 			= round((float)$unproductive_days_m[$i] + (float)$unproductive_days_f[$i], 2);
				$results['disease'][$i]['total_unproductive_days_HAS_ISF'] 	= round((float)$unproductive_days_m_HAS_ISF[$i] + (float)$unproductive_days_f_HAS_ISF[$i], 2);
				$results['disease'][$i]['total_unproductive_days_HAS_OE'] 	= round((float)$unproductive_days_m_HAS_OE[$i], 2);
				
				// male calculation values
				$number_of_deaths_m[$i] 			= (float)$data[number_male] * (float)$disease[mortality_rate_male] / 1000;
				$number_of_deaths_m_HAS_MSF[$i] 	= ((float)$data[number_male] * (float)$disease[mortality_rate_male] / 1000) * (float)$disease[mortality_scaling_factor_male];
				$death_absence_days_m[$i] 			= (float)$number_of_deaths_m[$i]  * (float)$data[working_days] * (float)$data[productivity_losses];
				$death_absence_days_m_HAS_MSF[$i] 	= (float)$number_of_deaths_m_HAS_MSF[$i]  * (float)$data[working_days] * (float)$data[productivity_losses];
				$death_absence_days_m_HAS_OE[$i] 	= (float)1  * (float)$data[working_days] * (float)$data[productivity_losses];
				
	
				// female calculation values
				$number_of_deaths_f[$i] 			= (float)$data[number_female] * (float)$disease[mortality_rate_female] / 1000;
				$number_of_deaths_f_HAS_MSF[$i] 	= ((float)$data[number_female] * (float)$disease[mortality_rate_female] / 1000) * (float)$disease[mortality_scaling_factor_female];
				$death_absence_days_f[$i] 			= (float)$number_of_deaths_f[$i] * (float)$data[working_days] * (float)$data[productivity_losses];
				$death_absence_days_f_HAS_MSF[$i] 	= (float)$number_of_deaths_f_HAS_MSF[$i] * (float)$data[working_days] * (float)$data[productivity_losses];
				
				// male
				$results['disease'][$i]['number_of_deaths_m'] 					= round((float)$data[number_male] * (float)$disease[mortality_rate_male] / 1000, 2);
				$results['disease'][$i]['number_of_deaths_m_HAS_MSF'] 			= round(((float)$data[number_male] * (float)$disease[mortality_rate_male] / 1000) * (float)$disease[mortality_scaling_factor_male], 2);
				$results['disease'][$i]['number_of_deaths_m_HAS_OE'] 			= 1;
				$results['disease'][$i]['death_absence_days_m'] 				= round((float)$number_of_deaths_m[$i]  * (float)$data[working_days] * (float)$data[productivity_losses], 2);
				$results['disease'][$i]['death_absence_days_m_HAS_MSF'] 		= round((float)$number_of_deaths_m_HAS_MSF[$i]  * (float)$data[working_days] * (float)$data[productivity_losses], 2);
				$results['disease'][$i]['death_absence_days_m_HAS_OE'] 			= round((float)1  * (float)$data[working_days] * (float)$data[productivity_losses], 2);
				
				// female
				$results['disease'][$i]['number_of_deaths_f'] 					= round((float)$data[number_female] * (float)$disease[mortality_rate_female] / 1000, 2);
				$results['disease'][$i]['number_of_deaths_f_HAS_MSF'] 			= round(((float)$data[number_female] * (float)$disease[mortality_rate_female] / 1000) * (float)$disease[mortality_scaling_factor_female], 2);
				$results['disease'][$i]['death_absence_days_f'] 				= round((float)$number_of_deaths_f[$i] * (float)$data[working_days] * (float)$data[productivity_losses], 2);
				$results['disease'][$i]['death_absence_days_f_HAS_MSF'] 		= round((float)$number_of_deaths_f_HAS_MSF[$i] * (float)$data[working_days] * (float)$data[productivity_losses], 2);
				
				// totals
				$total_death_absence_days[$i] 			= (float)$death_absence_days_m[$i] + (float)$death_absence_days_f[$i];
				$total_death_absence_days_HAS_MSF[$i] 	= (float)$death_absence_days_m_HAS_MSF[$i] + (float)$death_absence_days_f_HAS_MSF[$i];
				$total_death_absence_days_HAS_OE[$i] 	= (float)$death_absence_days_m_HAS_OE[$i];
				
				$results['disease'][$i]['total_death_absence_days'] 			= round((float)$death_absence_days_m[$i] + (float)$death_absence_days_f[$i], 2);
				$results['disease'][$i]['total_death_absence_days_HAS_MSF'] 	= round((float)$death_absence_days_m_HAS_MSF[$i] + (float)$death_absence_days_f_HAS_MSF[$i], 2);
				$results['disease'][$i]['total_death_absence_days_HAS_OE'] 		= (float)$death_absence_days_m_HAS_OE[$i];
							
				$total_days_lost[$i] 		= (float)$total_death_absence_days[$i] 
											+ (float)$total_absence_days[$i] 
											+ (float)$total_unproductive_days[$i];
																	
				$total_days_lost_HAS_SF[$i] = (float)$total_death_absence_days_HAS_MSF[$i] 
											+ (float)$total_absence_days_HAS_ISF[$i] 
											+ (float)$total_unproductive_days_HAS_ISF[$i];
																	
				$total_days_lost_HAS_OE[$i] = (float)$total_death_absence_days_HAS_OE[$i]
											+ (float)$total_absence_days_HAS_OE[$i] 
											+ (float)$total_unproductive_days_HAS_OE[$i];
																	
				$results['disease'][$i]['total_days_lost'] 			= round((float)$total_death_absence_days[$i] 
																	+ (float)$total_absence_days[$i] 
																	+ (float)$total_unproductive_days[$i], 2);
																	
				$results['disease'][$i]['total_days_lost_HAS_SF'] 	= round((float)$total_death_absence_days_HAS_MSF[$i] 
																	+ (float)$total_absence_days_HAS_ISF[$i] 
																	+ (float)$total_unproductive_days_HAS_ISF[$i], 2);
																	
				$results['disease'][$i]['total_days_lost_HAS_OE'] 	= round((float)$total_death_absence_days_HAS_OE[$i]
																	+ (float)$total_absence_days_HAS_OE[$i] 
																	+ (float)$total_unproductive_days_HAS_OE[$i], 2);
				
				// global totals
				$totalDaysLost 			+= (float)$total_days_lost[$i];
				$totalDaysLost_HAS_SF 	+= (float)$total_days_lost_HAS_SF[$i];
				$totalDaysLost_HAS_OE 	+= (float)$total_days_lost_HAS_OE[$i];
				
				$totalAbsenceDays 			+= (float)$total_absence_days[$i];
				$totalAbsenceDays_HAS_ISF 	+= (float)$total_absence_days_HAS_ISF[$i];
				$totalAbsenceDays_HAS_OE 	+= (float)$total_absence_days_HAS_OE[$i];
				
				$i++;
			}
		}
		
		// cost calculations
		$i = 0;
		$totalCostSickness_m 		= 0;
		$totalCostSickness_m_HAS_SF = 0;
		$totalCostSickness_f 		= 0;
		$totalCostSickness_f_HAS_SF = 0;
		$totalCostSickness 			= 0;
		$totalCostSickness_HAS_SF 	= 0;
		$totalCostSickness_HAS_OE 	= 0;
		
		$totalCostPresenteeism_m 		= 0;
		$totalCostPresenteeism_m_HAS_SF = 0;
		$totalCostPresenteeism_f 		= 0;
		$totalCostPresenteeism_f_HAS_SF = 0;
		$totalCostPresenteeism 			= 0;
		$totalCostPresenteeism_HAS_SF 	= 0;
		$totalCostPresenteeism_HAS_OE 	= 0;
		
		$totalCostDeath_m 			= 0;
		$totalCostDeath_m_HAS_SF 	= 0;
		$totalCostDeath_f 			= 0;
		$totalCostDeath_f_HAS_SF 	= 0;
		$totalCostDeath 			= 0;
		$totalCostDeath_HAS_SF 		= 0;
		$totalCostDeath_HAS_OE 		= 0;
		if(!empty($data['diseasedata'])){
			foreach ($data['diseasedata'] as $disease)
			{
				// COST CALCULATIONS
				
				// cost sickness calculations male
				$cost_sickness_m[$i] 			= ((float)$absence_days_m[$i] * (float)$data[total_salary]) / (((float)$data[number_male] + (float)$data[number_female]) 
												* (float)$data[working_days]) + (((float)$absence_days_m[$i] * (float)$data[total_healthcare]) /  $totalAbsenceDays);
												
				$cost_sickness_m_HAS_SF[$i] 	= ((float)$absence_days_m_HAS_ISF[$i] * (float)$data[total_salary]) / (((float)$data[number_male] + (float)$data[number_female]) 
												* (float)$data[working_days]) + (((float)$absence_days_m_HAS_ISF[$i] * (float)$data[total_healthcare]) /  $totalAbsenceDays_HAS_ISF);
												
				$cost_sickness_m_HAS_OE[$i] 	= ((float)$absence_days_m_HAS_OE[$i] * (float)$data[total_salary]) / (((float)$data[number_male] + (float)$data[number_female]) 
												* (float)$data[working_days]) + (((float)$absence_days_m_HAS_OE[$i] * (float)$data[total_healthcare]) /  $totalAbsenceDays_HAS_OE);
				
				// cost sickness calculations male AS money
				$results['disease'][$i]['cost_sickness_money_m'] 		= $this->makeMoney((float)$cost_sickness_m[$i]);
				$results['disease'][$i]['cost_sickness_money_m_HAS_SF'] = $this->makeMoney((float)$cost_sickness_m_HAS_SF[$i]);
				$results['disease'][$i]['cost_sickness_money_m_HAS_OE'] = $this->makeMoney((float)$cost_sickness_m_HAS_OE[$i]);
				
				// cost sickness calculations male AS values
				$results['disease'][$i]['cost_sickness_m'] 			= round((float)$cost_sickness_m[$i], 2);												
				$results['disease'][$i]['cost_sickness_m_HAS_SF'] 	= round((float)$cost_sickness_m_HAS_SF[$i], 2);													
				$results['disease'][$i]['cost_sickness_m_HAS_OE'] 	= round((float)$cost_sickness_m_HAS_OE[$i], 2);
				
				// cost sickness calculations female
				$cost_sickness_f[$i] 			= ((float)$absence_days_f[$i] * (float)$data[total_salary]) / (((float)$data[number_male] + (float)$data[number_female]) 
												* (float)$data[working_days]) + (((float)$absence_days_f[$i] * (float)$data[total_healthcare]) /  $totalAbsenceDays);
												
				$cost_sickness_f_HAS_SF[$i] 	= ((float)$absence_days_f_HAS_ISF[$i] * (float)$data[total_salary]) / (((float)$data[number_male] + (float)$data[number_female]) 
												* (float)$data[working_days]) + (((float)$absence_days_f_HAS_ISF[$i] * (float)$data[total_healthcare]) /  $totalAbsenceDays_HAS_ISF);
				
				// cost sickness calculations female AS money
				$results['disease'][$i]['cost_sickness_money_f'] 		= $this->makeMoney((float)$cost_sickness_f[$i]);
				$results['disease'][$i]['cost_sickness_money_f_HAS_SF'] = $this->makeMoney((float)$cost_sickness_f_HAS_SF[$i]);
				
				// cost sickness calculations female AS values
				$results['disease'][$i]['cost_sickness_f'] 			= round((float)$cost_sickness_f[$i], 2);													
				$results['disease'][$i]['cost_sickness_f_HAS_SF'] 	= round((float)$cost_sickness_f_HAS_SF[$i], 2);
				
				// cost sickness calculations totals
				$total_cost_sickness[$i] 			= (float)$cost_sickness_m[$i] + (float)$cost_sickness_f[$i];
				$total_cost_sickness_HAS_SF[$i] 	= (float)$cost_sickness_m_HAS_SF[$i] + (float)$cost_sickness_f_HAS_SF[$i];
				$total_cost_sickness_HAS_OE[$i] 	= (float)$cost_sickness_m_HAS_OE[$i];
				
				// cost sickness calculations totals AS money
				$results['disease'][$i]['total_cost_sickness_money'] 			= $this->makeMoney((float)$total_cost_sickness[$i]);
				$results['disease'][$i]['total_cost_sickness_money_HAS_SF'] 	= $this->makeMoney((float)$total_cost_sickness_HAS_SF[$i]);
				$results['disease'][$i]['total_cost_sickness_money_HAS_OE'] 	= $this->makeMoney((float)$cost_sickness_m_HAS_OE[$i]);
				
				// cost sickness calculations totals AS values
				$results['disease'][$i]['total_cost_sickness'] 			= round((float)$total_cost_sickness[$i], 2);
				$results['disease'][$i]['total_cost_sickness_HAS_SF'] 	= round((float)$total_cost_sickness_HAS_SF[$i], 2);
				$results['disease'][$i]['total_cost_sickness_HAS_OE'] 	= round((float)$cost_sickness_m_HAS_OE[$i], 2);
				
				
				// cost presenteeism calculation male			
				$cost_presenteeism_m[$i] 		= ((float)$unproductive_days_m[$i] * (float)$data[total_salary]) / ((float)$data[number_male] + (float)$data[number_female]) / (float)$data[working_days];
				$cost_presenteeism_m_HAS_SF[$i] = ((float)$unproductive_days_m_HAS_ISF[$i] * (float)$data[total_salary]) / ((float)$data[number_male] + (float)$data[number_female]) / (float)$data[working_days];
				$cost_presenteeism_m_HAS_OE[$i] = ((float)$unproductive_days_m_HAS_OE[$i] * (float)$data[total_salary]) / ((float)$data[number_male] + (float)$data[number_female]) / (float)$data[working_days];
				
				// cost presenteeism calculation male AS money
				$results['disease'][$i]['cost_presenteeism_money_m'] 		= $this->makeMoney((float)$cost_presenteeism_m[$i]);													
				$results['disease'][$i]['cost_presenteeism_money_m_HAS_SF']	= $this->makeMoney((float)$cost_presenteeism_m_HAS_SF[$i]);												
				$results['disease'][$i]['cost_presenteeism_money_m_HAS_OE'] = $this->makeMoney((float)$cost_presenteeism_m_HAS_OE[$i]);
				
				// cost presenteeism calculation male AS values
				$results['disease'][$i]['cost_presenteeism_m'] 			= round((float)$cost_presenteeism_m[$i], 2);
				$results['disease'][$i]['cost_presenteeism_m_HAS_SF']	= round((float)$cost_presenteeism_m_HAS_SF[$i], 2);
				$results['disease'][$i]['cost_presenteeism_m_HAS_OE'] 	= round((float)$cost_presenteeism_m_HAS_OE[$i], 2);
				
				// cost presenteeism calculation female
				$cost_presenteeism_f[$i] 		= ((float)$unproductive_days_f[$i] * (float)$data[total_salary]) / ((float)$data[number_male] + (float)$data[number_female]) / (float)$data[working_days];
				$cost_presenteeism_f_HAS_SF[$i] = ((float)$unproductive_days_f_HAS_ISF[$i] * (float)$data[total_salary]) / ((float)$data[number_male] + (float)$data[number_female]) / (float)$data[working_days];
				
				// cost presenteeism calculation female AS money
				$results['disease'][$i]['cost_presenteeism_money_f'] 			= $this->makeMoney((float)$cost_presenteeism_f[$i]);													
				$results['disease'][$i]['cost_presenteeism_money_f_HAS_SF'] 	= $this->makeMoney((float)$cost_presenteeism_f_HAS_SF[$i]);
				
				// cost presenteeism calculation female AS values
				$results['disease'][$i]['cost_presenteeism_f'] 			= round((float)$cost_presenteeism_f[$i], 2);													
				$results['disease'][$i]['cost_presenteeism_f_HAS_SF'] 	= round((float)$cost_presenteeism_f_HAS_SF[$i], 2);
				
				// cost presenteeism calculation totals
				$total_cost_presenteeism[$i] 			= (float)$cost_presenteeism_m[$i] + $cost_presenteeism_f[$i];
				$total_cost_presenteeism_HAS_SF[$i] 	= (float)$cost_presenteeism_m_HAS_SF[$i] + $cost_presenteeism_f_HAS_SF[$i];
				$total_cost_presenteeism_HAS_OE[$i] 	= (float)$cost_presenteeism_m_HAS_OE[$i];
				
				// cost presenteeism calculation totals AS money
				$results['disease'][$i]['total_cost_presenteeism_money'] 			= $this->makeMoney((float)$total_cost_presenteeism[$i]);
				$results['disease'][$i]['total_cost_presenteeism_money_HAS_SF'] 	= $this->makeMoney((float)$total_cost_presenteeism_HAS_SF[$i]);
				$results['disease'][$i]['total_cost_presenteeism_money_HAS_OE'] 	= $this->makeMoney((float)$total_cost_presenteeism_HAS_OE[$i]);
				
				// cost presenteeism calculation totals AS values
				$results['disease'][$i]['total_cost_presenteeism'] 			= round((float)$total_cost_presenteeism[$i], 2);
				$results['disease'][$i]['total_cost_presenteeism_HAS_SF'] 	= round((float)$total_cost_presenteeism_HAS_SF[$i], 2);
				$results['disease'][$i]['total_cost_presenteeism_HAS_OE'] 	= round((float)$total_cost_presenteeism_HAS_OE[$i], 2);
				
				
				// cost death calculations male
				$cost_death_m[$i] 			= ((float)$death_absence_days_m[$i] * (float)$data[total_salary]) / ((float)$data[number_male] + (float)$data[number_female]) / (float)$data[working_days];
				$cost_death_m_HAS_SF[$i] 	= ((float)$death_absence_days_m_HAS_MSF[$i] * (float)$data[total_salary]) / ((float)$data[number_male] + (float)$data[number_female]) / (float)$data[working_days];
				$cost_death_m_HAS_OE[$i] 	= ((float)$death_absence_days_m_HAS_OE[$i] * (float)$data[total_salary]) / ((float)$data[number_male] + (float)$data[number_female]) / (float)$data[working_days];
				
				// cost death calculations male AS money
				$results['disease'][$i]['cost_death_money_m'] 			= $this->makeMoney((float)$cost_death_m[$i]);
				$results['disease'][$i]['cost_death_money_m_HAS_SF']	= $this->makeMoney((float)$cost_death_m_HAS_SF[$i]);
				$results['disease'][$i]['cost_death_money_m_HAS_OE'] 	= $this->makeMoney((float)$cost_death_m_HAS_OE[$i]);
				
				// cost death calculations male AS values
				$results['disease'][$i]['cost_death_m'] 		= round((float)$cost_death_m[$i], 2);
				$results['disease'][$i]['cost_death_m_HAS_SF']	= round((float)$cost_death_m_HAS_SF[$i], 2);
				$results['disease'][$i]['cost_death_m_HAS_OE'] 	= round((float)$cost_death_m_HAS_OE[$i], 2);
				
				// cost death calculations female
				$cost_death_f[$i] 			= ((float)$death_absence_days_f[$i] * (float)$data[total_salary]) / ((float)$data[number_male] + (float)$data[number_female]) / (float)$data[working_days];
				$cost_death_f_HAS_SF[$i] 	= ((float)$death_absence_days_f_HAS_MSF[$i] * (float)$data[total_salary]) / ((float)$data[number_male] + (float)$data[number_female]) / (float)$data[working_days];
				
				// cost death calculations female AS money
				$results['disease'][$i]['cost_death_money_f'] 			= $this->makeMoney((float)$cost_death_f[$i]);
				$results['disease'][$i]['cost_death_money_f_HAS_SF'] 	= $this->makeMoney((float)$cost_death_f_HAS_SF[$i]);
				
				// cost death calculations female AS values
				$results['disease'][$i]['cost_death_f'] 		= round((float)$cost_death_f[$i], 2);
				$results['disease'][$i]['cost_death_f_HAS_SF'] 	= round((float)$cost_death_f_HAS_SF[$i], 2);
				
				// cost death calculations totals
				$total_cost_death[$i] 			= (float)$cost_death_m[$i] + $cost_death_f[$i];
				$total_cost_death_HAS_SF[$i] 	= (float)$cost_death_m_HAS_SF[$i] + $cost_death_f_HAS_SF[$i];
				$total_cost_death_HAS_OE[$i] 	= (float)$cost_death_m_HAS_OE[$i];
				
				// cost death calculations totals AS money
				$results['disease'][$i]['total_cost_death_money'] 			= $this->makeMoney((float)$total_cost_death[$i]);
				$results['disease'][$i]['total_cost_death_money_HAS_SF'] 	= $this->makeMoney((float)$total_cost_death_HAS_SF[$i]);
				$results['disease'][$i]['total_cost_death_money_HAS_OE'] 	= $this->makeMoney((float)$total_cost_death_HAS_OE[$i]);
				
				// cost death calculations totals AS values
				$results['disease'][$i]['total_cost_death'] 		= round((float)$total_cost_death[$i], 2);
				$results['disease'][$i]['total_cost_death_HAS_SF'] 	= round((float)$total_cost_death_HAS_SF[$i], 2);
				$results['disease'][$i]['total_cost_death_HAS_OE'] 	= round((float)$total_cost_death_HAS_OE[$i], 2);
				
				// local grand totals
				$total_cost_disease[$i] 			= (float)$total_cost_sickness[$i] + (float)$total_cost_death[$i] + (float)$total_cost_presenteeism[$i];
				$total_cost_disease_HAS_SF[$i] 		= (float)$total_cost_sickness_HAS_SF[$i] + (float)$total_cost_death_HAS_SF[$i] + (float)$total_cost_presenteeism_HAS_SF[$i];
				$total_cost_disease_HAS_OE[$i] 		= (float)$total_cost_sickness_HAS_OE[$i] + (float)$total_cost_death_HAS_OE[$i] + (float)$total_cost_presenteeism_HAS_OE[$i];
				
				// local grand totals AS money
				$results['disease'][$i]['total_cost_money'] 		= $this->makeMoney((float)$total_cost_sickness[$i] + (float)$total_cost_death[$i] + (float)$total_cost_presenteeism[$i]);
				$results['disease'][$i]['total_cost_money_HAS_SF'] 	= $this->makeMoney((float)$total_cost_sickness_HAS_SF[$i] + (float)$total_cost_death_HAS_SF[$i] + (float)$total_cost_presenteeism_HAS_SF[$i]);
				$results['disease'][$i]['total_cost_money_HAS_OE'] 	= $this->makeMoney((float)$total_cost_sickness_HAS_OE[$i] + (float)$total_cost_death_HAS_OE[$i] + (float)$total_cost_presenteeism_HAS_OE[$i]);
				
				// local grand totals AS values
				$results['disease'][$i]['total_cost'] 			= round((float)$total_cost_sickness[$i] + (float)$total_cost_death[$i] + (float)$total_cost_presenteeism[$i], 2);
				$results['disease'][$i]['total_cost_HAS_SF'] 	= round((float)$total_cost_sickness_HAS_SF[$i] + (float)$total_cost_death_HAS_SF[$i] + (float)$total_cost_presenteeism_HAS_SF[$i], 2);
				$results['disease'][$i]['total_cost_HAS_OE'] 	= round((float)$total_cost_sickness_HAS_OE[$i] + (float)$total_cost_death_HAS_OE[$i] + (float)$total_cost_presenteeism_HAS_OE[$i], 2);
				
				
				// global totals			
				$totalCostSickness_m 			+= (float)$cost_sickness_m[$i];
				$totalCostSickness_m_HAS_SF 	+= (float)$cost_sickness_m_HAS_SF[$i];
				$totalCostSickness_f 			+= (float)$cost_sickness_f[$i];
				$totalCostSickness_f_HAS_SF 	+= (float)$cost_sickness_f_HAS_SF[$i];
				$totalCostSickness 				+= (float)$cost_sickness_m[$i] + (float)$cost_sickness_f[$i];
				$totalCostSickness_HAS_SF 		+= (float)$cost_sickness_m_HAS_SF[$i] + (float)$cost_sickness_f_HAS_SF[$i];
				$totalCostSickness_HAS_OE 		+= (float)$cost_sickness_m_HAS_OE[$i];
				
				$totalCostPresenteeism_m 			+= (float)$cost_presenteeism_m[$i];
				$totalCostPresenteeism_m_HAS_SF 	+= (float)$cost_presenteeism_m_HAS_SF[$i];
				$totalCostPresenteeism_f 			+= (float)$cost_presenteeism_f[$i];
				$totalCostPresenteeism_f_HAS_SF 	+= (float)$cost_presenteeism_f_HAS_SF[$i];
				$totalCostPresenteeism 				+= (float)$cost_presenteeism_m[$i] + (float)$cost_presenteeism_f[$i];
				$totalCostPresenteeism_HAS_SF 		+= (float)$cost_presenteeism_m_HAS_SF[$i] + (float)$cost_presenteeism_f_HAS_SF[$i];
				$totalCostPresenteeism_HAS_OE 		+= (float)$cost_presenteeism_m_HAS_OE[$i];
				
				$totalCostDeath_m 			+= (float)$cost_death_m[$i];
				$totalCostDeath_m_HAS_SF 	+= (float)$cost_death_m_HAS_SF[$i];
				$totalCostDeath_f 			+= (float)$cost_death_f[$i];
				$totalCostDeath_f_HAS_SF 	+= (float)$cost_death_f_HAS_SF[$i];
				$totalCostDeath 			+= (float)$cost_death_m[$i] + (float)$cost_death_f[$i];
				$totalCostDeath_HAS_SF 		+= (float)$cost_death_m_HAS_SF[$i] + (float)$cost_death_f_HAS_SF[$i];
				$totalCostDeath_HAS_OE 		+= (float)$cost_death_m_HAS_OE[$i];
				
				$i++;
			}
		}
		
		// Calculations for Risk Factors
		
		$totalRiskAbsenceDays 			= 0;
		$totalRiskAbsenceDays_HAS_SF 	= 0;
		
		$totalRiskAbsenceDays_m 		= 0;
		$totalRiskAbsenceDays_m_HAS_SF 	= 0;
		$totalRiskAbsenceDays_f 		= 0;
		$totalRiskAbsenceDays_f_HAS_SF 	= 0;
		$totalRiskAbsenceDays 			= 0;
		$totalRiskAbsenceDays_HAS_SF 	= 0;
		
		$totalCostRisk_m 		= 0;
		$totalCostRisk_m_HAS_SF = 0;
		$totalCostRisk_f 		= 0;
		$totalCostRisk_f_HAS_SF = 0;
		$totalCostRisk 			= 0;
		$totalCostRisk_HAS_SF 	= 0;
		
		$i = 0;
		if(!empty($data['riskdata'])){
			foreach ($data['riskdata'] as $risk)
			{
				$results['risk'][$i]['risk_name'] = $risk[risk_name];
				
				// male												
				$risk_absence_days_m[$i] 			= ((float)$risk[prevalence_male]/1000) * (float)$data[number_male] * (float)$risk[annual_unproductive_male] * ((float)$data[working_days]/260);
				$risk_absence_days_m_HAS_SF[$i] 	= ((float)$risk[prevalence_male]/1000) * (float)$data[number_male] * (float)$risk[annual_unproductive_male] * ((float)$data[working_days]/260) 
													* (float)$risk[prevalence_scaling_factor_male] * (float)$risk[unproductive_scaling_factor_male];
																
				$results['risk'][$i]['risk_absence_days_m'] 		= round((float)$risk_absence_days_m[$i], 2);
				$results['risk'][$i]['risk_absence_days_m_HAS_SF'] 	= round((float)$risk_absence_days_m_HAS_SF[$i], 2);
				
				// female
				$risk_absence_days_f[$i] 			= ((float)$risk[prevalence_female]/1000) * (float)$data[number_female] * (float)$risk[annual_unproductive_female] * ((float)$data[working_days]/260);
				$risk_absence_days_f_HAS_SF[$i] 	= ((float)$risk[prevalence_female]/1000) * (float)$data[number_female] * (float)$risk[annual_unproductive_female] * ((float)$data[working_days]/260) 
													* (float)$risk[prevalence_scaling_factor_female] * (float)$risk[unproductive_scaling_factor_female];
																
				$results['risk'][$i]['risk_absence_days_f'] 		= round((float)$risk_absence_days_f[$i], 2);
				$results['risk'][$i]['risk_absence_days_f_HAS_SF'] 	= round((float)$risk_absence_days_f_HAS_SF[$i], 2);
				
				// totals
				$total_risk_absence_days[$i] 			= (float)$risk_absence_days_m[$i] + (float)$risk_absence_days_f[$i];
				$total_risk_absence_days_HAS_SF[$i] 	= (float)$risk_absence_days_m_HAS_SF[$i] + (float)$risk_absence_days_f_HAS_SF[$i];
				
				$results['risk'][$i]['total_risk_absence_days'] 		= round((float)$risk_absence_days_m[$i] + (float)$risk_absence_days_f[$i], 2);
				$results['risk'][$i]['total_risk_absence_days_HAS_SF'] 	= round((float)$risk_absence_days_m_HAS_SF[$i] + (float)$risk_absence_days_f_HAS_SF[$i], 2);
				
				// global totals
				$totalDaysLost 				+= (float)$total_risk_absence_days[$i];
				$totalDaysLost_HAS_SF 		+= (float)$total_risk_absence_days_HAS_SF[$i];
				
				$totalRiskAbsenceDays_m 		+= (float)$risk_absence_days_m[$i];
				$totalRiskAbsenceDays_m_HAS_SF 	+= (float)$risk_absence_days_m_HAS_SF[$i];
				$totalRiskAbsenceDays_f 		+= (float)$risk_absence_days_f[$i];
				$totalRiskAbsenceDays_f_HAS_SF 	+= (float)$risk_absence_days_f_HAS_SF[$i];
				$totalRiskAbsenceDays 			+= (float)$total_risk_absence_days[$i];
				$totalRiskAbsenceDays_HAS_SF 	+= (float)$total_risk_absence_days_HAS_SF[$i];
				
				
				// cost risk calculations male
				$cost_risk_m[$i] 			= ((float)$risk_absence_days_m[$i] * (float)$data[total_salary]) / ((float)$data[number_male] + (float)$data[number_female]) / (float)$data[working_days];
				$cost_risk_m_HAS_SF[$i] 	= ((float)$risk_absence_days_m_HAS_SF[$i] * (float)$data[total_salary]) / ((float)$data[number_male] + (float)$data[number_female]) / (float)$data[working_days];
				
				// cost risk calculations male AS money
				$results['risk'][$i]['cost_risk_money_m'] 			= $this->makeMoney((float)$cost_risk_m[$i]);
				$results['risk'][$i]['cost_risk_money_m_HAS_SF']	= $this->makeMoney((float)$cost_risk_m_HAS_SF[$i]);
				
				// cost risk calculations male AS values
				$results['risk'][$i]['cost_risk_m'] 		= round((float)$cost_risk_m[$i], 2);
				$results['risk'][$i]['cost_risk_m_HAS_SF']	= round((float)$cost_risk_m_HAS_SF[$i], 2);
				
				// cost risk calculations female
				$cost_risk_f[$i] 			= ((float)$risk_absence_days_f[$i] * (float)$data[total_salary]) / ((float)$data[number_male] + (float)$data[number_female]) / (float)$data[working_days];
				$cost_risk_f_HAS_SF[$i] 	= ((float)$risk_absence_days_f_HAS_SF[$i] * (float)$data[total_salary]) / ((float)$data[number_male] + (float)$data[number_female]) / (float)$data[working_days];
				
				// cost risk calculations female AS money
				$results['risk'][$i]['cost_risk_money_f'] 			= $this->makeMoney((float)$cost_risk_f[$i]);
				$results['risk'][$i]['cost_risk_money_f_HAS_SF'] 	= $this->makeMoney((float)$cost_risk_f_HAS_SF[$i]);
				
				// cost risk calculations female AS values
				$results['risk'][$i]['cost_risk_f'] 		= round((float)$cost_risk_f[$i], 2);
				$results['risk'][$i]['cost_risk_f_HAS_SF']	= round((float)$cost_risk_f_HAS_SF[$i], 2);
				
				// cost risk calculations totals
				$total_cost_risk[$i] 			= (float)$cost_risk_m[$i] + $cost_risk_f[$i];
				$total_cost_risk_HAS_SF[$i] 	= (float)$cost_risk_m_HAS_SF[$i] + $cost_risk_f_HAS_SF[$i];
				
				// cost risk calculations totals AS money
				$results['risk'][$i]['total_cost_risk_money'] 			= $this->makeMoney((float)$cost_risk_m[$i] + $cost_risk_f[$i]);
				$results['risk'][$i]['total_cost_risk_money_HAS_SF'] 	= $this->makeMoney((float)$cost_risk_m_HAS_SF[$i] + $cost_risk_f_HAS_SF[$i]);
				
				// cost risk calculations totals AS values
				$results['risk'][$i]['total_cost_risk'] 		= round((float)$cost_risk_m[$i] + $cost_risk_f[$i], 2);
				$results['risk'][$i]['total_cost_risk_HAS_SF'] 	= round((float)$cost_risk_m_HAS_SF[$i] + $cost_risk_f_HAS_SF[$i], 2);
				
				// global totals
				$totalCostRisk_m 		+= (float)$cost_risk_m[$i];
				$totalCostRisk_m_HAS_SF += (float)$cost_risk_m_HAS_SF[$i];
				$totalCostRisk_f 		+= (float)$cost_risk_f[$i];
				$totalCostRisk_f_HAS_SF += (float)$cost_risk_f_HAS_SF[$i];
				$totalCostRisk 			+= (float)$cost_risk_m[$i] + (float)$cost_risk_f[$i];
				$totalCostRisk_HAS_SF 	+= (float)$cost_risk_m_HAS_SF[$i] + (float)$cost_risk_f_HAS_SF[$i];
				
				$i++;
			}
		}
		// global totals
		$results['totalCostSickness_money_m'] 			= $this->makeMoney($totalCostSickness_m);
		$results['totalCostSickness_money_m_HAS_SF'] 	= $this->makeMoney($totalCostSickness_m_HAS_SF);
		$results['totalCostSickness_money_f'] 			= $this->makeMoney($totalCostSickness_f);
		$results['totalCostSickness_money_f_HAS_SF'] 	= $this->makeMoney($totalCostSickness_f_HAS_SF);
		$results['totalCostSickness_money'] 			= $this->makeMoney($totalCostSickness);
		$results['totalCostSickness_money_HAS_SF'] 		= $this->makeMoney($totalCostSickness_HAS_SF);
		$results['totalCostSickness_money_HAS_OE'] 		= $this->makeMoney($totalCostSickness_HAS_OE);
		
		$results['totalCostPresenteeism_money_m'] 			= $this->makeMoney($totalCostPresenteeism_m);
		$results['totalCostPresenteeism_money_m_HAS_SF'] 	= $this->makeMoney($totalCostPresenteeism_m_HAS_SF);
		$results['totalCostPresenteeism_money_f'] 			= $this->makeMoney($totalCostPresenteeism_f);
		$results['totalCostPresenteeism_money_f_HAS_SF'] 	= $this->makeMoney($totalCostPresenteeism_f_HAS_SF);
		$results['totalCostPresenteeism_money'] 			= $this->makeMoney($totalCostPresenteeism);
		$results['totalCostPresenteeism_money_HAS_SF'] 		= $this->makeMoney($totalCostPresenteeism_HAS_SF);
		$results['totalCostPresenteeism_money_HAS_OE'] 		= $this->makeMoney($totalCostPresenteeism_HAS_OE);
		
		$results['totalCostDeath_money_m'] 			= $this->makeMoney($totalCostDeath_m);
		$results['totalCostDeath_money_m_HAS_SF'] 	= $this->makeMoney($totalCostDeath_m_HAS_SF);
		$results['totalCostDeath_money_f'] 			= $this->makeMoney($totalCostDeath_f);
		$results['totalCostDeath_money_f_HAS_SF'] 	= $this->makeMoney($totalCostDeath_f_HAS_SF);
		$results['totalCostDeath_money'] 			= $this->makeMoney($totalCostDeath);
		$results['totalCostDeath_money_HAS_SF'] 	= $this->makeMoney($totalCostDeath_HAS_SF);
		$results['totalCostDeath_money_HAS_OE'] 	= $this->makeMoney($totalCostDeath_HAS_OE);
		
		$results['totalCostRisk_money_m'] 			= $this->makeMoney($totalCostRisk_m);
		$results['totalCostRisk_money_m_HAS_SF'] 	= $this->makeMoney($totalCostRisk_m_HAS_SF);
		$results['totalCostRisk_money_f'] 			= $this->makeMoney($totalCostRisk_f);
		$results['totalCostRisk_money_f_HAS_SF'] 	= $this->makeMoney($totalCostRisk_f_HAS_SF);
		$results['totalCostRisk_money'] 			= $this->makeMoney($totalCostRisk);
		$results['totalCostRisk_money_HAS_SF'] 		= $this->makeMoney($totalCostRisk_HAS_SF);
		
		$results['totalCost_money'] 		= $this->makeMoney((float)$totalCostSickness + (float)$totalCostPresenteeism + (float)$totalCostDeath + (float)$totalCostRisk);
		$results['totalCost_money_HAS_SF'] 	= $this->makeMoney((float)$totalCostSickness_HAS_SF + (float)$totalCostPresenteeism_HAS_SF + (float)$totalCostDeath_HAS_SF + (float)$totalCostRisk_HAS_SF);
		$results['totalCost_money_HAS_OE'] 	= $this->makeMoney((float)$totalCostSickness_HAS_OE + (float)$totalCostPresenteeism_HAS_OE + (float)$totalCostDeath_HAS_OE);
		
		$results['totalCost'] 			= round((float)$totalCostSickness + (float)$totalCostPresenteeism + (float)$totalCostDeath + (float)$totalCostRisk, 2);
		$results['totalCost_HAS_SF'] 	= round((float)$totalCostSickness_HAS_SF + (float)$totalCostPresenteeism_HAS_SF + (float)$totalCostDeath_HAS_SF + (float)$totalCostRisk_HAS_SF, 2);
		$results['totalCost_HAS_OE'] 	= round((float)$totalCostSickness_HAS_OE + (float)$totalCostPresenteeism_HAS_OE + (float)$totalCostDeath_HAS_OE, 2);
		
		$results['totalRiskAbsenceDays_m'] 			= round($totalRiskAbsenceDays_m, 2);
		$results['totalRiskAbsenceDays_m_HAS_SF'] 	= round($totalRiskAbsenceDays_m_HAS_SF, 2);
		$results['totalRiskAbsenceDays_f'] 			= round($totalRiskAbsenceDays_f, 2);
		$results['totalRiskAbsenceDays_f_HAS_SF'] 	= round($totalRiskAbsenceDays_f_HAS_SF, 2);
		$results['totalRiskAbsenceDays'] 			= round($totalRiskAbsenceDays, 2);
		$results['totalRiskAbsenceDays_HAS_SF'] 	= round($totalRiskAbsenceDays_HAS_SF, 2);
		
		$results['totalDaysLost'] 			= round($totalDaysLost, 2);
		$results['totalDaysLost_HAS_SF'] 	= round($totalDaysLost_HAS_SF, 2);
		$results['totalDaysLost_HAS_OE'] 	= round($totalDaysLost_HAS_OE, 2);
		
		// interventions calculations
		
		// get grand cost totals
		$grandTotalCost 			= (float)$totalCostSickness + (float)$totalCostPresenteeism + (float)$totalCostDeath + (float)$totalCostRisk;
		$grandTotalCost_HAS_SF 	= (float)$totalCostSickness_HAS_SF + (float)$totalCostPresenteeism_HAS_SF + (float)$totalCostDeath_HAS_SF + (float)$totalCostRisk_HAS_SF;
		$grandTotalCost_HAS_OE 	= (float)$totalCostSickness_HAS_OE + (float)$totalCostPresenteeism_HAS_OE + (float)$totalCostDeath_HAS_OE;
		
		if(is_array($data['interventiondata'])){
			$in = 0;
			foreach ($data['interventiondata'] as $intervention)
			{
				$totalContributionCost 							= 0;
				$totalContributionCost_HAS_SF 					= 0;
				$totalInterventionDuration 						= 0;
				$totalInterventionCoverage 						= 0;
				$totalCostPerEmployee 							= 0;
				$totalMorbidityReduction 						= 0;
				$totalMortalityReduction 						= 0;
				$totalCost 										= 0;
				$totalCost_HAS_SF 								= 0;
				$totalInterventionAnnualCost 					= 0;
				$totalInterventionAnnualBenefit 				= 0;
				$totalInterventionAnnualBenefit_HAS_SF 			= 0;
				$totalInterventionAnnualBenefitRatio 			= 0;
				$totalInterventionAnnualBenefitRatio_HAS_SF 	= 0;
				$totalInterventionAnnualNetBenefit 				= 0;
				$totalInterventionAnnualNetBenefit_HAS_SF 		= 0;
				
				$intervention_risks = NULL;
				if($intervention[risk_id]){
					$intervention_risks = json_decode($intervention[risk_id]);					
				}
				
				$intervention_diseases = NULL;
				if($intervention[disease_id]){
					$intervention_diseases = json_decode($intervention[disease_id]);
				}
				// set the disease results of this intervention
				if(is_array($data['diseasedata']) && is_array($intervention_diseases)){
					$i = 0;
					
					foreach ($data['diseasedata'] as $disease)
					{
						if(in_array((int)$disease[disease_id], (array)$intervention_diseases)){
							$results['interventions'][$in]['disease'][$i]['name'] 						= $disease[disease_name];
							$results['interventions'][$in]['disease'][$i]['intervention_name'] 			= $intervention[intervention_name];
							$results['interventions'][$in]['disease'][$i]['intervention_duration'] 		= (float)$intervention[duration];
							$results['interventions'][$in]['disease'][$i]['intervention_coverage'] 		= (float)$intervention[coverage];
							$disease_intervention_coverage[$i] 											= (float)$intervention[coverage]/100;
							
							// contribution persent of disease
							$disease_contribution_cost[$i] 			= (float)$total_cost_disease[$i] / (float)$grandTotalCost;
							$disease_contribution_cost_HAS_SF[$i]	= (float)$total_cost_disease_HAS_SF[$i] / (float)$grandTotalCost_HAS_SF;
							$disease_contribution_cost_HAS_OE[$i] 	= (float)$total_cost_disease_HAS_OE[$i] / (float)$grandTotalCost_HAS_OE;
							// contribution persent of disease AS values
							$results['interventions'][$in]['disease'][$i]['contribution_cost'] 			= round((float)$disease_contribution_cost[$i], 2);
							$results['interventions'][$in]['disease'][$i]['contribution_cost_HAS_SF']	= round((float)$disease_contribution_cost_HAS_SF[$i], 2);
							$results['interventions'][$in]['disease'][$i]['contribution_cost_HAS_OE'] 	= round((float)$disease_contribution_cost_HAS_OE[$i], 2);
							
							// get intervetnion values related to this disease
							foreach ($data['interventiondata'][$in]['diseasedata'] as $intervention_diseasedata){
								if($intervention_diseasedata['id'] == $disease[disease_id]){									
									$results['interventions'][$in]['disease'][$i]['cost_per_employee'] 			= (float)$intervention_diseasedata['cpe'];
									$results['interventions'][$in]['disease'][$i]['cost_per_employee_money'] 	= $this->makeMoney((float)$intervention_diseasedata['cpe']);
									$results['interventions'][$in]['disease'][$i]['morbidity_reduction'] 		= (float)$intervention_diseasedata['mbr'];
									$results['interventions'][$in]['disease'][$i]['mortality_reduction'] 		= (float)$intervention_diseasedata['mtr'];
									$disease_morbidity_reduction[$i] 	= (float)$intervention_diseasedata['mbr']/100;
									$disease_mortality_reduction[$i] 	= (float)$intervention_diseasedata['mtr']/100;
									break;
								}
							}
							
							// Total cost of disease
							$disease_cost[$i] 			= (float)$total_cost_disease[$i];
							$disease_cost_HAS_SF[$i]	= (float)$total_cost_disease_HAS_SF[$i];
							$disease_cost_HAS_OE[$i] 	= (float)$total_cost_disease_HAS_OE[$i];
							// Total cost of disease AS values
							$results['interventions'][$in]['disease'][$i]['cost'] 			= round((float)$disease_cost[$i], 2);
							$results['interventions'][$in]['disease'][$i]['cost_HAS_SF']	= round((float)$total_cost_disease_HAS_SF[$i], 2);
							$results['interventions'][$in]['disease'][$i]['cost_HAS_OE'] 	= round((float)$total_cost_disease_HAS_OE[$i], 2);
							// Total cost of disease AS money
							$results['interventions'][$in]['disease'][$i]['cost_money'] 		= $this->makeMoney((float)$disease_cost[$i]);
							$results['interventions'][$in]['disease'][$i]['cost_money_HAS_SF']	= $this->makeMoney((float)$disease_cost_HAS_SF[$i]);
							$results['interventions'][$in]['disease'][$i]['cost_money_HAS_OE'] 	= $this->makeMoney((float)$disease_cost_HAS_OE[$i]);
							
							// Annual Cost of the Intervention
							$disease_intervention_annual_cost[$i] 	= ((float)$data['number_male'] + (float)$data['number_female']) * (float)$results['interventions'][$in]['disease'][$i]['cost_per_employee']
																	* (float)$disease_intervention_coverage[$i];							
							// Annual Cost of the Intervention AS values
							$results['interventions'][$in]['disease'][$i]['intervention_annual_cost'] 		= round((float)$disease_intervention_annual_cost[$i], 2);
							// Annual Cost of the Intervention AS money
							$results['interventions'][$in]['disease'][$i]['intervention_annual_cost_money'] = $this->makeMoney((float)$disease_intervention_annual_cost[$i]);
							
							// Annual Benefit of the Intervention
							$disease_intervention_annual_benefit[$i] 	= (float)$disease_intervention_coverage[$i] * (((float)$disease_morbidity_reduction[$i]
																		* ((float)$total_cost_sickness[$i] + (float)$total_cost_presenteeism[$i]))
																		+ ((float)$disease_mortality_reduction[$i] * (float)$total_cost_death[$i]));
							$disease_intervention_annual_benefit_HAS_SF[$i] 	= (float)$disease_intervention_coverage[$i] * (((float)$disease_morbidity_reduction[$i]
																				* ((float)$total_cost_sickness_HAS_SF[$i] + (float)$total_cost_presenteeism_HAS_SF[$i]))
																				+ ((float)$disease_mortality_reduction[$i] * (float)$total_cost_death_HAS_SF[$i]));																				
							$disease_intervention_annual_benefit_HAS_OE[$i] 	= (float)$disease_intervention_coverage[$i] * (((float)$disease_morbidity_reduction[$i]
																				* ((float)$total_cost_sickness_HAS_OE[$i] + (float)$total_cost_presenteeism_HAS_OE[$i]))
																				+ ((float)$disease_mortality_reduction[$i] * (float)$total_cost_death_HAS_OE[$i]));									
							// Annual Benefit of the Intervention AS values
							$results['interventions'][$in]['disease'][$i]['intervention_annual_benefit'] 			= round((float)$disease_intervention_annual_benefit[$i], 2);
							$results['interventions'][$in]['disease'][$i]['intervention_annual_benefit_HAS_SF'] 	= round((float)$disease_intervention_annual_benefit_HAS_SF[$i], 2);
							$results['interventions'][$in]['disease'][$i]['intervention_annual_benefit_HAS_OE'] 	= round((float)$disease_intervention_annual_benefit_HAS_OE[$i], 2);
							// Annual Benefit of the Intervention AS money
							$results['interventions'][$in]['disease'][$i]['intervention_annual_benefit_money'] 			= $this->makeMoney((float)$disease_intervention_annual_benefit[$i]);
							$results['interventions'][$in]['disease'][$i]['intervention_annual_benefit_money_HAS_SF'] 	= $this->makeMoney((float)$disease_intervention_annual_benefit_HAS_SF[$i]);
							$results['interventions'][$in]['disease'][$i]['intervention_annual_benefit_money_HAS_OE'] 	= $this->makeMoney((float)$disease_intervention_annual_benefit_HAS_OE[$i]);
							
							// Annual Cost Benefit Ratio
							$disease_intervention_annual_benefit_ratio[$i] 			= (float)$disease_intervention_annual_cost[$i] / (float)$disease_intervention_annual_benefit[$i];
							$disease_intervention_annual_benefit_ratio_HAS_SF[$i] 	= (float)$disease_intervention_annual_cost[$i] / (float)$disease_intervention_annual_benefit_HAS_SF[$i];																				
							$disease_intervention_annual_benefit_ratio_HAS_OE[$i] 	= (float)$disease_intervention_annual_cost[$i] / (float)$disease_intervention_annual_benefit_HAS_OE[$i];
							// Annual Cost Benefit Ratio AS values
							$results['interventions'][$in]['disease'][$i]['intervention_annual_benefit_ratio'] 			= round((float)$disease_intervention_annual_benefit_ratio[$i], 2);
							$results['interventions'][$in]['disease'][$i]['intervention_annual_benefit_ratio_HAS_SF'] 	= round((float)$disease_intervention_annual_benefit_ratio_HAS_SF[$i], 2);
							$results['interventions'][$in]['disease'][$i]['intervention_annual_benefit_ratio_HAS_OE'] 	= round((float)$disease_intervention_annual_benefit_ratio_HAS_OE[$i], 2);
							
							// Annual Cost Net Benefit
							$disease_intervention_annual_net_benefit[$i] 			= (float)$disease_intervention_annual_benefit[$i] - (float)$disease_intervention_annual_cost[$i];
							$disease_intervention_annual_net_benefit_HAS_SF[$i] 	= (float)$disease_intervention_annual_benefit_HAS_SF[$i] - (float)$disease_intervention_annual_cost[$i];																				
							$disease_intervention_annual_net_benefit_HAS_OE[$i] 	= (float)$disease_intervention_annual_benefit_HAS_OE[$i] - (float)$disease_intervention_annual_cost[$i];									
							// Annual Cost Net Benefit AS values
							$results['interventions'][$in]['disease'][$i]['intervention_annual_net_benefit'] 			= round((float)$disease_intervention_annual_net_benefit[$i], 2);
							$results['interventions'][$in]['disease'][$i]['intervention_annual_net_benefit_HAS_SF'] 	= round((float)$disease_intervention_annual_net_benefit_HAS_SF[$i], 2);
							$results['interventions'][$in]['disease'][$i]['intervention_annual_net_benefit_HAS_OE'] 	= round((float)$disease_intervention_annual_net_benefit_HAS_OE[$i], 2);
							// Annual Cost Net Benefit AS money
							$results['interventions'][$in]['disease'][$i]['intervention_annual_net_benefit_money'] 			= $this->makeMoney((float)$disease_intervention_annual_net_benefit[$i]);
							$results['interventions'][$in]['disease'][$i]['intervention_annual_net_benefit_money_HAS_SF'] 	= $this->makeMoney((float)$disease_intervention_annual_net_benefit_HAS_SF[$i]);
							$results['interventions'][$in]['disease'][$i]['intervention_annual_net_benefit_money_HAS_OE'] 	= $this->makeMoney((float)$disease_intervention_annual_net_benefit_HAS_OE[$i]);
							
							// local totals
							$totalContributionCost 								+= $disease_contribution_cost[$i];
							$totalContributionCost_HAS_SF 						+= $disease_contribution_cost_HAS_SF[$i];
							$totalInterventionDuration 							+= $results['interventions'][$in]['disease'][$i]['intervention_duration'];
							$totalInterventionCoverage 							+= $results['interventions'][$in]['disease'][$i]['intervention_coverage'];

							$totalCostPerEmployee 								+= $results['interventions'][$in]['disease'][$i]['cost_per_employee'];
							$totalMorbidityReduction 							+= $results['interventions'][$in]['disease'][$i]['morbidity_reduction'];
							$totalMortalityReduction 							+= $results['interventions'][$in]['disease'][$i]['mortality_reduction'];
							$totalCost 											+= $disease_cost[$i];
							$totalCost_HAS_SF 									+= $disease_cost_HAS_SF[$i];
							$totalInterventionAnnualCost 						+= $disease_intervention_annual_cost[$i];
							$totalInterventionAnnualBenefit 					+= $disease_intervention_annual_benefit[$i];
							$totalInterventionAnnualBenefit_HAS_SF 				+= $disease_intervention_annual_benefit_HAS_SF[$i];
							$totalInterventionAnnualBenefitRatio 				+= $disease_intervention_annual_benefit_ratio[$i];
							$totalInterventionAnnualBenefitRatio_HAS_SF 		+= $disease_intervention_annual_benefit_ratio_HAS_SF[$i];
							$totalInterventionAnnualNetBenefit 					+= $disease_intervention_annual_net_benefit[$i];
							$totalInterventionAnnualNetBenefit_HAS_SF 			+= $disease_intervention_annual_net_benefit_HAS_SF[$i];
						}
						$i++;
					}
				}
				// set the risk results of this intervention
				if(is_array($data['riskdata']) && is_array($intervention_risks)){
					$i = 0;
					foreach ($data['riskdata'] as $risk)
					{
						if (in_array((int)$risk[risk_id], (array)$intervention_risks)){
							$results['interventions'][$in]['risk'][$i]['name'] 							= $risk[risk_name];
							$results['interventions'][$in]['risk'][$i]['intervention_name'] 			= $intervention[intervention_name];
							$results['interventions'][$in]['risk'][$i]['intervention_duration'] 		= (float)$intervention[duration];
							$results['interventions'][$in]['risk'][$i]['intervention_coverage'] 		= (float)$intervention[coverage];
							$risk_intervention_coverage[$i] 											= (float)$intervention[coverage]/100;////////////////////
							
							// contribution persent of risk
							$risk_contribution_cost[$i] 		= (float)$total_cost_risk[$i] / (float)$grandTotalCost;
							$risk_contribution_cost_HAS_SF[$i] 	= (float)$total_cost_risk_HAS_SF[$i] / (float)$grandTotalCost_HAS_SF;
							// contribution persent of risk AS values
							$results['interventions'][$in]['risk'][$i]['contribution_cost'] 		= round((float)$risk_contribution_cost[$i], 2);
							$results['interventions'][$in]['risk'][$i]['contribution_cost_HAS_SF'] 	= round((float)$risk_contribution_cost_HAS_SF[$i], 2);
							
							// get intervetnion values related to this risk
							foreach ($data['interventiondata'][$in]['riskdata'] as $intervention_riskdata){
								if($intervention_riskdata['id'] == $risk[risk_id]){
									$results['interventions'][$in]['risk'][$i]['cost_per_employee'] 		= (float)$intervention_riskdata['cpe'];
									$results['interventions'][$in]['risk'][$i]['cost_per_employee_money'] 	= $this->makeMoney((float)$intervention_riskdata['cpe']);
									$results['interventions'][$in]['risk'][$i]['morbidity_reduction'] 		= (float)$intervention_riskdata['mbr'];
									$risk_morbidity_reduction[$i] 	= (float)$intervention_riskdata['mbr']/100;
									break;
								}
							}
							
							// Total cost of risk
							$risk_cost[$i] 			= (float)$total_cost_risk[$i];
							$risk_cost_HAS_SF[$i]	= (float)$total_cost_risk_HAS_SF[$i];
							// Total cost of risk AS values
							$results['interventions'][$in]['risk'][$i]['cost'] 			= round((float)$total_cost_risk[$i], 2);
							$results['interventions'][$in]['risk'][$i]['cost_HAS_SF']	= round((float)$total_cost_risk_HAS_SF[$i], 2);
							// Total cost of risk AS money
							$results['interventions'][$in]['risk'][$i]['cost_money'] 			= $this->makeMoney((float)$total_cost_risk[$i]);
							$results['interventions'][$in]['risk'][$i]['cost_money_HAS_SF']		= $this->makeMoney((float)$total_cost_risk_HAS_SF[$i]);
							
							// Annual Cost of the Intervention
							$risk_intervention_annual_cost[$i]	= ((float)$data['number_male'] + (float)$data['number_female']) * (float)$results['interventions'][$in]['risk'][$i]['cost_per_employee']
																* (float)$risk_intervention_coverage[$i];								
							// Annual Cost of the Intervention AS values
							$results['interventions'][$in]['risk'][$i]['intervention_annual_cost'] 	= round((float)$risk_intervention_annual_cost[$i], 2);
							// Annual Cost of the Intervention AS money
							$results['interventions'][$in]['risk'][$i]['intervention_annual_cost_money'] 	= $this->makeMoney((float)$risk_intervention_annual_cost[$i]);
							
							// Annual Benefit of the Intervention
							$risk_intervention_annual_benefit[$i] 			= (float)$risk_intervention_coverage[$i] * ((float)$risk_morbidity_reduction[$i] * (float)$total_cost_risk[$i]);
							$risk_intervention_annual_benefit_HAS_SF[$i] 	= (float)$risk_intervention_coverage[$i] * ((float)$risk_morbidity_reduction[$i] * (float)$total_cost_risk_HAS_SF[$i]);							
							// Annual Benefit of the Intervention AS values
							$results['interventions'][$in]['risk'][$i]['intervention_annual_benefit'] 			= round((float)$risk_intervention_annual_benefit[$i], 2);
							$results['interventions'][$in]['risk'][$i]['intervention_annual_benefit_HAS_SF'] 	= round((float)$risk_intervention_annual_benefit_HAS_SF[$i], 2);
							// Annual Benefit of the Intervention AS money
							$results['interventions'][$in]['risk'][$i]['intervention_annual_benefit_money'] 		= $this->makeMoney((float)$risk_intervention_annual_benefit[$i]);
							$results['interventions'][$in]['risk'][$i]['intervention_annual_benefit_money_HAS_SF'] 	= $this->makeMoney((float)$risk_intervention_annual_benefit_HAS_SF[$i]);
							
							// Annual Cost Benefit Ratio
							$risk_intervention_annual_benefit_ratio[$i] 		= (float)$risk_intervention_annual_cost[$i] / (float)$risk_intervention_annual_benefit[$i];
							$risk_intervention_annual_benefit_ratio_HAS_SF[$i] 	= (float)$risk_intervention_annual_cost[$i] / (float)$risk_intervention_annual_benefit_HAS_SF[$i];							
							// Annual Cost Benefit Ratio AS values
							$results['interventions'][$in]['risk'][$i]['intervention_annual_benefit_ratio'] 		= round((float)$risk_intervention_annual_benefit_ratio[$i], 2);
							$results['interventions'][$in]['risk'][$i]['intervention_annual_benefit_ratio_HAS_SF'] 	= round((float)$risk_intervention_annual_benefit_ratio_HAS_SF[$i], 2);
							
							// Annual Cost Net Benefit
							$risk_intervention_annual_net_benefit[$i] 			= (float)$risk_intervention_annual_benefit[$i] - (float)$risk_intervention_annual_cost[$i];
							$risk_intervention_annual_net_benefit_HAS_SF[$i] 	= (float)$risk_intervention_annual_benefit_HAS_SF[$i] - (float)$risk_intervention_annual_cost[$i];									
							// Annual Cost Net Benefit AS values
							$results['interventions'][$in]['risk'][$i]['intervention_annual_net_benefit'] 			= round((float)$risk_intervention_annual_net_benefit[$i], 2);
							$results['interventions'][$in]['risk'][$i]['intervention_annual_net_benefit_HAS_SF'] 	= round((float)$risk_intervention_annual_net_benefit_HAS_SF[$i], 2);
							// Annual Cost Net Benefit AS money
							$results['interventions'][$in]['risk'][$i]['intervention_annual_net_benefit_money'] 		= $this->makeMoney((float)$risk_intervention_annual_net_benefit[$i]);
							$results['interventions'][$in]['risk'][$i]['intervention_annual_net_benefit_money_HAS_SF'] 	= $this->makeMoney((float)$risk_intervention_annual_net_benefit_HAS_SF[$i]);
							
							// local totals
							$totalContributionCost 								+= $risk_contribution_cost[$i];
							$totalContributionCost_HAS_SF 						+= $risk_contribution_cost_HAS_SF[$i];
							$totalInterventionDuration 							+= $results['interventions'][$in]['risk'][$i]['intervention_duration'];
							$totalInterventionCoverage 							+= $results['interventions'][$in]['risk'][$i]['intervention_coverage'];
							$totalCostPerEmployee 								+= $results['interventions'][$in]['risk'][$i]['cost_per_employee'];
							$totalMorbidityReduction 							+= $results['interventions'][$in]['risk'][$i]['morbidity_reduction'];
							$totalCost 											+= $risk_cost[$i];
							$totalCost_HAS_SF 									+= $risk_cost_HAS_SF[$i];;
							$totalInterventionAnnualCost 						+= $risk_intervention_annual_cost[$i];
							$totalInterventionAnnualBenefit 					+= $risk_intervention_annual_benefit[$i];
							$totalInterventionAnnualBenefit_HAS_SF 				+= $risk_intervention_annual_benefit_HAS_SF[$i];
							$totalInterventionAnnualBenefitRatio 				+= $risk_intervention_annual_benefit_ratio[$i];
							$totalInterventionAnnualBenefitRatio_HAS_SF 		+= $risk_intervention_annual_benefit_ratio_HAS_SF[$i];
							$totalInterventionAnnualNetBenefit 					+= $risk_intervention_annual_net_benefit[$i];
							$totalInterventionAnnualNetBenefit_HAS_SF 			+= $risk_intervention_annual_net_benefit_HAS_SF[$i];
						}
						$i++;
					}
				}
				if ($totalCost != 0){
					// Set intervention name
					$results['interventions'][$in]['name'] 											= $intervention[intervention_name];
					$results['interventions'][$in]['intervention_duration'] 						= (float)$intervention[duration];
					$results['interventions'][$in]['intervention_coverage'] 						= (float)$intervention[coverage];
					
					// local totals
					$results['interventions'][$in]['totalContributionCost'] 						= round($totalContributionCost, 2);
					$results['interventions'][$in]['totalContributionCost_HAS_SF'] 					= round($totalContributionCost_HAS_SF, 2);
					$results['interventions'][$in]['totalInterventionDuration'] 					= round($totalInterventionDuration, 2);
					$results['interventions'][$in]['totalInterventionCoverage'] 					= round($totalInterventionCoverage, 2);
					$results['interventions'][$in]['totalCostPerEmployee'] 							= $this->makeMoney((float)$totalCostPerEmployee);
					$results['interventions'][$in]['totalMorbidityReduction'] 						= round($totalMorbidityReduction, 2);
					$results['interventions'][$in]['totalMortalityReduction'] 						= round($totalMortalityReduction, 2);
					$results['interventions'][$in]['totalCost'] 									= $this->makeMoney((float)$totalCost);
					$results['interventions'][$in]['totalCost_HAS_SF'] 								= $this->makeMoney((float)$totalCost_HAS_SF);
					$results['interventions'][$in]['totalInterventionAnnualCost'] 					= $this->makeMoney((float)$totalInterventionAnnualCost);
					$results['interventions'][$in]['totalInterventionAnnualBenefit'] 				= $this->makeMoney((float)$totalInterventionAnnualBenefit);
					$results['interventions'][$in]['totalInterventionAnnualBenefit_HAS_SF'] 		= $this->makeMoney((float)$totalInterventionAnnualBenefit_HAS_SF);
					$results['interventions'][$in]['totalInterventionAnnualBenefitRatio'] 			= round($totalInterventionAnnualBenefitRatio, 2);
					$results['interventions'][$in]['totalInterventionAnnualBenefitRatio_HAS_SF'] 	= round($totalInterventionAnnualBenefitRatio_HAS_SF, 2);
					$results['interventions'][$in]['totalInterventionAnnualNetBenefit'] 			= $this->makeMoney((float)$totalInterventionAnnualNetBenefit);
					$results['interventions'][$in]['totalInterventionAnnualNetBenefit_HAS_SF'] 		= $this->makeMoney((float)$totalInterventionAnnualNetBenefit_HAS_SF);
					}
				$in++;
			}
		}
		return $results;
	}

}