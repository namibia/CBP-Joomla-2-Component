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

class CostbenefitprojectionModelRisksdata extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				't.id',
				'a.risk_name',
				't.prevalence_male',
				't.prevalence_female',
				't.annual_unproductive_male',
				't.annual_unproductive_female',
				't.prevalence_scaling_factor_male',
				't.prevalence_scaling_factor_female',
				't.unproductive_scaling_factor_male',
				't.unproductive_scaling_factor_female',
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
			$item->itemOwner = $this->userIs($item->owner);
			$item->countryName = $this->countryName('',$item->country);
			if ($item->itemOwner['type'] == 'country'){
				$item->owner = $item->countryName;
			} else {
				$item->owner = $item->countryName.' &#8594; '. $item->itemOwner['name'];
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

		$query->select('t.*, a.risk_name, u.name');
		$query->from('#__costbenefitprojection_riskdata AS t');
		$query->join('LEFT', '#__costbenefitprojection_risk AS a USING(risk_id)');
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
				"(a.risk_name LIKE {$search})";

			$query->where($field_searches);
		}
		
		$user = JFactory::getUser();
		$userIs = $this->userIs($user->id);
		
		// load only related country
		if ($userIs['type'] == 'country' || $userIs['type'] == 'service'){
			$query->where('t.country = '.$userIs['country'].'');
		}
		
		// load only members of related service provider
		if ($userIs['type'] == 'service'){
			$ids = $this->getServiceProviderUserIds($userIs['id']);
			$ids[] = (string)$this->getServiceProviderCountryId($userIs['country']);
			if($ids){
				// Add the clauses to the query.
				$query->where('t.owner IN ('.implode(',', $ids).')');
			} else {
				// load no users.
				$query->where('t.owner LIKE \'0\'');
			}
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
	 * Build an SQL query to get the id's of specific service provider.
	 *
	 * @return  int  An array of of ID's
	 *
	 */
	
	protected function getServiceProviderUserIds($agent = null)
	{				
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Set query.
		$query
			->select('user_id')
			->from('#__user_profiles')
			->where('profile_key LIKE \'%gizprofile.serviceprovider%\'')
			->where('profile_value = \'"'.$agent.'"\'');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// loadColumn() returns an indexed array from a single column in the table.
		$column = $db->loadColumn();

		return $column;
	}
	
	/**
	 * Build an SQL query to get the id's of specific service provider country.
	 *
	 * @return  int  An array of of ID's
	 *
	 */
	
	protected function getServiceProviderCountryId($country = null)
	{				
		$countryGroups = JComponentHelper::getParams('com_costbenefitprojection')->get('country');
		$countryIds = $this->getGroupUserIds($countryGroups);
		
		foreach ($countryIds as $id){
			if ( $country == JUserHelper::getProfile($id)->gizprofile[country]){
				return $id;
			}
		}
	}
	
	/**
	 * Build an SQL query to get the id's of groups.
	 *
	 * @return  int  An array of of ID's
	 *
	 */
	
	protected function getGroupUserIds($groups = null)
	{		
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all articles for users who have a username which starts with 'a'.
		// Order it by the created date.
		$query
			->select('a.id')
			->from('#__users AS a')
			->join('INNER', '#__user_usergroup_map AS b ON (b.user_id = a.id)')
			->where('b.group_id IN ('.implode(',', $groups).')');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// loadColumn() returns an indexed array from a single column in the table.
		$column = $db->loadColumn();

		return $column;
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
			$query->update($db->quoteName('#__costbenefitprojection_riskdata'))->set($fields)->where($conditions); 
				 
			$db->setQuery($query);
			 
			$result = $db->query();
		}
		
		return true;
	}
}