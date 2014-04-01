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

class CostbenefitprojectionModelDiseasedata extends JModelAdmin
{	
	public function getTable($type = 'Diseasedata', $prefix = 'CostbenefitprojectionTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_costbenefitprojection.edit.diseasedata.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}
		
		// check point to see if the user is allowed to edit this item
		$user = JFactory::getUser();
		$userIs = $this->userIs($user->id);
		$itemOwner = $this->userIs($data->owner);
		if($itemOwner['id']){
			if ($userIs['type'] == 'client'){
				if ($userIs['country'] == $itemOwner['country']){
					if ($itemOwner['id'] != $userIs['id']){
						$this->checkin();
						throw new Exception('ERROR! this item does not belong to you, so you may not edit it. <a href="javascript:history.go(-1)">Go back</a>');
					} 
				} else {
					$this->checkin();
					throw new Exception('ERROR! this item does not belong to you, so you may not edit it. <a href="javascript:history.go(-1)">Go back</a>');
				}
			} else {
				$this->checkin();
				throw new Exception('ERROR! <a href="javascript:history.go(-1)">Go back</a>');
			}
		}
		return $data;
	}

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_costbenefitprojection.diseasedata', 'diseasedata', array('control' => 'jform', 'load_data' => $loadData));

		return $form;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @since   11.1
	 */
	/*public function getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		$table = $this->getTable();
		$isNew = true;

		if ($pk > 0)
		{
			// Attempt to load the row.
			$return = $table->load($pk);
			$isNew = false;
			// Check for a table object error.
			if ($return === false && $table->getError())
			{
				$this->setError($table->getError());
				return false;
			}
		}
		// Convert to the JObject before adding other data.
		$properties = $table->getProperties($pk);
		
		if(!$isNew){
			$item = JArrayHelper::toObject($properties, 'JObject');
			
			if (property_exists($item, 'params'))
			{
				$registry = new JRegistry;
				$registry->loadString($item->params);
				$item->params = $registry->toArray();
			}
		} else {
			$item = NULL;	
		}

		return $item;
	}*/
	
	/**
	 * Get internal user info in relation to this application.
	 *
	 * @return an associative array
	 *
	 */
	public function userIs($id)
	{
		$userIs = array();
		$userIs['id'] = $id;
		$userIs['country'] = JUserHelper::getProfile($id)->gizprofile[country];
		$userIs['groups'] = JUserHelper::getUserGroups($id);
		$userIs['name'] = JFactory::getUser($id)->name;
		
		$AppGroups['admin'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('admin');
		$AppGroups['country'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('country');
		$AppGroups['service'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('service');
		$AppGroups['client'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('client');

		$admin_user = (count(array_intersect($AppGroups['admin'], $userIs['groups']))) ? true : false;
		$country_user = (count(array_intersect($AppGroups['country'], $userIs['groups']))) ? true : false;
		$service_user = (count(array_intersect($AppGroups['service'], $userIs['groups']))) ? true : false;
		$client_user = (count(array_intersect($AppGroups['client'], $userIs['groups']))) ? true : false;
		
		if ($admin_user){
			$userIs['type'] = 'admin';
		} elseif ($country_user){
			$userIs['type'] = 'country';
		} elseif ($service_user){
			$userIs['type'] = 'service';
		} elseif ($client_user) {
			$userIs['type'] = 'client';
			$userIs['service'] = JUserHelper::getProfile($id)->gizprofile[serviceprovider];
		}
		
		return $userIs;
	}
}