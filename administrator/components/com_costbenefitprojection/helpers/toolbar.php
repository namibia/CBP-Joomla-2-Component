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

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.helper');

abstract class CostbenefitprojectionToolbarHelper
{
	public static $isJ30 = null;
	
	public static function addToolbar($view='') {
		
		$user = JFactory::getUser();
		$user_groups = JUserHelper::getUserGroups($user->id);

		$AppGroups['admin'] = JComponentHelper::getParams('com_costbenefitprojection')->get('admin');
		$AppGroups['country'] = JComponentHelper::getParams('com_costbenefitprojection')->get('country');
		$AppGroups['service'] = JComponentHelper::getParams('com_costbenefitprojection')->get('service');		
		
		$admin_user = (count(array_intersect($AppGroups['admin'], $user_groups))) ? true : false;
		$country_user = (count(array_intersect($AppGroups['country'], $user_groups))) ? true : false;
		$service_user = (count(array_intersect($AppGroups['service'], $user_groups))) ? true : false;
				
		// load language file (.sys because the toolbar has the same options as the components dropdown)
		JFactory::getLanguage()->load('com_costbenefitprojection.sys', JPATH_ADMINISTRATOR);
		
		// add toolbar entries
		
		// overview
		self::addEntry('OVERVIEW_VIEW_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection', $view == '' || $view == 'costbenefitprojection');
		
		// charts
		self::addEntry('CHARTS_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=charts', $view == 'charts');

		// View Users
		self::addEntry('USERS_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=users', $view == 'users');
		
		if ($admin_user || $country_user) {
			// View Diseasecategories
			self::addEntry('DISEASECATEGORIES_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=diseasecategories', $view == 'diseasecategories');
	
			// View Diseases
			self::addEntry('DISEASES_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=diseases', $view == 'diseases');
		}
		
		// View Diseasesdata
		self::addEntry('DISEASESDATA_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=diseasesdata', $view == 'diseasesdata');
		
		if ($admin_user || $country_user) {
			// View Diseases
			self::addEntry('RISKS_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=risks', $view == 'risks');
		}
		
		// View Diseasesdata
		self::addEntry('RISKSDATA_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=risksdata', $view == 'risksdata');
		
		// View Interventions
		self::addEntry('INTERVENTIONS_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=interventions', $view == 'interventions');
		
		if ($admin_user) {
			// View Countries
			self::addEntry('COUNTRIES_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=countries', $view == 'countries');
			
			// View Currencies
			self::addEntry('CURRENCIES_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=currencies', $view == 'currencies');
		 
			// View Helps Edit
			self::addEntry('HELPSEDIT_EDITOR_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=helpsedit', $view == 'helpsedit');
		}

	}
	
	public static function addToolchart($view='') {
		
		// load language file (.sys because the toolbar has the same options as the components dropdown)
		JFactory::getLanguage()->load('com_costbenefitprojection.sys', JPATH_ADMINISTRATOR);
		
		// add toolbar entries
		
		// overview
		self::addEntry('OVERVIEW_VIEW_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection', $view == '' || $view == 'costbenefitprojection');
		
		// charts
		self::addEntry('CHARTS_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=charts', $view == 'charts');
		
		// View Users
		self::addEntry('USERS_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=users', $view == 'users');
		
		// View Diseasesdata
		self::addEntry('DISEASESDATA_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=diseasesdata', $view == 'diseasesdata');
		
		// View Diseasesdata
		self::addEntry('RISKSDATA_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=risksdata', $view == 'risksdata');
		
		// View Interventions
		self::addEntry('INTERVENTIONS_MENU_DEFAULT_TITLE', 'index.php?option=com_costbenefitprojection&view=interventions', $view == 'interventions');
		
	}
	
	public static function help($type = '', $url = 'index.php?option=com_costbenefitprojection&view=help&tmpl=component&id=')
	{
		$user = JFactory::getUser();
		$user_groups = JUserHelper::getUserGroups($user->id);
		
		$AppGroups['admin'] = JComponentHelper::getParams('com_costbenefitprojection')->get('admin');
		$AppGroups['country'] = JComponentHelper::getParams('com_costbenefitprojection')->get('country');
		$AppGroups['service'] = JComponentHelper::getParams('com_costbenefitprojection')->get('service');		
		
		$admin_user = (count(array_intersect($AppGroups['admin'], $user_groups))) ? true : false;
		$country_user = (count(array_intersect($AppGroups['country'], $user_groups))) ? true : false;
		$service_user = (count(array_intersect($AppGroups['service'], $user_groups))) ? true : false;

		if ($admin_user){
			$group = 'admin';
		} elseif ($country_user){
			$group = 'country';
		} elseif ($service_user){
			$group = 'service';
		}
		
		// Get a db connection.
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		
		$query
				->select('a.`help_id` AS id')
				->from('#__costbenefitprojection_helps AS a')
				->where('a.`help_link` = \'back/'.$group.'/'.$type.'\'')
				->where('a.`published` = 1');
		// echo nl2br(str_replace('#__','yvs9m_',$query)); die;	
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->query();
		$num_rowsA = $db->getNumRows();
		if ($num_rowsA == 1){
			$options = $db->loadObject();
		} elseif ($num_rowsA == 0){
			$query = $db->getQuery(true);
			$query
				->select('a.`help_id` AS id')
				->from('#__costbenefitprojection_helps AS a')
				->where('a.`help_link` = \'back/all/'.$type.'\'')
				->where('a.`published` =1');
				$db->setQuery($query);
				$db->query();
				$num_rowsB = $db->getNumRows();
		} 
		if ($num_rowsB == 1){
			$options = $db->loadObject();
		}
		
		if (!$options->id){
			$options->id = 1;
		}

		$bar = JToolBar::getInstance('toolbar');
		// Add a preview button.
		$bar->appendButton('Popup', 'help', 'Help', $url.$options->id);
	}
	
	protected static function addEntry($lang_key, $url, $default=false) {
		$lang_key = 'COM_COSTBENEFITPROJECTION_'.$lang_key;
		
		if (self::$isJ30) {
			JHtmlSidebar::addEntry(JText::_($lang_key), JRoute::_($url), $default);

		} else {
			JSubMenuHelper::addEntry(JText::_($lang_key), JRoute::_($url), $default);
		}
	}
	
	public static function addFilter($text, $key, $options) {
		if (self::$isJ30) {
			JHtmlSidebar::addFilter($text, $key, $options);
		}
		
		// nothing for 2.5
	}
	
	public static function render() {
		if (self::$isJ30) {
			return JHtmlSidebar::render();
		} else {
			return '';
		}
	}
}

$jversion = new JVersion();
CostbenefitprojectionToolbarHelper::$isJ30 = $jversion->isCompatible('3.0');