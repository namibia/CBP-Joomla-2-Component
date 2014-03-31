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
defined('_JEXEC') or die;
 
class com_CostbenefitprojectionInstallerScript
{
        // function install($parent) 
        // {
        //        $parent->getParent()->setRedirectURL('index.php?option=com_costbenefitprojection&view=categories');
        // }
		function uninstall($parent) 
        {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
 
			// delete all menus under the menu type costbenefitprojection.
			$conditions = array(
				"menutype = 'costbenefitprojection'");
			 
			$query->delete($db->quoteName('#__menu'));
			$query->where($conditions);
			 
			$db->setQuery($query);
			 
			try {
			   $result = $db->query(); // $db->execute(); for Joomla 3.0.
			} catch (Exception $e) {
			   // catch the error.
			}
			
			$query = $db->getQuery(true);
 
			// delete the menu type costbenefitprojection.
			$conditions = array(
				"menutype = 'costbenefitprojection'");
			 
			$query->delete($db->quoteName('#__menu_types'));
			$query->where($conditions);
			 
			$db->setQuery($query);
			 
			try {
			   $result = $db->query(); // $db->execute(); for Joomla 3.0.
			} catch (Exception $e) {
			   // catch the error.
			}
			
			echo '	<h2>What whent wrong? Please let us know at <a href="mailto:support@vdm.io">support@vdm.io</a></h2>
					<p>We are committed to building applications that serve you well!
					<br />Visit us at <a href="http://www.vdm.io" target="_blank">VDM</a>';
        }
		
        function postflight($type, $parent) 
        {
                if ($type == 'install') {
						// Set Global Settings
						$db = JFactory::getDBO();
						$query = $db->getQuery(true);
						$query->update('#__extensions');
						$defaults = '{"admin":["8","9"],"country":["7"],"service":["6"],"client":["2","3"],"backgroundColor":"#F7F7FA","mainwidth":"1000","chartAreaTop":"20","chartAreaLeft":"170","chartAreawidth":"60","legendTextStyleFontSize":"10","legendTextStyleFontColor":"#63B1F2","vAxisTextStyleFontColor":"#63B1F2","hAxisTitleTextStyleFontColor":"#63B1F2","hAxisTextStyleFontColor":"#63B1F2","lvdm_text":"1","lvdm_logo":"1","lvdm_front":"1","lvdm_theme":"1","lvdm_n":"VDM","lvdm_u":"http:\/\/www.vdm.io","lvdm_b":"","check_in":"-1 day"}';
						$query->set("params =  '{$defaults}'");
						$query->where("element = 'com_costbenefitprojection'"); 
						$db->setQuery($query);
						$db->query();
					   					 
						/*// import joomla's filesystem classes
						jimport('joomla.filesystem.folder');
						
						//First we set up parameters
						$dest = JPATH_ROOT . DS . 'images'.DS.'giz';
						$src = JPATH_ROOT . DS . 'components' . DS . 'com_costbenefitprojection' . DS . 'giz';
						
						//Then we move the demo pictures to the image folder
						if ( JFolder::move($src, $dest) ) {
							//Success
						}*/
				
				echo '	<p>Congratulations! Now you can start using Cost Benefit Projection!</p>
						<a target="_blank" href="http://www.vdm.io/" title="VDM">
						<img src="../media/com_costbenefitprojection/images/jm_c.jpg"/>
						</a>';
       			} 
				if ($type == 'update') {
					
				echo '	<p>Congratulations! You have successfully updated Cost Benefit Projection</p>
						<a target="_blank" href="http://www.vdm.io/" title="VDM">
						<img src="../media/com_costbenefitprojection/images/jm_c.jpg"/>
						</a>';
				}
        }
}