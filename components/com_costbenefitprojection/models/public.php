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
require_once JPATH_ADMINISTRATOR.'/components/com_costbenefitprojection/helpers/vdm.php' ;

class CostbenefitprojectionModelPublic extends CostbenefitprojectionModelChart
{
	protected function populateState() 
	{		
		parent::populateState();
		
		// Get the input data
		$jinput = JFactory::getApplication()->input;
		
		$country_id = $jinput->post->get('country', NULL, 'INT');
		$number_employees = $jinput->post->get('employees', NULL, 'INT');
		$average_salary = $jinput->post->get('salary', NULL, 'INT');
		
		// Set to state
		$this->setState('country.id', $country_id);
		$this->setState('number_employees', $number_employees);
		$this->setState('average_salary', $average_salary);
	}
	
	public function getChartTabs ()
		{
			return NULL;
		}
		
	public function getTableTabs ()
		{
			return NULL;
		}
		
	public function getItem()
	{
		if (!isset($this->item)) 
                {
					if ($this->getState('country.id') && $this->getState('number_employees') && $this->getState('average_salary')){
						
						$this->item['form'] = false;
						// Add Data
						$this->item['data']					= $this->getData();
						
						// Add Calculations Results
						$this->item['results']				= $this->getResults($this->data);
						
						// Add the items that has no data
						$this->item['noData']['disease'] 	= $this->noDiseaseData;
						$this->item['noData']['risk'] 		= $this->noRiskData;
						$this->item['countries'] 			= $this->getCountryList(false);
					} else {
						$this->item['form'] 				= true;
						$this->item['countries'] 			= $this->getCountryList();
					}
					
					
				}
		 return $this->item;
	}
	
	protected function getData()
	{
		if (!isset($this->data)) {
			
			// Get ID
			$id = $this->getCountryUserId($this->getState('country.id'));
			
			// Check point
			$profileResults = $this->getCountryCheck($id);
			
			if ($profileResults){
				$number_employees = $this->getState('number_employees');
				$average_salary = $this->getState('average_salary');
				
				// set country user id to model item
				$this->data['countryUserId'] = (int)$id;
				// set client id to model
				$this->data['clientId'] = (int)$id;
				$email = JFactory::getUser((int)$id)->email;
				$this->data['Gravatar'] = $this->getGravatar($email, 150);
				
				// set profile results to model item
				foreach ($profileResults as $item => $value){
					$this->data[$item] = $value;
				}
				// get public and published data of country
				$this->data["diseases"] = $this->getDiseases($id);
				$this->data["risks"] = $this->getRisks($id);
				$this->data["interventions"] = $this->getInterventions($id);
				
				//set currency details
				$this->data['currency'] = $this->getCurrency($this->data['currency']);
				
				if ($number_employees || $average_salary){
					$this->data["serviceprovider"] = 0;
					$this->data["number_male"] = $number_employees/2;
					$this->data["number_female"] = $number_employees/2;
					$this->data["working_days"] = 231;
					$this->data["total_salary"] = $number_employees*$average_salary;
					// get needed data
						
					$visitor = array( 	'number_employees' 	=> $number_employees, 
										'total_salary' 		=> $this->makeMoney((float)$this->data["total_salary"]),
										'countryName'		=> ($this->data['publicName']) ? $this->data['publicName']: JFactory::getUser((int)$id)->name,
										'countryEmail' 		=> ($this->data['publicEmail']) ? $this->data['publicEmail']: $email,
										'publicNumber' 		=> ($this->data['publicNumber']) ? $this->data['publicNumber']: '',
										'publicAddress' 	=> ($this->data['publicAddress']) ? $this->data['publicAddress']: '',
										'nameGlobal'		=> JComponentHelper::getParams('com_costbenefitprojection')->get('nameGlobal'),
										'emailGlobal'		=> JComponentHelper::getParams('com_costbenefitprojection')->get('emailGlobal')
									);
					
					// set the needed data
					$close = new Vault();
					$this->data["visitor"] = $close->the($visitor);
				}
				if($this->data["productivity_losses_country"]){
					$this->data["productivity_losses"] = $this->data["productivity_losses_country"];
				}
				if($this->data["healthcare_country"]){
					$this->data["total_healthcare"] = $this->data["total_salary"]*($this->data["healthcare_country"]/100);
				}
				if($this->data["diseases"]){
						sort($this->data["diseases"]); 
				}
				if($this->data["interventions"]){
					sort($this->data["interventions"]); 
				}
				if($this->data["risks"]){
					sort($this->data["risks"]); 
				}
				
				// set the data of the selected diseases
				$this->data['diseasedata'] = $this->getDiseasesData($this->data["diseases"]);	
				
				// set the data of the selected diseases
				$this->data['riskdata'] = $this->getRisksData($this->data["risks"]);
				
				// set the data of the selected diseases
				$this->data['interventiondata'] = $this->getInterventionsData($this->data['clientId']);
				
			} else {
				throw new Exception('ERROR!');
			}
							
       }
	   return $this->data;   
	}
	
