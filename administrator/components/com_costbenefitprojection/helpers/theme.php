<?php
/**
* 
* 	@version 	1.0.0 July 24, 2013
* 	@package 	Cost Benefit Projection Tool Application
*	@copyright	Vast Development Method <http://www.vdm.io>
* 	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
*
**/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');

$vdmLogo = JComponentHelper::getParams('com_costbenefitprojection')->get('lvdm_logo');
$vdmText = JComponentHelper::getParams('com_costbenefitprojection')->get('lvdm_text');
$vdmTheme = JComponentHelper::getParams('com_costbenefitprojection')->get('lvdm_theme');
$vdmTitle = JComponentHelper::getParams('com_costbenefitprojection')->get('lvdm_n');
$vdmUrl = JComponentHelper::getParams('com_costbenefitprojection')->get('lvdm_u');

if ($vdmText == 1){
	$vdm = 'jQuery("#footer").html("<p class=\"copyright\"><a href=\"http://'. $this->xml->authorUrl .'\" target=\"_blank\" title=\"Application Engineer\">'. $this->xml->author .'</a></p>");';
} else {
	$vdm = 'jQuery("#footer").html("<p class=\"copyright\">VDM</p>");';
}

if ($vdmTheme == 1){
	// set the theme style
	$theme = '

		jQuery(document).ready(function() {
			'.$vdm.'
			jQuery("#element-box").append("<div id=\"appName\">' .$this->xml->name . " " . $this->xml->version . ' | <a href=\"http://www.giz.de/\">' . $this->xml->copyright . '</a></div>");
			jQuery("#content-box").siblings("p").html("");
			jQuery(".loggedin-users").html("");
			jQuery(".backloggedin-users").html("");
			jQuery(".no-unread-messages").html("");
			jQuery("#border-top .title").html("<a href=\"http://www.giz.de/\"><img src=\"../media/com_costbenefitprojection/images/top_giz_logo.jpg\" /></a>");
			jQuery("#border-top .logo").html("<a target=\"_blank\" href=\"http://www.swisstph.ch\" title=\"Swiss TPH\"><img src=\"../media/com_costbenefitprojection/images/top_swiss_TPH_logo.jpg\" /></a>");
			jQuery("#menu li:contains(\'Help\')").html("");
			jQuery("#toolbar-box .icon-48-main").css({"background":"none"});
			jQuery("#menu li a").hover(function() {
				jQuery( this ).css({"border-radius":"8px"});
			});
		});
	
	';

} else {
	// set the theme style
	$theme = '

		jQuery(document).ready(function() {
			'.$vdm.'
			jQuery("#element-box").append("<div id=\"appName\">' .$this->xml->name . " " . $this->xml->version . ' | <a href=\"http://www.giz.de/\">' . $this->xml->copyright . '</a></div>");
			jQuery("#content-box").siblings("p").html("");
			jQuery("#menu li:contains(\'Help\')").html("");
			jQuery(".loggedin-users").html("");
			jQuery(".backloggedin-users").html("");
			jQuery(".no-unread-messages").html("");
			jQuery("#appName").css({"text-align":"center","font-size":"10px","margin":"10px"});
		});
	
	';
}