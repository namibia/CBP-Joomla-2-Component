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

class CostbenefitprojectionViewRiskdata extends JView
{
	protected $form;
	protected $item;
	protected $xml;
	
	public function display($tpl = null)
	{	
		$manifestUrl = JPATH_ADMINISTRATOR."/components/com_costbenefitprojection/manifest.xml";
		$this->xml = simplexml_load_file($manifestUrl);
		
		// Get data from the model
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		
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
		JHtml::stylesheet('com_costbenefitprojection/admin.stylesheet.css', array(), true, false, false);
		
		if ($this->item->id) {
			JToolBarHelper::title(JText::_('COM_COSTBENEFITPROJECTION_EDIT_RISKDATA_TITLE'), 'riskdata-edit');
		} else {
			JToolBarHelper::title(JText::_('COM_COSTBENEFITPROJECTION_ADD_RISKDATA_TITLE'), 'riskdata-add');
		}

		JToolBarHelper::apply('riskdata.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('riskdata.save', 'JTOOLBAR_SAVE');
		JToolBarHelper::custom('riskdata.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		JToolBarHelper::save2copy('riskdata.save2copy');

		JToolBarHelper::cancel('riskdata.cancel');
		
		JToolBarHelper::divider();

		require_once JPATH_COMPONENT.DS.'helpers'.DS.'toolbar.php';
		// helper toolbar
		CostbenefitprojectionToolbarHelper::help('riskdata');
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