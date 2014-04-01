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
/* <div class="uk-text-large"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_PERDICTED_WORK_DAYS_LOST_TITLE'); ?></div> */
?>
<!-- This is the subnav containing the toggling elements -->
<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#tablePWDL'}">
    <li class="uk-active uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?></a></li>
</ul>
<br/>
<!-- This is the container of the content items -->
<ul id="tablePWDL" class="uk-switcher">
    <li class="uk-active default" >
    <!-- tablePWDL_1 -->
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_TITLE'); ?></div>
    <table id="theTablePWDL_1" class="table data tablePWDL_1 metro-blue" data-filter="#tablePWDL_1">
        <thead>
            <tr >
                <th data-toggle="true" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
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
                $total_7 =	0;
        ?>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['number_of_episodes_m']; ?>'><?php echo $item['number_of_episodes_m']; ?></td>
                    <td data-value='<?php echo $item['hospital_admissions_m']; ?>'><?php echo $item['hospital_admissions_m']; ?></td>
                    <td data-value='<?php echo $item['number_of_episodes_f']; ?>'><?php echo $item['number_of_episodes_f']; ?></td>
                    <td data-value='<?php echo $item['hospital_admissions_f']; ?>'><?php echo $item['hospital_admissions_f']; ?></td>
                    <td data-value='<?php echo $item['absence_days_m']; ?>'><?php echo $item['absence_days_m']; ?></td>
                    <td data-value='<?php echo $item['absence_days_f']; ?>'><?php echo $item['absence_days_f']; ?></td>
                    <td data-value='<?php echo $item['total_absence_days']; ?>'><?php echo $item['total_absence_days']; ?></td>
                </tr>
                <?php
                    $total_1 +=	$item['number_of_episodes_m'];
                    $total_2 +=	$item['hospital_admissions_m'];
                    $total_3 +=	$item['number_of_episodes_f'];
                    $total_4 +=	$item['hospital_admissions_f'];
                    $total_5 +=	$item['absence_days_m'];
                    $total_6 +=	$item['absence_days_f']; 
                    $total_7 +=	$item['total_absence_days'];
                ?>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $total_1; ?></td>
                <td><?php echo $total_2; ?></td>
                <td><?php echo $total_3; ?></td>
                <td><?php echo $total_4; ?></td>
                <td><?php echo $total_5; ?></td>
                <td><?php echo $total_6; ?></td>
                <td><?php echo $total_7; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTablePWDL_1','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tablePWDL_2 -->
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_TITLE'); ?></div>
    <table id="theTablePWDL_2" class="table data tablePWDL_2 metro-blue" data-filter="#tablePWDL_2">
        <thead>    
            <tr >
                <th data-toggle="true" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_UNPRODUCTIVE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_UNPRODUCTIVE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_UNPRODUCTIVE_DAYS_LABEL'); ?></th>
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
                $total_7 =	0;
        ?>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['number_of_episodes_m']; ?>'><?php echo $item['number_of_episodes_m']; ?></td>
                    <td data-value='<?php echo $item['hospital_admissions_m']; ?>'><?php echo $item['hospital_admissions_m']; ?></td>
                    <td data-value='<?php echo $item['number_of_episodes_f']; ?>'><?php echo $item['number_of_episodes_f']; ?></td>
                    <td data-value='<?php echo $item['hospital_admissions_f']; ?>'><?php echo $item['hospital_admissions_f']; ?></td>
                    <td data-value='<?php echo $item['unproductive_days_m']; ?>'><?php echo $item['unproductive_days_m']; ?></td>
                    <td data-value='<?php echo $item['unproductive_days_f']; ?>'><?php echo $item['unproductive_days_f']; ?></td>
                    <td data-value='<?php echo $item['total_unproductive_days']; ?>'><?php echo $item['total_unproductive_days']; ?></td>
                </tr>
                <?php
                    $total_1 	+=	$item['number_of_episodes_m'];
                    $total_2 	+=	$item['hospital_admissions_m'];
                    $total_3 	+=	$item['number_of_episodes_f'];
                    $total_4 	+=	$item['hospital_admissions_f'];
                    $total_5 	+=	$item['unproductive_days_m'];
                    $total_6 	+=	$item['unproductive_days_f']; 
                    $total_7 	+=	$item['total_unproductive_days'];
                ?>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $total_1; ?></td>
                <td><?php echo $total_2; ?></td>
                <td><?php echo $total_3; ?></td>
                <td><?php echo $total_4; ?></td>
                <td><?php echo $total_5; ?></td>
                <td><?php echo $total_6; ?></td>
                <td><?php echo $total_7; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTablePWDL_2','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tablePWDL_3 -->
    <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_DEATH_TITLE'); ?></div>
    <table id="theTablePWDL_3" class="table data tablePWDL_3 metro-blue" data-filter="#tablePWDL_3">
        <thead>    
            <tr >
                <th data-toggle="true" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_DEATHS_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_DEATHS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php 
                $total_1 =	0;
                $total_2 =	0;
                $total_3 =	0;
                $total_4 =	0;
                $total_5 =	0;
        ?>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['number_of_deaths_m']; ?>'><?php echo $item['number_of_deaths_m']; ?></td>
                    <td data-value='<?php echo $item['death_absence_days_m']; ?>'><?php echo $item['death_absence_days_m']; ?></td>
                    <td data-value='<?php echo $item['number_of_deaths_f']; ?>'><?php echo $item['number_of_deaths_f']; ?></td>
                    <td data-value='<?php echo $item['death_absence_days_f']; ?>'><?php echo $item['death_absence_days_f']; ?></td>
                    <td data-value='<?php echo $item['total_death_absence_days']; ?>'><?php echo $item['total_death_absence_days']; ?></td>
                </tr>
                <?php
                    $total_1 	+=	$item['number_of_deaths_m'];
                    $total_2 	+=	$item['death_absence_days_m'];
                    $total_3 	+=	$item['number_of_deaths_f'];
                    $total_4 	+=	$item['death_absence_days_f'];
                    $total_5 	+=	$item['total_death_absence_days'];
                ?>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $total_1; ?></td>
                <td><?php echo $total_2; ?></td>
                <td><?php echo $total_3; ?></td>
                <td><?php echo $total_4; ?></td>
                <td><?php echo $total_5; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTablePWDL_3','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_DEATH_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tablePWDL_4 -->
    <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_RISK_TITLE'); ?></div>
    <table id="theTablePWDL_4" class="table data tablePWDL_4 metro-blue" data-filter="#tablePWDL_4">
        <thead>    
            <tr >
                <th data-toggle="true" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_RISK_NAMES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['risk'] ) : ?>
            <?php foreach ($results['risk'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['risk_name']; ?></td>
                    <td data-value='<?php echo $item['risk_absence_days_m']; ?>'><?php echo $item['risk_absence_days_m']; ?></td>
                    <td data-value='<?php echo $item['risk_absence_days_f']; ?>'><?php echo $item['risk_absence_days_f']; ?></td>
                    <td data-value='<?php echo $item['total_risk_absence_days']; ?>'><?php echo $item['total_risk_absence_days']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                    <td><?php echo $results['totalRiskAbsenceDays_m']; ?></td>
                <td><?php echo $results['totalRiskAbsenceDays_f']; ?></td>
                <td><?php echo $results['totalRiskAbsenceDays']; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTablePWDL_4','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_RISK_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    </li>
    <li class="scalling" >
    <!-- tablePWDL_1_isf -->
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_TITLE'); ?></div>
    <table id="theTablePWDL_1_isf" class="table data tablePWDL_1_isf metro-blue" data-filter="#tablePWDL_1_isf">
        <thead>    
            <tr >
                <th data-toggle="true" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
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
                $total_7 =	0;
        ?>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['number_of_episodes_m_HAS_ISF']; ?>'><?php echo $item['number_of_episodes_m_HAS_ISF']; ?></td>
                    <td data-value='<?php echo $item['hospital_admissions_m_HAS_ISF']; ?>'><?php echo $item['hospital_admissions_m_HAS_ISF']; ?></td>
                    <td data-value='<?php echo $item['number_of_episodes_f_HAS_ISF']; ?>'><?php echo $item['number_of_episodes_f_HAS_ISF']; ?></td>
                    <td data-value='<?php echo $item['hospital_admissions_f_HAS_ISF']; ?>'><?php echo $item['hospital_admissions_f_HAS_ISF']; ?></td>
                    <td data-value='<?php echo $item['absence_days_m_HAS_ISF']; ?>'><?php echo $item['absence_days_m_HAS_ISF']; ?></td>
                    <td data-value='<?php echo $item['absence_days_f_HAS_ISF']; ?>'><?php echo $item['absence_days_f_HAS_ISF']; ?></td>
                    <td data-value='<?php echo $item['total_absence_days_HAS_ISF']; ?>'><?php echo $item['total_absence_days_HAS_ISF']; ?></td>
                </tr>
                <?php
                    $total_1 +=	$item['number_of_episodes_m_HAS_ISF'];
                    $total_2 +=	$item['hospital_admissions_m_HAS_ISF'];
                    $total_3 +=	$item['number_of_episodes_f_HAS_ISF'];
                    $total_4 +=	$item['hospital_admissions_f_HAS_ISF'];
                    $total_5 +=	$item['absence_days_m_HAS_ISF'];
                    $total_6 +=	$item['absence_days_f_HAS_ISF']; 
                    $total_7 +=	$item['total_absence_days_HAS_ISF'];
                ?>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $total_1; ?></td>
                <td><?php echo $total_2; ?></td>
                <td><?php echo $total_3; ?></td>
                <td><?php echo $total_4; ?></td>
                <td><?php echo $total_5; ?></td>
                <td><?php echo $total_6; ?></td>
                <td><?php echo $total_7; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTablePWDL_1_isf','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tablePWDL_2_isf -->
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_TITLE'); ?></div>
    <table id="theTablePWDL_2_isf" class="table data tablePWDL_2_isf metro-blue" data-filter="#tablePWDL_2_isf">
        <thead>    
            <tr >
                <th data-toggle="true" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_UNPRODUCTIVE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_UNPRODUCTIVE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_UNPRODUCTIVE_DAYS_LABEL'); ?></th>
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
                $total_7 =	0;
        ?>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['number_of_episodes_m_HAS_ISF']; ?>'><?php echo $item['number_of_episodes_m_HAS_ISF']; ?></td>
                    <td data-value='<?php echo $item['hospital_admissions_m_HAS_ISF']; ?>'><?php echo $item['hospital_admissions_m_HAS_ISF']; ?></td>
                    <td data-value='<?php echo $item['number_of_episodes_f_HAS_ISF']; ?>'><?php echo $item['number_of_episodes_f_HAS_ISF']; ?></td>
                    <td data-value='<?php echo $item['hospital_admissions_f_HAS_ISF']; ?>'><?php echo $item['hospital_admissions_f_HAS_ISF']; ?></td>
                    <td data-value='<?php echo $item['unproductive_days_m_HAS_ISF']; ?>'><?php echo $item['unproductive_days_m_HAS_ISF']; ?></td>
                    <td data-value='<?php echo $item['unproductive_days_f_HAS_ISF']; ?>'><?php echo $item['unproductive_days_f_HAS_ISF']; ?></td>
                    <td data-value='<?php echo $item['total_unproductive_days_HAS_ISF']; ?>'><?php echo $item['total_unproductive_days_HAS_ISF']; ?></td>
                </tr>
                <?php
                    $total_1 	+=	$item['number_of_episodes_m_HAS_ISF'];
                    $total_2 	+=	$item['hospital_admissions_m_HAS_ISF'];
                    $total_3 	+=	$item['number_of_episodes_f_HAS_ISF'];
                    $total_4 	+=	$item['hospital_admissions_f_HAS_ISF'];
                    $total_5 	+=	$item['unproductive_days_m_HAS_ISF'];
                    $total_6 	+=	$item['unproductive_days_f_HAS_ISF']; 
                    $total_7 	+=	$item['total_unproductive_days_HAS_ISF'];
                ?>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $total_1; ?></td>
                <td><?php echo $total_2; ?></td>
                <td><?php echo $total_3; ?></td>
                <td><?php echo $total_4; ?></td>
                <td><?php echo $total_5; ?></td>
                <td><?php echo $total_6; ?></td>
                <td><?php echo $total_7; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTablePWDL_2_isf','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tablePWDL_3_msf -->
    <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_DEATH_TITLE'); ?></div>
    <table id="theTablePWDL_3_msf" class="table data tablePWDL_3_msf metro-blue" data-filter="#tablePWDL_3_msf">
        <thead>    
            <tr >
                <th data-toggle="true" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_DEATHS_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_DEATHS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php 
                $total_1 =	0;
                $total_2 =	0;
                $total_3 =	0;
                $total_4 =	0;
                $total_5 =	0;
        ?>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['number_of_deaths_m_HAS_MSF']; ?>'><?php echo $item['number_of_deaths_m_HAS_MSF']; ?></td>
                    <td data-value='<?php echo $item['death_absence_days_m_HAS_MSF']; ?>'><?php echo $item['death_absence_days_m_HAS_MSF']; ?></td>
                    <td data-value='<?php echo $item['number_of_deaths_f_HAS_MSF']; ?>'><?php echo $item['number_of_deaths_f_HAS_MSF']; ?></td>
                    <td data-value='<?php echo $item['death_absence_days_f_HAS_MSF']; ?>'><?php echo $item['death_absence_days_f_HAS_MSF']; ?></td>
                    <td data-value='<?php echo $item['total_death_absence_days_HAS_MSF']; ?>'><?php echo $item['total_death_absence_days_HAS_MSF']; ?></td>
                </tr>
                <?php
                    $total_1 	+=	$item['number_of_deaths_m_HAS_MSF'];
                    $total_2 	+=	$item['death_absence_days_m_HAS_MSF'];
                    $total_3 	+=	$item['number_of_deaths_f_HAS_MSF'];
                    $total_4 	+=	$item['death_absence_days_f_HAS_MSF'];
                    $total_5 	+=	$item['total_death_absence_days_HAS_MSF'];
                ?>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $total_1; ?></td>
                <td><?php echo $total_2; ?></td>
                <td><?php echo $total_3; ?></td>
                <td><?php echo $total_4; ?></td>
                <td><?php echo $total_5; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTablePWDL_3_msf','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_DEATH_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tablePWDL_4_sf -->
    <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_RISK_TITLE'); ?></div>
    <table id="theTablePWDL_4_sf" class="table data tablePWDL_4_sf metro-blue" data-filter="#tablePWDL_4_sf">
        <thead>    
            <tr >
                <th data-toggle="true" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_RISK_NAMES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['risk'] ) : ?>
            <?php foreach ($results['risk'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['risk_name']; ?></td>
                    <td data-value='<?php echo $item['risk_absence_days_m_HAS_SF']; ?>'><?php echo $item['risk_absence_days_m_HAS_SF']; ?></td>
                    <td data-value='<?php echo $item['risk_absence_days_f_HAS_SF']; ?>'><?php echo $item['risk_absence_days_f_HAS_SF']; ?></td>
                    <td data-value='<?php echo $item['total_risk_absence_days_HAS_SF']; ?>'><?php echo $item['total_risk_absence_days_HAS_SF']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>
        <tfoot>
            <tr>
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $results['totalRiskAbsenceDays_m_HAS_SF']; ?></td>
                <td><?php echo $results['totalRiskAbsenceDays_f_HAS_SF']; ?></td>
                <td><?php echo $results['totalRiskAbsenceDays_HAS_SF']; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTablePWDL_4_sf','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_RISK_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    </li>
    <li class="episode" >
    <!-- tablePWDL_1_oe -->
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_TITLE'); ?></div>
    <table id="theTablePWDL_1_oe" class="table data tablePWDL_1_oe metro-blue" data-filter="#tablePWDL_1_oe">
        <thead>    
            <tr >
                <th data-toggle="true" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['number_of_episodes_m_HAS_OE']; ?>'><?php echo $item['number_of_episodes_m_HAS_OE']; ?></td>
                    <td data-value='<?php echo $item['hospital_admissions_m_HAS_OE']; ?>'><?php echo $item['hospital_admissions_m_HAS_OE']; ?></td>
                    <td data-value='<?php echo $item['absence_days_m_HAS_OE']; ?>'><?php echo $item['absence_days_m_HAS_OE']; ?></td>
                    <td data-value='<?php echo $item['total_absence_days_HAS_OE']; ?>'><?php echo $item['total_absence_days_HAS_OE']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>                               
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTablePWDL_1_oe','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tablePWDL_2_eo -->
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_TITLE'); ?></div>
    <table id="theTablePWDL_2_oe" class="table data tablePWDL_2_eo metro-blue" data-filter="#tablePWDL_2_eo">
        <thead>    
            <tr >
                <th data-toggle="true" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_UNPRODUCTIVE_DAYS_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_UNPRODUCTIVE_DAYS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['number_of_episodes_m_HAS_OE']; ?>'><?php echo $item['number_of_episodes_m_HAS_OE']; ?></td>
                    <td data-value='<?php echo $item['hospital_admissions_m_HAS_OE']; ?>'><?php echo $item['hospital_admissions_m_HAS_OE']; ?></td>
                    <td data-value='<?php echo $item['unproductive_days_m_HAS_OE']; ?>'><?php echo $item['unproductive_days_m_HAS_OE']; ?></td>
                    <td data-value='<?php echo $item['total_unproductive_days_HAS_OE']; ?>'><?php echo $item['total_unproductive_days_HAS_OE']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>                               
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTablePWDL_2_oe','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    <br/><br/>
    <!-- tablePWDL_3_oe -->
    <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_DEATH_TITLE'); ?></div>
    <table id="theTablePWDL_3_oe" class="table data tablePWDL_3_oe metro-blue" data-filter="#tablePWDL_3_oe">
        <thead>    
            <tr >
                <th data-toggle="true" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_DEATHS_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_EMPLOYEES_LABEL'); ?></th>
                <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['number_of_deaths_m_HAS_OE']; ?>'><?php echo $item['number_of_deaths_m_HAS_OE']; ?></td>
                    <td data-value='<?php echo $item['death_absence_days_m_HAS_OE']; ?>'><?php echo $item['death_absence_days_m_HAS_OE']; ?></td>
                    <td data-value='<?php echo $item['total_death_absence_days_HAS_OE']; ?>'><?php echo $item['total_death_absence_days_HAS_OE']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                        
        </tbody>                               
    </table>  
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTablePWDL_3_oe','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_DEATH_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>  
    </li>
</ul>