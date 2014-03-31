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
<div class="hide hidden" id="giz_ccid">
<div id="view_ccid" style="margin:0 auto; width: 900px; height: 100%;">
<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_CALCULATED_COST_IN_DETAIL_TITLE'); ?></h1>
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

<table class="item_default" id="tableCCID_1" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_ABSENCE_DAYS_SICKNESS_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td data-sort='<?php echo $item['cost_sickness_m']; ?>' ><?php echo $item['cost_sickness_money_m']; ?></td>
                <td data-sort='<?php echo $item['cost_sickness_f']; ?>' ><?php echo $item['cost_sickness_money_f']; ?></td>
                <td data-sort='<?php echo $item['total_cost_sickness']; ?>' ><?php echo $item['total_cost_sickness_money']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>
    <tfoot>
        <tr>
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

            <td><?php echo $results['totalCostSickness_money_m']; ?></td>
            <td><?php echo $results['totalCostSickness_money_f']; ?></td>
            <td><?php echo $results['totalCostSickness_money']; ?></td>
        </tr>
    </tfoot>                                
</table>

<!-- hidden tables -->
<table class="hide item_sf" id="tableCCID_1_sf" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_ABSENCE_DAYS_SICKNESS_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td data-sort='<?php echo $item['cost_sickness_m_HAS_SF']; ?>' ><?php echo $item['cost_sickness_money_m_HAS_SF']; ?></td>
                <td data-sort='<?php echo $item['cost_sickness_f_HAS_SF']; ?>' ><?php echo $item['cost_sickness_money_f_HAS_SF']; ?></td>
                <td data-sort='<?php echo $item['total_cost_sickness_HAS_SF']; ?>' ><?php echo $item['total_cost_sickness_money_HAS_SF']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>
    <tfoot>
        <tr>
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

            <td><?php echo $results['totalCostSickness_money_m_HAS_SF']; ?></td>
            <td><?php echo $results['totalCostSickness_money_f_HAS_SF']; ?></td>
            <td><?php echo $results['totalCostSickness_money_HAS_SF']; ?></td>
        </tr>
    </tfoot>                                
</table>

<table class="hide item_oe" id="tableCCID_1_oe" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_ABSENCE_DAYS_SICKNESS_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td data-sort='<?php echo $item['cost_sickness_m_HAS_OE']; ?>' ><?php echo $item['cost_sickness_money_m_HAS_OE']; ?></td>
                <td data-sort='<?php echo $item['total_cost_sickness_HAS_OE']; ?>' ><?php echo $item['total_cost_sickness_money_HAS_OE']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>                              
</table>

<!-- first not hidden table tables -->

<table class="item_default" id="tableCCID_2" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_SICKNESS_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td data-sort='<?php echo $item['cost_presenteeism_m']; ?>' ><?php echo $item['cost_presenteeism_money_m']; ?></td>
                <td data-sort='<?php echo $item['cost_presenteeism_f']; ?>' ><?php echo $item['cost_presenteeism_money_f']; ?></td>
                <td data-sort='<?php echo $item['total_cost_presenteeism']; ?>' ><?php echo $item['total_cost_presenteeism_money']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>
    <tfoot>
        <tr>
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

            <td><?php echo $results['totalCostPresenteeism_money_m']; ?></td>
            <td><?php echo $results['totalCostPresenteeism_money_f']; ?></td>
            <td><?php echo $results['totalCostPresenteeism_money']; ?></td>
        </tr>
    </tfoot>                                
</table>

<!-- hidden tables -->
<table class="hide item_sf" id="tableCCID_2_sf" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_SICKNESS_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td data-sort='<?php echo $item['cost_presenteeism_m_HAS_SF']; ?>' ><?php echo $item['cost_presenteeism_money_m_HAS_SF']; ?></td>
                <td data-sort='<?php echo $item['cost_presenteeism_f_HAS_SF']; ?>' ><?php echo $item['cost_presenteeism_money_f_HAS_SF']; ?></td>
                <td data-sort='<?php echo $item['total_cost_presenteeism_HAS_SF']; ?>' ><?php echo $item['total_cost_presenteeism_money_HAS_SF']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>
    <tfoot>
        <tr>
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

            <td><?php echo $results['totalCostPresenteeism_money_m_HAS_SF']; ?></td>
            <td><?php echo $results['totalCostPresenteeism_money_f_HAS_SF']; ?></td>
            <td><?php echo $results['totalCostPresenteeism_money_HAS_SF']; ?></td>
        </tr>
    </tfoot>                                
</table>

