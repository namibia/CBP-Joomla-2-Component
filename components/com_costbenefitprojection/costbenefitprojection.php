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

include JPATH_COMPONENT . '/helpers/jquery.php';

jimport('joomla.application.component.controller');

$controller = JController::getInstance('Costbenefitprojection');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();