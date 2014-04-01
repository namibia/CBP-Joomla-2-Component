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

?>
<div id="loading" style="height:200px; width:100%">
	<div style="margin:0 auto; width:180px; height:24px; padding: 5px;">
    	<p style="text-align:center;"><i class="uk-icon-cog uk-icon-spin"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_PLEASE_WAIT'); ?></p>
    </div>
</div>
<div id="costbenefitprojection" style="display:none;"><!-- MAIN DIV -->
    <div data-uk-sticky style="background: #F7F7FA; z-index:3; padding: 5px 0 5px 0;" >
    <nav class="uk-navbar uk-hidden-small">
        <ul class="uk-navbar-nav">
            <li><a  href="index.php?option=com_users&task=user.logout&<?php echo JUtility::getToken(); ?>=1&Itemid=109" ><i class="uk-icon-power-off"></i></a></li>
            <li><a href="index.php?option=com_users&view=profile" ><i class="uk-icon-user"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_PROFILE'); ?></a></li>
            <li><a href="index.php?option=com_costbenefitprojection&view=cp"><i class="uk-icon-laptop"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CPANEL'); ?></a></li>
            <li><a href="index.php?option=com_costbenefitprojection&view=data"><i class="uk-icon-cogs"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_DATA'); ?></a></li>
            <li class="uk-active"><a data-uk-offcanvas="{target:'#offcanvas-help'}" href="" ><i class="uk-icon-lightbulb-o"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_HELP'); ?></a></li>
         </ul>
         <ul class="uk-navbar-nav uk-navbar-flip uk-hidden-small" >
            <li><a data-uk-offcanvas="{target:'#offcanvas-info'}" href="" ><i class="uk-icon-cog uk-icon-spin"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_TOOL'); ?></a></li>
        </ul> 
    </nav>
    </div>
    <div  class="uk-visible-small">
        <!-- This is the container enabling the JavaScript -->
        <div class="uk-button-dropdown" data-uk-dropdown>
        
            <!-- This is the button toggling the dropdown -->
            <div><button class="uk-button"><?php echo JText::_('COM_COSTBENEFITPROJECTION_MENU'); ?> <i class="uk-icon-caret-down"></i></button></div>
        
            <!-- This is the dropdown -->
            <div class="uk-dropdown uk-dropdown-small">
                <ul class="uk-nav uk-nav-dropdown">
                    <li><a  href="index.php?option=com_users&task=user.logout&<?php echo JUtility::getToken(); ?>=1&Itemid=109" ><i class="uk-icon-power-off"></i></a></li>
                    <li><a href="index.php?option=com_users&view=profile" ><i class="uk-icon-user"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_PROFILE'); ?></a></li>
                    <li><a href="index.php?option=com_costbenefitprojection&view=cp"><i class="uk-icon-laptop"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CPANEL'); ?></a></li>
            		<li><a href="index.php?option=com_costbenefitprojection&view=data"><i class="uk-icon-cogs"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_DATA'); ?></a></li>
                    <li class="uk-active"><a data-uk-offcanvas="{target:'#offcanvas-help'}" href="" ><i class="uk-icon-lightbulb-o"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_HELP'); ?></a></li>
                </ul>
            </div>
        </div>
        
    </div>
    <div class="uk-margin uk-grid">
        <div class="uk-width-medium-1">
              <?php echo $this->item; ?>
        </div>
    </div>
</div><!-- MAIN DIV -->

<div class="uk-offcanvas" id="offcanvas-help">
	<div class="uk-offcanvas-bar">
        <div class="uk-panel">
            <ul data-uk-nav="" class="uk-nav uk-nav-offcanvas uk-nav-parent-icon">
                <li class="uk-nav-header"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CONTACT'); ?></li>
                <li><a href="index.php?option=com_costbenefitprojection&view=help&id=<?php echo $this->Lock->it('client-','Contact Country'); ?>"><?php echo JText::_('COM_COSTBENEFITPROJECTION_COUNTRY_REPRESENTER'); ?></a></li>
                <li><a href="index.php?option=com_costbenefitprojection&view=help&id=<?php echo $this->Lock->it('client-','Contact Service'); ?>"><?php echo JText::_('COM_COSTBENEFITPROJECTION_SERVICE_PROVIDERS'); ?></a></li>
                <li class="uk-nav-header"><?php echo JText::_('COM_COSTBENEFITPROJECTION_ONLINE'); ?></li>
                <li><a href="index.php?option=com_costbenefitprojection&view=help&id=<?php echo $this->Lock->it('client-','Faq'); ?>"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FAQ'); ?></a></li>
                <li><a href="index.php?option=com_costbenefitprojection&view=help&id=<?php echo $this->Lock->it('client-','Desk'); ?>"><?php echo JText::_('COM_COSTBENEFITPROJECTION_HELP_DESK'); ?></a></li>
                <li class="uk-nav-header"><?php echo JText::_('COM_COSTBENEFITPROJECTION_WEBSITE'); ?></li>
                <li><a href="index.php?option=com_costbenefitprojection&view=help&id=<?php echo $this->Lock->it('client-','Bug'); ?>"><?php echo JText::_('COM_COSTBENEFITPROJECTION_BUG_REPORT'); ?></a></li>
            </ul>
        </div>
    </div>
</div>
        
<div class="uk-offcanvas" id="offcanvas-info">
	<div class="uk-offcanvas-bar uk-offcanvas-bar-flip">
        <div class="uk-panel">
            <div class="uk-animation-fade uk-animation-scale-down">
            	<img style="display: block; margin-left: auto; margin-right: auto; vertical-align: middle;" src="images/box.png" alt="box" />
            </div>
            <p class="uk-text-small uk-text-bold">
                <i class="uk-icon-cog uk-icon-spin"></i> <?php echo $this->xml->description; ?>
            </p>
            <ul class="uk-list uk-list-striped">
                <li class="uk-text-small"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CURRENT_INSTALLED_VERSION'); ?> <strong><span style="color:#DBA400;"><?php echo $this->xml->version; ?></span></strong></li>
                <li class="uk-text-small"><?php echo $this->xml->copyright; ?> </li>
                <li class="uk-text-small"><?php echo JText::_('COM_COSTBENEFITPROJECTION_LICENSE'); ?> <a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank"><?php echo JText::_('COM_COSTBENEFITPROJECTION_GNU_GPL'); ?></a> <?php echo JText::_('COM_COSTBENEFITPROJECTION_COMMERCIAL'); ?></li>
                <?php if ($this->workers): ?>
					<?php foreach($this->workers as $worker): ?>
                        <li class="uk-text-small"><?php echo $worker['title']; ?>
                            <br/><?php echo $worker['load']; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if ($this->params->get('lvdm_front') == 1) : ?>
                <li class="uk-text-small"><?php echo JText::_('COM_COSTBENEFITPROJECTION_APPLICATION_ENGINEER'); ?> <br/><a href="http://<?php echo $this->xml->authorUrl; ?>" target="_blank"><?php echo $this->xml->author; ?></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">


// page loading pause
jQuery(window).load(function() {
	jQuery('#loading').fadeOut( 'fast', function() {
		jQuery('#costbenefitprojection').fadeIn( 'fast', function() {
			jQuery('#tables.uk-active .footable').trigger('footable_resize');
		});
	});
});

</script>