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

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
jimport('joomla.application.component.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldInterventionowner extends JFormFieldList
{
	protected $type = 'interventionowner';
	
	public function getOptions()
	{
		$options = array();
		$user = JFactory::getUser();
		$countryID = JUserHelper::getProfile($user->id)->gizprofile[country];
		$user_groups = JUserHelper::getUserGroups($user->id);
		
		$AppGroups['admin'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('admin');
		$AppGroups['country'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('country');
		$AppGroups['service'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('service');
		$AppGroups['client'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('client');

		$admin_user = (count(array_intersect($AppGroups['admin'], $user_groups))) ? true : false;
		$country_user = (count(array_intersect($AppGroups['country'], $user_groups))) ? true : false;
		$service_user = (count(array_intersect($AppGroups['service'], $user_groups))) ? true : false;
		
		$adminLook = array_merge($AppGroups['client'],$AppGroups['country']);
						
		// Get a db connection.
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		
		if ($admin_user){
			$query
				->select('a.id AS value, a.name AS text')
				->from('#__users AS a')
				->join('INNER', '#__user_usergroup_map AS b ON (a.id = b.user_id)')
				->where('b.group_id IN ('.implode(',', $adminLook).')')	
				->order('b.group_id');
		} elseif ($country_user) {
			$query
				->select('a.id AS value, a.name AS text')
				->from('#__users AS a')
				->join('LEFT', '#__user_profiles AS c ON (a.id = c.user_id)')
				->where('c.profile_key LIKE \'%gizprofile.country%\'')
				->where('c.profile_value = \'"'.$countryID.'"\'')
				->join('INNER', '#__user_usergroup_map AS b ON (a.id = b.user_id)')
				->where('b.group_id IN ('.implode(',', $AppGroups['client']).')')
				->order('a.name');
		} elseif ($service_user) {
			$query
				->select('a.id AS value, a.name AS text')
				->from('#__users AS a')
				->join('LEFT', '#__user_profiles AS c ON (a.id = c.user_id)')
				->where('c.profile_key LIKE \'%gizprofile.serviceprovider%\'')
				->where('c.profile_value = \'"'.$user->id.'"\'')
				->join('INNER', '#__user_usergroup_map AS b ON (a.id = b.user_id)')
				->where('b.group_id IN ('.implode(',', $AppGroups['client']).')')
				->order('a.name');
		}
		
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		
		$options = $db->loadObjectList();
		
		if ($country_user) {
			array_unshift($options, JHtml::_('select.option', $user->id, JText::_('THIS COUNTRY "'.$user->name.'"')));
		}
		
		array_unshift($options, JHtml::_('select.option', '', JText::_('-- Please select --')));
		
		return $options;
	}
}