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

class CostbenefitprojectionModelCostbenefitprojection extends JModelList
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
			
			// View Helps
			$items[0] = array('name' => 'COM_COSTBENEFITPROJECTION_HELP_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=helps', 'img' => 'media/com_costbenefitprojection/images/helps-48.png');
			
			// View Charts & Tables
			$items[1] = array('name' => 'COM_COSTBENEFITPROJECTION_CHARTS_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=charts', 'img' => 'media/com_costbenefitprojection/images/charts-48.png');
			
			// View Members
			$items[2] = array('name' => 'COM_COSTBENEFITPROJECTION_USERS_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=users', 'img' => 'media/com_costbenefitprojection/images/members-icon-48.png');
			
			// Add Member
			$items[3] = array('name' => 'COM_COSTBENEFITPROJECTION_ADD_USERS_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=user&layout=edit', 'img' => 'media/com_costbenefitprojection/images/members-icon-48-add.png');
			
			if ($admin_user || $country_user) {	
				// View Diseasecategories
				$items[4] = array('name' => 'COM_COSTBENEFITPROJECTION_DISEASECATEGORIES_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=diseasecategories', 'img' => 'media/com_costbenefitprojection/images/diseasecategory-icon-48.png');
				
				// Add Diseasecategory
				$items[5] = array('name' => 'COM_COSTBENEFITPROJECTION_ADD_DISEASECATEGORY_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=diseasecategory&layout=edit', 'img' => 'media/com_costbenefitprojection/images/diseasecategory-icon-48-add.png');
		
				// View Diseases
				$items[6] = array('name' => 'COM_COSTBENEFITPROJECTION_DISEASES_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=diseases', 'img' =>'media/com_costbenefitprojection/images/diseases-48.png');
				
				// Add Disease
				$items[7] = array('name' => 'COM_COSTBENEFITPROJECTION_ADD_DISEASE_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=disease&layout=edit', 'img' =>'media/com_costbenefitprojection/images/disease-48-add.png');
			}
			
			// View Diseasesdata
			$items[8] = array('name' => 'COM_COSTBENEFITPROJECTION_DISEASESDATA_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=diseasesdata', 'img' =>'media/com_costbenefitprojection/images/diseasedata-48.png');
			
			// Add Diseasedata
			$items[9] = array('name' => 'COM_COSTBENEFITPROJECTION_ADD_DISEASEDATA_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=diseasedata&layout=edit', 'img' =>'media/com_costbenefitprojection/images/diseasedata-48-add.png');
			
			if ($admin_user || $country_user) {
				// View Risks
				$items[10] = array('name' => 'COM_COSTBENEFITPROJECTION_RISKS_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=risks', 'img' =>'media/com_costbenefitprojection/images/risks-48.png');
				
				// Add Risk
				$items[11] = array('name' => 'COM_COSTBENEFITPROJECTION_ADD_RISK_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=risk&layout=edit', 'img' =>'media/com_costbenefitprojection/images/risks-48-add.png');
			}
			
			// View Risksdata
			$items[12] = array('name' => 'COM_COSTBENEFITPROJECTION_RISKSDATA_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=risksdata', 'img' =>'media/com_costbenefitprojection/images/riskdata-48.png');
			
			// Add Riskdata
			$items[13] = array('name' => 'COM_COSTBENEFITPROJECTION_ADD_RISKDATA_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=riskdata&layout=edit', 'img' =>'media/com_costbenefitprojection/images/riskdata-48-add.png');
			
			// View Interventions
			$items[14] = array('name' => 'COM_COSTBENEFITPROJECTION_INTERVENTIONS_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=interventions', 'img' => 'media/com_costbenefitprojection/images/interventions-48.png');
			
			// Add Intervention
			$items[15] = array('name' => 'COM_COSTBENEFITPROJECTION_INTERVENTION_ADD_MENU_DEFAULT_TITLE', 'url' => 'index.php?option=com_costbenefitprojection&view=intervention&layout=edit', 'img' => 'media/com_costbenefitprojection/images/interventions-48-add.png');
			
			return $items;
		}
}