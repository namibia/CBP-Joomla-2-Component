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
		// Get the message id
		$input = JFactory::getApplication()->input;
		$help_id = $input->getInt('id');
		$this->setState('help.id', $help_id);

		parent::populateState();
	}
		
	public function getItem()
	{
		if (!isset($this->item)) 
                {
					$id = $this->getState('help.id');
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('t.*')
							->from('#__costbenefitprojection_helps AS t')
							->where('t.help_id = '. (int)$id .' AND t.published = 1');
                      // Reset the query using our newly populated query object.
					$db->setQuery($query);
					
					$this->item = $db->loadObject();
                }
          return $this->item;
	}
}