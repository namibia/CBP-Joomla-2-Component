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

jimport( 'joomla.application.component.view' );
jimport( 'joomla.application.component.helper' );

class CostbenefitprojectionViewPublic extends JView
{
	protected $item;
	protected $params;
	protected $xml;
	protected $Lock;
	protected $workers;
	protected $loadModule;

	public function display($tpl = null)
	{
		if (JFactory::getUser()->id) {
			throw new Exception(JText::_('COM_COSTBENEFITPROJECTION_ACCESS_DENIED'), 403);
		} else {
			//load chart builder
			require_once JPATH_ADMINISTRATOR.'/components/com_costbenefitprojection/models/chartbuilder.php';
			// load component manifest file
			$manifestUrl = JPATH_ADMINISTRATOR."/components/com_costbenefitprojection/manifest.xml";
			// Get data from the model
			$this->item			= $this->get('Item');
			$this->xml 			= simplexml_load_file($manifestUrl);
			
			if(class_exists(vdmLock)){
				$this->Lock = new vdmLock();
			} else {
				JPluginHelper::importPlugin('user','gizprofile');
				$this->Lock = new vdmLock();
			}
			
			if (!$this->item['form']){
				// Include the JLog class.
				jimport('joomla.log.log');
				
				// get ip for log
				$ip = $this->getUserIP();
				
				// Add the logger.
				JLog::addLogger(
					 // Pass an array of configuration options
					array(
							// Set the name of the log file
							'text_file' => 'costbenefitprojection_public.php',
							// (optional) you can change the directory
							//'text_file_path' => 'logs'
					 ),
					 JLog::NOTICE
				);
				$number_employees = $this->item['data']['number_male'] + $this->item['data']['number_female'];
				// start logging...
				$log = 'Country id->['.$this->item['data']['country'].'] Country User id->['.$this->item['data']['countryUserId'].']';
				$log .= 'Number of employees->[' . $number_employees . '] ';
				$log .= 'Total salary->['.$this->item['data']['currency']['currency_symbol'].' '.$this->item['data']['total_salary'].'] ';
				$log .= 'ip->['.$ip.']';
				JLog::add( $log, JLog::NOTICE, 'view' );
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
		 
		// load modules for this public page
		$this->loadPosition('publicForm');
				
		// set chart stuff
		$this->Chart['backgroundColor'] = $this->params->get('backgroundColor');
		$this->Chart['width'] = '800';
		$this->Chart['chartArea'] = array('top' => $this->params->get('chartAreaTop'), 'left' => 190/*$this->params->get('chartAreaLeft')*/, 'width' => $this->params->get('chartAreawidth').'%');
		$this->Chart['legend'] = array( 'textStyle' => array('fontSize' => $this->params->get('legendTextStyleFontSize'), 'color' => $this->params->get('legendTextStyleFontColor')));
		$this->Chart['vAxis'] = array('textStyle' => array('color' => $this->params->get('vAxisTextStyleFontColor')));
		$this->Chart['hAxis']['textStyle'] = array('color' => $this->params->get('hAxisTextStyleFontColor'));
		$this->Chart['hAxis']['titleTextStyle'] = array('color' => $this->params->get('hAxisTitleTextStyleFontColor'));
		
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
		
		$this->document->addScript(JURI::base( true ) . '/media/com_costbenefitprojection/js/jquery-1.10.2.min.js');
		$this->document->addScript(JURI::base( true ) . '/media/com_costbenefitprojection/js/google.jsapi.js');
		$this->document->addScript(JURI::base( true ) . '/media/com_costbenefitprojection/js/offline.min.js');
		
		// to check in app is online
		$offline	= "Offline.options = {checks: {image: {url: '" . JURI::base() . "media/com_costbenefitprojection/images/giz.png'}}};
						var run = function(){
						  if (Offline.state === 'up')
							Offline.check();
						}
						setInterval(run, 3000);";
		$this->document->addScriptDeclaration($offline);
				
		// JHTML::_('behavior.tooltip');
		// JHTML::_('behavior.modal');  
	}
	
	/**
	 * Get the modules published in a position
	 */
	protected function loadPosition($position)
	{
		// load modules
		$renderer = $this->document->loadRenderer('modules');
		$options = array('style' => 'raw');
		$this->loadModule[$position] = $renderer->render($position, $options, null);
	}
	
	protected function getUserIP()
	{
		$ip = "";
		
		if (isset($_SERVER)) {
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
				$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
				$ip = $_SERVER["HTTP_CLIENT_IP"];
			} else {
				$ip = $_SERVER["REMOTE_ADDR"];
			}
		} else {
			if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
				$ip = getenv( 'HTTP_X_FORWARDED_FOR' );
			} elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
				$ip = getenv( 'HTTP_CLIENT_IP' );
			} else {
				$ip = getenv( 'REMOTE_ADDR' );
			}
		}
		return $ip;
    }
}