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

class CostbenefitprojectionModelHelp extends JModelItem
{
	protected $item;
	
	protected function populateState() 
	{
		$app = JFactory::getApplication();
		// Get the message for value
		$jinput = JFactory::getApplication()->input;
		$help_id = $jinput->get('id', NULL, 'BASE64');
		$this->setState('help.id', $help_id);
		parent::populateState();
	}
		
	public function getItem()
	{
		if (!isset($this->item)) 
                {
					$id = $this->unLock();
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('t.help_content')
							->from('#__costbenefitprojection_helps AS t')
							->where('t.help_id = '. (int)$id .' AND t.published = 1');
                      // Reset the query using our newly populated query object.
					$db->setQuery($query);
					
					$this->item = $db->loadResult();
                }
          return $this->item;
	}
	
	protected function unLock()
	{
		$var = $this->getState('help.id');
		// get list of posible ids
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
				->select('t.help_for, t.help_id')
				->from('#__costbenefitprojection_helps AS t')
				->where('t.help_group = \'client\' AND t.published = 1');
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$assoc = $db->loadAssocList('help_for');
		// get lock tool
		if(class_exists(vdmLock)){
			$unLock = new vdmLock();
		} else {
			JPluginHelper::importPlugin('user','gizprofile');
			$unLock = new vdmLock();
		}
		// quick check to seeif this is a legitimate request
		foreach ($assoc as $key => $value)
		{
			if($var == $unLock->it('client-',$key)){
				$id = $value['help_id'];
			}
		}
		// if not throw error
		if (!$id){
			throw new Exception(JText::_('COM_COSTBENEFITPROJECTION_ACCESS_DENIED'), 403);
			return false;
		}
		// if it is a legitimate request unlock the id
		return $id;
	}
}