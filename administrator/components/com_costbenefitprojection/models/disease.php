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

jimport('joomla.application.component.modeladmin');

class CostbenefitprojectionModelDisease extends JModelAdmin
{
	public function getTable($type = 'Disease', $prefix = 'CostbenefitprojectionTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$user = JFactory::getUser();
		$user_groups = JUserHelper::getUserGroups($user->id);

		$AppGroups['service'] = JComponentHelper::getParams('com_costbenefitprojection')->get('service');		
		
		$serviceProvider = (count(array_intersect($AppGroups['service'], $user_groups))) ? true : false;

		if ($serviceProvider){
			$this->setError(JText::_('COM_COSTBENEFITPROJECTION_NO_PERMISSION'));
			return false;
		}
		
		$form = $this->loadForm('com_costbenefitprojection.disease', 'disease', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_costbenefitprojection.edit.disease.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

}
