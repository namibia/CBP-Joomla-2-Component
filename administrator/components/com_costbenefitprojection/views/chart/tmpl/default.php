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
<div id="loading" style="height:300px; width:100%">
	<h1 style="text-align:center;" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_LOADING_PLEASE_WAIT_TITLE'); ?></h1>
    <div style="margin:0 auto; width:180px; height:24px; padding: 5px;">
    	<img width="180" height="24" src="../media/com_costbenefitprojection/images/load.gif" alt="......." title="........"/>
    </div>
</div>
<div id="st-container" class="st-container" style="display:none;">
    <nav class="st-menu st-effect-11" id="menu-11">
    	<ul>
            <li><a onclick="loadViews('main')" class="icon CTsubmenu" href="javascript:void(0)"><?php echo JFactory::getUser($this->result->user->id)->name; ?></a></li>
        </ul>
        <h2 class="icon icon-lab"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_QUICK_LINKS_SIDE_MENU'); ?></h2>
        <ul>
        	<?php foreach ($this->chart_tabs as &$item): ?>
                <li>
                    <a onclick="loadViews('<?php echo JText::_($item['view']); ?>')" class="icon CTsubmenu" href="javascript:void(0)">
                        <?php echo JText::_($item['name']); ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
        <h2 class="icon icon-lab"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_QUICK_LINKS_SIDE_MENU'); ?></h2>
        <ul>
        	<?php foreach ($this->table_tabs as &$item): ?>
                <li>
                    <a onclick="loadViews('<?php echo JText::_($item['view']); ?>')" class="icon CTsubmenu" href="javascript:void(0)">
                        <?php echo JText::_($item['name']); ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
        
        
        <!--  Debug -->
        <?php 	
				//$canDo = UsersHelper::getActions();
				//if ($canDo->get('core.admin')):
		?>
        <h2 class="icon icon-lab">Dev Menu</h2>
        <ul>
            <li><a onclick="loadViews('variables')" class="icon CTsubmenu" href="javascript:void(0)">Variables</a></li>
        </ul>
         <?php //endif; ?>
        
    </nav>
    <!-- content push wrapper -->
    <div class="st-pusher">
    	<div class="st-content"><!-- this is the wrapper for the content -->
            <div class="st-content-inner"><!-- extra div for emulating position:fixed of the menu -->
                <!-- Top Navigation -->
                <div id="st-trigger-effects" class="clearfix notice_off" >
                <button id="tcm" data-effect="st-effect-11" style="margin: 5px; width:40px; height:40px; float: left; -webkit-border-radius: 5px;border-radius: 5px; "><span style="font-size:30px; color:#038CDA;">&equiv;</span></button>
                <?php if($menuNotice < 5): ?>
                	<div class="notice note_menu">&nbsp;&nbsp;&larr;&nbsp;easy<br/>&nbsp;&nbsp;&nbsp;&nbsp;navigation<br/>&nbsp;&nbsp;&nbsp;&nbsp;menu</div>
                <?php endif; ?>
                </div>
                    <div style="margin:0 auto; width: 900px;">
                    <br/>
                        <div class="switchbox" >
                            <div class="control_switch_sf" >
                                <div class="switch" onclick="controlSwitch()" >
                                    <span class="thumb"></span>
                                    <input type="checkbox" />
                                </div>
                                <div class="label" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="main clearfix">
                    		
                            <!-- MAIN PAGE -->
                    		<?php echo $this->loadTemplate('main'); ?>
                            
                    		<!-- CHARTS -->
                    		<!-- Work days Lost -->
                            <?php echo $this->loadTemplate('chart_work_days_lost'); ?>
                            
                            <!-- Work days Lost Percent -->
                            <?php echo $this->loadTemplate('chart_work_days_lost_percent'); ?>
                            
                            <!--  Costs -->
                            <?php echo $this->loadTemplate('chart_cost'); ?>
                            
                            <!-- Cost Percent -->
                            <?php echo $this->loadTemplate('chart_cost_percent'); ?>
                            
                            <!-- Intervention Cost Benefit -->
                            <?php echo $this->loadTemplate('chart_intervention_cost_benefit'); ?>
                            
                                                
                            <!-- TABLES -->
                            <!-- Work Days Lost Summary -->
                            <?php echo $this->loadTemplate('table_work_days_lost_summary'); ?>
                            
                            <!-- Cost Summary -->
                            <?php echo $this->loadTemplate('table_cost_summary'); ?>
                            
                            <!-- Calculated Costs in Detail -->
                            <?php echo $this->loadTemplate('table_calculated_cost_in_detail'); ?>
                            
                            <!-- Intervention Net Benefit -->
                            <?php echo $this->loadTemplate('table_intervention_net_benefit'); ?>
                            
                            <!--  Debug -->
                            <!-- Only for Developers -->
                            <?php echo $this->loadTemplate('variables');// if ($canDo->get('core.admin')){} ?>                            
                    </div>
                </div><!-- /main -->
            </div><!-- /st-content-inner -->
        </div><!-- /st-content -->
    </div><!-- /st-pusher -->
</div><!-- /st-container -->
<script>
// page loading pause
jQuery(window).load(function() {
	jQuery('#loading').fadeOut( 'fast', function() {
		jQuery('#st-container').fadeIn( 'fast', function() {
			 jQuery('#main .footable').trigger('footable_resize');
		});
	});
});
jQuery(function () {
    jQuery('table.data').footable().bind('footable_filtering', function(e){
      var selected = jQuery(this).prev('p').find('.filter-status').find(':selected').text();
      if (selected && selected.length > 0){
        e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
        e.clear = !e.filter;
      }
    });
});
// notice for menu controller
jQuery( "#tcm" ).hover(function() {
	jQuery(".notice").fadeOut("slow");
});

function loadViews(e){
	jQuery( ".hidden" ).hide();
	jQuery( "#giz_"+e ).show();
	jQuery( "#giz_"+e+" .footable" ).trigger('footable_resize');
	// set the view height to always be above 700px
	var h = jQuery("#giz_"+e).height();
	
	if (h < 700){
		var scale = 700 - h;
		var add = '<div style="height: '+scale+'px;"></div>'
		jQuery("#view_"+e).append(add);
	}
	
}

// Switch for Scaling factors & One Episode
function controlSwitch(){
	if ( jQuery(".switch").hasClass("on") ) {
		jQuery(".switch").removeClass("on");
		jQuery(".unscaled").css( "display", "table" );
		jQuery(".scaled").css( "display", "none" );
	} else {
		jQuery(".switch").addClass("on");
		jQuery(".scaled").css( "display", "table" );
		jQuery(".unscaled").css( "display", "none" );
	};
}
</script>