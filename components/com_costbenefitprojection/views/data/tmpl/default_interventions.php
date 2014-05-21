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
defined( '_JEXEC' ) or die;

// Set Disease
$interventions = $this->item["data"]["interventiondata"];
?>

<nav class="uk-navbar">
    <div class="uk-navbar-content uk-navbar-flip">
    <form class="uk-form uk-margin-remove uk-display-inline-block form-validate" action="index.php?option=com_costbenefitprojection&view=intervention&layout=edit<?php echo $this->temp; ?>" method="post">
        <button type="button" class="uk-button uk-button-small" style="margin-bottom: 10px;" onclick="Joomla.submitbutton('intervention.add')" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_ADD_INTERVENTION');  ?></button>
        <input type="hidden" name="task" value="" />
    </form>
	</div>
</nav>
<div class="uk-width-1-1">
    <div class="uk-float-left">
        <p>
            <?php echo JText::_('COM_COSTBENEFITPROJECTION_SEARCH');  ?>: <input id="filter3" type="text"/>
            <a class="uk-button uk-button-small clear-filter" href="#clear" title="clear filter"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CLEAR');  ?></a>
        </p>
    </div>
    <div class="uk-float-right">
        <p>
            <?php echo JText::_('COM_COSTBENEFITPROJECTION_STATUS');  ?>: <select id="status3" class="filter-status">
            <option value=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_ALL');  ?></option>
            <option value="<?php echo JText::_('COM_COSTBENEFITPROJECTION_PUBLISHED_LOWERCASE');  ?>"><?php echo JText::_('COM_COSTBENEFITPROJECTION_PUBLISHED');  ?></option>
            <option value="<?php echo JText::_('COM_COSTBENEFITPROJECTION_INACTIVE_LOWERCASE');  ?>"><?php echo JText::_('COM_COSTBENEFITPROJECTION_INACTIVE');  ?></option>
            <option value="<?php echo JText::_('COM_COSTBENEFITPROJECTION_ARCHIVED_LOWERCASE');  ?>"><?php echo JText::_('COM_COSTBENEFITPROJECTION_ARCHIVED');  ?></option>
            <option value="<?php echo JText::_('COM_COSTBENEFITPROJECTION_TRASHED_LOWERCASE');  ?>"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TRASHED');  ?></option>
            </select>
        </p>
    </div>
</div>
<div class="uk-clearfix"></div>

