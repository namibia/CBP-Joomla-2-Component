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
JFormHelper::loadFieldClass('list');

class JFormFieldDiseases extends JFormFieldList
{
	protected $type = 'diseases';

	public function getOptions()
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('disease_id AS value, disease_name AS text');
		$query->from('#__costbenefitprojection_diseases');
		$query->order('disease_name');
		$query->where('published = 1');

		$db->setQuery($query);

		$options = $db->loadObjectList();

		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		array_unshift($options, JHtml::_('select.option', '', JText::_('COM_COSTBENEFITPROJECTION_DROP_NO_DISEASE')));

		return $options;
	}
}