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

jimport( 'joomla.application.component.view');

class CostbenefitprojectionViewInterventions extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $xml;
	protected $user;
	
	public function display($tpl = null)
	{	
		$manifestUrl = JPATH_ADMINISTRATOR."/components/com_costbenefitprojection/manifest.xml";
		$this->xml = simplexml_load_file($manifestUrl);
		
		// Get data from the model
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->user = $this->get('User');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		$this->_prepareDocument();

		parent::display($tpl);
	}
		
	public function addToolbar()
	{		
		$canDo	= UsersHelper::getActions();
		
		JHtml::stylesheet('com_costbenefitprojection/admin.stylesheet.css', array(), true, false, false);
		
		JToolBarHelper::title(JText::_('COM_COSTBENEFITPROJECTION_INTERVENTIONS_TITLE'), 'interventions');

		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('intervention.add');
		}
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('intervention.edit');
		
			JToolBarHelper::divider();

			JToolBarHelper::publishList('interventions.publish');
			JToolBarHelper::unpublishList('interventions.unpublish');
	
			JToolBarHelper::divider();
	
			JToolBarHelper::archiveList('interventions.archive');
			
			if ($this->user['type'] == 'admin') {
				JToolBarHelper::checkin('interventions.checkin');
			}
		}
		
		
		if ($canDo->get('core.delete')) {

			if ($this->state->get('filter.published') == '-2') {
				JToolBarHelper::deleteList('', 'interventions.delete');
			} else {
				JToolBarHelper::trash('interventions.trash');
			}
		}
		
		JToolBarHelper::divider();

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_costbenefitprojection');
			JToolBarHelper::divider();
		}

		require_once JPATH_COMPONENT.DS.'helpers'.DS.'toolbar.php';
		// helper toolbar
		CostbenefitprojectionToolbarHelper::help($this->getName());
		// Sub menu		
		CostbenefitprojectionToolbarHelper::addToolbar($this->getName());
	}
	
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		// Add Theme to Page
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'theme.php' );
		// The CSS for Theme
		if ($vdmTheme == 1){
			$this->document->addStyleSheet(JURI::base() . '../media/com_costbenefitprojection/css/theme.css');
		}
		// The  JS
		$this->document->addScript(JURI::base() . '../media/com_costbenefitprojection/js/jquery-1.10.2.min.js');
		$this->document->addScriptDeclaration($theme); 
		
		JHTML::_('behavior.tooltip');         
	}
}