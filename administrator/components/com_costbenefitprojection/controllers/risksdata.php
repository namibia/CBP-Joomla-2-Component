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

jimport('joomla.application.component.controlleradmin');

class CostbenefitprojectionControllerRisksdata extends JControllerAdmin
{
	protected $text_prefix = 'COM_COSTBENEFITPROJECTION_RISKS';

	public function getModel($name = 'Riskdata', $prefix = 'CostbenefitprojectionModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	public function gotoRisks() {

        //Put code you want to execute here 
        //You could forexample require_once(JPATH_COMPONENT_SITE.'/views/yourview/track_addtrack.php');

    }

}