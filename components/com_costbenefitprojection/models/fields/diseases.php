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

class JFormFieldDiseases extends JFormFieldList
{
	protected $type = 'diseases';

	public function getOptions()
	{
		$diseases = $this->getDiseases();
		
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('disease_id AS value, disease_name AS text');
		$query->from('#__costbenefitprojection_diseases');
		$query->order('disease_name');
		$query->where('disease_id IN ('.implode(',', $diseases).')');
		$query->where('published = 1');

		$db->setQuery($query);

		$options = $db->loadObjectList();

		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		array_unshift($options, JHtml::_('select.option', '', JText::_('COM_COSTBENEFITPROJECTION_DROP_NO_DISEASE')));

		return $options;
	}
	
	protected function getDiseases()
	{
		$id = JFactory::getUser()->id;
		$diseasesSelected = JUserHelper::getProfile($id)->gizprofile["diseases"];
		// sort disease selected array
		if(is_array($diseasesSelected)){
				sort($diseasesSelected); 
		}
		
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		$diseaseSet = NULL;
		
		if (is_array($diseasesSelected)){
			$query
				->select('a.disease_id')
				->from('#__costbenefitprojection_diseasedata AS a')
				->where('a.owner = \''.$id.'\'')
				->where('a.disease_id IN ('.implode(',', $diseasesSelected).')');
		 
			// echo nl2br(str_replace('#__','yvs9m_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			$diseasesSet = $db->loadColumn();
		} else {
			throw new Exception(JText::_('You have no diseases selected in user profile!'));
		}
		
		// sort disease set array
		if(is_array($diseasesSet)){
				sort($diseasesSet);
				$diseases = array_diff($diseasesSelected, $diseasesSet);
				
				// set curreny selected disease back in array
				if($this->value){
					array_unshift($diseases,$this->value);
				}
				
		} else {
			$diseases = $diseasesSelected;
		}
		
		return array_unique($diseases);
	}
}