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

jimport('joomla.application.component.controllerform');

class CostbenefitprojectionControllerIntervention extends JControllerForm
{
	protected $text_prefix = 'COM_COSTBENEFITPROJECTION_INTERVENTIONS';
	
	protected $view_list = 'data';
	
	/**
	 * Gets the URL arguments to append to a list redirect.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 */
	protected function getRedirectToListAppend()
	{
		$tmpl = JRequest::getCmd('tmpl');
		$append = '';
		
		// Setup redirect info.
		if($tmpl){
			$append .= '&tmpl='.$tmpl;
		}
		
		$append .= '&type=3';
		
		return $append;
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$tmpl   = JRequest::getCmd('tmpl');
		$layout = JRequest::getCmd('layout', 'edit');
		$append = '';

		// Setup redirect info.
		if ($tmpl)
		{
			$append .= '&tmpl=' . $tmpl;
		}

		if ($layout)
		{
			$append .= '&layout=' . $layout;
		}

		if ($recordId)
		{
			$append .= '&' . $urlVar . '=' . $recordId;
		}

		return $append;
	}
}