<table class="hide item_oe" id="tableCCID_2_oe" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_SICKNESS_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td data-sort='<?php echo $item['cost_presenteeism_m_HAS_OE']; ?>' ><?php echo $item['cost_presenteeism_money_m_HAS_OE']; ?></td>
                <td data-sort='<?php echo $item['total_cost_presenteeism_HAS_OE']; ?>' ><?php echo $item['total_cost_presenteeism_money_HAS_OE']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>                              
</table>

<!-- first not hidden table tables -->

<table class="item_default" id="tableCCID_3" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_DEATHS_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td data-sort='<?php echo $item['cost_death_m']; ?>' ><?php echo $item['cost_death_money_m']; ?></td>
                <td data-sort='<?php echo $item['cost_death_f']; ?>' ><?php echo $item['cost_death_money_f']; ?></td>
                <td data-sort='<?php echo $item['total_cost_death']; ?>' ><?php echo $item['total_cost_death_money']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>
    <tfoot>
        <tr>
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

            <td><?php echo $results['totalCostDeath_money_m']; ?></td>
            <td><?php echo $results['totalCostDeath_money_f']; ?></td>
            <td><?php echo $results['totalCostDeath_money']; ?></td>
        </tr>
    </tfoot>                                
</table>

<!-- hidden tables -->
<table class="hide item_sf" id="tableCCID_3_sf" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_DEATHS_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td data-sort='<?php echo $item['cost_death_m_HAS_SF']; ?>' ><?php echo $item['cost_death_money_m_HAS_SF']; ?></td>
                <td data-sort='<?php echo $item['cost_death_f_HAS_SF']; ?>' ><?php echo $item['cost_death_money_f_HAS_SF']; ?></td>
                <td data-sort='<?php echo $item['total_cost_death_HAS_SF']; ?>' ><?php echo $item['total_cost_death_money_HAS_SF']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>
    <tfoot>
        <tr>
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

            <td><?php echo $results['totalCostDeath_money_m_HAS_SF']; ?></td>
            <td><?php echo $results['totalCostDeath_money_f_HAS_SF']; ?></td>
            <td><?php echo $results['totalCostDeath_money_HAS_SF']; ?></td>
        </tr>
    </tfoot>                                
</table>

<table class="hide item_oe" id="tableCCID_3_oe" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_DEATHS_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td data-sort='<?php echo $item['cost_death_m_HAS_OE']; ?>' ><?php echo $item['cost_death_money_m_HAS_OE']; ?></td>
                <td data-sort='<?php echo $item['total_cost_death_HAS_OE']; ?>' ><?php echo $item['total_cost_death_money_HAS_OE']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>                              
</table>

<!-- first not hidden table tables -->

<table class="item_default" id="tableCCID_4" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_RISK_FACTOR_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_RISK_NAMES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['risk'] ) : ?>
		<?php foreach ($results['risk'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['risk_name']; ?></th>
                <td data-sort='<?php echo $item['cost_risk_m']; ?>' ><?php echo $item['cost_risk_money_m']; ?></td>
                <td data-sort='<?php echo $item['cost_risk_f']; ?>' ><?php echo $item['cost_risk_money_f']; ?></td>
                <td data-sort='<?php echo $item['total_cost_risk']; ?>' ><?php echo $item['total_cost_risk_money']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>
    <tfoot>
        <tr>
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

            <td><?php echo $results['totalCostRisk_money_m']; ?></td>
            <td><?php echo $results['totalCostRisk_money_f']; ?></td>
            <td><?php echo $results['totalCostRisk_money']; ?></td>
        </tr>
    </tfoot>                                
</table>

<!-- hidden tables -->
<table class="hide item_sf" id="tableCCID_4_sf" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_RISK_FACTOR_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_RISK_NAMES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th width="20%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['risk'] ) : ?>
		<?php foreach ($results['risk'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['risk_name']; ?></th>
                <td data-sort='<?php echo $item['cost_risk_m_HAS_SF']; ?>' ><?php echo $item['cost_risk_money_m_HAS_SF']; ?></td>
                <td data-sort='<?php echo $item['cost_risk_f_HAS_SF']; ?>' ><?php echo $item['cost_risk_money_f_HAS_SF']; ?></td>
                <td data-sort='<?php echo $item['total_cost_risk_HAS_SF']; ?>' ><?php echo $item['total_cost_risk_money_HAS_SF']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>
    <tfoot>
        <tr>
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

            <td><?php echo $results['totalCostRisk_money_m_HAS_SF']; ?></td>
            <td><?php echo $results['totalCostRisk_money_f_HAS_SF']; ?></td>
            <td><?php echo $results['totalCostRisk_money_HAS_SF']; ?></td>
        </tr>
    </tfoot>                                
</table>

</div>
</div>