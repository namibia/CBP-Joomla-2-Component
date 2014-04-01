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

class UsersHelper
{
	/**
	 * @var    JObject  A cache for the available actions.
	 * 
	 */
	protected static $actions;

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 */
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_COSTBENEFITPROJECTION_SUBMENU_USERS'),
			'index.php?option=com_costbenefitprojection&view=users',
			$vName == 'users'
		);

		// Groups and Levels are restricted to core.admin
		$canDo = self::getActions();

		if ($canDo->get('core.admin'))
		{
			JSubMenuHelper::addEntry(
				JText::_('COM_COSTBENEFITPROJECTION_SUBMENU_GROUPS'),
				'index.php?option=com_costbenefitprojection&view=groups',
				$vName == 'groups'
			);
			JSubMenuHelper::addEntry(
				JText::_('COM_COSTBENEFITPROJECTION_SUBMENU_LEVELS'),
				'index.php?option=com_costbenefitprojection&view=levels',
				$vName == 'levels'
			);
			JSubMenuHelper::addEntry(
				JText::_('COM_COSTBENEFITPROJECTION_SUBMENU_NOTES'),
				'index.php?option=com_costbenefitprojection&view=notes',
				$vName == 'notes'
			);

			$extension = JRequest::getString('extension');
			JSubMenuHelper::addEntry(
				JText::_('COM_COSTBENEFITPROJECTION_SUBMENU_NOTE_CATEGORIES'),
				'index.php?option=com_categories&extension=com_costbenefitprojection',
				$vName == 'categories' || $extension == 'com_costbenefitprojection'
			);
		}
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return  JObject
	 *
	 * @todo    Refactor to work with notes
	 */
	public static function getActions()
	{
		if (empty(self::$actions))
		{
			$user = JFactory::getUser();
			self::$actions = new JObject;

			$actions = JAccess::getActions('com_costbenefitprojection');

			foreach ($actions as $action)
			{
				self::$actions->set($action->name, $user->authorise($action->name, 'com_costbenefitprojection'));
			}
		}

		return self::$actions;
	}

	/**
	 * Get a list of filter options for the blocked state of a user.
	 *
	 * @return  array  An array of JHtmlOption elements.
	 *
	 */
	static function getStateOptions()
	{
		// Build the filter options.
		$options = array();
		$options[] = JHtml::_('select.option', '0', JText::_('JENABLED'));
		$options[] = JHtml::_('select.option', '1', JText::_('JDISABLED'));

		return $options;
	}

	/**
	 * Get a list of filter options for the activated state of a user.
	 *
	 * @return  array  An array of JHtmlOption elements.
	 *
	 */
	static function getActiveOptions()
	{
		// Build the filter options.
		$options = array();
		$options[] = JHtml::_('select.option', '0', JText::_('COM_COSTBENEFITPROJECTION_ACTIVATED'));
		$options[] = JHtml::_('select.option', '1', JText::_('COM_COSTBENEFITPROJECTION_UNACTIVATED'));

		return $options;
	}

	/**
	 * Get a list of countries for filtering.
	 *
	 * @return  array  An array of Country names.
	 *
	 */
	static function getCountry()
	{
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT a.country_id AS value, a.country_name AS text' .
			' FROM #__costbenefitprojection_countries AS a' .
			' WHERE a.published = 1' .
			' GROUP BY a.country_id, a.country_name' .
			' ORDER BY a.country_name ASC'
		);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		return $options;
	}
	
	/**
	 * Build an SQL query to get the id's of specific service providers members.
	 *
	 * @return  int  An array of of ID's
	 *
	 */
	
	static function getMembers($agent = NULL, $country = NULL)
	{				
		// Get a db connection.
		$db = JFactory::getDbo();
		
		// get only clients
		$groups = JComponentHelper::getParams('com_costbenefitprojection')->get('client');
		// Create a new query object.
		$query = $db->getQuery(true);
		
		// Select all articles for users who have a username which starts with 'a'.
		// Order it by the created date.
		if(is_numeric($agent)){
			$query->select('a.user_id AS value, b.name AS text');
			$query->from('#__user_profiles AS a');
			$query->join('LEFT', '#__users AS b ON b.id = a.user_id');
			$query->where('a.profile_key LIKE \'%gizprofile.serviceprovider%\'');
			$query->where('a.profile_value = \'"'.$agent.'"\'');
			$query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = a.user_id');
			$query->where('map2.group_id IN ('.implode(',', $groups).')');
		} elseif(is_numeric($country)) {
			$query->select('a.user_id AS value, b.name AS text');
			$query->from('#__user_profiles AS a');
			$query->join('LEFT', '#__users AS b ON b.id = a.user_id');
			$query->where('a.profile_key LIKE \'%gizprofile.country%\'');
			$query->where('a.profile_value = \'"'.$country.'"\'');
			$query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = a.user_id');
			$query->where('map2.group_id IN ('.implode(',', $groups).')');
		} else {
			$query->select('b.id AS value, b.name AS text');
			$query->from('#__users AS b');
			$query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = b.id');
			$query->where('map2.group_id IN ('.implode(',', $groups).')');
			$query->where('b.block = 0');
		}
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects.
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		return $options;
	}
	
	/**
	 * Get a list of Service Providers for filtering.
	 *
	 * @return  array  An array of Service Providers names.
	 *
	 */
	static function getServiceProviders($countryID = NULL)
	{
		$options = array();

		// Get a db connection.
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		if (!$countryID){
			$query
				->select('a.id AS value, a.name AS text')
				->from('#__users AS a')
				->join('INNER', '#__user_usergroup_map AS b ON (a.id = b.user_id)')
				->where('b.group_id = 6')
				->where('a.block = 0')
				->order('a.name');
		} else {
			$query
				->select('a.id AS value, a.name AS text')
				->from('#__users AS a')
				->join('LEFT', '#__user_profiles AS c ON (a.id = c.user_id)')
				->where('profile_key LIKE \'%gizprofile.country%\'')
				->where('profile_value = \'"'.$countryID.'"\'')
				->join('INNER', '#__user_usergroup_map AS b ON (a.id = b.user_id)')
				->where('b.group_id = 6')
				->where('a.block = 0')
				->order('a.name');
		}	
		
		//echo nl2br(str_replace('#__','yvs9m_',$query)); die; 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		return $options;
	}

	/**
	 * Creates a list of range options used in filter select list
	 * used in com_costbenefitprojection on users view
	 *
	 * @return  array
	 *
	 */
	public static function getRangeOptions()
	{
		$options = array(
			JHtml::_('select.option', 'today', JText::_('COM_COSTBENEFITPROJECTION_OPTION_RANGE_TODAY')),
			JHtml::_('select.option', 'past_week', JText::_('COM_COSTBENEFITPROJECTION_OPTION_RANGE_PAST_WEEK')),
			JHtml::_('select.option', 'past_1month', JText::_('COM_COSTBENEFITPROJECTION_OPTION_RANGE_PAST_1MONTH')),
			JHtml::_('select.option', 'past_3month', JText::_('COM_COSTBENEFITPROJECTION_OPTION_RANGE_PAST_3MONTH')),
			JHtml::_('select.option', 'past_6month', JText::_('COM_COSTBENEFITPROJECTION_OPTION_RANGE_PAST_6MONTH')),
			JHtml::_('select.option', 'past_year', JText::_('COM_COSTBENEFITPROJECTION_OPTION_RANGE_PAST_YEAR')),
			JHtml::_('select.option', 'post_year', JText::_('COM_COSTBENEFITPROJECTION_OPTION_RANGE_POST_YEAR')),
		);
		return $options;
	}
}