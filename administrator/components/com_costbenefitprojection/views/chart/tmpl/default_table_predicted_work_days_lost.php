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
<div class="hide hidden" id="giz_pwdl">
<div id="view_pwdl" style="margin:0 auto; width: 900px; height: 100%;">
<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_PERDICTED_WORK_DAYS_LOST_TITLE'); ?></h1>
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

<table class="item_default" id="tablePWDL_1" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
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
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td><?php echo $item['number_of_episodes_m']; ?></td>
                <td><?php echo $item['hospital_admissions_m']; ?></td>
                <td><?php echo $item['number_of_episodes_f']; ?></td>
                <td><?php echo $item['hospital_admissions_f']; ?></td>
                <td><?php echo $item['absence_days_m']; ?></td>
                <td><?php echo $item['absence_days_f']; ?></td>
                <td><?php echo $item['total_absence_days']; ?></td>
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
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

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

<!-- hidden tables -->

<table class="hide item_sf" id="tablePWDL_1_isf" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
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
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td><?php echo $item['number_of_episodes_m_HAS_ISF']; ?></td>
                <td><?php echo $item['hospital_admissions_m_HAS_ISF']; ?></td>
                <td><?php echo $item['number_of_episodes_f_HAS_ISF']; ?></td>
                <td><?php echo $item['hospital_admissions_f_HAS_ISF']; ?></td>
                <td><?php echo $item['absence_days_m_HAS_ISF']; ?></td>
                <td><?php echo $item['absence_days_f_HAS_ISF']; ?></td>
                <td><?php echo $item['total_absence_days_HAS_ISF']; ?></td>
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
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>
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

<table class="hide item_oe" id="tablePWDL_1_oe" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td><?php echo $item['number_of_episodes_m_HAS_OE']; ?></td>
                <td><?php echo $item['hospital_admissions_m_HAS_OE']; ?></td>
                <td><?php echo $item['absence_days_m_HAS_OE']; ?></td>
                <td><?php echo $item['total_absence_days_HAS_OE']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>                               
</table>

<!-- first not hidden table tables -->

 <table class="item_default" id="tablePWDL_2" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_UNPRODUCTIVE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_UNPRODUCTIVE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_UNPRODUCTIVE_DAYS_LABEL'); ?></th>
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
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td><?php echo $item['number_of_episodes_m']; ?></td>
                <td><?php echo $item['hospital_admissions_m']; ?></td>
                <td><?php echo $item['number_of_episodes_f']; ?></td>
                <td><?php echo $item['hospital_admissions_f']; ?></td>
                <td><?php echo $item['unproductive_days_m']; ?></td>
                <td><?php echo $item['unproductive_days_f']; ?></td>
                <td><?php echo $item['total_unproductive_days']; ?></td>
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
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>
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

<!-- hidden tables -->

<table class="hide item_sf" id="tablePWDL_2_isf" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_UNPRODUCTIVE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_UNPRODUCTIVE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_UNPRODUCTIVE_DAYS_LABEL'); ?></th>
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
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td><?php echo $item['number_of_episodes_m_HAS_ISF']; ?></td>
                <td><?php echo $item['hospital_admissions_m_HAS_ISF']; ?></td>
                <td><?php echo $item['number_of_episodes_f_HAS_ISF']; ?></td>
                <td><?php echo $item['hospital_admissions_f_HAS_ISF']; ?></td>
                <td><?php echo $item['unproductive_days_m_HAS_ISF']; ?></td>
                <td><?php echo $item['unproductive_days_f_HAS_ISF']; ?></td>
                <td><?php echo $item['total_unproductive_days_HAS_ISF']; ?></td>
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
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>
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

<table class="hide item_oe" id="tablePWDL_2_oe" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="8" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_EPISODES_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_HOSPITAL_ADMISSIONS_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_UNPRODUCTIVE_DAYS_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_UNPRODUCTIVE_DAYS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td><?php echo $item['number_of_episodes_m_HAS_OE']; ?></td>
                <td><?php echo $item['hospital_admissions_m_HAS_OE']; ?></td>
                <td><?php echo $item['unproductive_days_m_HAS_OE']; ?></td>
                <td><?php echo $item['total_unproductive_days_HAS_OE']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>                               
</table>

<!-- first not hidden table tables -->

