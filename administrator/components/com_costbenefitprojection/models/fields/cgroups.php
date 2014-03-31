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

class JFormFieldCgroups extends JFormFieldCheckboxes
{
	protected $type = 'cgroups';

	public function getOptions()
	{
		// Get a db connection.
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		
		$query
			->select('a.id AS value, a.title AS text')
			->from('#__usergroups AS a')
			->order('a.title');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$options = $db->loadObjectList();
		
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		return $options;
	}
	
}