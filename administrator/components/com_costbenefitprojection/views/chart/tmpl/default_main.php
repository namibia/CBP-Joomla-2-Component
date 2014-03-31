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

// No direct access.
defined('_JEXEC') or die;


?>
<div class="hidden" id="giz_main">     
<div id="view_main" style="margin:0 auto; width: 900px; height: 700px;">
<div style="margin:0 auto; width: 900px;">
    <br/>
        <div class="switchbox" >
            <div class="control_switch_sf" >
                <div class="switch switch_sf" onclick="controlSwitch('switch_sf')" >
                    <span class="thumb"></span>
                    <input type="checkbox" />
                </div>
                <div class="label" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></div>
            </div>
            
            <div class="control_switch_oe" >
                <div class="switch switch_oe" onclick="controlSwitch('switch_oe')">
                    <span class="thumb"></span>
                    <input type="checkbox" />
                </div>
                <div class="label" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?></div>
             </div>
        </div>  
    <br/><br/>
</div>
<div id="costbenefitprojection" >
    <form action="index.php?option=com_costbenefitprojection"
        method="post" name="adminForm" class="form-validate">
    <div class="width-50 fltlft">
        <fieldset class="adminform">
            <h3 class="title">
                <span><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_QUICK_LINKS_HEADER'); ?></span>
            </h3>
                <?php foreach ($this->chart_tabs as $item): ?>
                    <div class="icon-wrapper">
                            <div class="icon">
                            <a onclick="loadViews('<?php echo JText::_($item['view']); ?>')" class="icon CTsubmenu" href="javascript:void(0)">
                                <img alt="<?php echo JText::_($item['name']); ?>" src="<?php echo JURI::root().$item['img']; ?>">
                                <span><?php echo JText::_($item['name']); ?></span>
                            </a>
                        </div>
                    </div>
                <?php endforeach ?>
        </fieldset>
    </div>
    <div class="width-50 fltlft">
        <fieldset class="adminform">
            <h3 class="title">
                <span><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_QUICK_LINKS_HEADER'); ?></span>
            </h3>
                <?php foreach ($this->table_tabs as $item): ?>
                    <div class="icon-wrapper">
                            <div class="icon">
                            <a onclick="loadViews('<?php echo JText::_($item['view']); ?>')" class="icon CTsubmenu" href="javascript:void(0)">
                                <img alt="<?php echo JText::_($item['name']); ?>" src="<?php echo JURI::root().$item['img']; ?>">
                                <span><?php echo JText::_($item['name']); ?></span>
                            </a>
                        </div>
                    </div>
                <?php endforeach ?>
        </fieldset>
    </div>
    
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
    </form>
    </div>
	</div>
</div>