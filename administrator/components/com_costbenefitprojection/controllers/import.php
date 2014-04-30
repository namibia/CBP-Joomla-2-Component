<?php
/**
* 
* 	@version 	2.0.0 March 13, 2014
* 	@package 	Staff Health Cost Benefit Projection
* 	@editor  	Vast Development Method <http://www.vdm.io>
* 	@copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
* 	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
*
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

class CostbenefitprojectionControllerImport extends JControllerLegacy
{
	/**
	 * Import data.
	 *
	 * @return	void
	 */
	public function import()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$model = $this->getModel('import');
		if ($model->import()) {
			$cache = JFactory::getCache('mod_menu');
			$cache->clean();
			// TODO: Reset the users acl here as well to kill off any missing bits
		}

		$app = JFactory::getApplication();
		$redirect_url = $app->getUserState('com_costbenefitprojection.redirect_url');
		if(empty($redirect_url)) {
			$redirect_url = JRoute::_('index.php?option=com_costbenefitprojection&view=import', false);
		} else
		{
			// wipe out the user state when we're going to redirect
			$app->setUserState('com_costbenefitprojection.redirect_url', '');
			$app->setUserState('com_costbenefitprojection.message', '');
			$app->setUserState('com_costbenefitprojection.extension_message', '');
		}
		$this->setRedirect($redirect_url);
	}
}

