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

class CostbenefitprojectionViewDiseasedata extends JView
{
	protected $form;
	protected $item;
	protected $tmpl;
	
	public function display($tpl = null)
	{		
		if (!JFactory::getUser()->id) {
			throw new Exception(JText::_('COM_COSTBENEFITPROJECTION_ACCESS_DENIED'), 403);
		} else {
			// Get data from the model
			$this->form = $this->get('Form');
			$this->item = $this->get('Item');
			// Get tmpl values
			$this->tmpl = JRequest::getCmd('tmpl');
			
			// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}    	
		
			$this->_prepareDocument();
			// Display the template
			parent::display($tpl);
		}
 
	}
		
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway 	= $app->getPathway();
		$title		= null;
		
		$this->document->addStyleSheet(JURI::base( true ).'/media/com_costbenefitprojection/css/offline.css');
		$this->document->addStyleSheet(JURI::base( true ).'/media/com_costbenefitprojection/css/main.css');
        $this->document->addStyleSheet(JURI::base( true ).'/media/com_costbenefitprojection/css/bootstrap.css');
		$this->document->addStyleSheet(JURI::base( true ).'/media/com_costbenefitprojection/css/uikit.min.css');
		
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/jquery-1.10.2.min.js');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/bootstrap.min.js');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/uikit.min.js');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/offline.min.js');
		
		// to check in app is online
		$offline	= "Offline.options = {checks: {image: {url: '" . JURI::base() . "media/com_costbenefitprojection/images/giz.png'}}};
						var run = function(){
						  if (Offline.state === 'up')
							Offline.check();
						}
						setInterval(run, 3000);";
		$this->document->addScriptDeclaration($offline);
		
		JHTML::_('behavior.tooltip');   
	}
}