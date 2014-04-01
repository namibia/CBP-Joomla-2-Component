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
<?php if($this->item[noData][disease][nr] > 0 || $this->item[noData][risk][nr] > 0): ?>
    <div>
        <?php if($this->item[noData][disease][nr] > 0): ?>
        <div class="alert alert-warning">
            <h3>
                <span  style="color:red;">
					<?php 
                        if($this->item[noData][disease][nr] > 1){ 
                            echo JText::_('COM_COSTBENEFITPROJECTION_CHART_NODATA_DISEASES'); 
                        } else {
                            echo JText::_('COM_COSTBENEFITPROJECTION_CHART_NODATA_DISEASE');
                        }
                    ?>
                </span>
                	<?php echo $this->item[noData][disease][name]; ?>
            </h3>
        </div>
        <?php endif; ?>
        <?php if($this->item[noData][risk][nr] > 0): ?>
        <div class="alert alert-warning">
            <h3>
                <span  style="color:red;">
					<?php 
                        if($this->item[noData][risk][nr] > 1){
                            echo JText::_('COM_COSTBENEFITPROJECTION_CHART_NODATA_RISKS'); 
                        } else {
                            echo JText::_('COM_COSTBENEFITPROJECTION_CHART_NODATA_RISK');
                        }
                    ?>
                </span>
                	<?php echo $this->item[noData][risk][name]; ?>
            </h3>
        <?php endif; ?>
    </div>
<?php endif; ?>
<div id="loading" style="height:300px; width:100%">
	<h1 style="text-align:center;" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_LOADING_PLEASE_WAIT_TITLE'); ?></h1>
    <div style="margin:0 auto; width:180px; height:24px; padding: 5px;">
    	<img width="180" height="24" src="../media/com_costbenefitprojection/images/load.gif" alt="......." title="........"/>
    </div>
</div>
<div id="st-container" class="st-container" style="display:none;">
    <nav class="st-menu st-effect-11" id="menu-11">
    	<ul>
            <li><a onclick="loadViews('main')" class="icon CTsubmenu" href="javascript:void(0)"><?php echo JFactory::getUser($this->item[data][clientId])->name; ?></a></li>
        </ul>
        <h2 class="icon icon-lab"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_QUICK_LINKS_SIDE_MENU'); ?></h2>
        <ul>
        	<?php foreach ($this->chart_tabs as $item): ?>
                <li>
                    <a onclick="loadViews('<?php echo JText::_($item['view']); ?>')" class="icon CTsubmenu" href="javascript:void(0)">
                        <?php echo JText::_($item['name']); ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
        <h2 class="icon icon-lab"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_QUICK_LINKS_SIDE_MENU'); ?></h2>
        <ul>
        	<?php foreach ($this->table_tabs as $item): ?>
                <li>
                    <a onclick="loadViews('<?php echo JText::_($item['view']); ?>')" class="icon CTsubmenu" href="javascript:void(0)">
                        <?php echo JText::_($item['name']); ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
        
        
        <!--  Debug -->
        <?php 	
				$canDo = UsersHelper::getActions();
				if ($canDo->get('core.admin')):
		?>
        <h2 class="icon icon-lab">Dev Menu</h2>
        <ul>
            <li><a onclick="loadViews('variables')" class="icon CTsubmenu" href="javascript:void(0)">Variables</a></li>
        </ul>
         <?php endif; ?>
        
    </nav>
    <!-- content push wrapper -->
    <div class="st-pusher">
    	<div class="st-content"><!-- this is the wrapper for the content -->
            <div class="st-content-inner"><!-- extra div for emulating position:fixed of the menu -->
                <!-- Top Navigation -->
                <div id="st-trigger-effects" class="clearfix notice_off" >
                <button id="tcm" data-effect="st-effect-11" style="margin: 5px; width:40px; height:40px; float: left; -webkit-border-radius: 5px;border-radius: 5px; "><span style="font-size:30px; color:#038CDA;">&equiv;</span></button>
                <?php if($menuNotice < 5): ?>
                	<div class="notice note_menu">&nbsp;&nbsp;&uarr;&nbsp;easy<br/>&nbsp;&nbsp;&nbsp;&nbsp;navigation<br/>&nbsp;&nbsp;&nbsp;&nbsp;menu</div>
                <?php endif; ?>
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
                            
                            <!-- Predicted Work Days Lost -->
                            <?php echo $this->loadTemplate('table_predicted_work_days_lost'); ?>
                            
                            <!-- Cost Summary -->
                            <?php echo $this->loadTemplate('table_cost_summary'); ?>
                            
                            <!-- Calculated Costs in Detail -->
                            <?php echo $this->loadTemplate('table_calculated_cost_in_detail'); ?>
                            
                            <!-- Intervention Net Benefit -->
                            <?php echo $this->loadTemplate('table_intervention_net_benefit'); ?>
                            
                            <!--  Debug -->
                            <!-- Only for Developers -->
                            <?php if ($canDo->get('core.admin')){echo $this->loadTemplate('variables');} ?>                            
                    </div>
                </div><!-- /main -->
            </div><!-- /st-content-inner -->
        </div><!-- /st-content -->
    </div><!-- /st-pusher -->
