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

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_costbenefitprojection')) {
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

// Register helper class
JLoader::register('UsersHelper', dirname(__FILE__) . '/helpers/users.php');

jimport('joomla.application.component.controller');

$controller = JController::getInstance('Costbenefitprojection');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();