	/**
	 * Get the list of countries available.
	 *
	 * @return an array
	 *
	 */
	public function getCountryList($pleaseSelect = true)
	{
		$list = array();
		
		// Get a db connection.
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$query->select(array('a.country_name AS text', 'a.country_id AS value'));
		$query->from('#__costbenefitprojection_countries AS a');
		$query->where('a.published = 1');
		$query->order('a.country_name');
		
		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$options = $db->loadObjectList();
		
		foreach ($options as $option){
			$result = $this->getCountryCheck($this->getCountryUserId($option->value));
			if($result){
				$list[] = $option;
			}
		}
		
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		if ($pleaseSelect){
			array_unshift($list, JHtml::_('select.option', '', JText::_('Please select')));
		}
		return $list;
	}
	
	/**
	 * Check if Country id is correct.
	 *
	 * @return a bool
	 *
	 */
	protected function getCountryCheck($id)
	{
		$profileResults = false;
		if($id){
			// set profile results to model item
			$profile = JUserHelper::getProfile($id)->gizprofile;
			if ($profile[healthcare_country] && $profile[productivity_losses_country]){
				$profileResults = $profile;
			}
		}
		
		return $profileResults;
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
	 * Get diseases that belongs to this country.
	 *
	 * @return an associative array
	 *
	 */
	protected function getDiseases($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		$items = NULL;
		
		if ($id){
			$query
				->select('a.id')
				->from('#__costbenefitprojection_diseasedata AS a')
				->where('a.owner = '.$id.'')
				->where('a.access = 1 AND a.published = 1');
		
			// echo nl2br(str_replace('#__','giz_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			$items = $db->loadColumn();
		} 
		
		return $items;
	}
	
	/**
	 * Get disease data that belongs to this country.
	 *
	 * @return an associative array
	 *
	 */
	protected function getDiseasesData($diseases)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		$diseasedata = NULL;
		
		if (is_array($diseases)){
			$query
				->select('d.disease_name, a.*')
				->from('#__costbenefitprojection_diseasedata AS a')
				->where('a.id IN ('.implode(',', $diseases).')')
				->join('LEFT', '#__costbenefitprojection_diseases AS d ON d.disease_id = a.disease_id ');
			// echo nl2br(str_replace('#__','yvs9m_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			$diseasedata = $db->loadAssocList();
		}
		
		return $diseasedata;
	}
	
	/**
	 * Get risks that belongs to this country.
	 *
	 * @return an associative array
	 *
	 */
	protected function getRisks($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		$items = NULL;
		
		if ($id){
			$query
				->select('a.id')
				->from('#__costbenefitprojection_riskdata AS a')
				->where('a.owner = '.$id.'')
				->where('a.access = 1 AND a.published = 1');
		
			// echo nl2br(str_replace('#__','giz_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			$items = $db->loadColumn();
		} 
		
		return $items;
	}
	
	/**
	 * Get risk data that belongs to this country.
	 *
	 * @return an associative array
	 *
	 */
	protected function getRisksData($risks)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		$riskdata = NULL;
		
		if (is_array($risks)){
			$query
				->select('d.risk_name , a.*')
				->from('#__costbenefitprojection_riskdata AS a')
				->where('a.id IN ('.implode(',', $risks).')')
				->join('LEFT', '#__costbenefitprojection_risk AS d ON d.risk_id = a.risk_id ');
		
			// echo nl2br(str_replace('#__','yvs9m_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			$riskdata = $db->loadAssocList();
		} 
		return $riskdata;
	}
	
	/**
	 * Get interventions that belongs to this country.
	 *
	 * @return an associative array
	 *
	 */
	protected function getInterventions($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		$items = NULL;
		
		if ($id){
			$query
				->select('a.intervention_id as id')
				->from('#__costbenefitprojection_interventions AS a')
				->where('a.owner = '.$id.'')
				->where('a.access = 1 AND a.published = 1');
		
			// echo nl2br(str_replace('#__','giz_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			$items = $db->loadColumn();
		}
		
		return $items;
	}
	
	/**
	 * Get intervention data that belongs to this country.
	 *
	 * @return an associative array
	 *
	 */
	protected function getInterventionsData($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		$interventiondata = NULL;
		
		if ($id){
			$query
				->select('a.*')
				->from('#__costbenefitprojection_interventions AS a')
				->where('a.owner = '.$id.'')
				->where('a.access = 1 AND a.published = 1');
		
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
	
	/**
	 * Get either a Gravatar URL or complete image tag for a specified email address.
	 *
	 * @param string $email The email address
	 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
	 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
	 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
	 * @param boole $img True to return a complete IMG tag False for just the URL
	 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
	 * @return String containing either just a URL or a complete image tag
	 * @source http://gravatar.com/site/implement/images/php/
	 */
	protected function getGravatar( $email, $s = 15, $d = 'identicon', $img = false, $r = 'g', $atts = array("title"=>"To update click here!") ) 
	{
		$url = 'http://www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$s&d=$d&r=$r";
		if ( $img ) {
			$url = '<img src="' . $url . '"';
			foreach ( $atts as $key => $val )
				$url .= ' ' . $key . '="' . $val . '"';
			$url .= ' />';
		}
		return $url;
	}
}