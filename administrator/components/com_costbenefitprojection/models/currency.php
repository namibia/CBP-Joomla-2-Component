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
jimport('joomla.application.component.helper');

class CostbenefitprojectionModelCurrency extends JModelAdmin
{
	public function getTable($type = 'Currency', $prefix = 'CostbenefitprojectionTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$user = JFactory::getUser();
		$user_groups = JUserHelper::getUserGroups($user->id);

		$AppGroups['admin'] = JComponentHelper::getParams('com_costbenefitprojection')->get('admin');		
		
		$admin_user = (count(array_intersect($AppGroups['admin'], $user_groups))) ? true : false;

		if (!$admin_user){
			$this->setError(JText::_('COM_COSTBENEFITPROJECTION_NO_PERMISSION'));
			return false;
		}
		
		$form = $this->loadForm('com_costbenefitprojection.currency', 'currency', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_costbenefitprojection.edit.currency.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

}