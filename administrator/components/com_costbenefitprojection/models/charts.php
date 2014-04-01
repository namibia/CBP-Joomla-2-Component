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

jimport('joomla.application.component.modellist');
jimport('joomla.application.component.helper');

class CostbenefitprojectionModelCharts extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'a.id',
				'a.name'
			);
		}

		parent::__construct($config);
	}

	public function getItems()
	{
		//$user = JFactory::getUser();
		//$user_groups = JUserHelper::getUserGroups($user->id);
		
		$items = parent::getItems();
		
		if($items){
			foreach ($items as &$item) {
				$item->url = 'index.php?option=com_costbenefitprojection&view=chart&amp;id=' . $item->id;
				$serviceprovider = JUserHelper::getProfile($item->id)->gizprofile[serviceprovider];
				$country = $this->countryName($item->id);
				
				if ($serviceprovider)
				{
					$item->serviceprovider = JFactory::getUser($serviceprovider)->name;
				}
				
				if ($country)
				{
					$item->country = $country;
				}
			}
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

		$query->select('a.id, a.name');
		$query->from('#__users AS a');
		
		$user = $this->getUser();
		
		if ($user['type'] == 'country'){
			$ids = $this->getMemberIds('', $user['country']);
		} elseif ($user['type'] == 'service'){
			$ids = $this->getMemberIds($user['id']);
		} elseif ($user['type'] == 'admin'){
			$ids = $this->getMemberIds();
		}

		if($ids){
			// Add the clauses to the query.
			$query->where('a.id IN ('.implode(',', $ids).')');
		} else {
			// load no users.
			$query->where('a.id LIKE \'0\'');	
		}
		
		$search = $this->getState('filter.search');

		$db = $this->getDbo();

		if (!empty($search)) {
			$search = '%' . $db->getEscaped($search, true) . '%';

			$field_searches =
				"(a.name LIKE '{$search}')";

			$query->where($field_searches);
		}
		
		// Filter by countries
		$selectedCountry = $this->getState('filter.country');
		if (!empty($selectedCountry))
		{
			$countryMembers = $this->countryMembers($selectedCountry);
			if ($countryMembers)
			{	
				// Add the clauses to the query.
				$query->where('a.id IN ('.implode(',', $countryMembers).')');
			} elseif (!$countryMembers && $selectedCountry != 0) {
				// Add the clauses to the query.
				$query->where('a.id = 0');
			}
		}
		
		// Filter by Service Provider
		$serviceProvider = $this->getState('filter.serviceProvider');
		if (!empty($serviceProvider))
		{
			$ServiceProviderUserIds = $this->getServiceProviderUserIds($serviceProvider);
			if ($ServiceProviderUserIds)
			{	
				// Add the clauses to the query.
				$query->where('a.id IN ('.implode(',', $ServiceProviderUserIds).')');
			} elseif (!$ServiceProviderUserIds && $serviceProvider != 0) {
				// Add the clauses to the query.
				$query->where('a.id = 0');
			}
		}

		// Column ordering
		$orderCol = $this->getState('list.ordering');
		$orderDirn = $this->getState('list.direction');

		if ($orderCol != '') {
			$query->order($db->getEscaped($orderCol.' '.$orderDirn));
		}
				
		return $query;
	}

	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$state = $this->getUserStateFromRequest($this->context.'.filter.country', 'filter_country');
		$this->setState('filter.country', $state);
		
		$state = $this->getUserStateFromRequest($this->context.'.filter.serviceProvider', 'filter_serviceProvider');
		$this->setState('filter.serviceProvider', $state);


		parent::populateState($ordering, $direction);
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
	
	/**
	 * Build an SQL query to get the id's of specific service providers members.
	 *
	 * @return  int  An array of of ID's
	 *
	 */
	
	protected function getMemberIds($agent = NULL, $country = NULL)
	{				
		// Get a db connection.
		$db = JFactory::getDbo();
		
		// get only clients
		$groups = JComponentHelper::getParams('com_costbenefitprojection')->get('client');;
		
		// Create a new query object.
		$query = $db->getQuery(true);
		
		// Select all articles for users who have a username which starts with 'a'.
		// Order it by the created date.
		if(is_numeric($agent)){
			$query->select('a.user_id');
			$query->from('#__user_profiles AS a');
			$query->where('a.profile_key LIKE \'%gizprofile.serviceprovider%\'');
			$query->where('a.profile_value = \'"'.$agent.'"\'');
			$query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = a.user_id');
			$query->where('map2.group_id IN ('.implode(',', $groups).')');
			//$query->join('LEFT', '#__users AS b ON b.id = a.user_id');
			//$query->where('b.block = 0');
		} elseif(is_numeric($country)) {
			$query->select('a.user_id');
			$query->from('#__user_profiles AS a');
			$query->where('a.profile_key LIKE \'%gizprofile.country%\'');
			$query->where('a.profile_value = \'"'.$country.'"\'');
			$query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = a.user_id');
			$query->where('map2.group_id IN ('.implode(',', $groups).')');
			//$query->join('LEFT', '#__users AS b ON b.id = a.user_id');
			//$query->where('b.block = 0');
		} else {
			$query->select('b.id AS user_id');
			$query->from('#__users AS b');
			$query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = b.id');
			$query->where('map2.group_id IN ('.implode(',', $groups).')');
			$query->where('b.block = 0');
		}
		// echo nl2br(str_replace('#__','yvs9m_',$query)); die; 
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects.
		$rows = $db->loadObjectList();

		$userIDs = array();
		$i = 0;
		foreach ($rows as $row) {
		  $userIDs[$i] = $row->user_id;
		  $i++;
		}
		
		return $userIDs;
	}
	
	/**
	 * Build an SQL query to get the name of a country.
	 *
	 * @return  a string
	 *
	 */
	protected function countryName($userID)
	{
		$countryID = JUserHelper::getProfile($userID)->gizprofile[country];
		
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
	 * Build an SQL query to get the id's of specific service provider.
	 *
	 * @return  int  An array of of ID's
	 *
	 */
	
	protected function getServiceProviderUserIds($agent = NULL)
	{				

		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all articles for users who have a username which starts with 'a'.
		// Order it by the created date.
		$query
			->select('user_id')
			->from('#__user_profiles')
			->where('profile_key LIKE \'%gizprofile.serviceprovider%\'')
			->where('profile_value = \'"'.$agent.'"\'');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects.
		$rows = $db->loadObjectList();

		$userIDs = array();
		$i = 0;
		foreach ($rows as $row) {
		  $userIDs[$i] = $row->user_id;
		  $i++;
		}
		return $userIDs;
	}
	
	
	/**
	 * Build an SQL query to get the ID of a all members in a country.
	 *
	 * @return  int  An array of of ID's
	 *
	 */
	protected function countryMembers($countryID = NULL)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
		
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all articles for users who have a username which starts with 'a'.
		// Order it by the created date.
		$query
			->select('user_id')
			->from('#__user_profiles')
			->where('profile_key LIKE \'%gizprofile.country%\'')
			->where('profile_value = \'"'.$countryID.'"\'');
		
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects.
		$objects = $db->loadObjectList();
		if ($objects){
			$members = array();
			$i = 0;
			foreach ($objects as $object)
			{
				$members[$i] = $object->user_id;
				$i++;
			}
		} else {
			$members = NULL;
		}

		return $members;
	}
}