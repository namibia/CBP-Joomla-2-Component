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

// import Joomla view library
jimport( 'joomla.application.component.view');

class CostbenefitprojectionViewChart extends JView
{
	protected $item;
	protected $xml;
	protected $user;
	protected $chart_tabs;
	protected $table_tabs;
	
	public function display($tpl = null)
	{	
		require_once JPATH_COMPONENT.DS.'models'.DS.'chartbuilder.php';
		$manifestUrl = JPATH_ADMINISTRATOR."/components/com_costbenefitprojection/manifest.xml";
		$this->xml = simplexml_load_file($manifestUrl);
		
		// Get data from the model
		$this->item			= $this->get('Item');
		$this->chart_tabs 	= $this->get('ChartTabs');
		$this->table_tabs 	= $this->get('TableTabs');
		$this->user 		= $this->get('User');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->addToolbar();
		$this->_prepareDocument();
		// Display the template
		parent::display($tpl);
	}
	
	public function addToolbar()
	{	
		$canDo	= UsersHelper::getActions();
		
		JHtml::stylesheet('com_costbenefitprojection/admin.stylesheet.css', array(), true, false, false);
		
		JToolBarHelper::title(JText::sprintf('COM_COSTBENEFITPROJECTION_CHART_TITLE', JFactory::getUser($this->item[data][clientId])->name), 'charts');
		
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_costbenefitprojection');
			JToolBarHelper::divider();
		}

		require_once JPATH_COMPONENT.DS.'helpers'.DS.'toolbar.php';
		// helper toolbar
		CostbenefitprojectionToolbarHelper::help($this->getName());
		// Sub menu		
		CostbenefitprojectionToolbarHelper::addToolchart('charts');
	}
		
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		// notice session controller
		$session =& JFactory::getSession();
		$menuNotice = $session->get( 'CT_SubMenuNotice', 'empty' );
		if ( $menuNotice == 'empty' ){
			$session->set( 'CT_SubMenuNotice', '1' );
		} elseif ($menuNotice < 6 ) {
			$menuNotice++;
			$session->set( 'CT_SubMenuNotice', $menuNotice );
		}

		// Add Theme to Page
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'theme.php' );
		// The CSS for Theme
		if ($vdmTheme == 1){
			$this->document->addStyleSheet(JURI::base() . '../media/com_costbenefitprojection/css/theme.css');
		}
		$this->document->addStyleSheet(JURI::base() . '../media/com_costbenefitprojection/css/chart.css');
		// The  JS
		$this->document->addScript(JURI::base() . '../media/com_costbenefitprojection/js/google.jsapi.js');
		$this->document->addScript(JURI::base() . '../media/com_costbenefitprojection/js/jquery-1.10.2.min.js');
		$this->document->addScript(JURI::base() . '../media/com_costbenefitprojection/js/chartMenu.js');
		$this->document->addScript('http://cdnjs.cloudflare.com/ajax/libs/tablesort/1.6.1/tablesort.js');
		$this->document->addScriptDeclaration($theme);           
	}
}