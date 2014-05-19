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

jimport('joomla.application.component.modelitem');
jimport('joomla.application.component.helper');
require_once JPATH_ADMINISTRATOR.'/components/com_costbenefitprojection/helpers/vdm.php';

class CostbenefitprojectionModelChart extends JModelItem
{
	protected $result;
	
	protected function populateState() 
	{
		// Get the user id
		$input = JFactory::getApplication()->input;
		$user_id = $input->getInt('id');
		$this->setState('user.id', $user_id);
		parent::populateState();
	}
	
	public function getChartTabs ()
		{
					
			// Work Days Lost
			$items[0] = array('name' => 'COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_TITLE', 'view' => 'wdl', 'img' => 'media/com_costbenefitprojection/images/charts-48.png');
			
			// Work days Lost Percent
			$items[1] = array('name' => 'COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_PERCENT', 'view' => 'wdlp', 'img' => 'media/com_costbenefitprojection/images/charts-48.png');
			
			// Cost
			$items[2] = array('name' => 'COM_COSTBENEFITPROJECTION_CHARTS_COST_TITLE', 'view' => 'c', 'img' => 'media/com_costbenefitprojection/images/charts-48.png');
			
			// Cost Percent
			$items[3] = array('name' => 'COM_COSTBENEFITPROJECTION_CHARTS_COST_PERCENT_TITLE', 'view' => 'cp', 'img' => 'media/com_costbenefitprojection/images/charts-48.png');
			
			// Intervention Cost Benefit
			$items[4] = array('name' => 'COM_COSTBENEFITPROJECTION_CHARTS_INTERVENTION_COST_BENEFIT_TITLE', 'view' => 'icb', 'img' => 'media/com_costbenefitprojection/images/charts-48.png');
			
			return $items;
		}
		
	public function getTableTabs ()
		{
					
			// Work Days Lost Summary
			$items[0] = array('name' => 'COM_COSTBENEFITPROJECTION_TABLES_WORK_DAYS_LOST_SUMMARY_TITLE', 'view' => 'wdls', 'img' => 'media/com_costbenefitprojection/images/tables-48.png');
			
			// Cost Summary
			$items[1] = array('name' => 'COM_COSTBENEFITPROJECTION_TABLES_COST_SUMMARY_TITLE', 'view' => 'cs', 'img' => 'media/com_costbenefitprojection/images/tables-48.png');
			
			// Calculated Costs in Detail
			$items[2] = array('name' => 'COM_COSTBENEFITPROJECTION_TABLES_CALCULATED_COST_IN_DETAIL_TITLE', 'view' => 'ccid', 'img' => 'media/com_costbenefitprojection/images/tables-48.png');
			
			// Intervention Net Benefit
			$items[3] = array('name' => 'COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NET_BENEFIT_TITLE', 'view' => 'inb', 'img' => 'media/com_costbenefitprojection/images/tables-48.png');
			
			return $items;
		}
		
	public function getResult()
	{
		if (!isset($this->result)) 
                {
					$id = $this->getState('user.id');
					// check point to see if the user is allowed to edit this item
					$user = $this->getUser();
					$itemOwner = $this->userIs($id);
					if($itemOwner['id']){
						if ($user['type'] != 'admin'){
							if ($user['country'] == $itemOwner['country']){
								if (($user['type'] == 'service') && ($itemOwner['service'] != $user['id'])){
									throw new Exception('ERROR! this item does not belong to you, so you may not view it. <a href="javascript:history.go(-1)">Go back</a>');
								} 
							} else {
								throw new Exception('ERROR! this item does not belong to you, so you may not view it. <a href="javascript:history.go(-1)">Go back</a>');
							}
						}
					}
					
					// Add Data
					$this->result = $this->getMembersData($id);					
				}
			//echo '<pre>';var_dump($this->result);exit;
		 return $this->result;
	}
	
	public function getUser()
	{
		// get user
		$user = JFactory::getUser();
		
		return $this->userIs($user->id);
	}

	/**
	 * Get internal user info in relation to this application.
	 *
	 * @return an associative array
	 *
	 */
	protected function userIs($id)
	{
		$userIs = array();
		$userIs['id'] 	= $id;
		$userIs['country'] 	= JUserHelper::getProfile($id)->gizprofile[country];
		$userIs['groups'] 	= JUserHelper::getUserGroups($id);
		$userIs['name'] 	= JFactory::getUser($id)->name;
		
		$AppGroups['admin'] 	= &JComponentHelper::getParams('com_costbenefitprojection')->get('admin');
		$AppGroups['country'] 	= &JComponentHelper::getParams('com_costbenefitprojection')->get('country');
		$AppGroups['service'] 	= &JComponentHelper::getParams('com_costbenefitprojection')->get('service');
		$AppGroups['client'] 	= &JComponentHelper::getParams('com_costbenefitprojection')->get('client');

		$admin_user 	= (count(array_intersect($AppGroups['admin'], $userIs['groups']))) ? true : false;
		$country_user 	= (count(array_intersect($AppGroups['country'], $userIs['groups']))) ? true : false;
		$service_user 	= (count(array_intersect($AppGroups['service'], $userIs['groups']))) ? true : false;
		$client_user 	= (count(array_intersect($AppGroups['client'], $userIs['groups']))) ? true : false;
		
		if ($admin_user){
			$userIs['type'] 	= 'admin';
		} elseif ($country_user){
			$userIs['type'] 	= 'country';
		} elseif ($service_user){
			$userIs['type'] 	= 'service';
		} elseif ($client_user) {
			$userIs['type'] 	= 'client';
			$userIs['service'] 	= JUserHelper::getProfile($id)->gizprofile[serviceprovider];
		}
		
		return $userIs;
	}
	
	/**
	 * Get Caues/Risk data that belongs to this Memeber.
	 *
	 * @return an associative array
	 *
	 */
	protected function getMembersData($userId)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName(array('params')));
		$query->from($db->quoteName('#__costbenefitprojection_memberdata'));
		$query->where($db->quoteName('owner') . ' = '. $db->quote($userId));
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();
		if($db->getNumRows()){
			$open = new Vault(false);
			$result = $open->the($db->loadResult());
			return json_decode($result);
		} 
		return false;
	}

}