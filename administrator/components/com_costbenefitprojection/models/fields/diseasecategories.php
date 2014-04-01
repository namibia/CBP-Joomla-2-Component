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

class JFormFieldDiseasecategories extends JFormFieldList
{
	protected $type = 'Diseasecategories';

	public function getOptions()
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('diseasecategory_id AS value, diseasecategory_name AS text');
		$query->from('#__costbenefitprojection_diseasecategories');
		$query->order('diseasecategory_name');
		$query->where('published = 1');

		$db->setQuery($query);

		$options = $db->loadObjectList();

		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		array_unshift($options, JHtml::_('select.option', '', JText::_('COM_COSTBENEFITPROJECTION_DROP_NO_DISEASECATEGORY')));

		return $options;
	}
}