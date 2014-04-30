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

class CostbenefitprojectionModelDiseases extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				't.ref',
				't.disease_name',
				't.disease_description',
				't.disease_id',
				'a.diseasecategory_name',
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
		$user = JFactory::getUser();
		$user_groups = JUserHelper::getUserGroups($user->id);

		$AppGroups['service'] = JComponentHelper::getParams('com_costbenefitprojection')->get('service');		
		
		$serviceProvider = (count(array_intersect($AppGroups['service'], $user_groups))) ? true : false;

		if ($serviceProvider){
			$this->setError(JText::_('COM_COSTBENEFITPROJECTION_NO_PERMISSION'));
			return false;
		}
		
		// check in items
		$this->checkInNow();
		
		$items = parent::getItems();

		foreach ($items as &$item) {
			$item->url = 'index.php?option=com_costbenefitprojection&amp;task=disease.edit&amp;disease_id=' . $item->disease_id;
			$item->disease_description = $this->truncate($item->disease_description);
			
			if ($item->created_by == 0){
				$item->created_by = '';
				$item->createduser = '';
				$item->created_on = '';
			} else {
				$item->createduser = 'index.php?option=com_costbenefitprojection&task=user.edit&id=' . $item->created_by;
				$user = JFactory::getUser($item->created_by);
				$item->created_by = $user->name;
			}
			
			if ($item->modified_by == 0){
				$item->modified_by = '';
				$item->modifieduser = '';
				$item->modified_on = '';
			} else {
				$item->modifieduser = 'index.php?option=com_costbenefitprojection&task=user.edit&id=' . $item->modified_by;
				$user = JFactory::getUser($item->modified_by);
				$item->modified_by = $user->name;
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

		$query->select('t.*, a.diseasecategory_name, u.name');
		$query->from('#__costbenefitprojection_diseases AS t');
		$query->join('LEFT', '#__costbenefitprojection_diseasecategories AS a USING(diseasecategory_id)');
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
				"(t.disease_name LIKE {$search} OR " .
				"t.disease_description LIKE {$search} OR " .
				"a.diseasecategory_name LIKE {$search})";

			$query->where($field_searches);
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


		parent::populateState($ordering, $direction);
	}

	protected function truncate($text, $length = 100) 
	{
		$length = abs((int)$length);
		if(strlen($text) > $length) {
			$text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
		}
		return($text);
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
			$query->update($db->quoteName('#__costbenefitprojection_diseases'))->set($fields)->where($conditions); 
				 
			$db->setQuery($query);
			 
			$result = $db->query();
		}
		
		return true;
	}
}