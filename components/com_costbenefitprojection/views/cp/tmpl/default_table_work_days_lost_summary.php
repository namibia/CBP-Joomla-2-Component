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

/* <div class="uk-text-large"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_WORK_DAYS_LOST_SUMMARY_TITLE'); ?></div> */
?>
<!-- This is the subnav containing the toggling elements -->
<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#tableWDLS'}">
    <li class="uk-active uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></a></li>
</ul>
<br/>
<!-- This is the container of the content items -->
<?php if ($this->result->items) : ?>
<ul id="tableWDLS" class="uk-switcher">
	<?php foreach ($this->scale as $scale): ?>
        <li class="<?php echo ($scale == 'unscaled') ? 'uk-active default' : 'scalling'; ?>" >
        <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_TOTAL_CONTRIBUTION_ALL_TITLE'); ?></div>
            <table id="theTableWDLS_<?php echo $scale; ?>" class="table data tableWDLS_<?php echo $scale; ?> metro-blue" data-filter="#tableWDLS_<?php echo $scale; ?>" data-page-size="50">
                <thead>        
                    <tr >
                        <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                        <th data-hide="phone" width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORBIDITY_LABEL'); ?></th>
                        <th data-hide="phone" width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_PRESENTEEISM_LABEL'); ?></th>
                        <th data-hide="phone" width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MORTALITY_LABEL'); ?></th>
                        <th data-hide="all" width="5%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_MALE_EMPLOYEES_LABEL'); ?></th>
                        <th data-hide="all" width="5%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DAYS_FEMALE_EMPLOYEES_LABEL'); ?></th>
                        <th data-hide="phone" width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
                        <th data-hide="phone" width="11%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PERCENT_TOTAL_LOST_DAYS_PER_DR_LABEL'); ?></th>
                    </tr>        
                </thead>                                    
                <tbody>
				<?php foreach ($this->result->items as $i => &$item): ?>
                    <tr>
                        <td data-value='<?php echo $item->details->allias; ?>' scope="row"><?php echo $item->details->name; ?></td>
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
                        <td scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
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
            <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" 
            onclick="getEXEL('#theTableWDLS_<?php echo $scale; ?>','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_WORK_DAYS_LOST_SUMMARY_TITLE'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
            <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
            </button>
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASE_RISK_SELECTED'); ?></h2>
<?php endif; ?>