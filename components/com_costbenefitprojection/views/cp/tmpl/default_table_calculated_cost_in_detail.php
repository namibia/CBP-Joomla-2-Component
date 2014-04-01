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
/* <div class="uk-text-large"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_CALCULATED_COST_IN_DETAIL_TITLE'); ?></div> */
?>
<!-- This is the subnav containing the toggling elements -->
<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#tableCCID'}">
    <li class="uk-active uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?></a></li>
</ul>
<br/>
<!-- This is the container of the content items -->
<ul id="tableCCID" class="uk-switcher">
    <li class="uk-active default" >
    <!-- tableCCID_1 -->
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_ABSENCE_DAYS_SICKNESS_TITLE'); ?></div>
    <table id="theTableCCID_1" class="table data tableCCID_1 metro-blue" data-filter="#tableCCID_1">
        <thead>        
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['cost_sickness_m']; ?>' ><?php echo $item['cost_sickness_money_m']; ?></td>
                    <td data-value='<?php echo $item['cost_sickness_f']; ?>' ><?php echo $item['cost_sickness_money_f']; ?></td>
                    <td data-value='<?php echo $item['total_cost_sickness']; ?>' ><?php echo $item['total_cost_sickness_money']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $results['totalCostSickness_money_m']; ?></td>
                <td><?php echo $results['totalCostSickness_money_f']; ?></td>
                <td><?php echo $results['totalCostSickness_money']; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCCID_1','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_ABSENCE_DAYS_SICKNESS_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tableCCID_2 -->
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_SICKNESS_TITLE'); ?></div>
    <table id="theTableCCID_2" class="table data tableCCID_2 metro-blue" data-filter="#tableCCID_2">
        <thead>    
             <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['cost_presenteeism_m']; ?>' ><?php echo $item['cost_presenteeism_money_m']; ?></td>
                    <td data-value='<?php echo $item['cost_presenteeism_f']; ?>' ><?php echo $item['cost_presenteeism_money_f']; ?></td>
                    <td data-value='<?php echo $item['total_cost_presenteeism']; ?>' ><?php echo $item['total_cost_presenteeism_money']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $results['totalCostPresenteeism_money_m']; ?></td>
                <td><?php echo $results['totalCostPresenteeism_money_f']; ?></td>
                <td><?php echo $results['totalCostPresenteeism_money']; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCCID_2','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_SICKNESS_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tableCCID_3 -->
    <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_DEATHS_TITLE'); ?></div>
    <table id="theTableCCID_3" class="table data tableCCID_3 metro-blue" data-filter="#tableCCID_3">
        <thead>
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['cost_death_m']; ?>' ><?php echo $item['cost_death_money_m']; ?></td>
                    <td data-value='<?php echo $item['cost_death_f']; ?>' ><?php echo $item['cost_death_money_f']; ?></td>
                    <td data-value='<?php echo $item['total_cost_death']; ?>' ><?php echo $item['total_cost_death_money']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $results['totalCostDeath_money_m']; ?></td>
                <td><?php echo $results['totalCostDeath_money_f']; ?></td>
                <td><?php echo $results['totalCostDeath_money']; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCCID_3','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_DEATHS_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tableCCID_4 -->
    <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_RISK_FACTOR_TITLE'); ?></div>
    <table id="theTableCCID_4" class="table data tableCCID_4 metro-blue" data-filter="#tableCCID_4">
        <thead>    
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_RISK_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['risk'] ) : ?>
            <?php foreach ($results['risk'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['risk_name']; ?></td>
                    <td data-value='<?php echo $item['cost_risk_m']; ?>' ><?php echo $item['cost_risk_money_m']; ?></td>
                    <td data-value='<?php echo $item['cost_risk_f']; ?>' ><?php echo $item['cost_risk_money_f']; ?></td>
                    <td data-value='<?php echo $item['total_cost_risk']; ?>' ><?php echo $item['total_cost_risk_money']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $results['totalCostRisk_money_m']; ?></td>
                <td><?php echo $results['totalCostRisk_money_f']; ?></td>
                <td><?php echo $results['totalCostRisk_money']; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCCID_4','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_RISK_FACTOR_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    </li>
    <li class="scalling" >
    <!-- tableCCID_1_sf -->
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_ABSENCE_DAYS_SICKNESS_TITLE'); ?></div>
    <table id="theTableCCID_1_sf" class="table data tableCCID_1_sf metro-blue" data-filter="#tableCCID_1_sf">
        <thead>        
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['cost_sickness_m_HAS_SF']; ?>' ><?php echo $item['cost_sickness_money_m_HAS_SF']; ?></td>
                    <td data-value='<?php echo $item['cost_sickness_f_HAS_SF']; ?>' ><?php echo $item['cost_sickness_money_f_HAS_SF']; ?></td>
                    <td data-value='<?php echo $item['total_cost_sickness_HAS_SF']; ?>' ><?php echo $item['total_cost_sickness_money_HAS_SF']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $results['totalCostSickness_money_m_HAS_SF']; ?></td>
                <td><?php echo $results['totalCostSickness_money_f_HAS_SF']; ?></td>
                <td><?php echo $results['totalCostSickness_money_HAS_SF']; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCCID_1_sf','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_ABSENCE_DAYS_SICKNESS_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tableCCID_2_sf -->
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_SICKNESS_TITLE'); ?></div>
    <table id="theTableCCID_2_sf" class="table data tableCCID_2_sf metro-blue" data-filter="#tableCCID_2_sf">
        <thead>    
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['cost_presenteeism_m_HAS_SF']; ?>' ><?php echo $item['cost_presenteeism_money_m_HAS_SF']; ?></td>
                    <td data-value='<?php echo $item['cost_presenteeism_f_HAS_SF']; ?>' ><?php echo $item['cost_presenteeism_money_f_HAS_SF']; ?></td>
                    <td data-value='<?php echo $item['total_cost_presenteeism_HAS_SF']; ?>' ><?php echo $item['total_cost_presenteeism_money_HAS_SF']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $results['totalCostPresenteeism_money_m_HAS_SF']; ?></td>
                <td><?php echo $results['totalCostPresenteeism_money_f_HAS_SF']; ?></td>
                <td><?php echo $results['totalCostPresenteeism_money_HAS_SF']; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCCID_2_sf','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_SICKNESS_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tableCCID_3_sf -->
    <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_DEATHS_TITLE'); ?></div>
    <table id="theTableCCID_3_sf" class="table data tableCCID_3_sf metro-blue" data-filter="#tableCCID_3_sf">
        <thead>    
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['cost_death_m_HAS_SF']; ?>' ><?php echo $item['cost_death_money_m_HAS_SF']; ?></td>
                    <td data-value='<?php echo $item['cost_death_f_HAS_SF']; ?>' ><?php echo $item['cost_death_money_f_HAS_SF']; ?></td>
                    <td data-value='<?php echo $item['total_cost_death_HAS_SF']; ?>' ><?php echo $item['total_cost_death_money_HAS_SF']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $results['totalCostDeath_money_m_HAS_SF']; ?></td>
                <td><?php echo $results['totalCostDeath_money_f_HAS_SF']; ?></td>
                <td><?php echo $results['totalCostDeath_money_HAS_SF']; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCCID_3_sf','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_DEATHS_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tableCCID_4_sf -->
    <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_RISK_FACTOR_TITLE'); ?></div>
    <table id="theTableCCID_4_sf" class="table data tableCCID_4_sf metro-blue" data-filter="#tableCCID_4_sf"> <thead>    
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_RISK_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['risk'] ) : ?>
            <?php foreach ($results['risk'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['risk_name']; ?></td>
                    <td data-value='<?php echo $item['cost_risk_m_HAS_SF']; ?>' ><?php echo $item['cost_risk_money_m_HAS_SF']; ?></td>
                    <td data-value='<?php echo $item['cost_risk_f_HAS_SF']; ?>' ><?php echo $item['cost_risk_money_f_HAS_SF']; ?></td>
                    <td data-value='<?php echo $item['total_cost_risk_HAS_SF']; ?>' ><?php echo $item['total_cost_risk_money_HAS_SF']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $results['totalCostRisk_money_m_HAS_SF']; ?></td>
                <td><?php echo $results['totalCostRisk_money_f_HAS_SF']; ?></td>
                <td><?php echo $results['totalCostRisk_money_HAS_SF']; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCCID_4_sf','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_RISK_FACTOR_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    </li>
    <li class="episode" >
    <!-- tableCCID_1_oe -->
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_ABSENCE_DAYS_SICKNESS_TITLE'); ?></div>
    <table id="theTableCCID_1_oe" class="table data tableCCID_1_oe metro-blue" data-filter="#tableCCID_1_oe">
        <thead>    
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['cost_sickness_m_HAS_OE']; ?>' ><?php echo $item['cost_sickness_money_m_HAS_OE']; ?></td>
                    <td data-value='<?php echo $item['total_cost_sickness_HAS_OE']; ?>' ><?php echo $item['total_cost_sickness_money_HAS_OE']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>                              
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCCID_1_oe','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_ABSENCE_DAYS_SICKNESS_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tableCCID_2_eo -->
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_SICKNESS_TITLE'); ?></div>
    <table id="theTableCCID_2_oe" class="table data tableCCID_2_oe metro-blue" data-filter="#tableCCID_2_oe">
        <thead>    
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['cost_presenteeism_m_HAS_OE']; ?>' ><?php echo $item['cost_presenteeism_money_m_HAS_OE']; ?></td>
                    <td data-value='<?php echo $item['total_cost_presenteeism_HAS_OE']; ?>' ><?php echo $item['total_cost_presenteeism_money_HAS_OE']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>                              
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCCID_2_oe','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_SICKNESS_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tableCCID_3_oe -->
    <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_DEATHS_TITLE'); ?></div>
    <table id="theTableCCID_3_oe" class="table data tableCCID_3_oe metro-blue" data-filter="#tableCCID_3_oe">
        <thead>    
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone" width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['cost_death_m_HAS_OE']; ?>' ><?php echo $item['cost_death_money_m_HAS_OE']; ?></td>
                    <td data-value='<?php echo $item['total_cost_death_HAS_OE']; ?>' ><?php echo $item['total_cost_death_money_HAS_OE']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>                              
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCCID_3_oe','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_DEATHS_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
      
    </li>
</ul>