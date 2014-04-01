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

jimport('joomla.form.formrule');

/**
* Form Rule class for the Joomla Framework.
*
* @package        Joomla.Framework
* @since          1.6
*/
class JFormRuleNop extends JFormRule
{
     public function test(& $element, $value, $group = null, & $input = null, & $form = null)
    {
			if ($value < 0 || $value > 1){
				
				return false;
			}
        return true;
    }
}