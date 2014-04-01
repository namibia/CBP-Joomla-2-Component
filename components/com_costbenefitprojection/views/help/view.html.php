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

class CostbenefitprojectionViewHelp extends JView
{
	protected $item;
	protected $xml;
	protected $Lock;
	protected $params;
	protected $workers;
	
	public function display($tpl = null)
	{	
		$manifestUrl = JPATH_ADMINISTRATOR."/components/com_costbenefitprojection/manifest.xml";
		$this->xml = simplexml_load_file($manifestUrl);
		
		// Get data from the model
		$this->item = $this->get('Item');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		if(class_exists(vdmLock)){
			$this->Lock = new vdmLock();
		} else {
			JPluginHelper::importPlugin('user','gizprofile');
			$this->Lock = new vdmLock();
		}
		
		// load document
		$this->_prepareDocument();
		
		parent::display($tpl);
	}
	
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app			= JFactory::getApplication();
		$menus			= $app->getMenu();
		$pathway 		= $app->getPathway();
		$this->params	= $app->getParams();
		$title			= null;
			
		// set contributors
		$w = 0;
		for ($i = 1; $i <= 4; $i++) {
			// should we show the worker
			$showWorker[$i] = $this->params->get('showWorker'.$i);
			if ( $showWorker[$i] == 2 || $showWorker[$i] == 3){
				$this->workers[$w]['name'] = $this->params->get('nameWorker'.$i);
				$this->workers[$w]['title'] = $this->params->get('titleWorker'.$i);
				// how to link to worker
				$useWorker[$i] = $this->params->get('useWorker'.$i);
				if ($useWorker[$i] == 1){
					$this->workers[$w]['load'] = '<a href="mailto:' . $this->params->get('emailWorker'.$i) . '" target="_blank">'
					. $this->workers[$w]['name'] . '</a>';
				} elseif ($useWorker[$i] == 2){
					$this->workers[$w]['load'] = '<a href="' . $this->params->get('linkWorker'.$i) . '" target="_blank">'
					. $this->workers[$w]['name'] . '</a>';
				} else {
					$this->workers[$w]['load'] = $this->workers[$w]['name'];
				}
				
				$w++;
			}
		}
		
		$this->document->addStyleSheet(JURI::base( true ).'/media/com_costbenefitprojection/css/offline.css');
		$this->document->addStyleSheet(JURI::base( true ).'/media/com_costbenefitprojection/css/main.css');
		
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/jquery-1.10.2.min.js');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/uikit.min.js'); 
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/sticky.js');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/offline.min.js');	
		
		// to check in app is online
		$offline	= "Offline.options = {checks: {image: {url: '" . JURI::base() . "media/com_costbenefitprojection/images/giz.png'}}};
						var run = function(){
						  if (Offline.state === 'up')
							Offline.check();
						}
						setInterval(run, 3000);";
		$this->document->addScriptDeclaration($offline);
			
		//JHTML::_('behavior.tooltip');
		JHTML::_('behavior.modal');  
	}

}