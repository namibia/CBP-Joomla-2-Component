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

jimport('joomla.application.component.controllerform');
require_once JPATH_ADMINISTRATOR.'/components/com_costbenefitprojection/helpers/sum.php';

class CostbenefitprojectionControllerIntervention extends JControllerForm
{
	protected $text_prefix = 'COM_COSTBENEFITPROJECTION_INTERVENTIONS';
	
	protected $view_list = 'interventions';
	
	public function save($key = null, $urlVar = null)
	{
		parent::save($key, $urlVar);
		
		$recordId = JRequest::getInt("intervention_id");
		
		if($recordId){
			// Get a db connection.
			$db = JFactory::getDbo();
			// Create a new query object.
			$query = $db->getQuery(true);
			
			// Select all records from the user profile table where key begins with "custom.".
			// Order it by the ordering field.
			$query->select($db->quoteName(array('owner')));
			$query->from($db->quoteName('#__costbenefitprojection_interventions'));
			$query->where($db->quoteName('intervention_id') . ' = ' . $recordId);
			//echo nl2br(str_replace('#__','giz_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			// load the owner id of this record
			$owner = $db->loadResult();
			
			if($owner){
				$sum = new Sum();
				$sum->save($owner);
			}
		}
	}
}