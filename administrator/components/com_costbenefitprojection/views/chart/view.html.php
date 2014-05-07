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
jimport( 'joomla.application.component.helper' );

class CostbenefitprojectionViewChart extends JView
{
	protected $result;
	protected $xml;
	protected $user;
	protected $chart_tabs;
	protected $table_tabs;
	protected $Chart;
	
	public function display($tpl = null)
	{	
		require_once JPATH_COMPONENT.DS.'models'.DS.'chartbuilder.php';
		$manifestUrl = JPATH_ADMINISTRATOR."/components/com_costbenefitprojection/manifest.xml";
		$this->xml = simplexml_load_file($manifestUrl);
		
		// Get data from the model
		$this->result			= $this->get('Result');
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
		
		JToolBarHelper::title(JText::sprintf('COM_COSTBENEFITPROJECTION_CHART_TITLE', JFactory::getUser($this->result->user->id)->name), 'charts');
		
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
		// get app params
		$this->params = &JComponentHelper::getParams('com_costbenefitprojection');
				
		// set chart stuff
		$this->Chart['backgroundColor'] = $this->params->get('backend_backgroundColor');
		$this->Chart['width'] = $this->params->get('backend_mainwidth');
		$this->Chart['chartArea'] = array('top' => $this->params->get('backend_chartAreaTop'), 'left' => $this->params->get('backend_chartAreaLeft'), 'width' => $this->params->get('backend_chartAreawidth').'%');
		$this->Chart['legend'] = array( 'textStyle' => array('fontSize' => $this->params->get('backend_legendTextStyleFontSize'), 'color' => $this->params->get('backend_legendTextStyleFontColor')));
		$this->Chart['vAxis'] = array('textStyle' => array('color' => $this->params->get('backend_vAxisTextStyleFontColor')));
		$this->Chart['hAxis']['textStyle'] = array('color' => $this->params->get('backend_hAxisTextStyleFontColor'));
		$this->Chart['hAxis']['titleTextStyle'] = array('color' => $this->params->get('backend_hAxisTitleTextStyleFontColor'));
				
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
		$this->document->addStyleSheet(JURI::base() . '../media/com_costbenefitprojection/css/footable.core.css?v=2-0-1');
		$this->document->addStyleSheet(JURI::base() . '../media/com_costbenefitprojection/css/footable.metro.css');
		// The  JS
		$this->document->addScript(JURI::base() . '../media/com_costbenefitprojection/js/jquery-1.10.2.min.js');
		$this->document->addScript(JURI::base() . '../media/com_costbenefitprojection/js/google.jsapi.js');
		$this->document->addScript(JURI::base() . '../media/com_costbenefitprojection/js/footable.js?v=2-0-1');
		$this->document->addScript(JURI::base() . '../media/com_costbenefitprojection/js/footable.sort.js?v=2-0-1');
		$this->document->addScript(JURI::base() . '../media/com_costbenefitprojection/js/footable-set.js');
		// $this->document->addScript(JURI::base() . '../media/com_costbenefitprojection/js/table2excel.js');
		$this->document->addScript(JURI::base() . '../media/com_costbenefitprojection/js/chartMenu.js');
				
		$this->document->addScript('http://canvg.googlecode.com/svn/trunk/rgbcolor.js');
		$this->document->addScript('http://canvg.googlecode.com/svn/trunk/canvg.js');	
		//JHTML::_('behavior.tooltip');
		JHTML::_('behavior.modal'); 
		$this->document->addScriptDeclaration($theme);           
	}
}