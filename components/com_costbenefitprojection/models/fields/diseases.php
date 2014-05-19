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

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldDiseases extends JFormFieldList
{
	protected $type = 'diseases';
	
	public function getOptions()
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('disease_id AS value, disease_name AS text, ref');
		$query->from('#__costbenefitprojection_diseases');
		$query->order('disease_id');
		$query->where('published = 1');

		$db->setQuery($query);

		$options = $db->loadObjectList();
		
		foreach($options as $key => $option){
			$options[$key]->text = $option->ref . ' ' . $option->text;
			unset($options[$key]->ref);
		}
		
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		// get this cause/risk id
		$recordId = JRequest::getInt("id");
		if($recordId){
			$query = $db->getQuery(true);
	
			$query->select('disease_id');
			$query->from('#__costbenefitprojection_diseasedata');
			$query->where($db->quoteName('id') . ' = ' . $recordId);
	
			$db->setQuery($query);
			
			$disease_id = $db->loadResult();
		}
		// get the users cause/risk id's
		$diseases = $this->getDiseases();
		
		foreach($options as $key => $values){
			if($disease_id){
				if(in_array($values->value,$diseases) && $disease_id != $values->value){
					unset($options[$key]);
				}
			} else {
				if(in_array($values->value,$diseases)){
					unset($options[$key]);
				}
			}
		}
		array_unshift($options, JHtml::_('select.option', '', JText::_('COM_COSTBENEFITPROJECTION_DROP_NO_DISEASE')));

		return $options;
	}
	
	protected function getDiseases()
	{
		$id = JFactory::getUser()->id;
		
		// Get a db connection.
		$db = JFactory::getDbo();
				
		$query = $db->getQuery(true);
		
		if ($id){
			$query
				->select('a.disease_id')
				->from('#__costbenefitprojection_diseasedata AS a')
				->where('a.owner = \''.$id.'\'');
		 
			// echo nl2br(str_replace('#__','yvs9m_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			
			$diseases = $db->loadColumn();
		} else {
			throw new Exception(JText::_('Error!'));
		}
		
		return array_unique($diseases);
	}
}