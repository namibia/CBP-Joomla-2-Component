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

// No direct access.
defined('_JEXEC') or die;

?>
<div class="uk-grid" data-uk-grid-match="{target:'.uk-panel'}" data-uk-grid-margin="">
    <div class="uk-width-medium-1-2 uk-animation-slide-left">
        <div class="uk-panel uk-panel-box uk-text-center">
            <h2><i class="uk-icon-user uk-icon-large"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_COMPANY_PROFILE'); ?></h2>
            <?php /*?><a class="uk-button uk-button-primary uk-button-large uk-button-expand" href="index.php?option=com_users&view=profile"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CLICK_HERE'); ?></a><?php */?>
        </div>
    </div>
    <div class="uk-width-medium-1-2 uk-animation-slide-right">
        <div class="uk-panel uk-panel-box uk-text-center">
            <h2><i class="uk-icon-gears uk-icon-large"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_DATA'); ?></h2>
           <?php /*?> <a class="uk-button uk-button-primary uk-button-large uk-button-expand" href="index.php?option=com_costbenefitprojection&view=data"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CLICK_HERE'); ?></a><?php */?>
        </div>
    </div>
    <div class="uk-width-medium-1-1 uk-animation-slide-left">
        <div class="uk-panel uk-panel-box uk-text-center">
        	<h1 class="uk-text-success"><i class="uk-icon-check-square-o uk-icon-large"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_RESULTS'); ?></h1>
            <button id="results-switch" class="uk-button uk-button-primary uk-button-large uk-button-expand" type="button"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CLICK_HERE'); ?></button>
        </div>
    </div>
</div>
<script type="text/javascript">
// seting the correct view for the results button
jQuery( "#results-switch" ).click(function() {
	// change to the correct menu
	jQuery( "#target-menu li:nth-child(1)" ).removeClass("uk-active");
	jQuery( "#target-menu li:nth-child(2)" ).addClass("uk-active");
	// move the correct view into place
	jQuery( "#main li#cpanel" ).removeClass("uk-active");
	jQuery( "#main li#results-page" ).addClass("uk-active");
	jQuery( "#results li#results-one" ).addClass("uk-active");
	
});

</script>