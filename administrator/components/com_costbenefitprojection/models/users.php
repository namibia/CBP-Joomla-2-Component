<?php
/**
*	
*	@copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
*	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
* 
* 	@version 	1.0.0 July 24, 2013
* 	@package 	Cost Benefit Projection Tool Application
* 	@customized	Vast Development Method <http://www.vdm.io>
* 	@for		German Development Cooperation <http://www.giz.de>
*
**/

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
jimport('joomla.application.component.helper');

class CostbenefitprojectionModelUsers extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * 
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'username', 'a.username',
				'email', 'a.email',
				'block', 'a.block',
				'sendEmail', 'a.sendEmail',
				'registerDate', 'a.registerDate',
				'lastvisitDate', 'a.lastvisitDate',
				'activation', 'a.activation',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout', 'default'))
		{
			$this->context .= '.'.$layout;
		}
		
		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$active = $this->getUserStateFromRequest($this->context.'.filter.active', 'filter_active');
		$this->setState('filter.active', $active);

		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state');
		$this->setState('filter.state', $state);
		
		$state = $this->getUserStateFromRequest($this->context.'.filter.country', 'filter_country');
		$this->setState('filter.country', $state);
		
		$state = $this->getUserStateFromRequest($this->context.'.filter.serviceProvider', 'filter_serviceProvider');
		$this->setState('filter.serviceProvider', $state);

		$groupId = $this->getUserStateFromRequest($this->context.'.filter.group', 'filter_group_id', null, 'int');
		$this->setState('filter.group_id', $groupId);

		$range = $this->getUserStateFromRequest($this->context.'.filter.range', 'filter_range');
		$this->setState('filter.range', $range);

		$groups = json_decode(base64_decode(JRequest::getVar('groups', '', 'default', 'BASE64')));
		if (isset($groups))
		{
			JArrayHelper::toInteger($groups);
		}
		$this->setState('filter.groups', $groups);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_costbenefitprojection');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.name', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.active');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.country');
		$id	.= ':'.$this->getState('filter.serviceProvider');
		$id	.= ':'.$this->getState('filter.group_id');
		$id .= ':'.$this->getState('filter.range');

		return parent::getStoreId($id);
	}

	/**
	 * Gets the list of users and adds expensive joins to the result set.
	 *

	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (empty($this->cache[$store]))
		{
			$groups = $this->getState('filter.groups');
			$groupId = $this->getState('filter.group_id');
			if (isset($groups) && (empty($groups) || $groupId && !in_array($groupId, $groups)))
			{
				$items = array();
			}
			else
			{
				$items = parent::getItems();
			}

			// Bail out on an error or empty list.
			if (empty($items))
			{
				$this->cache[$store] = $items;

				return $items;
			}

			// Joining the groups with the main query is a performance hog.
			// Find the information only on the result set.

			// First pass: get list of the user id's and reset the counts.
			$userIds = array();
			foreach ($items as $item)
			{
				$userIds[] = (int) $item->id;
				$item->group_count = 0;
				$item->group_names = '';
				$item->note_count = 0;
			}

			// Get the counts from the database only for the users in the list.
			$db = $this->getDbo();
			$query = $db->getQuery(true);

			// Join over the group mapping table.
			$query->select('map.user_id, COUNT(map.group_id) AS group_count')
				->from('#__user_usergroup_map AS map')
				->where('map.user_id IN ('.implode(',', $userIds).')')
				->group('map.user_id')
				// Join over the user groups table.
				->join('LEFT', '#__usergroups AS g2 ON g2.id = map.group_id');

			$db->setQuery($query);

			// Load the counts into an array indexed on the user id field.
			$userGroups = $db->loadObjectList('user_id');

			$error = $db->getErrorMsg();
			if ($error)
			{
				$this->setError($error);

				return false;
			}

			$query->clear()
				->select('n.user_id, COUNT(n.id) As note_count')
				->from('#__user_notes AS n')
				->where('n.user_id IN ('.implode(',', $userIds).')')
				->where('n.state >= 0')
				->group('n.user_id');

			$db->setQuery((string) $query);

			// Load the counts into an array indexed on the aro.value field (the user id).
			$userNotes = $db->loadObjectList('user_id');

			$error = $db->getErrorMsg();
			if ($error)
			{
				$this->setError($error);

				return false;
			}

			// Second pass: collect the group counts into the master items array.
			foreach ($items as &$item)
			{
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
				
				if (isset($userGroups[$item->id]))
				{
					$item->group_count = $userGroups[$item->id]->group_count;
					//Group_concat in other databases is not supported
					$item->group_names = $this->_getUserDisplayedGroups($item->id);
				}

				if (isset($userNotes[$item->id]))
				{
					$item->note_count = $userNotes[$item->id]->note_count;
				}
				
				$item->gravatar = $this->getGravatar($item->email);
			}

			// Add the items to the internal cache.
			$this->cache[$store] = $items;
		}

		return $this->cache[$store];
	}
	
	/**
	 * Build an SQL query to get the name of a country.
	 *
	 * @return  a string
	 *
	 */
	public function countryName($userID)
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
	 * Build an SQL query to get the ID of a country.
	 *
	 * @return  int
	 *
	 */
	public function countryID($countryName)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
		
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all articles for users who have a username which starts with 'a'.
		// Order it by the created date.
		$query
			->select('country_id')
			->from('#__costbenefitprojection_countries')
			->where('country_name LIKE \'%'.$countryName.'%\'');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects.
		$object = $db->loadObject();
		
		return $object->country_id;
	}
	
	/**
	 * Build an SQL query to get the ID of a all members in a country.
	 *
	 * @return  int  An array of of ID's
	 *
	 */
	public function countryMembers($countryID = NULL)
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
		 
		// Load the results as a list of stdClass objects.
		$rows = $db->loadObjectList();

		$userIDs = array();
		$i = 0;
		foreach ($rows as $row) {
		  $userIDs[$i] = $row->id;
		  $i++;
		}
		return $userIDs;
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
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);

		$query->from($db->quoteName('#__users').' AS a');

		// If the model is set to check item state, add to the query.
		$state = $this->getState('filter.state');

		if (is_numeric($state))
		{
			$query->where('a.block = '.(int) $state);
		}

		// If the model is set to check the activated state, add to the query.
		$active = $this->getState('filter.active');

		if (is_numeric($active))
		{
			if ($active == '0')
			{
				$query->where('a.activation = '.$db->quote(''));
			}
			elseif ($active == '1')
			{
				$query->where($query->length('a.activation').' = 32');
			}
		}

		// Filter the items over the group id.
		
		$groupId = $this->getState('filter.group_id');
		$result = array();
		$user = JFactory::getUser();
		$result = JUserHelper::getUserGroups($user->id);
		
		$AppGroups['admin'] = JComponentHelper::getParams('com_costbenefitprojection')->get('admin');
		$AppGroups['country'] = JComponentHelper::getParams('com_costbenefitprojection')->get('country');
		$AppGroups['service'] = JComponentHelper::getParams('com_costbenefitprojection')->get('service');
		$AppGroups['client'] = JComponentHelper::getParams('com_costbenefitprojection')->get('client');
		
		$admin_user = (count(array_intersect($AppGroups['admin'], $result))) ? true : false;
		$country_user = (count(array_intersect($AppGroups['country'], $result))) ? true : false;
		$service_user = (count(array_intersect($AppGroups['service'], $result))) ? true : false;
		
		if ($admin_user){
			$groups = array_merge($AppGroups['country'],$AppGroups['service'],$AppGroups['client']);
		} elseif ($country_user){
			$groups = array_merge($AppGroups['service'],$AppGroups['client']);
		} elseif ($service_user){
			$groups = $AppGroups['client'];
		} else {
			$groups = array(0=>1);
		}

		
		// will use this to upgrade the group selection latter
		// in_array($group_id, $user->getAuthorisedGroups() )

		if ($groupId || isset($groups))
		{
			$query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = a.id');
			$query->group('a.id,a.name,a.username,a.password,a.usertype,a.block,a.sendEmail,a.registerDate,a.lastvisitDate,a.activation,a.params,a.email');

			if ($groupId)
			{
				$query->where('map2.group_id = '.(int) $groupId);
			}

			if (isset($groups))
			{
				$query->where('map2.group_id IN ('.implode(',', $groups).')');
			}
			
			// Load only users relavent to country.
			if ($country_user || $service_user)
			{
				$ids = $this->getGroupUserIds($groups);
				$country = JUserHelper::getProfile($user->id)->gizprofile[country];
				$userList = array();
				$i = 0;
				foreach ($ids as $id){
					
					$countryResult = JUserHelper::getProfile($id)->gizprofile[country];
					if ($countryResult == $country){
						$userList[$i] = $id;
						$i++;
					}
					
				}
	
				// Add the clauses to the query.
				if($userList){
					$query->where('a.id IN ('.implode(',', $userList).')');
				} else {
					$query->where('a.id LIKE \'0\'');
				}
				
			}
			// Load only users relavent to Service Provider.
			if ($service_user)
			{
				$ids = $this->getServiceProviderUserIds($user->id);
				if($ids){
					// Add the clauses to the query.
					$query->where('a.id IN ('.implode(',', $ids).')');
				} else {
					// load no users.
					$query->where('a.id LIKE \'0\'');	
				}
				
			}

		}
		
		
		
		// Filter the items over the search string if set.
		if ($this->getState('filter.search') !== '')
		{
			// Escape the search token.
			$token	= $db->Quote('%'.$db->escape($this->getState('filter.search')).'%');
			
			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'a.name LIKE '.$token;
			$searches[]	= 'a.username LIKE '.$token;
			$searches[]	= 'a.email LIKE '.$token;

			// Add the clauses to the query.
			$query->where('('.implode(' OR ', $searches).')');
		}
		
		// Add filter for registration ranges select list
		$range = $this->getState('filter.range');

		// Apply the range filter.
		if ($range = $this->getState('filter.range'))
		{
			jimport('joomla.utilities.date');

			// Get UTC for now.
			$dNow = new JDate;
			$dStart = clone $dNow;

			switch ($range)
			{
				case 'past_week':
					$dStart->modify('-7 day');
					break;

				case 'past_1month':
					$dStart->modify('-1 month');
					break;

				case 'past_3month':
					$dStart->modify('-3 month');
					break;

				case 'past_6month':
					$dStart->modify('-6 month');
					break;

				case 'post_year':
				case 'past_year':
					$dStart->modify('-1 year');
					break;

				case 'today':
					// Ranges that need to align with local 'days' need special treatment.
					$app	= JFactory::getApplication();
					$offset	= $app->getCfg('offset');

					// Reset the start time to be the beginning of today, local time.
					$dStart	= new JDate('now', $offset);
					$dStart->setTime(0, 0, 0);

					// Now change the timezone back to UTC.
					$tz = new DateTimeZone('GMT');
					$dStart->setTimezone($tz);
					break;
			}

			if ($range == 'post_year')
			{
				$query->where(
					'a.registerDate < '.$db->quote($dStart->format('Y-m-d H:i:s'))
				);
			}
			else
			{
				$query->where(
					'a.registerDate >= '.$db->quote($dStart->format('Y-m-d H:i:s')).
					' AND a.registerDate <='.$db->quote($dNow->format('Y-m-d H:i:s'))
				);
			}
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

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.name')).' '.$db->escape($this->getState('list.direction', 'ASC')));
		
		// echo nl2br(str_replace('#__','giz_',$query)); die;
		return $query;
	}
	//sqlsrv change
	function _getUserDisplayedGroups($user_id)
	{
		$db = JFactory::getDbo();
		$sql = "SELECT title FROM ".$db->quoteName('#__usergroups')." ug left join ".
				$db->quoteName('#__user_usergroup_map')." map on (ug.id = map.group_id)".
				" WHERE map.user_id=".$user_id;

		$db->setQuery($sql);
		$result = $db->loadColumn();
		return implode("\n", $result);
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
	protected function getGravatar( $email, $s = 15, $d = 'identicon', $r = 'g', $img = true, $atts = array("title"=>"To update click here!") ) 
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