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

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('checkboxes');

class JFormFieldClusterid extends JFormFieldCheckboxes
{
	public $type = 'clusterid';
	
	public function getOptions()
	{
		$options = array();
		
		// Get the user id
		$input = JFactory::getApplication()->input;
		$member_id = $input->getInt('id');

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('intervention_id AS value, intervention_name, owner, access');
		$query->from('#__costbenefitprojection_interventions');	
		$query->where('published = \'1\'');
		$query->where('type = \'1\'');
		$query->order('intervention_name');

		$db->setQuery($query);

		$options = $db->loadObjectList();

		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		foreach ($options as $key => $option){
			if ($option->access == 3 && $option->owner != $member_id){
				unset($options[$key]);
			}
			$option->text = $option->intervention_name. ' &#8594; ' . JFactory::getUser($option->owner)->name;
		}
		
		return $options;
	}
}