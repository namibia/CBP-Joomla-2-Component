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

jimport( 'joomla.application.component.view' );
jimport( 'joomla.application.component.helper' );

class CostbenefitprojectionViewCp extends JView
{
	protected $item;
	protected $chart_tabs;
	protected $table_tabs;
	protected $user;
	protected $params;
	protected $xml;
	protected $Chart;
	protected $Lock;
	protected $workers;

	public function display($tpl = null)
	{
		if (!JFactory::getUser()->id) {
			throw new Exception(JText::_('COM_COSTBENEFITPROJECTION_ACCESS_DENIED'), 403);
		} else {
			//load chart builder
			require_once JPATH_ADMINISTRATOR.'/components/com_costbenefitprojection/models/chartbuilder.php';
			// load component manifest file
			$manifestUrl = JPATH_ADMINISTRATOR."/components/com_costbenefitprojection/manifest.xml";
			// Get data from the model
			$this->item			= $this->get('Item');
			$this->chart_tabs 	= $this->get('ChartTabs');
			$this->table_tabs 	= $this->get('TableTabs');
			$this->user 		= $this->get('User');
			$this->xml 			= simplexml_load_file($manifestUrl);
			
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
			// load document
			$this->_prepareDocument();
			
			parent::display($tpl);
		}
	}
		
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app			= JFactory::getApplication();
		$this->params	= $app->getParams();
		$menus			= $app->getMenu();
		$pathway 		= $app->getPathway();
		$title			= null;
				
		// set chart stuff
		$this->Chart['backgroundColor'] = $this->params->get('backgroundColor');
		$this->Chart['width'] = $this->params->get('mainwidth');
		$this->Chart['chartArea'] = array('top' => $this->params->get('chartAreaTop'), 'left' => $this->params->get('chartAreaLeft'), 'width' => $this->params->get('chartAreawidth').'%');
		$this->Chart['legend'] = array( 'textStyle' => array('fontSize' => $this->params->get('legendTextStyleFontSize'), 'color' => $this->params->get('legendTextStyleFontColor')));
		$this->Chart['vAxis'] = array('textStyle' => array('color' => $this->params->get('vAxisTextStyleFontColor')));
		$this->Chart['hAxis']['textStyle'] = array('color' => $this->params->get('hAxisTextStyleFontColor'));
		$this->Chart['hAxis']['titleTextStyle'] = array('color' => $this->params->get('hAxisTitleTextStyleFontColor'));
		
		$this->document->addStyleSheet(JURI::base( true ).'/media/com_costbenefitprojection/css/offline.css');			
		$this->document->addStyleSheet(JURI::base( true ).'/media/com_costbenefitprojection/css/main.css');
		$this->document->addStyleSheet(JURI::base( true ).'/media/com_costbenefitprojection/css/footable.core.css?v=2-0-1');
		$this->document->addStyleSheet(JURI::base( true ).'/media/com_costbenefitprojection/css/footable.metro.css');
		
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/jquery-1.10.2.min.js');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/google.jsapi.js');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/footable.js?v=2-0-1');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/footable.sort.js?v=2-0-1');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/footable.filter.js?v=2-0-1');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/footable.paginate.js?v=2-0-1'); 
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/uikit.min.js');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/sticky.js');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/footable-set.js');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/offline.min.js');
		$this->document->addScript(JURI::base( true ). '/media/com_costbenefitprojection/js/table2excel.js');
		
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
		
		// to check in app is online
		$offline	= "Offline.options = {checks: {image: {url: '" . JURI::base() . "media/com_costbenefitprojection/images/giz.png'}}};
						var run = function(){
						  if (Offline.state === 'up')
							Offline.check();
						}
						setInterval(run, 3000);";
		$this->document->addScriptDeclaration($offline);
		
		
		$this->document->addScript('http://canvg.googlecode.com/svn/trunk/rgbcolor.js');
		$this->document->addScript('http://canvg.googlecode.com/svn/trunk/canvg.js');	
		//JHTML::_('behavior.tooltip');
		JHTML::_('behavior.modal');  
	}
}