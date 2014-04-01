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

$results = $this->item['results'];
?>
<div class="hide hidden" id="giz_wdls">
<div id="view_wdls" style="margin:0 auto; width: 900px; height: 100%;">
<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_WORK_DAYS_LOST_SUMMARY_TITLE'); ?></h1>
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

<!-- first not hidden table tables -->

<table class="item_default" id="tableWDLS" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_CONTRIBUTION_ALL_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORBIDITY_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_PRESENTEEISM_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_PRESENTEEISM_RISK_FACTOR_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORTALITY_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php 
            $total_1 =	0;
            $total_2 =	0;
            $total_3 =	0;
            $total_4 =	0;
            $total_5 =	0;
            $total_6 =	0;
    ?>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td><?php echo $item['total_absence_days']; ?></td>
                <td><?php echo $item['total_unproductive_days']; ?></td>
                <td>0</td>
                <td><?php echo $item['total_death_absence_days']; ?></td>
                <td><?php echo $item['total_days_lost']; ?></td>
                <td><?php echo round(($item['total_days_lost'] / $results['totalDaysLost'])*100, 2).'%'; ?></td>
            </tr>
            <?php
                $total_1 +=	$item['total_absence_days'];
                $total_2 +=	$item['total_unproductive_days'];
                $total_3 +=	0;
                $total_4 +=	$item['total_death_absence_days'];
                $total_5 +=	$item['total_days_lost'];
                $total_6 +=	($item['total_days_lost'] / $results['totalDaysLost'])*100;
            ?>
        <?php endforeach; ?>
	<?php endif; ?>
    <?php if ($results['risk'] ) : ?>
		<?php foreach ($results['risk'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['risk_name']; ?></th>
                <td>0</td>
                <td>0</td>
                <td><?php echo $item['total_risk_absence_days']; ?></td>
                <td>0</td>
                <td><?php echo $item['total_risk_absence_days']; ?></td>
                <td><?php echo round(($item['total_risk_absence_days'] / $results['totalDaysLost'])*100, 2).'%'; ?></td>
            </tr>
            <?php
                $total_1 +=	0;
                $total_2 +=	0;
                $total_3 +=	$item['total_risk_absence_days'];
                $total_4 +=	0;
                $total_5 +=	$item['total_risk_absence_days'];
                $total_6 +=	($item['total_risk_absence_days'] / $results['totalDaysLost'])*100;
            ?>
        <?php endforeach; ?>
	<?php endif; ?>                                         
    </tbody>
    <tfoot>
        <tr>
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

            <td><?php echo $total_1; ?></td>
            <td><?php echo $total_2; ?></td>
            <td><?php echo $total_3; ?></td>
            <td><?php echo $total_4; ?></td>
            <td><?php echo $total_5; ?></td>
            <td><?php echo round($total_6).'%'; ?></td>
        </tr>
    </tfoot>                                
</table>

<!-- hidden tables -->

<table class="hide item_sf" id="tableWDLS_sf" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_CONTRIBUTION_ALL_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORBIDITY_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_PRESENTEEISM_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_PRESENTEEISM_RISK_FACTOR_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORTALITY_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php 
            $total_1 =	0;
            $total_2 =	0;
            $total_3 =	0;
            $total_4 =	0;
            $total_5 =	0;
            $total_6 =	0;
    ?>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td><?php echo $item['total_absence_days_HAS_ISF']; ?></td>
                <td><?php echo $item['total_unproductive_days_HAS_ISF']; ?></td>
                <td>0</td>
                <td><?php echo $item['total_death_absence_days_HAS_MSF']; ?></td>
                <td><?php echo $item['total_days_lost_HAS_SF']; ?></td>
                <td><?php echo round(($item['total_days_lost_HAS_SF'] / $results['totalDaysLost_HAS_SF'])*100, 2).'%'; ?></td>
            </tr>
            <?php
                $total_1 +=	$item['total_absence_days_HAS_ISF'];
                $total_2 +=	$item['total_unproductive_days_HAS_ISF'];
                $total_3 +=	0;
                $total_4 +=	$item['total_death_absence_days_HAS_MSF'];
                $total_5 +=	$item['total_days_lost_HAS_SF'];
                $total_6 +=	($item['total_days_lost_HAS_SF'] / $results['totalDaysLost_HAS_SF'])*100;
            ?>
        <?php endforeach; ?>
	<?php endif; ?>
    <?php if ($results['risk'] ) : ?>
		<?php foreach ($results['risk'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['risk_name']; ?></th>
                <td>0</td>
                <td>0</td>
                <td><?php echo $item['total_risk_absence_days_HAS_SF']; ?></td>
                <td>0</td>
                <td><?php echo $item['total_risk_absence_days_HAS_SF']; ?></td>
                <td><?php echo round(($item['total_risk_absence_days_HAS_SF'] / $results['totalDaysLost_HAS_SF'])*100, 2).'%'; ?></td>
            </tr>
            <?php
                $total_1 +=	0;
                $total_2 +=	0;
                $total_3 +=	$item['total_risk_absence_days_HAS_SF'];
                $total_4 +=	0;
                $total_5 +=	$item['total_risk_absence_days_HAS_SF'];
                $total_6 +=	($item['total_risk_absence_days_HAS_SF'] / $results['totalDaysLost_HAS_SF'])*100;
            ?>
        <?php endforeach; ?>
	<?php endif; ?>                                         
    </tbody>
    <tfoot>
        <tr>
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

            <td><?php echo $total_1; ?></td>
            <td><?php echo $total_2; ?></td>
            <td><?php echo $total_3; ?></td>
            <td><?php echo $total_4; ?></td>
            <td><?php echo $total_5; ?></td>
            <td><?php echo round($total_6).'%'; ?></td>
        </tr>
    </tfoot>                                
</table>

<table class="hide item_oe" id="tableWDLS_oe" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_CONTRIBUTION_ALL_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORBIDITY_LABEL'); ?></th>
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_PRESENTEEISM_LABEL'); ?></th>
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORTALITY_LABEL'); ?></th>
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td><?php echo $item['total_absence_days_HAS_OE']; ?></td>
                <td><?php echo $item['total_unproductive_days_HAS_OE']; ?></td>
                <td><?php echo $item['total_death_absence_days_HAS_OE']; ?></td>
                <td><?php echo $item['total_days_lost_HAS_OE']; ?></td>
                <td><?php echo round(($item['total_days_lost_HAS_OE'] / $results['totalDaysLost_HAS_OE'])*100, 2).'%'; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                         
    </tbody>                               
</table>

</div>
</div>