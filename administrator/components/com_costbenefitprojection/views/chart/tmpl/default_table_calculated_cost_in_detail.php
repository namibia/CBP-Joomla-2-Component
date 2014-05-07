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
<div class="hide hidden" id="giz_ccid">
<div id="view_ccid">
<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_CALCULATED_COST_IN_DETAIL_TITLE'); ?></h1>

<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_PRESENTEEISM_SICKNESS_TITLE'); ?>
<?php if ($this->result->items) : ?>
	<?php foreach ($scaled as $scale): ?>
        <table id="theTableCCID_<?php echo $scale; ?>" class="table data metro-blue <?php echo $scale; ?>" style="display: <?php echo ($scale == 'unscaled') ? 'table' : 'none'; ?>;" data-page-size="50">
            <thead>        
                <tr >
                    <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                    <th width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
                    <th width="20%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
                    <th width="25%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
                </tr>        
            </thead>                                    
            <tbody>
			<?php foreach ($this->result->items as $i => &$item): ?>
                <tr>
                    <th data-value='<?php echo $item->details->allias; ?>' scope="row"><?php echo $item->details->name; ?></th>
                    <td data-value='<?php echo $item->{'Males_cost_'.$scale}; ?>' ><?php echo $item->{'Males_costmoney_'.$scale}; ?></td>
                    <td data-value='<?php echo $item->{'Females_cost_'.$scale}; ?>' ><?php echo $item->{'Females_costmoney_'.$scale}; ?></td>
                    <td data-value='<?php echo $item->{'subtotal_cost_'.$scale}; ?>' ><?php echo $item->{'subtotal_costmoney_'.$scale}; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></th>
            
                    <td><?php echo $this->result->totals->{'Males_costmoney_'.$scale}; ?></td>
                    <td><?php echo $this->result->totals->{'Females_costmoney_'.$scale}; ?></td>
                    <td><?php echo $this->result->totals->{'total_costmoney_'.$scale}; ?></td>
                </tr>
            </tfoot>                                
        </table>
    <?php endforeach; ?>
<?php else: ?>
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASE_RISK_SELECTED'); ?></h2>
<?php endif; ?>
</div>
</div>