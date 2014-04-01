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

class CostbenefitprojectionModelHelps extends JModelList
{
		public function getItems ()
		{
			$user = JFactory::getUser();
			$user_groups = JUserHelper::getUserGroups($user->id);
	
			$AppGroups['admin'] = JComponentHelper::getParams('com_costbenefitprojection')->get('admin');
			$AppGroups['country'] = JComponentHelper::getParams('com_costbenefitprojection')->get('country');
			$AppGroups['service'] = JComponentHelper::getParams('com_costbenefitprojection')->get('service');		
			
			$admin_user = (count(array_intersect($AppGroups['admin'], $user_groups))) ? true : false;
			$country_user = (count(array_intersect($AppGroups['country'], $user_groups))) ? true : false;
			$service_user = (count(array_intersect($AppGroups['service'], $user_groups))) ? true : false;
			
			$url = 'index.php?option=com_costbenefitprojection&view=help&tmpl=component&id=';

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
			
			$query->select('a.`help_id` AS url, a.`help_for` AS name');
			$query->from('#__costbenefitprojection_helps AS a');
			
			if ($admin_user || $country_user){
				$query->where('a.`help_group` = \''.$group.'\' OR a.`help_group` = \'all\'');
			} else {
				$query->where('a.`help_group` = \''.$group.'\'');
			}
			$query->where('a.`location` = \'back\'');
			$query->where('a.`published` = 1');
			
			// echo nl2br(str_replace('#__','yvs9m_',$query)); die;	
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			$db->query();
			
			$options = $db->loadAssocList();
			$i = 0;
			foreach ($options as $option){
				if ($option["url"] <= 4){
					unset($options[$i]);
				} else {
					$options[$i]['img'] = 'media/com_costbenefitprojection/images/help-'.str_replace(' ', '', strtolower($option["name"])).'.png';
					$options[$i]['url'] = $url.$option["url"];
				}
				$i++;
			}
			
			return $options;
		}
}