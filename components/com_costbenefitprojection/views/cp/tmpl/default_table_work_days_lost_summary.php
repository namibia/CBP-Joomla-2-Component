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
/* <div class="uk-text-large"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_WORK_DAYS_LOST_SUMMARY_TITLE'); ?></div> */
?>
<!-- This is the subnav containing the toggling elements -->
<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#tableWDLS'}">
    <li class="uk-active uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?></a></li>
</ul>
<br/>
<!-- This is the container of the content items -->
<ul id="tableWDLS" class="uk-switcher">
    <li class="uk-active default" >
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_CONTRIBUTION_ALL_TITLE'); ?></div>
    <!-- tableWDLS -->
    <table id="theTableWDLS" class="table data tableWDLS metro-blue" data-filter="#tableWDLS">
        <thead>    
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORBIDITY_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_PRESENTEEISM_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_PRESENTEEISM_RISK_FACTOR_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORTALITY_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
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
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['total_absence_days']; ?>'><?php echo $item['total_absence_days']; ?></td>
                    <td data-value='<?php echo $item['total_unproductive_days']; ?>'><?php echo $item['total_unproductive_days']; ?></td>
                    <td data-value='0'>0</td>
                    <td data-value='<?php echo $item['total_death_absence_days']; ?>'><?php echo $item['total_death_absence_days']; ?></td>
                    <td data-value='<?php echo $item['total_days_lost']; ?>'><?php echo $item['total_days_lost']; ?></td>
                    <td data-value='<?php echo round(($item['total_days_lost'] / $results['totalDaysLost'])*100, 2);?>'><?php echo round(($item['total_days_lost'] / $results['totalDaysLost'])*100, 2).'%'; ?></td>
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
                    <td><?php echo $item['risk_name']; ?></td>
                    <td data-value='0'>0</td>
                    <td data-value='0'>0</td>
                    <td data-value='<?php echo $item['total_risk_absence_days']; ?>'><?php echo $item['total_risk_absence_days']; ?></td>
                    <td data-value='0'>0</td>
                    <td data-value='<?php echo $item['total_risk_absence_days']; ?>'><?php echo $item['total_risk_absence_days']; ?></td>
                    <td data-value='<?php echo ($item['total_risk_absence_days'] / $results['totalDaysLost'])*100; ?>'><?php echo round(($item['total_risk_absence_days'] / $results['totalDaysLost'])*100, 2).'%'; ?></td>
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
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $total_1; ?></td>
                <td><?php echo $total_2; ?></td>
                <td><?php echo $total_3; ?></td>
                <td><?php echo $total_4; ?></td>
                <td><?php echo $total_5; ?></td>
                <td><?php echo round($total_6).'%'; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableWDLS','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_WORK_DAYS_LOST_SUMMARY_TITLE'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    </li>
    <li class="scalling" >
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_CONTRIBUTION_ALL_TITLE'); ?></div>
    <!-- tableWDLS_sf -->
    <table id="theTableWDLS_sf" class="table data tableWDLS_sf metro-blue" data-filter="#tableWDLS_sf">
        <thead>    
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORBIDITY_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_PRESENTEEISM_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_PRESENTEEISM_RISK_FACTOR_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORTALITY_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
                <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
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
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['total_absence_days_HAS_ISF']; ?>'><?php echo $item['total_absence_days_HAS_ISF']; ?></td>
                    <td data-value='<?php echo $item['total_unproductive_days_HAS_ISF']; ?>'><?php echo $item['total_unproductive_days_HAS_ISF']; ?></td>
                    <td data-value='0'>0</td>
                    <td data-value='<?php echo $item['total_death_absence_days_HAS_MSF']; ?>'><?php echo $item['total_death_absence_days_HAS_MSF']; ?></td>
                    <td data-value='<?php echo $item['total_days_lost_HAS_SF']; ?>'><?php echo $item['total_days_lost_HAS_SF']; ?></td>
                    <td data-value='<?php echo ($item['total_days_lost_HAS_SF'] / $results['totalDaysLost_HAS_SF'])*100; ?>'><?php echo round(($item['total_days_lost_HAS_SF'] / $results['totalDaysLost_HAS_SF'])*100, 2).'%'; ?></td>
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
                    <td><?php echo $item['risk_name']; ?></td>
                    <td data-value='0'>0</td>
                    <td data-value='0'>0</td>
                    <td data-value='<?php echo $item['total_risk_absence_days_HAS_SF']; ?>'><?php echo $item['total_risk_absence_days_HAS_SF']; ?></td>
                    <td data-value='0'>0</td>
                    <td data-value='<?php echo $item['total_risk_absence_days_HAS_SF']; ?>'><?php echo $item['total_risk_absence_days_HAS_SF']; ?></td>
                    <td data-value='<?php echo ($item['total_risk_absence_days_HAS_SF'] / $results['totalDaysLost_HAS_SF'])*100; ?>'><?php echo round(($item['total_risk_absence_days_HAS_SF'] / $results['totalDaysLost_HAS_SF'])*100, 2).'%'; ?></td>
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
                <td><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                <td><?php echo $total_1; ?></td>
                <td><?php echo $total_2; ?></td>
                <td><?php echo $total_3; ?></td>
                <td><?php echo $total_4; ?></td>
                <td><?php echo $total_5; ?></td>
                <td><?php echo round($total_6).'%'; ?></td>
            </tr>
        </tfoot>                                
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableWDLS_sf','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_WORK_DAYS_LOST_SUMMARY_TITLE'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    </li>
    <li class="episode" >
	<div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_CONTRIBUTION_ALL_TITLE'); ?></div>
    <!-- tableWDLS_oe -->
    <table id="theTableWDLS_oe" class="table data tableWDLS_oe metro-blue" data-filter="#tableWDLS_oe">
        <thead>    
            <tr >
                <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                <th data-hide="phone" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORBIDITY_LABEL'); ?></th>
                <th data-hide="phone" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_PRESENTEEISM_LABEL'); ?></th>
                <th data-hide="phone" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORTALITY_LABEL'); ?></th>
                <th data-hide="phone" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
                <th data-hide="phone" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
            </tr>        
        </thead>                                    
        <tbody>
        <?php if ($results['disease']) : ?>
            <?php foreach ($results['disease'] as $i => $item): ?>
                <tr>
                    <td><?php echo $item['disease_name']; ?></td>
                    <td data-value='<?php echo $item['total_absence_days_HAS_OE']; ?>'><?php echo $item['total_absence_days_HAS_OE']; ?></td>
                    <td data-value='<?php echo $item['total_unproductive_days_HAS_OE']; ?>'><?php echo $item['total_unproductive_days_HAS_OE']; ?></td>
                    <td data-value='<?php echo $item['total_death_absence_days_HAS_OE']; ?>'><?php echo $item['total_death_absence_days_HAS_OE']; ?></td>
                    <td data-value='<?php echo $item['total_days_lost_HAS_OE']; ?>'><?php echo $item['total_days_lost_HAS_OE']; ?></td>
                    <td data-value='<?php echo ($item['total_days_lost_HAS_OE'] / $results['totalDaysLost_HAS_OE'])*100; ?>'><?php echo round(($item['total_days_lost_HAS_OE'] / $results['totalDaysLost_HAS_OE'])*100, 2).'%'; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>                                         
        </tbody>                               
    </table>
    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableWDLS_oe','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_WORK_DAYS_LOST_SUMMARY_TITLE'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?>');">
    <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
    </button>
    
    </li>
</ul>