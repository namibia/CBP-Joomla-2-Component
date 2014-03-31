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

jimport('joomla.form.formrule');

/**
* Form Rule class for the Joomla Framework.
*
* @package        Joomla.Framework
* @since          1.6
*/
class JFormRuleNtp extends JFormRule
{
     public function test(& $element, $value, $group = null, & $input = null, & $form = null)
    {
			if ($value < 0 || $value > 1000){
				
				return false;
			}
        return true;
    }
}