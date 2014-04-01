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
jimport('joomla.application.component.view');

class CostbenefitprojectionViewData extends JView
{
	protected $item;
	protected $user;
	protected $temp;
	protected $type;
	protected $params;
	protected $xml;
	protected $Lock;
	protected $workers;
	
	public function display($tpl = null)
	{	
		if (!JFactory::getUser()->id) {
			throw new Exception(JText::_('COM_COSTBENEFITPROJECTION_ACCESS_DENIED'), 403);
		} else {
			// load component manifest file
			$manifestUrl = JPATH_ADMINISTRATOR."/components/com_costbenefitprojection/manifest.xml";
			$this->xml 			= simplexml_load_file($manifestUrl);
			
			// Get data from the model
			$this->item			= $this->get('Item');
			$this->user 		= $this->get('User');
			
			// We don't need toolbar in the modal window.
			if ($this->item['tmpl'] == 'component') {
				$this->temp 	= '&tmpl=component';
			} else {
				$this->temp 	= '';
			}
			
			// set the tab view.
			$this->type = $this->item['type'];
			
			if(class_exists(vdmLock)){
				$this->Lock = new vdmLock();
			} else {
				JPluginHelper::importPlugin('user','gizprofile');
				$this->Lock = new vdmLock();
			}
			
			// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}
			
			if (!$this->item) {
				throw new Exception(JText::_('SOON'), 404);
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
		$this->document->addStyleSheet(JURI::base( true ).'/media/com_costbenefitprojection/css/footable.core.css?v=2-0-1');
		$this->document->addStyleSheet(JURI::base( true ).'/media/com_costbenefitprojection/css/footable.metro.css');
		$this->document->addStyleSheet(JURI::base( true ).'/media/com_costbenefitprojection/css/uikit.min.css');
		
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/jquery-1.10.2.min.js');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/footable.js?v=2-0-1');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/footable.sort.js?v=2-0-1');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/footable.filter.js?v=2-0-1');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/footable.paginate.js?v=2-0-1');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/footable-set.js'); 
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
		
		JHTML::_('behavior.tooltip');   
	}
}