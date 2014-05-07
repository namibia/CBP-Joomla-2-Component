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
$scaled = array('unscaled','scaled');

?>
<div class="hide hidden" id="giz_inb">
<div id="view_inb">
<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NET_BENEFIT_TITLE'); ?></h1>

<?php if($this->result->interventions): ?>
    <?php foreach ($this->result->interventions as $intervention) :?>
		<?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NAME_TITLE', $intervention->name); ?> | 
        <?php 
            if($intervention->duration > 1){
                echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATIONS_TITLE', $intervention->duration); 
            } else {
                echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATION_TITLE', $intervention->duration);
            } 
        ?> | 
        <?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_COVERAGE_TITLE', round($intervention->coverage)); ?>%
    
		<?php foreach ($scaled as $scale): ?>
            <table  id="tableINT_<?php echo $intervention->id ?>_<?php echo $scale; ?>"  
                    class="table data metro-blue <?php echo $scale; ?>" 
                    style="display: <?php echo ($scale == 'unscaled') ? 'table' : 'none'; ?>;" 
                    data-page-size="50" >
                <thead>
                    <tr >
                        <th><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                        <th width="8%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_CONTRIBUTION_COST'); ?></th>
                        <th width="8%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_PER_EMPLOYEE'); ?></th>
                        <th width="5%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_REDUCTION_MORBIDITY'); ?></th>
                        <th width="5%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_REDUCTION_MORTALITY'); ?></th>
                        <th width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_PROBLEM'); ?></th>
                        <th width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_INTERVENTION'); ?></th>
                        <th width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_BENEFIT'); ?></th>
                        <th width="5%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_BENEFIT_RATIO'); ?></th>
                        <th width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_NET_BENEFIT'); ?></th>
                    </tr>
                </thead>                                    
                <tbody>
                <?php if(is_object($intervention->items) || is_array($intervention->items)):?>
                    <?php foreach ($intervention->items as $key => &$item): ?>
                        <tr>
                            <th data-value='<?php echo $item->allias; ?>' scope="row"><?php echo $item->name; ?></th>
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
                        <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>
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
        <?php endforeach; ?>
	<?php endforeach; ?>
<?php else: ?> 
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_INTERVENTION_SELECTED'); ?></h2>
<?php endif; ?>
</div>
</div>