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
<div data-uk-sticky style="background: #F7F7FA; z-index:3; padding: 5px 0 5px 0; width:100%;" >
    <nav class="uk-navbar uk-hidden-small uk-animation-slide-top">
        <ul class="uk-navbar-nav">
            <li><a  href="index.php?option=com_users&task=user.logout&<?php echo JUtility::getToken(); ?>=1&Itemid=109" ><i class="uk-icon-power-off"></i></a></li>
            <li><a href="index.php?option=com_users&view=profile" ><i class="uk-icon-user"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_PROFILE'); ?></a></li>
            <li>
                <ul id="target-menu" class="uk-navbar-nav" data-uk-switcher="{connect:'#main'}">
                    <li class="uk-active"><a href=""><i class="uk-icon-laptop"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CPANEL'); ?></a></li>
                    <li class=""><a href=""><i class="uk-icon-check-square-o"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_RESULTS'); ?></a></li>
                </ul>
            </li>
            <li><a href="index.php?option=com_costbenefitprojection&view=data"><i class="uk-icon-cogs"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_DATA'); ?></a></li>
            <li><a data-uk-offcanvas="{target:'#offcanvas-help'}" href="" ><i class="uk-icon-lightbulb-o"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_HELP'); ?></a></li>
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
                <li>
                    <ul class="uk-nav  uk-nav-dropdown" data-uk-switcher="{connect:'#main'}">
                        <li class="uk-active"><a href=""><i class="uk-icon-laptop"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CPANEL'); ?></a></li>
                        <li class=""><a href=""><i class="uk-icon-check-square-o"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_RESULTS'); ?></a></li>
                    </ul>
                </li>
                <li><a href="index.php?option=com_costbenefitprojection&view=data"><i class="uk-icon-cogs"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_DATA'); ?></a></li>
                <li><a data-uk-offcanvas="{target:'#offcanvas-help'}" href="" ><i class="uk-icon-lightbulb-o"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_HELP'); ?></a></li>
            </ul>
        </div>
    </div>
    
</div>

<div data-uk-grid-margin="" class="uk-margin uk-grid">
<div class="uk-width-medium-1">
      
    <ul class="uk-switcher uk-margin" id="main">
        <li id="cpanel" class="uk-active">
                <!-- Main page -->
                 <?php echo $this->loadTemplate('main'); ?>
        </li>
        <li id="results-page" class="">
            <ul data-uk-switcher="{connect:'#results'}" class="uk-subnav uk-subnav-pill">
                <li class="uk-active uk-text-small"><a href="#"> <?php echo JText::_('COM_COSTBENEFITPROJECTION_ANNUAL_SAVINGS'); ?></a></li>
                <li class="uk-text-small" data-uk-dropdown="{mode:'hover'}">
                	<a href="#"> <?php echo JText::_('COM_COSTBENEFITPROJECTION_MORE_DETAILS'); ?></a>
                    
                    <div class="uk-dropdown uk-dropdown-small">
                        <ul data-uk-switcher="{connect:'#table-chart'}" class="uk-nav uk-nav-dropdown">
                            <li><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES'); ?></a></li>
                            <li><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS'); ?></a></li>
                        </ul>
                    </div>
                    
                </li>
            </ul>
             <ul class="uk-switcher uk-margin" id="results">
                <li id="results-one" class="uk-active">
                     <!-- Intervention Cost Benefit -->
                     <?php echo $this->loadTemplate('chart_intervention_cost_benefit_save'); ?>
                </li>
                <li>
                	<ul class="uk-switcher uk-margin" id="table-chart">
                         <li class="">
                            <ul id="table-switch" data-uk-tab="{connect:'#tables'}" class="uk-tab">
                                <li class="uk-text-small"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_WORK_DAYS_LOST_SUMMARY_TITLE'); ?></a></li>
                                <li class="uk-text-small"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_COST_SUMMARY_TITLE'); ?></a></li>
                                <li class="uk-active uk-text-small"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_CALCULATED_COST_IN_DETAIL_TITLE'); ?></a></li>
                                <li class="uk-text-small"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NET_BENEFIT_TITLE'); ?></a></li>
                            </ul>
                            <ul id="tables" class="uk-switcher uk-margin">
                                <li class="">
                                    <!-- Work Days Lost Summary -->
                                    <?php echo $this->loadTemplate('table_work_days_lost_summary'); ?>
                                </li>
                                <li class="">
                                    <!-- Cost Summary -->
                                    <?php echo $this->loadTemplate('table_cost_summary'); ?>
                                </li>
                                <li class="uk-active">
                                    <!-- Calculated Costs in Detail -->
                                   <?php echo $this->loadTemplate('table_calculated_cost_in_detail'); ?>
                                </li>
                                <li class="">
                                    <!-- Intervention Net Benefit -->
                                    <?php echo $this->loadTemplate('table_intervention_net_benefit'); ?>
                                </li>
                            </ul>
                        </li>
                        <li class="uk-active">
                            <ul data-uk-tab="{connect:'#charts'}" class="uk-tab">
                                <li class="uk-text-small"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_TITLE'); ?></a></li>
                                <li class="uk-text-small"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_PERCENT'); ?></a></li>
                                <li class="uk-active uk-text-small"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_COST_TITLE'); ?></a></li>
                                <li class="uk-text-small"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_COST_PERCENT_TITLE'); ?></a></li>
                                <li class="uk-text-small"><a href="#"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_INTERVENTION_COST_BENEFIT_TITLE'); ?></a></li>
                            </ul>
                            <ul id="charts" class="uk-switcher uk-margin">
                                <li class="">
                                    <!-- Work days Lost -->
                                    <?php echo $this->loadTemplate('chart_work_days_lost'); ?>
                                </li>
                                <li class="">
                                    <!-- Work days Lost Percent -->
                                    <?php echo $this->loadTemplate('chart_work_days_lost_percent'); ?>
                                </li>
                                <li class="uk-active">
                                    <!--  Costs -->
                                    <?php echo $this->loadTemplate('chart_cost'); ?>
                                </li>
                                <li class="">
                                    <!-- Cost Percent -->
                                    <?php echo $this->loadTemplate('chart_cost_percent'); ?>
                                </li>
                                <li class="">
                                     <!-- Intervention Cost Benefit -->
                                    <?php echo $this->loadTemplate('chart_intervention_cost_benefit'); ?>
                                </li>
                            </ul>
                        </li>
					</ul>
                </li>
            </ul>
        </li>
    </ul>
    
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
<script type="text/javascript" src="http://cbp.vdm.io/media/com_costbenefitprojection/js/footable.js?v=2-0-1"></script>
<script type="text/javascript" src="http://cbp.vdm.io/media/com_costbenefitprojection/js/footable.sort.js?v=2-0-1"></script>
<script type="text/javascript" src="http://cbp.vdm.io/media/com_costbenefitprojection/js/footable.filter.js?v=2-0-1"></script>
<script type="text/javascript" src="http://cbp.vdm.io/media/com_costbenefitprojection/js/footable.paginate.js?v=2-0-1"></script>
<script type="text/javascript" src="http://cbp.vdm.io/media/com_costbenefitprojection/js/footable-set.js"></script>
<script type="text/javascript" src="http://cbp.vdm.io/media/com_costbenefitprojection/js/table2excel.js"></script>	
<script type="text/javascript">

