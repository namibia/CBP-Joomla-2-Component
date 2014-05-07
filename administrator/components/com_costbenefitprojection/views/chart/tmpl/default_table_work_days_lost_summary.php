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
<div class="hide hidden" id="giz_wdls">
<div id="view_wdls">
<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_WORK_DAYS_LOST_SUMMARY_TITLE'); ?></h1>

<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_CONTRIBUTION_ALL_TITLE'); ?>
<?php if ($this->result->items) : ?>
	<?php foreach ($scaled as $scale): ?>
        <table id="theTableWDLS_<?php echo $scale; ?>" class="table data metro-blue <?php echo $scale; ?>" style="display: <?php echo ($scale == 'unscaled') ? 'table' : 'none'; ?>;" data-page-size="50">
            <thead>        
                <tr >
                    <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                    <th width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORBIDITY_LABEL'); ?></th>
                    <th width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_PRESENTEEISM_LABEL'); ?></th>
                    <th width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORTALITY_LABEL'); ?></th>
                    <th width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
                    <th width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                    <th width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
                    <th width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
                </tr>        
            </thead>                                    
            <tbody>
			<?php foreach ($this->result->items as $i => &$item): ?>
                <tr>
                    <th data-value='<?php echo $item->details->allias; ?>' scope="row"><?php echo $item->details->name; ?></th>
                    <td data-value='<?php echo $item->{'subtotal_morbidity_'.$scale}; ?>' ><?php echo round($item->{'subtotal_morbidity_'.$scale},3); ?></td>
                    <td data-value='<?php echo $item->{'subtotal_presenteeism_'.$scale}; ?>' ><?php echo round($item->{'subtotal_presenteeism_'.$scale},3); ?></td>
                    <td data-value='<?php echo $item->{'subtotal_days_lost_mortality_'.$scale}; ?>' ><?php echo round($item->{'subtotal_days_lost_mortality_'.$scale},3); ?></td>
                    <td data-value='<?php echo $item->{'Males_days_lost_'.$scale}; ?>' ><?php echo round($item->{'Males_days_lost_'.$scale},3); ?></td>
                    <td data-value='<?php echo $item->{'Females_days_lost_'.$scale}; ?>' ><?php echo round($item->{'Females_days_lost_'.$scale},3); ?></td>
                    <td data-value='<?php echo $item->{'subtotal_days_lost_'.$scale}; ?>' ><?php echo round($item->{'subtotal_days_lost_'.$scale},3); ?></td>
                    <td data-value='<?php echo ($item->{'subtotal_days_lost_'.$scale} / $this->result->totals->{'total_days_lost_'.$scale})*100; ?>' ><?php echo round(($item->{'subtotal_days_lost_'.$scale} / $this->result->totals->{'total_days_lost_'.$scale})*100,3).'%'; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>
            
                    <td><?php echo round($this->result->totals->{'total_morbidity_'.$scale},3); ?></td>
                    <td><?php echo round($this->result->totals->{'total_presenteeism_'.$scale},3); ?></td>
                    <td><?php echo round($this->result->totals->{'total_days_lost_mortality_'.$scale},3); ?></td>
                    <td><?php echo round($this->result->totals->{'Males_days_lost_'.$scale},3); ?></td>
                    <td><?php echo round($this->result->totals->{'Females_days_lost_'.$scale},3); ?></td>
                    <td><?php echo round($this->result->totals->{'total_days_lost_'.$scale},3); ?></td>
                    <td><?php echo round(($this->result->totals->{'total_days_lost_'.$scale} / $this->result->totals->{'total_days_lost_'.$scale})*100,3).'%'; ?></td>
                </tr>
            </tfoot>                                
        </table>
    <?php endforeach; ?>
<?php else: ?>
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASE_RISK_SELECTED'); ?></h2>
<?php endif; ?>
</div>
</div>