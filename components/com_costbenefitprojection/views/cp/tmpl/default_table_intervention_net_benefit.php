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

/* <div class="uk-text-large"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NET_BENEFIT_TITLE'); ?></div> */
?>
<?php if($results['interventions']): ?>
<!-- This is the subnav containing the toggling elements -->
<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#tableINB'}">
    <li class="uk-active uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></a></li>
</ul>
<br/>


<!-- This is the container of the content items -->
<ul id="tableINB" class="uk-switcher">
    <li class="uk-active default" >
		<?php $i = 0; ?>
        <?php foreach ($results['interventions'] as $intervention) :?>
        <!-- tableINB<?php echo '_'.$i; ?> -->
        <div class="uk-text-small uk-container-center uk-animation-fade">
            <?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NAME_TITLE', $intervention['name']); ?> | 
            <?php 
                if($intervention['intervention_duration'] > 1){
                    echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATIONS_TITLE', $intervention['intervention_duration']); 
                } else {
                    echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATION_TITLE', $intervention['intervention_duration']);
                } 
            ?> | 
            <?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_COVERAGE_TITLE', $intervention['intervention_coverage']); ?>%
        </div>
        <table id="theTableINB<?php echo '_'.$i; ?>" class="table data tableINB<?php echo '_'.$i; ?> metro-blue" data-filter="#tableINB<?php echo '_'.$i; ?>">
            <thead>    
                <tr >
                    <th data-toggle="true" width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                    <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_CONTRIBUTION_COST'); ?></th>
                    <th data-hide="phone" width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_PER_EMPLOYEE'); ?></th>
                    <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_REDUCTION_MORBIDITY'); ?></th>
                    <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_REDUCTION_MORTALITY'); ?></th>
                    <th data-hide="phone" width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_PROBLEM'); ?></th>
                    <th data-hide="phone" width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_INTERVENTION'); ?></th>
                    <th data-hide="phone" width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_BENEFIT'); ?></th>
                    <th data-hide="phone" width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_BENEFIT_RATIO'); ?></th>
                    <th data-hide="phone" width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_NET_BENEFIT'); ?></th>
    
                </tr>        
            </thead>                                    
            <tbody>
            <?php if(is_array($intervention['disease'])):?>
                <?php foreach ($intervention['disease'] as $key => $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td data-value='<?php echo $item['contribution_cost']; ?>' ><?php echo $item['contribution_cost']; ?>%</td>
                        <td data-value='<?php echo $item['cost_per_employee']; ?>' ><?php echo $item['cost_per_employee_money']; ?></td>
                        <td data-value='<?php echo $item['morbidity_reduction']; ?>' ><?php echo $item['morbidity_reduction']; ?>%</td>
                        <td data-value='<?php echo $item['mortality_reduction']; ?>' ><?php echo $item['mortality_reduction']; ?>%</td>
                        <td data-value='<?php echo $item['cost']; ?>' ><?php echo $item['cost_money']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_cost']; ?>' ><?php echo $item['intervention_annual_cost_money']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_benefit']; ?>' ><?php echo $item['intervention_annual_benefit_money']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_benefit_ratio']; ?>' ><?php echo $item['intervention_annual_benefit_ratio']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_net_benefit']; ?>' ><?php echo $item['intervention_annual_net_benefit_money']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if(is_array($intervention['risk'])):?>
                <?php foreach ($intervention['risk'] as $key => $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td data-value='<?php echo $item['contribution_cost']; ?>' ><?php echo $item['contribution_cost']; ?>%</td>
                        <td data-value='<?php echo $item['cost_per_employee']; ?>' ><?php echo $item['cost_per_employee_money']; ?></td>
                        <td data-value='<?php echo $item['morbidity_reduction']; ?>' ><?php echo $item['morbidity_reduction']; ?>%</td>
                        <td data-value='0' >0%</td>
                        <td data-value='<?php echo $item['cost']; ?>' ><?php echo $item['cost_money']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_cost']; ?>' ><?php echo $item['intervention_annual_cost_money']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_benefit']; ?>' ><?php echo $item['intervention_annual_benefit_money']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_benefit_ratio']; ?>' ><?php echo $item['intervention_annual_benefit_ratio']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_net_benefit']; ?>' ><?php echo $item['intervention_annual_net_benefit_money']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>                                        
            </tbody>
            <tfoot>
                <tr>
                    <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                    <td><?php echo $intervention['totalContributionCost']; ?>%</td>
                    <td><?php echo $intervention['totalCostPerEmployee']; ?></td>
                    <td><?php echo $intervention['totalMorbidityReduction']; ?>%</td>
                    <td><?php echo $intervention['totalMortalityReduction']; ?>%</td>
                    <td><?php echo $intervention['totalCost']; ?></td>
                    <td><?php echo $intervention['totalInterventionAnnualCost']; ?></td>
                    <td><?php echo $intervention['totalInterventionAnnualBenefit']; ?></td>
                    <td><?php echo $intervention['totalInterventionAnnualBenefitRatio']; ?></td>
                    <td><?php echo $intervention['totalInterventionAnnualNetBenefit']; ?></td>
                </tr>
            </tfoot>                                
        </table>
        <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableINB<?php echo '_'.$i; ?>','<?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NAME_TITLE_EXCEL', $intervention['name']); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
        <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
        </button>
        <br/><br/>
        <?php $i++; ?>
        <?php endforeach; ?>    
    </li>
    <li class="scalling" >
		<?php $i = 0; ?>
        <?php foreach ($results['interventions'] as $intervention) :?>
        <!-- tableINB<?php echo '_'.$i; ?>_sf -->
        <div class="uk-text-small uk-container-center uk-animation-fade">
            <?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NAME_TITLE', $intervention['name']); ?> | 
            <?php 
                if($intervention['intervention_duration'] > 1){
                    echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATIONS_TITLE', $intervention['intervention_duration']); 
                } else {
                    echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATION_TITLE', $intervention['intervention_duration']);
                } 
            ?> | 
            <?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_COVERAGE_TITLE', $intervention['intervention_coverage']); ?>%
        </div>
        <table id="theTableINB<?php echo '_'.$i; ?>_sf" class="table data tableINB<?php echo '_'.$i; ?>_sf metro-blue" data-filter="#tableINB<?php echo '_'.$i; ?>_sf">
            <thead>    
                <tr >
                    <th data-toggle="true" width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                    <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_CONTRIBUTION_COST'); ?></th>
                    <th data-hide="phone" width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_PER_EMPLOYEE'); ?></th>
                    <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_REDUCTION_MORBIDITY'); ?></th>
                    <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_REDUCTION_MORTALITY'); ?></th>
                    <th data-hide="phone" width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_PROBLEM'); ?></th>
                    <th data-hide="phone" width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_INTERVENTION'); ?></th>
                    <th data-hide="phone" width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_BENEFIT'); ?></th>
                    <th data-hide="phone" width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_BENEFIT_RATIO'); ?></th>
                    <th data-hide="phone" width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_NET_BENEFIT'); ?></th>
                </tr>      
            </thead>                                    
            <tbody>
            <?php if(is_array($intervention['disease'])):?>
                <?php foreach ($intervention['disease'] as $key => $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td data-value='<?php echo $item['contribution_cost_HAS_SF']; ?>' ><?php echo $item['contribution_cost_HAS_SF']; ?>%</td>
                        <td data-value='<?php echo $item['cost_per_employee']; ?>' ><?php echo $item['cost_per_employee_money']; ?></td>
                        <td data-value='<?php echo $item['morbidity_reduction']; ?>' ><?php echo $item['morbidity_reduction']; ?>%</td>
                        <td data-value='<?php echo $item['mortality_reduction']; ?>' ><?php echo $item['mortality_reduction']; ?>%</td>
                        <td data-value='<?php echo $item['cost_HAS_SF']; ?>' ><?php echo $item['cost_money_HAS_SF']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_cost']; ?>' ><?php echo $item['intervention_annual_cost_money']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_benefit_HAS_SF']; ?>' ><?php echo $item['intervention_annual_benefit_money_HAS_SF']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_benefit_ratio_HAS_SF']; ?>' ><?php echo $item['intervention_annual_benefit_ratio_HAS_SF']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_net_benefit_HAS_SF']; ?>' ><?php echo $item['intervention_annual_net_benefit_money_HAS_SF']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if(is_array($intervention['risk'])):?>
                <?php foreach ($intervention['risk'] as $key => $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td data-value='<?php echo $item['contribution_cost_HAS_SF']; ?>' ><?php echo $item['contribution_cost_HAS_SF']; ?>%</td>
                        <td data-value='<?php echo $item['cost_per_employee']; ?>' ><?php echo $item['cost_per_employee_money']; ?></td>
                        <td data-value='<?php echo $item['morbidity_reduction']; ?>' ><?php echo $item['morbidity_reduction']; ?>%</td>
                        <td data-value='0' >0%</td>
                        <td data-value='<?php echo $item['cost_HAS_SF']; ?>' ><?php echo $item['cost_money_HAS_SF']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_cost']; ?>' ><?php echo $item['intervention_annual_cost_money']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_benefit_HAS_SF']; ?>' ><?php echo $item['intervention_annual_benefit_money_HAS_SF']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_benefit_ratio_HAS_SF']; ?>' ><?php echo $item['intervention_annual_benefit_ratio_HAS_SF']; ?></td>
                        <td data-value='<?php echo $item['intervention_annual_net_benefit_HAS_SF']; ?>' ><?php echo $item['intervention_annual_net_benefit_money_HAS_SF']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>                                        
            </tbody>
            <tfoot>
                <tr>
                    <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                  	<td><?php echo $intervention['totalContributionCost_HAS_SF']; ?>%</td>
                    <td><?php echo $intervention['totalCostPerEmployee']; ?></td>
                    <td><?php echo $intervention['totalMorbidityReduction']; ?>%</td>
                    <td><?php echo $intervention['totalMortalityReduction']; ?>%</td>
                    <td><?php echo $intervention['totalCost_HAS_SF']; ?></td>
                    <td><?php echo $intervention['totalInterventionAnnualCost']; ?></td>
                    <td><?php echo $intervention['totalInterventionAnnualBenefit_HAS_SF']; ?></td>
                    <td><?php echo $intervention['totalInterventionAnnualBenefitRatio_HAS_SF']; ?></td>
                    <td><?php echo $intervention['totalInterventionAnnualNetBenefit_HAS_SF']; ?></td>
                </tr>
            </tfoot>                            
        </table>
        <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableINB<?php echo '_'.$i; ?>_sf','<?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NAME_TITLE_EXCEL', $intervention['name']); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>');">
        <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
        </button>
        <br/><br/>
        <?php $i++; ?>
        <?php endforeach; ?>    
    </li>
</ul>
<?php else: ?> 
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_INTERVENTION_SELECTED'); ?></h2>
<?php endif; ?>