var height 	= 0;
var width 	= 0;
var url 	= 0;

// page loading pause
jQuery(window).load(function() {
	jQuery('#loading').fadeOut( 'fast', function() {
		jQuery('#costbenefitprojection').fadeIn( 'fast', function() {
			jQuery('#tables li.uk-active .footable').trigger('footable_resize');
		});
	});
});
// resize table on default/include/one click
jQuery('[data-uk-switcher]').on('uk.switcher.show', function(event, area){
	jQuery('li.uk-active .footable').trigger('footable_resize');
});
// resize table on tab click
jQuery('[data-uk-tab]').on('uk.switcher.show', function(event, area){
	jQuery('li.uk-active .footable').trigger('footable_resize');
});

// reload CP when popup is closed
SqueezeBox.addEvent('onClose', function() {
    window.location.reload(true);
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

/* The PDF generator funtions */
function getImgData(chartContainer) {
    var chartArea = chartContainer.getElementsByTagName('svg')[0].parentNode;
    var svg = chartArea.innerHTML;
    var doc = chartContainer.ownerDocument;
    var canvas = doc.createElement('canvas');
    canvas.setAttribute('width', chartArea.offsetWidth);
    canvas.setAttribute('height', chartArea.offsetHeight);


    canvas.setAttribute(
        'style',
        'position: absolute; ' +
        'top: ' + (-chartArea.offsetHeight * 2) + 'px;' +
        'left: ' + (-chartArea.offsetWidth * 2) + 'px;');
    doc.body.appendChild(canvas);
    canvg(canvas, svg);
    var imgData = canvas.toDataURL("image/png");
    canvas.parentNode.removeChild(canvas);
    return imgData;
}
jQuery.download = function(url, dataOne, dataTwo, method){
        //url and data options required
        if( url && dataOne ){
                //split params into form inputs
                var input = '<input type="hidden" name="<?php echo 'key'; ?>" value="'+ dataOne +'" />';
				if(dataTwo){
					input += '<input type="hidden" name="<?php echo 'text'; ?>" value="'+ dataTwo +'" />';
				}
                //send request
                jQuery('<form action="'+ url +'" method="'+ (method||'post') +'">'+input+'</form>')
                .appendTo('body').submit().remove();
        };
};
function getPDF(chartContainer,text) {
	var doc = chartContainer.ownerDocument;
	var img = doc.createElement('img');
	img.src = getImgData(chartContainer);
	var url = 'index.php?option=com_costbenefitprojection&view=cp&format=pdf';
	var data = img.src;
	jQuery.download(url,data,text );
}

/* The Excel generator funtions */
jQuery.exel = function(url, data, title){
	//url and data options required
	if( url && data ){
			//split params into form inputs
			var input = '<input type="hidden" name="csv_text" value="'+ data +'" />';
			if(title){
				input += '<input type="hidden" name="title" value="'+ title +'" />';
			}
			//send request
			jQuery('<form action="'+ url +'" method="post">'+input+'</form>')
			.appendTo('body').submit().remove();
	};
};

function getEXEL(theTable,theTitle){
	var title = theTitle;
	var url = 'index.php?option=com_costbenefitprojection&view=cp&format=csv';
	var data = jQuery(theTable).table2excel();
	jQuery.exel(url,data,title);
}
</script>