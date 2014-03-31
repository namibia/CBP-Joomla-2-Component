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

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldRisks extends JFormFieldList
{
	protected $type = 'risks';

	public function getOptions()
	{
		$risks = $this->getRisks();
		
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('risk_id AS value, risk_name AS text');
		$query->from('#__costbenefitprojection_risk');
		$query->order('risk_name');
		$query->where('risk_id IN ('.implode(',', $risks).')');
		$query->where('published = 1');

		$db->setQuery($query);

		$options = $db->loadObjectList();

		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		array_unshift($options, JHtml::_('select.option', '', JText::_('COM_COSTBENEFITPROJECTION_DROP_NO_RISK')));

		return $options;
	}
	
	protected function getRisks()
	{
		$id = JFactory::getUser()->id;
		$risksSelected = JUserHelper::getProfile($id)->gizprofile["risks"];
		// sort disease selected array
		if(is_array($risksSelected)){
				sort($risksSelected); 
		}
		
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		$risksSet = NULL;
		
		if (is_array($risksSelected)){
			$query
				->select('a.risk_id')
				->from('#__costbenefitprojection_riskdata AS a')
				->where('owner = \''.$id.'\'')
				->where('a.risk_id IN ('.implode(',', $risksSelected).')');
		 
			// echo nl2br(str_replace('#__','yvs9m_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			$risksSet = $db->loadColumn();
		} else {
			throw new Exception(JText::_('You have no risks selected in user profile!'));
		}
		
		// sort risk set array
		if(is_array($risksSet)){
				sort($risksSet);
				$risks = array_diff($risksSelected, $risksSet);
				// set curreny selected disease back in array
				
				// set curreny selected disease back in array
				if($this->value){
					array_unshift($risks,$this->value);
				}
				
		} else {
			$risks = $risksSelected;
		}
		
		return array_unique($risks);
	}
}