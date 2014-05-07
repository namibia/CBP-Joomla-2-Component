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

/* <div class="uk-text-large"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NET_BENEFIT_TITLE'); ?></div> */
?>
<!-- This is the subnav containing the toggling elements -->
<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#tableINB'}">
    <li class="uk-active uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></a></li>
</ul>
<br/>

<!-- This is the container of the content items -->
<?php if($this->result->interventions): ?>
    <ul id="tableINB" class="uk-switcher">
        <?php foreach ($this->scale as &$scale): ?>
            <li class="<?php echo ($scale == 'unscaled') ? 'uk-active default' : 'scalling'; ?>" >
                <?php foreach ($this->result->interventions as $intervention) :?>
                 <div class="uk-text-small uk-container-center uk-animation-fade">
                    <?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NAME_TITLE', $intervention->name); ?> | 
                    <?php 
                        if($intervention->duration > 1){
                            echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATIONS_TITLE', $intervention->duration); 
                        } else {
                            echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATION_TITLE', $intervention->duration);
                        } 
                    ?> | 
                    <?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_COVERAGE_TITLE', round($intervention->coverage)); ?>%
                </div>
                <table 	id="theTableINB_<?php echo $intervention->id ?>_<?php echo $scale; ?>"  
                    class="table data tableINB_<?php echo $intervention->id ?>_<?php echo $scale; ?> metro-blue" 
                    data-filter="#tableINB_<?php echo $intervention->id ?>_<?php echo $scale; ?>" 
                    data-page-size="50" >
                <thead>
                    <tr >
                        <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                        <th data-hide="phone" width="7%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_CONTRIBUTION_COST'); ?></th>
                        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_PER_EMPLOYEE'); ?></th>
                        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_REDUCTION_MORBIDITY'); ?></th>
                        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_REDUCTION_MORTALITY'); ?></th>
                        <th data-hide="phone" width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_PROBLEM'); ?></th>
                        <th data-hide="phone" width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_INTERVENTION'); ?></th>
                        <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_BENEFIT'); ?></th>
                        <th data-hide="phone" width="5%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_BENEFIT_RATIO'); ?></th>
                        <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_NET_BENEFIT'); ?></th>
                    </tr>
                </thead>                                    
                <tbody>
                <?php if(is_object($intervention->items) || is_array($intervention->items)):?>
                    <?php foreach ($intervention->items as $key => &$item): ?>
                        <tr>
                            <td data-value='<?php echo $item->allias; ?>' scope="row"><?php echo $item->name; ?></td>
                            <td data-value='<?php echo $item->{'contribution_to_cost_'.$scale}; ?>' ><?php echo round($item->{'contribution_to_cost_'.$scale}, 3); ?>%</td>
                            <td data-value='<?php echo $item->cpe; ?>' ><?php echo $item->annual_costmoney_per_employee; ?></td>
                            <td data-value='<?php echo $item->mbr; ?>' ><?php echo $item->mbr; ?>%</td>
                            <td data-value='<?php echo $item->mtr; ?>' ><?php echo $item->mtr; ?>%</td>
                            <td data-value='<?php echo $item->{'cost_of_problem_'.$scale}; ?>' ><?php echo $item->{'costmoney_of_problem_'.$scale}; ?></td>
                            <td data-value='<?php echo $item->annual_cost; ?>' ><?php echo $item->annual_costmoney; ?></td>
                            <td data-value='<?php echo $item->{'annual_benefit_'.$scale}; ?>' ><?php echo $item->{'annualmoney_benefit_'.$scale}; ?></td>
                            <td data-value='<?php echo $item->{'benefit_ratio_'.$scale}; ?>' >1:<?php echo round($item->{'benefit_ratio_'.$scale}); ?></td>
                            <td data-value='<?php echo $item->{'net_benefit_'.$scale}; ?>' ><?php echo $item->{'netmoney_benefit_'.$scale}; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>                                       
                </tbody>
                <tfoot>
                    <tr>
                        <td scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                        <td><?php echo $intervention->totals->{'contribution_to_cost_'.$scale}; ?>%</td>
                        <td><?php echo $intervention->totals->annual_costmoney_per_employee; ?></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $intervention->totals->{'costmoney_of_problem_'.$scale}; ?></td>
                        <td><?php echo $intervention->totals->annual_costmoney; ?></td>
                        <td><?php echo $intervention->totals->{'annualmoney_benefit_'.$scale}; ?></td>
                        <td></td>
                        <td><?php echo $intervention->totals->{'netmoney_benefit_'.$scale}; ?></td>
                    </tr>
                </tfoot>                                
            </table>
            <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" 
            onclick="getEXEL('#theTableINB_<?php echo $intervention->id ?>_<?php echo $scale; ?>','<?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NAME_TITLE_EXCEL', $intervention->name); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
            <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
            </button>
            <br/><br/> 
            <?php endforeach; ?>   
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?> 
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_INTERVENTION_SELECTED'); ?></h2>
<?php endif; ?>