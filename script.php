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
						$defaults = '{"admin":["7","6","8"],"country":["5"],"service":["4"],"client":["2","3"],"backgroundColor":"#F7F7FA","mainwidth":"1000","chartAreaTop":"20","chartAreaLeft":"320","chartAreawidth":"60","legendTextStyleFontSize":"10","legendTextStyleFontColor":"#63B1F2","vAxisTextStyleFontColor":"#63B1F2","hAxisTitleTextStyleFontColor":"#63B1F2","hAxisTextStyleFontColor":"#63B1F2","backend_backgroundColor":"#ffffff","backend_mainwidth":"1200","backend_chartAreaTop":"20","backend_chartAreaLeft":"320","backend_chartAreawidth":"60","backend_legendTextStyleFontSize":"10","backend_legendTextStyleFontColor":"#63B1F2","backend_vAxisTextStyleFontColor":"#63B1F2","backend_hAxisTitleTextStyleFontColor":"#63B1F2","backend_hAxisTextStyleFontColor":"#63B1F2","lvdm_text":"1","lvdm_logo":"1","lvdm_front":"1","lvdm_theme":"1","lvdm_n":"VDM","lvdm_u":"http:\/\/www.vdm.io","lvdm_b":"","check_in":"-1 day","nameGlobal":"GIZ","emailGlobal":"matthew.black@giz.de","titleWorker1":"Health Economist","nameWorker1":"Patrick Hanlon, M.Sc. PH","emailWorker1":"Patrick.Hanlon@unibas.ch","linkWorker1":"http:\/\/www.swisstph.ch\/about-us\/staff\/detailview.html?tx_x4epersdb_pi1[showUid]=2267&cHash=1b1c5db0808e04d3f1afe0f3a3f67998","useWorker1":"2","showWorker1":"3","titleWorker2":"Development Advisor","nameWorker2":"Matthew Black","emailWorker2":"matthew.black@giz.de","linkWorker2":"http:\/\/www.giz.de","useWorker2":"1","showWorker2":"3","titleWorker3":"Associate Expert","nameWorker3":"Dr. Pascal Geldsetzer","emailWorker3":"pascal.geldsetzer@giz.de","linkWorker3":"http:\/\/www.giz.de","useWorker3":"1","showWorker3":"3","titleWorker4":"","nameWorker4":"","emailWorker4":"","linkWorker4":"","useWorker4":"0","showWorker4":"0","salt_one":"","salt_two":"","salt_three":"ipTX7QfuPihZo0iWc2aURdti3REryyiTCyO1ou9Qq9I","salt_four":"Qt3hMQ7M49xkCB7sANT8qAWXVNK8AtGiqqrq0IhiTxE"}';
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