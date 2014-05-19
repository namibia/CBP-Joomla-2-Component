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

jimport('joomla.application.component.controllerform');
require_once JPATH_ADMINISTRATOR.'/components/com_costbenefitprojection/helpers/sum.php';

class CostbenefitprojectionControllerDiseasedata extends JControllerForm
{
	protected $text_prefix = 'COM_COSTBENEFITPROJECTION_DISEASES';
	
	protected $view_list = 'data';
	// the sum funtion
	protected $sum;
	
	protected function postSaveHook($model, $validData)
	{	
		$item 		= $model->getItem();
		$recordId	= $item->get('id');
			
		if($recordId){
		
			// Get a db connection.
			$db = JFactory::getDbo();
			
			// Create a new query object.
			$query = $db->getQuery(true);
			
			// Select all records from the user profile table where key begins with "custom.".
			// Order it by the ordering field.
			$query->select($db->quoteName(array('owner')));
			$query->from($db->quoteName('#__costbenefitprojection_diseasedata'));
			$query->where($db->quoteName('id') . ' = ' . $recordId);
			//echo nl2br(str_replace('#__','giz_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			// load the owner id of this record
			$owner = $db->loadResult();	
		}
		
		if($owner){
			// set sum function
			$this->sum = new Sum();
			// Create a new query object.
			$query = $db->getQuery(true);
			
			// Select all records from the user profile table where key begins with "custom.".
			// Order it by the ordering field.
			$query->select($db->quoteName('disease_id'));
			$query->from($db->quoteName('#__costbenefitprojection_diseasedata'));
			$query->where($db->quoteName('published') . ' = 1');
			$query->where($db->quoteName('owner') . ' = ' . $owner);
			//echo nl2br(str_replace('#__','giz_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			// load the disease_ids of this owner
			$results = $db->loadColumn();
		
			// set new selection
			if(is_array($results)){
				$selected = array_unique($results);
				sort($selected);
				$selected = $this->sum->vdm->the($selected);
				$selected = json_encode($selected);
				$query = $db->getQuery(true);
	
				// Fields to update.
				$fields = array(
					$db->quoteName('profile_value') . ' = ' . $db->quote($selected)
				);
				 
				// Conditions for which records should be updated.
				$conditions = array(
					$db->quoteName('user_id') . ' = '.$owner, 
					$db->quoteName('profile_key') . ' = ' . $db->quote('gizprofile.diseases')
				);
				 
				$query->update($db->quoteName('#__user_profiles'))->set($fields)->where($conditions);
				 
				$db->setQuery($query);
				 
				$result = $db->query();
			} else {
				$query = $db->getQuery(true);
				
				$conditions = array(
					$db->quoteName('user_id') . ' = '.$owner,
					$db->quoteName('profile_key') . ' = ' . $db->quote('gizprofile.diseases')
				);
				 
				$query->delete($db->quoteName('#__user_profiles'));
				$query->where($conditions);
				 
				$db->setQuery($query);
				
				$result = $db->query();
			}
		
			// do calculations			
			$this->sum->save($owner);
		
		}
	} 
	
	/**
	 * Gets the URL arguments to append to a list redirect.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 */
	protected function getRedirectToListAppend()
	{
		$tmpl = JRequest::getCmd('tmpl');
		$append = '';
		
		// Setup redirect info.
		if($tmpl){
			$append .= '&tmpl='.$tmpl;
		}
		
		$append .= '&type=1';
		
		return $append;
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$tmpl   = JRequest::getCmd('tmpl');
		$layout = JRequest::getCmd('layout', 'edit');
		$append = '';

		// Setup redirect info.
		if ($tmpl)
		{
			$append .= '&tmpl=' . $tmpl;
		}

		if ($layout)
		{
			$append .= '&layout=' . $layout;
		}

		if ($recordId)
		{
			$append .= '&' . $urlVar . '=' . $recordId;
		}

		return $append;
	}
}