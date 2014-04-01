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

class JFormFieldUgroups extends JFormFieldList
{
	protected $type = 'ugroups';

	public function getOptions()
	{
		$options = array();
		
		$AppGroups['admin'] = JComponentHelper::getParams('com_costbenefitprojection')->get('admin');
		$AppGroups['country'] = JComponentHelper::getParams('com_costbenefitprojection')->get('country');
		$AppGroups['service'] = JComponentHelper::getParams('com_costbenefitprojection')->get('service');
		$AppGroups['client'] = JComponentHelper::getParams('com_costbenefitprojection')->get('client');
		
		$user = JFactory::getUser();
		$user_groups = JUserHelper::getUserGroups($user->id);
		
		$admin_user = (count(array_intersect($AppGroups['admin'], $user_groups))) ? true : false;
		$country_user = (count(array_intersect($AppGroups['country'], $user_groups))) ? true : false;
		$service_user = (count(array_intersect($AppGroups['service'], $user_groups))) ? true : false;
		
		if ($admin_user){
			$Uaccess = array_merge($AppGroups['country'],$AppGroups['service'],$AppGroups['client']);
		} elseif ($country_user){
			$Uaccess = array_merge($AppGroups['service'],$AppGroups['client']);
		} elseif ($service_user){
			$Uaccess = $AppGroups['client'];
		} 

		// Get a db connection.
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		
		$query
			->select('a.id AS value, a.title AS text')
			->from('#__usergroups AS a')
			->where('a.id IN ('.implode(',', $Uaccess).')')
			->order('id');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$options = $db->loadObjectList();
		
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		array_unshift($options, JHtml::_('select.option', '', JText::_(' --Select-- ')));
		
		$input = JFactory::getApplication()->input;
		$id = $input->getInt('id');
		
		if ($id){
			// Get a new db connection.	
			$query = $db->getQuery(true);
			
			$query
				->select('a.group_id')
				->from('#__user_usergroup_map AS a')
				->where('a.user_id = '.$id.'');
			
			// echo nl2br(str_replace('#__','yvs9m_',$query)); die; 		
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
	
			$result = $db->loadObjectList();
			
			$this->value = $result[0]->group_id;
		} 
		 
		
		
		return $options;
	}
	
}