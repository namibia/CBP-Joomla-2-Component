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

/* <div class="uk-text-large"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_COST_SUMMARY_TITLE'); ?></div> */
?>
<!-- This is the subnav containing the toggling elements -->
<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#tableCS'}">
    <li class="uk-active uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></a></li>
</ul>
<br/>
<!-- This is the container of the content items -->
<?php if ($this->result->items) : ?>
<ul id="tableCS" class="uk-switcher">
	<?php foreach ($this->scale as $scale): ?>
        <li class="<?php echo ($scale == 'unscaled') ? 'uk-active default' : 'scalling'; ?>" >
            <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_COST_CONTRIBUTION_ALL_TITLE'); ?></div>
            <!-- tableCS -->
            <table id="theTableCS_<?php echo $scale; ?>" class="table data metro-blue tableCS_<?php echo $scale; ?>" data-filter="#tableCS_<?php echo $scale; ?>" data-page-size="50">
                <thead>        
                    <tr >
                        <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                        <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORBIDITY_LABEL'); ?></th>
                        <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_PRESENTEEISM_LABEL'); ?></th>
                        <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORTALITY_LABEL'); ?></th>
                        <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_COST_PER_DR_LABEL'); ?></th>
                        <th data-hide="phone" width="13%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_COST_PER_DR_LABEL'); ?></th>
                    </tr>        
                </thead>                                    
                <tbody>
				<?php foreach ($this->result->items as $i => &$item): ?>
                    <tr>
                        <td data-value='<?php echo $item->details->allias; ?>' scope="row"><?php echo $item->details->name; ?></td>
                        <td data-value='<?php echo $item->{'subtotal_cost_morbidity_'.$scale}; ?>' ><?php echo $item->{'subtotal_costmoney_morbidity_'.$scale}; ?></td>
                        <td data-value='<?php echo $item->{'subtotal_cost_presenteeism_'.$scale}; ?>' ><?php echo $item->{'subtotal_costmoney_presenteeism_'.$scale}; ?></td>
                        <td data-value='<?php echo $item->{'subtotal_cost_mortality_'.$scale}; ?>' ><?php echo $item->{'subtotal_costmoney_mortality_'.$scale}; ?></td>
                        <td data-value='<?php echo $item->{'subtotal_cost_'.$scale}; ?>' ><?php echo $item->{'subtotal_costmoney_'.$scale}; ?></td>
                        <td data-value='<?php echo ($item->{'subtotal_cost_'.$scale} / $this->result->totals->{'total_cost_'.$scale})*100; ?>' ><?php echo round(($item->{'subtotal_cost_'.$scale} / $this->result->totals->{'total_cost_'.$scale})*100,3).'%'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
                		<td><?php echo $this->result->totals->{'total_costmoney_morbidity_'.$scale}; ?></td>
                        <td><?php echo $this->result->totals->{'total_costmoney_presenteeism_'.$scale}; ?></td>
                        <td><?php echo $this->result->totals->{'total_costmoney_mortality_'.$scale}; ?></td>
                        <td><?php echo $this->result->totals->{'total_costmoney_'.$scale}; ?></td>
                        <td><?php echo round(($this->result->totals->{'total_cost_'.$scale} / $this->result->totals->{'total_cost_'.$scale})*100,3).'%'; ?></td>
                    </tr>
                </tfoot>                                
            </table> 
            <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getEXEL('#theTableCS_<?php echo $scale; ?>','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_COST_SUMMARY_TITLE'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
            <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
            </button>
    	</li>
	<?php endforeach; ?>
</ul>
<?php else: ?>
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASE_RISK_SELECTED'); ?></h2>
<?php endif; ?>