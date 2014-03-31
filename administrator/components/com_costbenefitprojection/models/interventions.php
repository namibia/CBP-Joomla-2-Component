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

jimport('joomla.application.component.modellist');
jimport('joomla.application.component.helper');

class CostbenefitprojectionModelInterventions extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				't.intervention_name',
				't.intervention_id',
				't.duration',
				't.coverage',
				't.created_by',
				't.created_on',
				't.modified_by',
				't.modified_on',
				't.published'
			);
		}

		parent::__construct($config);
	}

	public function getItems()
	{
		// check in items
		$this->checkInNow();
		
		$items = parent::getItems();

		foreach ($items as &$item) {
			$item->url = 'index.php?option=com_costbenefitprojection&amp;task=intervention.edit&amp;intervention_id=' . $item->intervention_id;
			
			if ($item->created_by == 0){
				$item->created_by = '';
				$item->createduser = '';
				$item->created_on = '';
			} else {
				$item->createduser = 'index.php?option=com_costbenefitprojection&task=user.edit&id=' . $item->created_by;
				$user = JFactory::getUser($item->created_by);
				$item->created_by_id =  $item->created_by;
				$item->created_by = $user->name;
			}
			
			if ($item->modified_by == 0){
				$item->modified_by = '';
				$item->modifieduser = '';
				$item->modified_on = '';
			} else {
				$item->modifieduser = 'index.php?option=com_costbenefitprojection&task=user.edit&id=' . $item->modified_by;
				$user = JFactory::getUser($item->modified_by);
				$item->modified_by_id = $item->modified_by;
				$item->modified_by = $user->name;
			}
			
			$item->itemOwner = $this->userIs($item->owner);
			$item->countryName = $this->countryName('',$item->country);
			if ($item->itemOwner['type'] == 'country'){
				$item->owner_id = $item->owner;
				$item->owner = $item->countryName;
			} else {
				$item->owner_id = $item->owner;
				$item->owner = $item->countryName.' &#8594; '. $item->itemOwner['name'];
			}
			
			// Convert the vlues ftom jason to an array.
									
			$item->params = json_decode($item->params);
			if ($item->params){
				$d = 0;
				$r =0;
				foreach ($item->params as $key => $p){
					$pdata = explode("_", $key);
					if ($pdata[0] == 'disease'){
						$diseasedata = $item->diseasedata;
						if(is_array($diseasedata)){
							$dname = $this->getDiseasename($pdata[2]);
							$found = false;
							foreach ($diseasedata as $alreadykey => $alreadyData) {
								if ($alreadyData['name'] == $dname) {
									$loaction = $alreadykey;
									$found = true;
									break;
								}
							}
							
							if ($found === false) {
								$item->diseasedata[$d] = array('name' => $this->getDiseasename($pdata[2]),$pdata[1] => $p);
								$d++;
							} elseif ($found){
								$item->diseasedata[$loaction] = array_merge($item->diseasedata[$loaction], array($pdata[1] => $p));
							}
							
						} elseif ($d == 0) {
							$item->diseasedata[$d] = array('name' => $this->getDiseasename($pdata[2]),$pdata[1] => $p);
							$d++;
						}
					} elseif ($pdata[0] == 'risk'){
						$riskdata = $item->riskdata;
						if(is_array($riskdata)){
							$rname = $this->getRiskname($pdata[2]);
							$found = false;
							foreach ($riskdata as $alreadykey => $alreadyData) {
								if ($alreadyData['name'] == $rname) {
									$loaction = $alreadykey;
									$found = true;
									break;
								}
							}
							
							if ($found === false) {
								$item->riskdata[$d] = array('name' => $this->getRiskname($pdata[2]),$pdata[1] => $p);
								$d++;
							} elseif ($found){
								$item->riskdata[$loaction] = array_merge($item->riskdata[$loaction], array($pdata[1] => $p));
							}
							
						} elseif ($r == 0) {
							$item->riskdata[$d] = array('name' => $this->getRiskname($pdata[2]),$pdata[1] => $p);
							$d++;
						}
					}
				}
				$item->params = array( 'nr_diseases' => sizeof($item->diseasedata), 'nr_risks' => sizeof($item->riskdata) );
			}
			$item->disease_id = json_decode($item->disease_id);
			$item->risk_id = json_decode($item->risk_id);
			
		}
		return $items;
	}
	
	public function getUser()
	{
		// get user
		$user = JFactory::getUser();
		
		return $user = $this->userIs($user->id);
	}
	
	public function getListQuery()
	{
		$query = parent::getListQuery();

		$query->select('t.*, u.name');
		$query->from('#__costbenefitprojection_interventions AS t');
		$query->join('LEFT', '#__users AS u ON u.id = t.checked_out');

		$published = $this->getState('filter.published');

		if ($published == '') {
			$query->where('(t.published = 1 OR t.published = 0)');
		} else if ($published != '*') {
			$published = (int) $published;
			$query->where("(t.published = '{$published}')");
		}
		
		$search = $this->getState('filter.search');

		$db = $this->getDbo();

		if (!empty($search)) {
			$search = $db->Quote('%'.$db->getEscaped($search, true).'%');

			$field_searches =
				"(t.intervention_name LIKE {$search} OR " .
				"t.intervention_description LIKE {$search})";

			$query->where($field_searches);
		}
		
		// Filter by countries
		$selectedCountry = $this->getState('filter.country');
		if (!empty($selectedCountry))
		{
			if ($selectedCountry)
			{	
				// Add the clauses to the query.
				$query->where('t.country = '.$selectedCountry.'');
			} elseif ($selectedCountry != 0) {
				// Add the clauses to the query.
				$query->where('t.country = 0');
			}
		}
		
		// Filter by Member
		$member = $this->getState('filter.member');
		if (!empty($member))
		{
			if ($member)
			{	
				// Add the clauses to the query.
				$query->where('t.owner = '. $member .'');
			} elseif ($member != 0) {
				// Add the clauses to the query.
				$query->where('t.owner = 0');
			}
		}

		// Column ordering
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');

		if ($orderCol != '') {
			$query->order($db->getEscaped($orderCol.' '.$orderDirn));
		}

		return $query;
	}

	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published');
		$this->setState('filter.published', $published);
		
		$state = $this->getUserStateFromRequest($this->context.'.filter.country', 'filter_country');
		$this->setState('filter.country', $state);
		
		$state = $this->getUserStateFromRequest($this->context.'.filter.member', 'filter_member');
		$this->setState('filter.member', $state);


		parent::populateState($ordering, $direction);
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
		
		$AppGroups['admin'] = JComponentHelper::getParams('com_costbenefitprojection')->get('admin');
		$AppGroups['country'] = JComponentHelper::getParams('com_costbenefitprojection')->get('country');
		$AppGroups['service'] = JComponentHelper::getParams('com_costbenefitprojection')->get('service');		
		
		$admin_user = (count(array_intersect($AppGroups['admin'], $userIs['groups']))) ? true : false;
		$country_user = (count(array_intersect($AppGroups['country'], $userIs['groups']))) ? true : false;
		$service_user = (count(array_intersect($AppGroups['service'], $userIs['groups']))) ? true : false;
		
		if ($admin_user){
			$userIs['type'] = 'admin';
		} elseif ($country_user){
			$userIs['type'] = 'country';
		} elseif ($service_user){
			$userIs['type'] = 'service';
		} else {
			$userIs['service'] = JUserHelper::getProfile($id)->gizprofile[serviceprovider];
		}
		
		return $userIs;
	}
	
	protected function getDiseasename($id)
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
	
	protected function getRiskname($id)
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('risk_alias');
		$query->from('#__costbenefitprojection_risk');;
		$query->where('risk_id = \''.$id.'\'');

		$db->setQuery($query);

		$name = $db->loadResult();

		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		$name = ucwords(str_replace('-',' ',$name));
		return $name;
	}
		
	/**
	 * Build an SQL query to checkin all items left chekced out longer then a day.
	 *
	 * @return  a bool
	 *
	 */
	protected function checkInNow()
	{
		// Get set check in time
		$time 	= JComponentHelper::getParams('com_costbenefitprojection')->get('check_in');
		
		if ($time){
			// Get Yesterdays date
			$date =& JFactory::getDate()->modify($time)->toSql();	
	
			// Get a db connection.
			$db = JFactory::getDbo();
			
			$query = $db->getQuery(true);
			 
			// Fields to update.
			$fields = array(
				$db->quoteName('checked_out_time') . '=\'0000-00-00 00:00:00\'',
				$db->quoteName('checked_out') . '=0'
			);
			 
			// Conditions for which records should be updated.
			$conditions = array(
				$db->quoteName('checked_out') . '!=0', 
				$db->quoteName('checked_out_time') . '<\''.$date.'\''
			);
			
			// Check table
			$query->update($db->quoteName('#__costbenefitprojection_interventions'))->set($fields)->where($conditions); 
				 
			$db->setQuery($query);
			 
			$result = $db->query();
		}
		
		return true;
	}
}