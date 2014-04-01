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

jimport('joomla.application.component.modelitem');
jimport('joomla.application.component.helper');

class CostbenefitprojectionModelData extends JModelItem
{
	protected $item;
	protected $data;
	protected $noRiskData;
	protected $noDiseaseData;
			
	public function getItem()
	{
		if (!isset($this->item)) 
                {
					// check point to see if the user is allowed to edit this item
					$user = $this->getUser();
					
					if ($user){
						if ($user['type'] != 'client'){
							throw new Exception('ERROR! You can only access these data via the backend. <a href="javascript:history.go(-1)">Go back</a>');
						}
					} else {
						throw new Exception('ERROR! You must login to view this. <a href="javascript:history.go(-1)">Go back</a>');
					}
					
					$input = JFactory::getApplication()->input;					
					$this->item['tmpl'] = $input->get('tmpl', 'normal', 'word');
					$this->item['type'] = $input->get('type', 0, 'INT');
										
					// Add Data
					$this->item['data']					= $this->getData();
					// Add the items that has no data
					$this->item['noData']['disease'] 	= $this->noDiseaseData;
					$this->item['noData']['risk'] 		= $this->noRiskData;
										
				}
		 return $this->item;
	}
	
	protected function getData()
	{
		if (!isset($this->data)) {	
			$id = JFactory::getUser()->id;
			// set country user id to model item
			$this->data['countryUserId'] = $this->getCountryUserId(JUserHelper::getProfile($id)->gizprofile[country]);
			// set client id to model
			$this->data['clientId'] = (int)$id;
			// set profile results to model item
			$profileResults = JUserHelper::getProfile($id)->gizprofile;
			$this->data["diseases"] = array();
			$this->data["risks"] = array();
			$this->data["interventions"] = array();
			foreach ($profileResults as $item => $value){
				$this->data[$item] = $value;
			}
			if(is_array($this->data["diseases"])){
					sort($this->data["diseases"]); 
			}
			if(is_array($this->data["interventions"])){
				sort($this->data["interventions"]); 
			}
			if($this->data["risks"]){
				sort($this->data["risks"]); 
			}
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
		$id = JFactory::getUser()->id;
		
		if ($id !== 0){
			return $user = $this->userIs($id);
		} else {
			return false;
		}
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
		$userIs['id'] = $id;
		$userIs['country'] = JUserHelper::getProfile($id)->gizprofile[country];
		$userIs['groups'] = JUserHelper::getUserGroups($id);
		$userIs['name'] = JFactory::getUser($id)->name;
		
		$AppGroups['admin'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('admin');
		$AppGroups['country'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('country');
		$AppGroups['service'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('service');
		$AppGroups['client'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('client');

		$admin_user = (count(array_intersect($AppGroups['admin'], $userIs['groups']))) ? true : false;
		$country_user = (count(array_intersect($AppGroups['country'], $userIs['groups']))) ? true : false;
		$service_user = (count(array_intersect($AppGroups['service'], $userIs['groups']))) ? true : false;
		$client_user = (count(array_intersect($AppGroups['client'], $userIs['groups']))) ? true : false;
		
		if ($admin_user){
			$userIs['type'] = 'admin';
		} elseif ($country_user){
			$userIs['type'] = 'country';
		} elseif ($service_user){
			$userIs['type'] = 'service';
		} elseif ($client_user) {
			$userIs['type'] = 'client';
			$userIs['service'] = JUserHelper::getProfile($id)->gizprofile[serviceprovider];
		}
		
		return $userIs;
	}
	
	/**
	 * Build an SQL query to get the name of a country.
	 *
	 * @return  a string
	 *
	 */
	public function countryName($userID = NULL, $countryID = NULL)
	{
		if ($userID){
			$countryID = JUserHelper::getProfile($userID)->gizprofile[country];
		}
		
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all articles for users who have a username which starts with 'a'.
		// Order it by the created date.
		$query
			->select('a.country_name')
			->from('#__costbenefitprojection_countries AS a')
			->where('a.country_id = '.$countryID.'');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects.
		$object = $db->loadObject();
		
		return $object->country_name;
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
				->select('d.disease_name, a.*, u.name, c.diseasecategory_name')
				->from('#__costbenefitprojection_diseasedata AS a')
				->join('LEFT', '#__costbenefitprojection_diseases AS d ON d.disease_id = a.disease_id ')
				->join('LEFT', '#__costbenefitprojection_diseasecategories AS c ON c.diseasecategory_id = d.diseasecategory_id ')
				->join('LEFT', '#__users AS u ON u.id = a.checked_out')
				->where('a.owner = \''.$id.'\'')
				->where('a.disease_id IN ('.implode(',', $diseases).')');
		 
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
						$data['owner_id'] 	= $data['owner'];
						$data['owner'] 		= JFactory::getUser($data['owner'])->name;
						$resultA[$i] 		= $data;
						$foundA[$i]			= $data['disease_id'];
					}
				}
				
				foreach ($countrydiseasedata as $data){
					if ($diseases[$i] == $data['disease_id']){
						$data['owner_id'] 	= $data['owner'];
						$data['owner'] 		= $this->countryName($data['owner']);
						$resultB[$i] 		= $data;
						$foundB[$i]			= $data['disease_id'];
					}
				}
				$i++;	
			}
			
			// remove found data
			if (is_array($foundB) || is_array($foundA)){
				$found = array_merge((array)$foundA, (array)$foundB);
				$found = array_unique((array)$found);
				foreach ($found as $remove){
					// if found remove from diseases array
					unset($diseases[array_search($remove,$diseases)] );
				}
			}
			
			if (!empty($diseases)){
				// set diseases that has no data
				$i = 0;
				$data = NULL;
				foreach ($diseases as $disease){
					$data['name'][$disease] = $this->getDiseasename($disease);
					$data['nr'] =  $i + 1;
					$i++;
				}
				$this->noDiseaseData = $data;
			} else {
				$this->noDiseaseData = array( 'name' => 'none', 'nr' => 0 );
			}
		}
		 
		$result = (array_merge((array)$resultA, (array)$resultB));

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
				->select('d.risk_name , a.*, u.name')
				->from('#__costbenefitprojection_riskdata AS a')
				->where('a.owner = \''.$id.'\'')
				->where('a.risk_id IN ('.implode(',', $risks).')')
				->join('LEFT', '#__costbenefitprojection_risk AS d ON d.risk_id = a.risk_id ')
				->join('LEFT', '#__users AS u ON u.id = a.checked_out');
		
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
						$data['owner_id'] 	= $data['owner'];
						$data['owner'] 		= JFactory::getUser($data['owner'])->name;
						$resultA[$i] 		= $data;
						$foundA[$i]			= $data['risk_id'];
					}
				}
				foreach ($countryriskdata as $keyt => $data){
					if ($risks[$i] == $data['risk_id']){
						$data['owner_id'] 	= $data['owner'];
						$data['owner'] 		= $this->countryName($data['owner']);
						$resultB[$i] 		= $data;
						$foundB[$i]			= $data['risk_id'];
					}
				}				
				$i++;	
			}
			
			// remove data found
			if (is_array($foundB) || is_array($foundA)){
				$found = array_merge((array)$foundA, (array)$foundB);
				$found = array_unique((array)$found);
				foreach ($found as $remove){
					// if found remove from diseases array
					unset($risks[array_search($remove,$risks)] );
				}
			}
			
			if (!empty($risks)){
				// set risk that has no data
				$i = 0;
				$data = NULL;
				foreach ($risks as $risk){
					$data['name'][$risk] = $this->getRiskname($risk);
					$data['nr'] =  $i + 1;
					$i++;
				}
				$this->noRiskData = $data;
			} else {
				$this->noRiskData = array( 'name' => 'none', 'nr' => 0 );
			}
		}
		 
		$result = (array_merge((array)$resultA, (array)$resultB));

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
			// Convert the values from jason to an array.
			foreach ($interventiondata as $key => &$item){
				$item['params'] = json_decode($item['params']);
				$owner = $this->userIs($item["owner"]);
				if ($owner['type'] == 'country'){
					$interventiondata[$key]["owner_id"] = $item['owner'];
					$item["owner"] = $this->countryName($item['owner']);
				} else {
					$interventiondata[$key]["owner_id"] = $item['owner'];
					$item["owner"] = JFactory::getUser($item["owner"])->name;
				}
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
}