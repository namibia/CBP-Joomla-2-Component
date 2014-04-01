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

jimport('joomla.application.component.modeladmin');
jimport('joomla.application.component.helper');

class CostbenefitprojectionModelDiseasecategory extends JModelAdmin
{
	public function getTable($type = 'Diseasecategory', $prefix = 'CostbenefitprojectionTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function loadFormData()
	{
		$user = JFactory::getUser();
		$user_groups = JUserHelper::getUserGroups($user->id);
		
		$AppGroups['service'] = JComponentHelper::getParams('com_costbenefitprojection')->get('service');		
		
		$serviceProvider = (count(array_intersect($AppGroups['service'], $user_groups))) ? true : false;

		if ($serviceProvider){
			$this->setError(JText::_('COM_COSTBENEFITPROJECTION_NO_PERMISSION'));
			return false;
		}
		
		$data = JFactory::getApplication()->getUserState('com_costbenefitprojection.edit.diseasecategory.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_costbenefitprojection.diseasecategory', 'diseasecategory', array('control' => 'jform', 'load_data' => $loadData));

		return $form;
	}
}