</div><!-- /st-container -->
<script>
// page loding pause
jQuery(window).load(function() {
  jQuery("#loading").hide();
  jQuery("#st-container").show('slow');
});

// notice for menu controller
jQuery( "#tcm" ).hover(function() {
	jQuery(".notice").fadeOut("slow");
});

function loadViews(e){
	jQuery( ".hidden" ).hide();
	jQuery( "#giz_"+e ).show();
	
	// set the view height to always be above 700px
	var h = jQuery("#giz_"+e).height();
	
	if (h < 700){
		var scale = 700 - h;
		var add = '<div style="height: '+scale+'px;"></div>'
		jQuery("#view_"+e).append(add);
	}
	
}

// table sorting
new Tablesort(document.getElementById("tablePWDL_1"), {
  descending: true
});
new Tablesort(document.getElementById("tablePWDL_2"), {
  descending: true
});
new Tablesort(document.getElementById("tablePWDL_3"), {
  descending: true
});
new Tablesort(document.getElementById("tablePWDL_4"), {
  descending: true
});
new Tablesort(document.getElementById("tableWDLS"), {
  descending: true
});
new Tablesort(document.getElementById("tableCS"), {
  descending: true
});
new Tablesort(document.getElementById("tableCCID_1"), {
  descending: true
});
new Tablesort(document.getElementById("tableCCID_2"), {
  descending: true
});
new Tablesort(document.getElementById("tableCCID_3"), {
  descending: true
});
new Tablesort(document.getElementById("tableCCID_4"), {
  descending: true
});

new Tablesort(document.getElementById("tablePWDL_1_isf"), {
  descending: true
});
new Tablesort(document.getElementById("tablePWDL_2_isf"), {
  descending: true
});
new Tablesort(document.getElementById("tablePWDL_3_msf"), {
  descending: true
});
new Tablesort(document.getElementById("tablePWDL_4_sf"), {
  descending: true
});
new Tablesort(document.getElementById("tableWDLS_sf"), {
  descending: true
});
new Tablesort(document.getElementById("tableCCID_1_sf"), {
  descending: true
});
new Tablesort(document.getElementById("tableCCID_2_sf"), {
  descending: true
});
new Tablesort(document.getElementById("tableCCID_3_sf"), {
  descending: true
});
new Tablesort(document.getElementById("tableCCID_4_sf"), {
  descending: true
});
new Tablesort(document.getElementById("tableCS_sf"), {
  descending: true
});

new Tablesort(document.getElementById("tablePWDL_1_oe"), {
  descending: true
});
new Tablesort(document.getElementById("tablePWDL_2_oe"), {
  descending: true
});
new Tablesort(document.getElementById("tablePWDL_3_oe"), {
  descending: true
});
new Tablesort(document.getElementById("tableWDLS_oe"), {
  descending: true
});
new Tablesort(document.getElementById("tableCCID_1_oe"), {
  descending: true
});
new Tablesort(document.getElementById("tableCCID_2_oe"), {
  descending: true
});
new Tablesort(document.getElementById("tableCCID_3_oe"), {
  descending: true
});
new Tablesort(document.getElementById("tableCS_oe"), {
  descending: true
});

// Switch for Scaling factors & One Episode
function controlSwitch(e){
	
	var attr = jQuery("."+e).hasClass("on");
	
	if (!attr) {
		jQuery("."+e).addClass("on");
		
		var table = e.split("_");
		
		if (table[1] == "sf") {
			jQuery(".item_default").hide();
			jQuery(".switch_oe").removeClass("on");
			jQuery(".item_oe").hide();
			jQuery(".item_sf").show();
		} else if (table[1] == "oe"){
			jQuery(".item_default").hide();
			jQuery(".item_sf").hide();
			jQuery(".switch_sf").removeClass("on");
			jQuery(".item_oe").show();
		}
	}else{
		
		jQuery("."+e).removeClass("on");
		
		var table = e.split("_");
		
		if (table[1] == "sf") {
			jQuery(".item_sf").hide();
			jQuery(".item_default").show();
		} else if (table[1] == "oe"){
			jQuery(".item_oe").hide();
			jQuery(".item_default").show();
		}
	}
}
</script>