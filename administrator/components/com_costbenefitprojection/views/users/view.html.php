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

defined('_JEXEC') or die;

class CostbenefitprojectionViewUsers extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $xml;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$manifestUrl = JPATH_ADMINISTRATOR."/components/com_costbenefitprojection/manifest.xml";
		$this->xml = simplexml_load_file($manifestUrl);
		
		// Get data from the model
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

		$this->addToolbar();
		$this->_prepareDocument();
		
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 */
	protected function addToolbar()
	{
		$canDo	= UsersHelper::getActions();

		JToolBarHelper::title(JText::_('COM_COSTBENEFITPROJECTION_VIEW_USERS_TITLE'), 'user');

		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('user.add');
		}
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('user.edit');
		}

		/*if ($canDo->get('core.edit.state')) {
			JToolBarHelper::divider();
			JToolBarHelper::publish('users.activate', 'COM_COSTBENEFITPROJECTION_TOOLBAR_ACTIVATE', true);
			JToolBarHelper::unpublish('users.block', 'COM_COSTBENEFITPROJECTION_TOOLBAR_BLOCK', true);
			JToolBarHelper::custom('users.unblock', 'unblock.png', 'unblock_f2.png', 'COM_COSTBENEFITPROJECTION_TOOLBAR_UNBLOCK', true);
			JToolBarHelper::divider();
		}*/

		if ($canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'users.delete');
			JToolBarHelper::divider();
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_costbenefitprojection');
			JToolBarHelper::divider();
		}

		require_once JPATH_COMPONENT.DS.'helpers'.DS.'toolbar.php';
		// helper toolbar
		CostbenefitprojectionToolbarHelper::help('members');
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
		
		// Load the tooltip behavior.
		JHtml::_('behavior.tooltip');
		JHtml::_('behavior.multiselect');
		JHtml::_('behavior.modal');          
	}
}