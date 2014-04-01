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
/* <div class="uk-text-large"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_COST_SUMMARY_TITLE'); ?></div> */
?>
<!-- This is the subnav containing the toggling elements -->
<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#tableCS'}">
    <li class="uk-active uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?></a></li>
</ul>
<br/>
<!-- This is the container of the content items -->
<ul id="tableCS" class="uk-switcher">
    <li class="uk-active default" >
    <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_COST_CONTRIBUTION_ALL_TITLE'); ?></div>
    <!-- tableCS -->
    <table id="theTableCS" class="table data tableCS metro-blue" data-filter="#tableCS">
        <thead>    
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORBIDITY_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_PRESENTEEISM_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_PRESENTEEISM_RISK_FACTOR_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORTALITY_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_COST_PER_DR_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_COST_PER_DR_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php $percent = 0; foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; $percent += ($item['total_cost'] / $results['totalCost'])*100; ?></td>
                    <td data-value='<?php echo $item['total_cost_sickness']; ?>' ><?php echo $item['total_cost_sickness_money']; ?></td>
                    <td data-value='<?php echo $item['total_cost_presenteeism']; ?>' ><?php echo $item['total_cost_presenteeism_money']; ?></td>
                    <td data-value='0' >0</td>
                    <td data-value='<?php echo $item['total_cost_death']; ?>' ><?php echo $item['total_cost_death_money']; ?></td>
                    <td data-value='<?php echo $item['total_cost']; ?>' ><?php echo $item['total_cost_money']; ?></td>
                    <td><?php echo round(($item['total_cost'] / $results['totalCost'])*100, 2).'%'; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($results['risk'] ) : ?>
            <?php foreach ($results['risk'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['risk_name']; $percent += ($item['total_cost_risk'] / $results['totalCost'])*100;?></td>
                    <td data-value='0' >0</td>
                    <td data-value='0' >0</td>
                    <td data-value='<?php echo $item['total_cost_risk']; ?>' ><?php echo $item['total_cost_risk_money']; ?></td>
                    <td data-value='0' >0</td>
                    <td data-value='<?php echo $item['total_cost_risk']; ?>' ><?php echo $item['total_cost_risk_money']; ?></td>
                    <td><?php echo round(($item['total_cost_risk'] / $results['totalCost'])*100, 2).'%'; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                         
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $results['totalCostSickness_money']; ?></td>
                <td><?php echo $results['totalCostPresenteeism_money']; ?></td>
                <td><?php echo $results['totalCostRisk_money']; ?></td>
                <td><?php echo $results['totalCostDeath_money']; ?></td>
                <td><?php echo $results['totalCost_money']; ?></td>
                <td><?php echo round($percent).'%'; ?></td>
            </tr>
        </tfoot>                                
    </table> 
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCS','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_COST_SUMMARY_TITLE'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    </li>
    <li class="scalling" >
    <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_COST_CONTRIBUTION_ALL_TITLE'); ?></div>
    <!-- tableCS_sf -->
    <table id="theTableCS_sf" class="table data tableCS metro-blue" data-filter="#tableCS_sf">
        <thead>    
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORBIDITY_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_PRESENTEEISM_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_PRESENTEEISM_RISK_FACTOR_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORTALITY_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_COST_PER_DR_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_COST_PER_DR_LABEL'); ?></th>
            </tr>         
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php $percent = 0; foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; $percent += ($item['total_cost_HAS_SF'] / $results['totalCost_HAS_SF'])*100;?></td>
                    <td data-value='<?php echo $item['total_cost_sickness_HAS_SF']; ?>' ><?php echo $item['total_cost_sickness_money_HAS_SF']; ?></td>
                    <td data-value='<?php echo $item['total_cost_presenteeism_HAS_SF']; ?>' ><?php echo $item['total_cost_presenteeism_money_HAS_SF']; ?></td>
                    <td data-value='0' >0</td>
                    <td data-value='<?php echo $item['total_cost_death_HAS_SF']; ?>' ><?php echo $item['total_cost_death_money_HAS_SF']; ?></td>
                    <td data-value='<?php echo $item['total_cost_HAS_SF']; ?>' ><?php echo $item['total_cost_money_HAS_SF']; ?></td>
                    <td><?php echo round(($item['total_cost_HAS_SF'] / $results['totalCost_HAS_SF'])*100, 2).'%'; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($results['risk'] ) : ?>
            <?php foreach ($results['risk'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['risk_name']; $percent += ($item['total_cost_risk_HAS_SF'] / $results['totalCost_HAS_SF'])*100;?></td>
                    <td data-value='0' >0</td>
                    <td data-value='0' >0</td>
                    <td data-value='<?php echo $item['total_cost_risk_HAS_SF']; ?>' ><?php echo $item['total_cost_risk_money_HAS_SF']; ?></td>
                    <td data-value='0' >0</td>
                    <td data-value='<?php echo $item['total_cost_risk_HAS_SF']; ?>' ><?php echo $item['total_cost_risk_money_HAS_SF']; ?></td>
                    <td><?php echo round(($item['total_cost_risk_HAS_SF'] / $results['totalCost_HAS_SF'])*100, 2).'%'; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                         
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $results['totalCostSickness_money_HAS_SF']; ?></td>
                <td><?php echo $results['totalCostPresenteeism_money_HAS_SF']; ?></td>
                <td><?php echo $results['totalCostRisk_money_HAS_SF']; ?></td>
                <td><?php echo $results['totalCostDeath_money_HAS_SF']; ?></td>
                <td><?php echo $results['totalCost_money_HAS_SF']; ?></td>
                <td><?php echo round($percent).'%'; ?></td>
            </tr>
        </tfoot>                                
    </table> 
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCS_sf','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_COST_SUMMARY_TITLE'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    </li>
    <li class="episode" >
    <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_COST_CONTRIBUTION_ALL_TITLE'); ?></div>
    <!-- tableCS_oe -->
    <table id="theTableCS_oe" class="table data tableCS_oe metro-blue" data-filter="#tableCS_oe">
        <thead>    
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORBIDITY_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_PRESENTEEISM_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORTALITY_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_COST_PER_DR_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_COST_PER_DR_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
        <?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <td><?php echo $item['disease_name']; ?></td>
                <td data-value='<?php echo $item['total_cost_sickness_HAS_OE']; ?>' ><?php echo $item['total_cost_sickness_money_HAS_OE']; ?></td>
                <td data-value='<?php echo $item['total_cost_presenteeism_HAS_OE']; ?>' ><?php echo $item['total_cost_presenteeism_money_HAS_OE']; ?></td>
                <td data-value='<?php echo $item['total_cost_death_HAS_OE']; ?>' ><?php echo $item['total_cost_death_money_HAS_OE']; ?></td>
                <td data-value='<?php echo $item['total_cost_HAS_OE']; ?>' ><?php echo $item['total_cost_money_HAS_OE']; ?></td>
                <td><?php echo round(($item['total_cost_HAS_OE'] / $results['totalCost_HAS_OE'])*100, 2).'%'; ?></td>
            </tr>
        <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>                               
    </table> 
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCS_oe','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_COST_SUMMARY_TITLE'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    </li>
</ul>