<table class="table data three metro-blue" data-filter="#filter3" data-page-size="12">
    
    <thead>
        <tr>
            <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_INTERVENTION_NAME_LABEL'); ?></th>
            
            <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DURATION_LABEL'); 
                            $td = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DURATION_DESC');
                            ?></th>
            <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COVERAGE_LABEL'); 
                            $td = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COVERAGE_DESC');
                            ?></th>
                            
            <th><?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_FIELD_APPLICABLE_TO_NAME_LABEL') ?></th>
            <th data-hide="phone,tablet"><?php echo JText::_('COM_COSTBENEFITPROJECTION_OWNER'); ?></th>
            <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_FIELD_APPLICABLE_TO_NAME_LABEL') ?></th>
            <th data-hide="phone"><?php echo JText::_('JSTATUS'); ?></th>
        </tr>
    </thead>
    
    <tbody>
    <?php if ($interventions) : ?>
 		<?php foreach ($interventions as $i => $item): ?>
				<tr class="row<?php echo $i % 2 ?>">
                	<td class="center">
						<?php if ( $item["owner_id"] == $this->user['id'] ) : ?>
                            <?php if ($item["checked_out"]) : ?>
                                <?php
                                    $d 	= '<i class="uk-icon-lock"></i>';
                                    $tt = 'Checked out';
                                    $td = 'by '.JFactory::getUser($item["checked_out"])->name;
                                    echo JHTML::tooltip($td, $tt, '', $d );
                                ?>
                                <?php if ($item["checked_out"] == $this->user['id']) : ?>
                                    <a href="index.php?option=com_costbenefitprojection&task=intervention.edit&intervention_id=<?php echo $item["intervention_id"]; ?><?php echo $this->temp; ?>" title="edit">
                                        <?php echo $this->escape($item["intervention_name"]) ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo $this->escape($item["intervention_name"]); ?>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="index.php?option=com_costbenefitprojection&task=intervention.edit&intervention_id=<?php echo $item["intervention_id"]; ?><?php echo $this->temp; ?>" title="edit">
                                    <?php echo $this->escape($item["intervention_name"]) ?>
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php echo $this->escape($item["disease_name"]); ?>
                        <?php endif; ?>
						<?php 
                            if ($item["type"] == 2){
                                $d 	= ' <span style="cursor:help;" >[C]</span> ';
                                $tt = 'Cluster Intervention';
                                $td = '';
                                echo JHTML::tooltip($td, $tt, '', $d );
                            } 
                        ?>
                    </td>
                    <?php if ($item["access"] != 3 || $this->user['id'] == $item["owner_id"]) : ?>
                        <td class="center"><?php echo $this->escape($item["duration"]) ?></td>
                        <td class="center"><?php echo $this->escape($item["coverage"]) ?></td>
                    <?php else : ?>
                    	<td class="center">&#35;</td>
                    	<td class="center">&#35;</td>
                    <?php endif; ?>
					<td class="center">
						<?php if(is_array($item["diseasedata"])):?>  
                            <?php 
                                if ($item["params"]["nr_diseases"] <= 1) {
                                    $Dtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_FIELD_DISEASE_NAME_LABEL');
                                    $TTtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DISEASE_LIST_LABEL');
                                } else {
                                    $Dtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_FIELD_DISEASES_NAME_LABEL');
                                    $TTtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DISEASES_LIST_LABEL');
                                }
                                $list = '<ul>';
                                foreach ($item["diseasedata"] as $disease){
                                    $list .= '<li>';
                                    $list .= $disease["name"] . '<br />';
                                    $list .= '</li>';
                                }
                                $list .= '</ul>';
                                $d = ' <span style="cursor:help;" >[ '.$item["params"]["nr_diseases"].' ] '.$Dtext.'</span> ';
                                $tt =  $TTtext;
                                $td = $list;
                                echo JHTML::tooltip($td, $tt, '', $d );
                            ?>
                        <?php endif; ?>
                        <?php if(is_array($item["diseasedata"]) && is_array($item["riskdata"])):?>
                        &nbsp;&nbsp;&amp;&nbsp;&nbsp;
                        <?php endif; ?>
                        <?php if(is_array($item["riskdata"])):?>  
                            <?php 
                                if ($item["params"]["nr_risks"] <= 1) {
                                    $Dtext = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_NAME_LABEL');
                                    $TTtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_RISK_LIST_LABEL');
                                } else {
                                    $Dtext = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISKS_NAME_LABEL');
                                    $TTtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_RISKS_LIST_LABEL');
                                }
                                $list = '<ul>';
                                foreach ($item["riskdata"] as $risk){
                                    $list .= '<li>';
                                    $list .= $risk["name"] . '<br />';
                                   	$list .= '</li>';
                                }
                                $list .= '</ul>';
                                $d = ' <span style="cursor:help;" >[ '.$item["params"]["nr_risks"].' ] '.$Dtext.'</span> ';
                                $tt =  $TTtext;
                                $td = $list;
                                echo JHTML::tooltip($td, $tt, '', $d );
                            ?>
                        <?php endif; ?>
                    </td>
                    <td class="center"><?php echo $item["owner"]; ?></td>
                    <td class="center">
                    <?php if(is_array($item["diseasedata"])):?>  
						<?php
                            if ($item["params"]["nr_diseases"] <= 1) {
                                $TTtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DISEASE_LIST_LABEL');
                            } else {
                                $TTtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DISEASES_LIST_LABEL');
                            }
                            $list = '<ul>';
                            foreach ($item["diseasedata"] as $disease){
                                $list .= '<li>';
                                $list .= $disease["name"] . '<br/><small><i>';
                                $list .= " (" . JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COST_PER_EMPLOYEE_LABEL') . ": " . $disease["cpe"]. ")<br/>";
                                $list .= " (" . JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_MORB_REDUCTION_LABEL') . ": " . $disease["mbr"]. ")<br/>";
                                $list .= " (" . JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_MORTALITY_REDUCTION_LABEL') . ": " . $disease["mtr"]. ")</i></small>";
                                $list .= '</li>';
                            }
                            $list .= '</ul>';
                            echo '<b>'.$TTtext.'</b><br/>'.$list;
                        ?>
                    <?php endif; ?>
                    </td>
                    <?php if ($item["published"] == 1):?>
                    <td class="center"  data-value="1">
                        <span class="status-metro status-published" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_PUBLISHED');  ?>">
                            <span style="display:none;"><?php echo JText::_('COM_COSTBENEFITPROJECTION_PUBLISHED');  ?></span>
                            <i class="uk-icon-check"></i>
                        </span>
                    </td>
                    <?php elseif ($item["published"] == 0): ?>
                    <td class="center"  data-value="2">
                    	<span class="status-metro status-inactive" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_INACTIVE');  ?>">
                        	<span style="display:none;"><?php echo JText::_('COM_COSTBENEFITPROJECTION_INACTIVE');  ?></span>
                            <i class="uk-icon-ban"></i>
                        </span>
                    </td>
                    <?php elseif ($item["published"] == 2): ?>
                    <td class="center"  data-value="3">
                    	<span class="status-metro status-archived" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_ARCHIVED');  ?>">
                            <span style="display:none;"><?php echo JText::_('COM_COSTBENEFITPROJECTION_ARCHIVED');  ?></span>
                            <i class="uk-icon-archive"></i>
                        </span>
                    </td>
                    <?php elseif ($item["published"] == -2): ?>
                    <td class="center"  data-value="4">
                        <span class="status-metro status-trashed" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_TRASHED');  ?>">
                            <span style="display:none;"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TRASHED');  ?></span>
                            <i class="uk-icon-trash-o"></i>
                        </span>
                    </td>
                    <?php endif; ?>  
				</tr>
		<?php endforeach; ?>
	<?php else: ?>
        <tr>
            <td colspan="9" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DATA'); ?></td>
        </tr>
    <?php endif; ?>
	</tbody>
    
    <tfoot class="hide-if-no-paging">
        <tr>
          <td colspan="9">
            <div class="pagination pagination-centered"></div>
          </td>
        </tr>
     </tfoot>
</table>