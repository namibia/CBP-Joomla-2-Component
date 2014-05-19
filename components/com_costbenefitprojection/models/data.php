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
			foreach ($profileResults as $item => $value){
				$this->data[$item] = $value;
			}
			if(is_array($this->data["diseases"])){
					sort($this->data["diseases"]); 
			}
			// set the data of the selected diseases
			$this->data['diseasedata'] = $this->getDiseasedata($this->data['clientId']);
			
			// set the data of the selected diseases
			$this->data['interventiondata'] = $this->getInterventiondata($this->data['clientId']);				
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
	protected function getDiseasedata($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		$diseasedata = NULL;
		
		if ($id){
			$query
				->select('d.disease_name, a.*, u.name, c.diseasecategory_name')
				->from('#__costbenefitprojection_diseasedata AS a')
				->join('LEFT', '#__costbenefitprojection_diseases AS d ON d.disease_id = a.disease_id ')
				->join('LEFT', '#__costbenefitprojection_diseasecategories AS c ON c.diseasecategory_id = d.diseasecategory_id ')
				->join('LEFT', '#__users AS u ON u.id = a.checked_out')
				->where('a.owner = \''.$id.'\'');
		 
			// echo nl2br(str_replace('#__','yvs9m_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			$results = $db->loadAssocList();
			
			if($results){
				foreach($results as $key => &$result){
					$result['owner_id'] 	= $result['owner'];
					$result['owner'] 		= JFactory::getUser($result['owner'])->name;
					$result['params'] 		= json_decode($result['params'], true);
					foreach($result['params'] as $param_key => $param){
						$result[$param_key] = $param;
					}
					unset($result['params']);
				}
			}
			return $results;
		}
		
		return false;
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
	 * Get intervention data that belongs to this client.
	 *
	 * @return an associative array
	 *
	 */
	protected function getInterventiondata($id)
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
					} 
				}
				$item['params'] = array( 'nr_diseases' => sizeof($item['diseasedata'] ) );			
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
}