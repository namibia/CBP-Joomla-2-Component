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
<div class="hide hidden" id="giz_inb">
<div id="view_inb" style="margin:0 auto; width: 1100px; height: 100%;">
<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NET_BENEFIT_TITLE'); ?></h1>
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
<?php if($results['interventions']): ?>
<?php $i = 0; ?>
<?php foreach ($results['interventions'] as $intervention) :?>
    <table class="item_default" id="tableINB<?php echo '_'.$i; ?>" summary="">
        <thead>    
            <tr>
                <th scope="col" colspan="12" class='no-sort'>
					<?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NAME_TITLE', $intervention['name']); ?> | 
					<?php 
						if($intervention['intervention_duration'] > 1){
							echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATIONS_TITLE', $intervention['intervention_duration']); 
						} else {
							echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATION_TITLE', $intervention['intervention_duration']);
						} 
					?> | 
					<?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_COVERAGE_TITLE', $intervention['intervention_coverage']); ?>%
                </th>
            </tr>
            
            <tr >
                <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_CONTRIBUTION_COST'); ?></th>
                <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_PER_EMPLOYEE'); ?></th>
                <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_REDUCTION_MORBIDITY'); ?></th>
                <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_REDUCTION_MORTALITY'); ?></th>
                <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_PROBLEM'); ?></th>
                <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_INTERVENTION'); ?></th>
                <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_BENEFIT'); ?></th>
                <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_BENEFIT_RATIO'); ?></th>
                <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_NET_BENEFIT'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if(is_array($intervention['disease'])):?>
			<?php foreach ($intervention['disease'] as $key => $item): ?>
                <tr>
                    <th scope="row"><?php echo $item['name']; ?></th>
                    <td data-sort='<?php echo $item['contribution_cost']; ?>' ><?php echo $item['contribution_cost']; ?>%</td>
                    <td data-sort='<?php echo $item['cost_per_employee']; ?>' ><?php echo $item['cost_per_employee_money']; ?></td>
                    <td data-sort='<?php echo $item['morbidity_reduction']; ?>' ><?php echo $item['morbidity_reduction']; ?>%</td>
                    <td data-sort='<?php echo $item['mortality_reduction']; ?>' ><?php echo $item['mortality_reduction']; ?>%</td>
                    <td data-sort='<?php echo $item['cost']; ?>' ><?php echo $item['cost_money']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_cost']; ?>' ><?php echo $item['intervention_annual_cost_money']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_benefit']; ?>' ><?php echo $item['intervention_annual_benefit_money']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_benefit_ratio']; ?>' ><?php echo $item['intervention_annual_benefit_ratio']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_net_benefit']; ?>' ><?php echo $item['intervention_annual_net_benefit_money']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if(is_array($intervention['risk'])):?>
			<?php foreach ($intervention['risk'] as $key => $item): ?>
                <tr>
                    <th scope="row"><?php echo $item['name']; ?></th>
                    <td data-sort='<?php echo $item['contribution_cost']; ?>' ><?php echo $item['contribution_cost']; ?>%</td>
                    <td data-sort='<?php echo $item['cost_per_employee']; ?>' ><?php echo $item['cost_per_employee_money']; ?></td>
                    <td data-sort='<?php echo $item['morbidity_reduction']; ?>' ><?php echo $item['morbidity_reduction']; ?>%</td>
                    <td data-sort='0' >0%</td>
                    <td data-sort='<?php echo $item['cost']; ?>' ><?php echo $item['cost_money']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_cost']; ?>' ><?php echo $item['intervention_annual_cost_money']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_benefit']; ?>' ><?php echo $item['intervention_annual_benefit_money']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_benefit_ratio']; ?>' ><?php echo $item['intervention_annual_benefit_ratio']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_net_benefit']; ?>' ><?php echo $item['intervention_annual_net_benefit_money']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>
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
    
    <!-- hidden tables -->
    <table class="hide item_sf" id="tableINB<?php echo '_'.$i; ?>_sf" summary="">
        <thead>    
            <tr>
                <th scope="col" colspan="12" class='no-sort'>
					<?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NAME_TITLE', $intervention['name']); ?> | 
					<?php 
						if($intervention['intervention_duration'] == 1){
							echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATION_TITLE', $intervention['intervention_duration']); 
						} else {
							echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATIONS_TITLE', $intervention['intervention_duration']);
						} 
					?> | 
					<?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_COVERAGE_TITLE', $intervention['intervention_coverage']); ?>%
                </th>
           	</tr>
            
            <tr >
                <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_CONTRIBUTION_COST'); ?></th>
                <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_PER_EMPLOYEE'); ?></th>
                <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_REDUCTION_MORBIDITY'); ?></th>
                <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_REDUCTION_MORTALITY'); ?></th>
                <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_PROBLEM'); ?></th>
                <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_INTERVENTION'); ?></th>
                <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_BENEFIT'); ?></th>
                <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_BENEFIT_RATIO'); ?></th>
                <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_NET_BENEFIT'); ?></th>
            </tr>      
        </thead>                                    
        <tbody>
        <?php if(is_array($intervention['disease'])):?>
			<?php foreach ($intervention['disease'] as $key => $item): ?>
                <tr>
                    <th scope="row"><?php echo $item['name']; ?></th>
                    <td data-sort='<?php echo $item['contribution_cost_HAS_SF']; ?>' ><?php echo $item['contribution_cost_HAS_SF']; ?>%</td>
                    <td data-sort='<?php echo $item['cost_per_employee']; ?>' ><?php echo $item['cost_per_employee_money']; ?></td>
                    <td data-sort='<?php echo $item['morbidity_reduction']; ?>' ><?php echo $item['morbidity_reduction']; ?>%</td>
                    <td data-sort='<?php echo $item['mortality_reduction']; ?>' ><?php echo $item['mortality_reduction']; ?>%</td>
                    <td data-sort='<?php echo $item['cost_HAS_SF']; ?>' ><?php echo $item['cost_money_HAS_SF']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_cost']; ?>' ><?php echo $item['intervention_annual_cost_money']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_benefit_HAS_SF']; ?>' ><?php echo $item['intervention_annual_benefit_money_HAS_SF']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_benefit_ratio_HAS_SF']; ?>' ><?php echo $item['intervention_annual_benefit_ratio_HAS_SF']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_net_benefit_HAS_SF']; ?>' ><?php echo $item['intervention_annual_net_benefit_money_HAS_SF']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if(is_array($intervention['risk'])):?>
			<?php foreach ($intervention['risk'] as $key => $item): ?>
                <tr>
                    <th scope="row"><?php echo $item['name']; ?></th>
                    <td data-sort='<?php echo $item['contribution_cost_HAS_SF']; ?>' ><?php echo $item['contribution_cost_HAS_SF']; ?>%</td>
                    <td data-sort='<?php echo $item['cost_per_employee']; ?>' ><?php echo $item['cost_per_employee_money']; ?></td>
                    <td data-sort='<?php echo $item['morbidity_reduction']; ?>' ><?php echo $item['morbidity_reduction']; ?>%</td>
                    <td data-sort='0' >0%</td>
                    <td data-sort='<?php echo $item['cost_HAS_SF']; ?>' ><?php echo $item['cost_money_HAS_SF']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_cost']; ?>' ><?php echo $item['intervention_annual_cost_money']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_benefit_HAS_SF']; ?>' ><?php echo $item['intervention_annual_benefit_money_HAS_SF']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_benefit_ratio_HAS_SF']; ?>' ><?php echo $item['intervention_annual_benefit_ratio_HAS_SF']; ?></td>
                    <td data-sort='<?php echo $item['intervention_annual_net_benefit_HAS_SF']; ?>' ><?php echo $item['intervention_annual_net_benefit_money_HAS_SF']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>
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
    <?php if(is_array($intervention['disease'])):?>
        <table class="hide item_oe" id="tableINB<?php echo '_'.$i; ?>_oe" summary="">
            <thead>    
                <tr>
                    <th scope="col" colspan="12" class='no-sort'>
						<?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NAME_TITLE', $intervention['name']); ?> | 
                        <?php 
                            if($intervention['intervention_duration'] > 1){
                                echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATIONS_TITLE', $intervention['intervention_duration']); 
                            } else {
                                echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATION_TITLE', $intervention['intervention_duration']);
                            } 
                        ?> | 
                        <?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_COVERAGE_TITLE', $intervention['intervention_coverage']); ?>%
                	</th>
                </tr>
                
                <tr >
                    <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                    <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_CONTRIBUTION_COST'); ?></th>
                    <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_PER_EMPLOYEE'); ?></th>
                    <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_REDUCTION_MORBIDITY'); ?></th>
                    <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_REDUCTION_MORTALITY'); ?></th>
                    <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_PROBLEM'); ?></th>
                    <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_INTERVENTION'); ?></th>
                    <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_BENEFIT'); ?></th>
                    <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_BENEFIT_RATIO'); ?></th>
                    <th width="10%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_NET_BENEFIT'); ?></th>
                </tr>      
            </thead>                                    
            <tbody>
            <?php if(is_array($intervention['disease'])):?>
                <?php foreach ($intervention['disease'] as $key => $item): ?>
                    <tr>
                        <th scope="row"><?php echo $item['name']; ?></th>
                        <td data-sort='<?php echo $item['contribution_cost_HAS_OE']; ?>' ><?php echo $item['contribution_cost_HAS_OE']; ?>%</td>
                        <td data-sort='<?php echo $item['cost_per_employee']; ?>' ><?php echo $item['cost_per_employee_money']; ?></td>
                        <td data-sort='<?php echo $item['morbidity_reduction']; ?>' ><?php echo $item['morbidity_reduction']; ?>%</td>
                        <td data-sort='<?php echo $item['mortality_reduction']; ?>' ><?php echo $item['mortality_reduction']; ?>%</td>
                        <td data-sort='<?php echo $item['cost_HAS_OE']; ?>' ><?php echo $item['cost_money_HAS_OE']; ?></td>
                        <td data-sort='<?php echo $item['intervention_annual_cost']; ?>' ><?php echo $item['intervention_annual_cost_money']; ?></td>
                        <td data-sort='<?php echo $item['intervention_annual_benefit_HAS_OE']; ?>' ><?php echo $item['intervention_annual_benefit_money_HAS_OE']; ?></td>
                        <td data-sort='<?php echo $item['intervention_annual_benefit_ratio_HAS_OE']; ?>' ><?php echo $item['intervention_annual_benefit_ratio_HAS_OE']; ?></td>
                        <td data-sort='<?php echo $item['intervention_annual_net_benefit_HAS_OE']; ?>' ><?php echo $item['intervention_annual_net_benefit_money_HAS_OE']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>                                       
            </tbody>                              
        </table>
    <?php endif; ?> 
<script>
// table sorting
new Tablesort(document.getElementById("tableINB<?php echo '_'.$i; ?>"), {
  descending: true
});
new Tablesort(document.getElementById("tableINB<?php echo '_'.$i; ?>_sf"), {
  descending: true
});
new Tablesort(document.getElementById("tableINB<?php echo '_'.$i; ?>_oe"), {
  descending: true
});
</script>
<?php $i++; ?>
<?php endforeach; ?>
<?php else: ?> 
<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_INTERVENTION_SELECTED'); ?></h2>
<?php endif; ?> 
</div>
</div>