<table class="item_default" id="tablePWDL_3" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="6" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_DEATH_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_DEATHS_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_DEATHS_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
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
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td><?php echo $item['number_of_deaths_m']; ?></td>
                <td><?php echo $item['death_absence_days_m']; ?></td>
                <td><?php echo $item['number_of_deaths_f']; ?></td>
                <td><?php echo $item['death_absence_days_f']; ?></td>
                <td><?php echo $item['total_death_absence_days']; ?></td>
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
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>
            <td><?php echo $total_1; ?></td>
            <td><?php echo $total_2; ?></td>
            <td><?php echo $total_3; ?></td>
            <td><?php echo $total_4; ?></td>
            <td><?php echo $total_5; ?></td>
        </tr>
    </tfoot>                                
</table>

<!-- hidden tables -->

<table class="hide item_sf" id="tablePWDL_3_msf" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="6" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_DEATH_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_DEATHS_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_DEATHS_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
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
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td><?php echo $item['number_of_deaths_m_HAS_MSF']; ?></td>
                <td><?php echo $item['death_absence_days_m_HAS_MSF']; ?></td>
                <td><?php echo $item['number_of_deaths_f_HAS_MSF']; ?></td>
                <td><?php echo $item['death_absence_days_f_HAS_MSF']; ?></td>
                <td><?php echo $item['total_death_absence_days_HAS_MSF']; ?></td>
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
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>
            <td><?php echo $total_1; ?></td>
            <td><?php echo $total_2; ?></td>
            <td><?php echo $total_3; ?></td>
            <td><?php echo $total_4; ?></td>
            <td><?php echo $total_5; ?></td>
        </tr>
    </tfoot>                                
</table>

<table class="hide item_oe" id="tablePWDL_3_oe" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="4" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_WORK_DAYS_LOST_DEATH_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_NAMES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_NUMBER_DEATHS_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['disease']) : ?>
		<?php foreach ($results['disease'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['disease_name']; ?></th>
                <td><?php echo $item['number_of_deaths_m_HAS_OE']; ?></td>
                <td><?php echo $item['death_absence_days_m_HAS_OE']; ?></td>
                <td><?php echo $item['total_death_absence_days_HAS_OE']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>                               
</table>

<!-- first not hidden table tables -->

<table class="item_default" id="tablePWDL_4" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="4" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_RISK_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_RISK_NAMES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['risk'] ) : ?>
		<?php foreach ($results['risk'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['risk_name']; ?></th>
                <td><?php echo $item['risk_absence_days_m']; ?></td>
                <td><?php echo $item['risk_absence_days_f']; ?></td>
                <td><?php echo $item['total_risk_absence_days']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>
    <tfoot>
        <tr>
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

            <td><?php echo $results['totalRiskAbsenceDays_m']; ?></td>
            <td><?php echo $results['totalRiskAbsenceDays_f']; ?></td>
            <td><?php echo $results['totalRiskAbsenceDays']; ?></td>
        </tr>
    </tfoot>                                
</table>

<!-- hidden tables -->

<table class="hide item_sf" id="tablePWDL_4_sf" summary="">
    <thead>    
        <tr>
            <th scope="col" colspan="4" class='no-sort'><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_CALCULATED_PERSENTEEISM_RISK_TITLE'); ?></th>
            
        </tr>
        
        <tr >
            <th width="15%" scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_RISK_NAMES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_ABSENCE_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
            <th scope="col"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_ABSENCE_DAYS_LABEL'); ?></th>
        </tr>        
    </thead>                                    
    <tbody>
    <?php if ($results['risk'] ) : ?>
		<?php foreach ($results['risk'] as $i => $item): ?>
            <tr>
                <th scope="row"><?php echo $item['risk_name']; ?></th>
                <td><?php echo $item['risk_absence_days_m_HAS_SF']; ?></td>
                <td><?php echo $item['risk_absence_days_f_HAS_SF']; ?></td>
                <td><?php echo $item['total_risk_absence_days_HAS_SF']; ?></td>
            </tr>
        <?php endforeach; ?>
	<?php endif; ?>                                        
    </tbody>
    <tfoot>
        <tr>
            <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>

            <td><?php echo $results['totalRiskAbsenceDays_m_HAS_SF']; ?></td>
            <td><?php echo $results['totalRiskAbsenceDays_f_HAS_SF']; ?></td>
            <td><?php echo $results['totalRiskAbsenceDays_HAS_SF']; ?></td>
        </tr>
    </tfoot>                                
</table>
</div>
</div>