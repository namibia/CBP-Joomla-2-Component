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

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * 
 * @package	com_costbenefitprojection
 */
class JFormFieldGroupParent extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 */
	protected $type = 'GroupParent';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();

		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$query = $db->getQuery(true);

		$query->select('a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level');
		$query->from('#__usergroups AS a');
		$query->join('LEFT', $db->quoteName('#__usergroups').' AS b ON a.lft > b.lft AND a.rgt < b.rgt');

		// Prevent parenting to children of this item.
		if ($id = $this->form->getValue('id')) {
			$query->join('LEFT', $db->quoteName('#__usergroups').' AS p ON p.id = '.(int) $id);
			$query->where('NOT(a.lft >= p.lft AND a.rgt <= p.rgt)');
		}

		$query->group('a.id, a.title, a.lft, a.rgt');
		$query->order('a.lft ASC');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		// Pad the option text with spaces using depth level as a multiplier.
		for ($i = 0, $n = count($options); $i < $n; $i++) {
			// Show groups only if user is super admin or group is not super admin
			if ($user->authorise('core.admin') || (!JAccess::checkGroup($options[$i]->value, 'core.admin'))) {
				$options[$i]->text = str_repeat('- ', $options[$i]->level).$options[$i]->text;
			}
			else {
			 unset ($options[$i]);
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
