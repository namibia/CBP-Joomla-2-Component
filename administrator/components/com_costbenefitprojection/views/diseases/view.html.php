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

jimport( 'joomla.application.component.view');

class CostbenefitprojectionViewDiseases extends JView
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
		
		JToolBarHelper::title(JText::_('COM_COSTBENEFITPROJECTION_DISEASES_TITLE'), 'diseases');

		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('disease.add');
		}
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('disease.edit');
		}

		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::divider();

			JToolBarHelper::publishList('diseases.publish');
			JToolBarHelper::unpublishList('diseases.unpublish');
	
			JToolBarHelper::divider();
	
			JToolBarHelper::archiveList('diseases.archive');
			
			if ($canDo->get('core.admin')) {
				JToolBarHelper::checkin('diseases.checkin');
			}
		}
		
		if ($canDo->get('core.delete')) {

			if ($this->state->get('filter.published') == '-2') {
				JToolBarHelper::deleteList('', 'diseases.delete');
			} else {
				JToolBarHelper::trash('diseases.trash');
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
	}
}