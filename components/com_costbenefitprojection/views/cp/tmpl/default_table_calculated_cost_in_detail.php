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

/* <div class="uk-text-large"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLES_CALCULATED_COST_IN_DETAIL_TITLE'); ?></div> */
?>
<!-- This is the subnav containing the toggling elements -->
<?php if($this->not_basic) : ?>
<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#tableCCID'}">
    <li class="uk-active uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></a></li>
</ul>
<br/>
<?php endif; ?>
<!-- This is the container of the content items -->
<?php if ($this->result->items) : ?>
<ul id="tableCCID" class="uk-switcher">
	<?php foreach ($this->scale as $scale): ?>
        <li class="<?php echo ($scale == 'unscaled') ? 'uk-active default' : 'scalling'; ?>" >
        <!-- table -->
        <div class="uk-text-small uk-container-center uk-animation-fade"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_ABSENCE_DAYS_SICKNESS_TITLE'); ?></div>
        <table id="theTableCCID_<?php echo $scale; ?>" class="table data tableCCID_<?php echo $scale; ?> metro-blue" data-filter="#tableCCID_<?php echo $scale; ?>" data-page-size="50">
            <thead>        
                <tr >
                    <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'); ?></th>
                    <th data-hide="phone" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MALE_EMPLOYEES_LABEL'); ?></th>
                    <th data-hide="phone" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_COST_FEMALE_EMPLOYEES_LABEL'); ?></th>
                    <th data-hide="phone" width="15%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTAL_COSTS_LABEL'); ?></th>
                    <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_DETAILS_LABEL'); ?></th> 
                </tr>        
            </thead>                                    
            <tbody>
            <?php foreach ($this->result->items as $i => &$item): ?>
                <tr>
                    <td data-value='<?php echo $item->details->allias; ?>' scope="row"><?php echo $item->details->name; ?></td>
                    <td data-value='<?php echo $item->{'Males_cost_'.$scale}; ?>' ><?php echo $item->{'Males_costmoney_'.$scale}; ?></td>
                    <td data-value='<?php echo $item->{'Females_cost_'.$scale}; ?>' ><?php echo $item->{'Females_costmoney_'.$scale}; ?></td>
                    <td data-value='<?php echo $item->{'subtotal_cost_'.$scale}; ?>' ><?php echo $item->{'subtotal_costmoney_'.$scale}; ?></td>
                    <td>
                    <div class="uk-grid">
						<?php foreach ($this->genders as $gender): ?>  
                                <?php
                                    $list[$gender] = '<div style="float:left;"><ul class="uk-list uk-list-striped" >';
                                    foreach ($item->$gender as $a => &$group){
                                        $list[$gender] .= '<li>'.$gender.' Age Group '. $a . '<br/><small>';
                                        $list[$gender] .= "&nbsp;&raquo;&nbsp;" . JText::_('YLD') . ": <b>" . $group->yld. "</b><br/>";
                                        $list[$gender] .= "&nbsp;&raquo;&nbsp;" . JText::_('Death') . ": <b>" . $group->death. "</b><br/>";
                                        $list[$gender] .= "&nbsp;&raquo;&nbsp;" . JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORBIDITY_LABEL') . ": <b>" . $group->{'costmoney_morbidity_'.$scale}. "</b><br/>";
                                        $list[$gender] .= "&nbsp;&raquo;&nbsp;" . JText::_('COM_COSTBENEFITPROJECTION_CT_COST_PRESENTEEISM_LABEL') . ": <b>" . $group->{'costmoney_presenteeism_'.$scale}. "</b><br/>";
                                        $list[$gender] .= "&nbsp;&raquo;&nbsp;" . JText::_('COM_COSTBENEFITPROJECTION_CT_COST_MORTALITY_LABEL') . ": <b>" . $group->{'costmoney_mortality_'.$scale}. "</b></small>";
                                        $list[$gender] .= '</li>';
                                    }
                                    $list[$gender] .= '</ul></div>';
                                    echo $list[$gender];
                                ?>
                        <?php endforeach; ?>
                    </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td scope="row"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_TOTALS_LABEL'); ?></td>
            		<td><?php echo $this->result->totals->{'Males_costmoney_'.$scale}; ?></td>
                    <td><?php echo $this->result->totals->{'Females_costmoney_'.$scale}; ?></td>
                    <td><?php echo $this->result->totals->{'total_costmoney_'.$scale}; ?></td>
                </tr>
            </tfoot>                                
        </table>
        <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" 
        onclick="getEXEL('#theTableCCID_<?php echo $scale; ?>','<?php echo JText::_('COM_COSTBENEFITPROJECTION_TABLE_COST_DUE_ABSENCE_DAYS_SICKNESS_TITLE_EXCEL'); ?> <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>');">
        <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_EXEL_DOWNLOAD'); ?>
        </button>
    </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASE_RISK_SELECTED'); ?></h2>
<?php endif; ?>