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

$results = $this->item['results'];
?>
<div class="hide hidden" id="giz_cs">
<div id="view_cs" style="margin:0 auto; width: 900px; height: 100%;">
<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_COST_SUMMARY_TITLE'); ?></h1>
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

<table class="item_default" id="tableCS" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_COST_CONTRIBUTION_ALL_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORBIDITY_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_PRESENTEEISM_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_PRESENTEEISM_RISK_FACTOR_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORTALITY_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_COST_PER_DR_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_COST_PER_DR_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php $percent = 0; foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; $percent += ($item['total_cost'] / $results['totalCost'])*100; ?></th>
                <td data-sort='<?php echo $item['total_cost_sickness']; ?>' ><?php echo $item['total_cost_sickness_money']; ?></td>
                <td data-sort='<?php echo $item['total_cost_presenteeism']; ?>' ><?php echo $item['total_cost_presenteeism_money']; ?></td>
                <td data-sort='0' >0</td>
                <td data-sort='<?php echo $item['total_cost_death']; ?>' ><?php echo $item['total_cost_death_money']; ?></td>
                <td data-sort='<?php echo $item['total_cost']; ?>' ><?php echo $item['total_cost_money']; ?></td>
                <td><?php echo round(($item['total_cost'] / $results['totalCost'])*100, 2).'%'; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>
    <?php if ($results['risk'] ) : ?>
		<?php foreach ($results['risk'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['risk_name']; $percent += ($item['total_cost_risk'] / $results['totalCost'])*100;?></th>
                <td data-sort='0' >0</td>
                <td data-sort='0' >0</td>
                <td data-sort='<?php echo $item['total_cost_risk']; ?>' ><?php echo $item['total_cost_risk_money']; ?></td>
                <td data-sort='0' >0</td>
                <td data-sort='<?php echo $item['total_cost_risk']; ?>' ><?php echo $item['total_cost_risk_money']; ?></td>
                <td><?php echo round(($item['total_cost_risk'] / $results['totalCost'])*100, 2).'%'; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                         
    </tbody>
    <tfoot>
        <tr>
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

            <td><?php echo $results['totalCostSickness_money']; ?></td>
            <td><?php echo $results['totalCostPresenteeism_money']; ?></td>
            <td><?php echo $results['totalCostRisk_money']; ?></td>
            <td><?php echo $results['totalCostDeath_money']; ?></td>
            <td><?php echo $results['totalCost_money']; ?></td>
            <td><?php echo round($percent).'%'; ?></td>
        </tr>
    </tfoot>                                
</table>

<!-- hidden tables -->

<table class="hide item_sf" id="tableCS_sf" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_COST_CONTRIBUTION_ALL_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORBIDITY_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_PRESENTEEISM_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_PRESENTEEISM_RISK_FACTOR_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORTALITY_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_COST_PER_DR_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_COST_PER_DR_LABEL'); ?></th>
        </tr>         
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php $percent = 0; foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; $percent += ($item['total_cost_HAS_SF'] / $results['totalCost_HAS_SF'])*100;?></th>
                <td data-sort='<?php echo $item['total_cost_sickness_HAS_SF']; ?>' ><?php echo $item['total_cost_sickness_money_HAS_SF']; ?></td>
                <td data-sort='<?php echo $item['total_cost_presenteeism_HAS_SF']; ?>' ><?php echo $item['total_cost_presenteeism_money_HAS_SF']; ?></td>
                <td data-sort='0' >0</td>
                <td data-sort='<?php echo $item['total_cost_death_HAS_SF']; ?>' ><?php echo $item['total_cost_death_money_HAS_SF']; ?></td>
                <td data-sort='<?php echo $item['total_cost_HAS_SF']; ?>' ><?php echo $item['total_cost_money_HAS_SF']; ?></td>
                <td><?php echo round(($item['total_cost_HAS_SF'] / $results['totalCost_HAS_SF'])*100, 2).'%'; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>
    <?php if ($results['risk'] ) : ?>
		<?php foreach ($results['risk'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['risk_name']; $percent += ($item['total_cost_risk_HAS_SF'] / $results['totalCost_HAS_SF'])*100;?></th>
                <td data-sort='0' >0</td>
                <td data-sort='0' >0</td>
                <td data-sort='<?php echo $item['total_cost_risk_HAS_SF']; ?>' ><?php echo $item['total_cost_risk_money_HAS_SF']; ?></td>
                <td data-sort='0' >0</td>
                <td data-sort='<?php echo $item['total_cost_risk_HAS_SF']; ?>' ><?php echo $item['total_cost_risk_money_HAS_SF']; ?></td>
                <td><?php echo round(($item['total_cost_risk_HAS_SF'] / $results['totalCost_HAS_SF'])*100, 2).'%'; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                         
    </tbody>
    <tfoot>
        <tr>
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

            <td><?php echo $results['totalCostSickness_money_HAS_SF']; ?></td>
            <td><?php echo $results['totalCostPresenteeism_money_HAS_SF']; ?></td>
            <td><?php echo $results['totalCostRisk_money_HAS_SF']; ?></td>
            <td><?php echo $results['totalCostDeath_money_HAS_SF']; ?></td>
            <td><?php echo $results['totalCost_money_HAS_SF']; ?></td>
            <td><?php echo round($percent).'%'; ?></td>
        </tr>
    </tfoot>                                
</table>

<table class="hide item_oe" id="tableCS_oe" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_COST_CONTRIBUTION_ALL_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORBIDITY_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_PRESENTEEISM_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORTALITY_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_COST_PER_DR_LABEL'); ?></th>
            <th width="13%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_COST_PER_DR_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
    <?php foreach ($results['disease'] as $i => $item): ?>
        <tr>
            <th scope="row"><?php echo $item['disease_name']; ?></th>
            <td data-sort='<?php echo $item['total_cost_sickness_HAS_OE']; ?>' ><?php echo $item['total_cost_sickness_money_HAS_OE']; ?></td>
            <td data-sort='<?php echo $item['total_cost_presenteeism_HAS_OE']; ?>' ><?php echo $item['total_cost_presenteeism_money_HAS_OE']; ?></td>
            <td data-sort='<?php echo $item['total_cost_death_HAS_OE']; ?>' ><?php echo $item['total_cost_death_money_HAS_OE']; ?></td>
            <td data-sort='<?php echo $item['total_cost_HAS_OE']; ?>' ><?php echo $item['total_cost_money_HAS_OE']; ?></td>
            <td><?php echo round(($item['total_cost_HAS_OE'] / $results['totalCost_HAS_OE'])*100, 2).'%'; ?></td>
        </tr>
    <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>                               
</table>
 
</div>
</div>