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
defined('_JEXEC') or die;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>
<form action="index.php?option=com_costbenefitprojection&view=interventions" method="post" name="adminForm" id="adminForm">

	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="Search" />

			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>

		<div class="filter-select fltrt">
        		<label for="filter_state">
                    <?php echo JText::_('COM_COSTBENEFITPROJECTION_FILTER_INTERVENTIONS_LABEL'); ?>
                </label>
			<?php if ($this->user['type'] == 'admin') : ?>
                <select name="filter.country" class="inputbox" onchange="this.form.submit()">
                    <option value="0"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FILTER_COUNTRY');?></option>
                    <?php echo JHtml::_('select.options', UsersHelper::getCountry(), 'value', 'text', $this->state->get('filter.country'));?> 
                </select>
                <select name="filter.member" class="inputbox" onchange="this.form.submit()">
                    <option value="0"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FILTER_MEMBERS');?></option>
                    <?php echo JHtml::_('select.options', UsersHelper::getMembers(), 'value', 'text', $this->state->get('filter.member'));?> 
                </select>
			<?php elseif($this->user['type'] == 'country'): ?>
                <select name="filter.member" class="inputbox" onchange="this.form.submit()">
                    <option value="0"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FILTER_MEMBERS');?></option>
                    <?php echo JHtml::_('select.options', UsersHelper::getMembers(NULL,$this->user['country']), 'value', 'text', $this->state->get('filter.member'));?> 
                </select>
            <?php elseif($this->user['type'] == 'service'): ?>
                <select name="filter.member" class="inputbox" onchange="this.form.submit()">
                    <option value="0"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FILTER_MEMBERS');?></option>
                    <?php echo JHtml::_('select.options', UsersHelper::getMembers($this->user['id']), 'value', 'text', $this->state->get('filter.member'));?> 
                </select>
            <?php endif; ?>
                <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                    <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                    <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
                </select>
		</div>

	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%"><input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /></th>
				<th><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_INTERVENTION_NAME_LABEL', 't.intervention_name', $listDirn, $listOrder) ?></th>
                
                <th width="7%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_INTERVENTION_DURATION_SHORT', 't.duration', $listDirn, $listOrder);
								$d = ' <span style="cursor:help;" >&nbsp;&nbsp;<b><span style="color:#008ED4;">&#105;</span></b>&nbsp;</span>';
								$tt = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DURATION_LABEL'); 
								$td = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DURATION_DESC');
								echo JHTML::tooltip($td, $tt, '', $d );
								?></th>
                <th width="7%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_INTERVENTION_COVERAGE_SHORT', 't.coverage', $listDirn, $listOrder);
								$d = ' <span style="cursor:help;" >&nbsp;&nbsp;<b><span style="color:#008ED4;">&#105;</span></b>&nbsp;</span>';
								$tt = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COVERAGE_LABEL'); 
								$td = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COVERAGE_DESC');
								echo JHTML::tooltip($td, $tt, '', $d );
								?></th>
                                
				<th><?php echo JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_FIELD_APPLICABLE_TO_NAME_LABEL') ?></th>
                <th width="10%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_OWNER'); ?></th>
                <th width="10%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_CREATEDBY_LABEL', 't.created_by', $listDirn, $listOrder) ?></th>
				<th width="10%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_CREATEDON_LABEL', 't.created_on', $listDirn, $listOrder) ?></th>
                <th width="10%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_MODIFIEDBY_LABEL', 't.modified_by', $listDirn, $listOrder) ?></th>
				<th width="10%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_MODIFIEDON_LABEL', 't.modified_on', $listDirn, $listOrder) ?></th>
                <th width="4%"><?php echo JHtml::_('grid.sort', 'JSTATUS', 't.published', $listDirn, $listOrder) ?></th>
                <th width="2%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_ID_LABEL', 't.intervention_id', $listDirn, $listOrder) ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="12">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
        <?php //echo '<pre>'; var_dump($this->items); ?>
        	<?php foreach ($this->items as $i => $item): ?>
				<tr class="row<?php echo $i % 2 ?>">
                	<td class="center">
                    <?php if ($item->access != 3 || $this->user['type'] == 'admin' || $this->user['type'] == 'country' || $this->user['id'] == JUserHelper::getProfile($item->owner_id)->gizprofile[serviceprovider] ) : ?>
						<?php if ($item->checked_out) : ?>
                            <?php if ($item->checked_out == $this->user['id'] || $this->user['type'] == 'admin') : ?>
                            	<?php echo JHtml::_('grid.id', $i, $item->intervention_id); ?>
                            <?php else: ?>
                            	&#35;
                            <?php endif; ?>
                        <?php else: ?>
                        	<?php echo JHtml::_('grid.id', $i, $item->intervention_id); ?>
                        <?php endif; ?>
                    <?php else: ?>
                        &#35;
                    <?php endif; ?>
                    </td>
					<td class="center">
                    <?php if ($item->access != 3 || $this->user['type'] == 'admin' || $this->user['type'] == 'country' || $this->user['id'] == JUserHelper::getProfile($item->owner_id)->gizprofile[serviceprovider]) : ?>
						<?php if ($item->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->name, $item->checked_out_time, 'interventions.'); ?>
                            <?php if ($item->checked_out == $this->user['id']) : ?>
                           		<a href="<?php echo $item->url; ?>">
                                     <?php echo $this->escape($item->intervention_name) ?>
                                </a>
                                <?php 
									if ($item->type == 2){
										$d 	= ' <span style="cursor:help;" >[C]</span> ';
										$tt = 'Cluster Intervention';
										$td = '';
										echo JHTML::tooltip($td, $tt, '', $d );
									} 
								?>
							<?php else: ?>
                                <?php echo $this->escape($item->intervention_name) ?>
                                <?php 
									if ($item->type == 2){
										$d 	= ' <span style="cursor:help;" >[C]</span> ';
										$tt = 'Cluster Intervention';
										$td = '';
										echo JHTML::tooltip($td, $tt, '', $d );
									} 
								?>
                            <?php endif; ?>
						<?php else: ?>
							<a href="<?php echo $item->url; ?>">
                            	 <?php echo $this->escape($item->intervention_name) ?>
                            </a>
							<?php 
                                if ($item->type == 2){
                                    $d 	= ' <span style="cursor:help;" >[C]</span> ';
                                    $tt = 'Cluster Intervention';
                                    $td = '';
                                    echo JHTML::tooltip($td, $tt, '', $d );
                                } 
                            ?>
                        <?php endif; ?>
                    <?php else : ?>
                    	<?php echo $this->escape($item->intervention_name) ?>
						<?php 
                            if ($item->type == 2){
                                $d 	= ' <span style="cursor:help;" >[C]</span> ';
                                $tt = 'Cluster Intervention';
                                $td = '';
                                echo JHTML::tooltip($td, $tt, '', $d );
                            } 
                        ?>
                    <?php endif; ?>
                    </td>
                    <?php if ($item->access != 3 || $this->user['type'] == 'admin' || $this->user['type'] == 'country' || $this->user['id'] == JUserHelper::getProfile($item->owner_id)->gizprofile[serviceprovider]) : ?>
                        <td class="center"><?php echo $this->escape($item->duration) ?></td>
                        <td class="center"><?php echo $this->escape($item->coverage) ?></td>
                    <?php else : ?>
                    	<td class="center">&#35;</td>
                    	<td class="center">&#35;</td>
                    <?php endif; ?>
					<td class="center">
                    <?php if ($item->access != 3 || $this->user['type'] == 'admin' || $this->user['type'] == 'country' || $this->user['id'] == JUserHelper::getProfile($item->owner_id)->gizprofile[serviceprovider]) : ?>
						<?php if(is_array($item->diseasedata)):?>  
                            <?php 
                                if ($item->params["nr_diseases"] <= 1) {
                                    $Dtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_FIELD_DISEASE_NAME_LABEL');
                                    $TTtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DISEASE_LIST_LABEL');
                                } else {
                                    $Dtext = JText::_('COM_COSTBENEFITPROJECTION_DISEASES_MENU_DEFAULT_TITLE');
                                    $TTtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DISEASES_LIST_LABEL');
                                }
                                $list = '<ul>';
                                foreach ($item->diseasedata as $disease){
                                    $list .= '<li>';
                                    $list .= $disease["name"] . '<br />';
                                    $list .= " (" . JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COST_PER_EMPLOYEE_LABEL') . ": " . $disease["cpe"]. ") ";
                                    $list .= " (" . JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_MORB_REDUCTION_LABEL') . ": " . $disease["mbr"]. ") ";
                                    $list .= " (" . JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_MORTALITY_REDUCTION_LABEL') . ": " . $disease["mtr"]. ")<br /><br />";
                                    $list .= '</li>';
                                }
                                $list .= '</ul>';
                                $d = ' <span style="cursor:help;" >[ '.$item->params["nr_diseases"].' ] '.$Dtext.'</span> ';
                                $tt =  $TTtext;
                                $td = $list;
                                echo JHTML::tooltip($td, $tt, '', $d );
                            ?>
                        <?php endif; ?>
                        <?php if(is_array($item->diseasedata) && is_array($item->riskdata)):?>
                        &nbsp;&nbsp;&amp;&nbsp;&nbsp;
                        <?php endif; ?>
                        <?php if(is_array($item->riskdata)):?>  
                            <?php 
                                if ($item->params["nr_risks"] <= 1) {
                                    $Dtext = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_NAME_LABEL');
                                    $TTtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_RISK_LIST_LABEL');
                                } else {
                                    $Dtext = JText::_('COM_COSTBENEFITPROJECTION_RISKS_MENU_DEFAULT_TITLE');
                                    $TTtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_RISKS_LIST_LABEL');
                                }
                                $list = '<ul>';
                                foreach ($item->riskdata as $risk){
                                    $list .= '<li>';
                                    $list .= $risk["name"] . '<br />';
                                    $list .= " (" . JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_COST_PER_EMPLOYEE_LABEL') . ": " . $risk["cpe"]. ") ";
                                    $list .= " (" . JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_MORB_REDUCTION_LABEL') . ": " . $risk["mbr"]. ")<br /><br />";
                                    $list .= '</li>';
                                }
                                $list .= '</ul>';
                                $d = ' <span style="cursor:help;" >[ '.$item->params["nr_risks"].' ] '.$Dtext.'</span> ';
                                $tt =  $TTtext;
                                $td = $list;
                                echo JHTML::tooltip($td, $tt, '', $d );
                            ?>
                        <?php endif; ?>
                    <?php else: ?>
						<?php if(is_array($item->diseasedata)):?>  
                            <?php 
                                if ($item->params["nr_diseases"] <= 1) {
                                    $Dtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_FIELD_DISEASE_NAME_LABEL');
                                    $TTtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DISEASE_LIST_LABEL');
                                } else {
                                    $Dtext = JText::_('COM_COSTBENEFITPROJECTION_DISEASES_MENU_DEFAULT_TITLE');
                                    $TTtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_DISEASES_LIST_LABEL');
                                }
                                $list = '<ul>';
                                foreach ($item->diseasedata as $disease){
                                    $list .= '<li>';
                                    $list .= $disease["name"] . '<br />';
                                    $list .= '</li>';
                                }
                                $list .= '</ul>';
                                $d = ' <span style="cursor:help;" >[ '.$item->params["nr_diseases"].' ] '.$Dtext.'</span> ';
                                $tt =  $TTtext;
                                $td = $list;
                                echo JHTML::tooltip($td, $tt, '', $d );
                            ?>
                        <?php endif; ?>
                        <?php if(is_array($item->diseasedata) && is_array($item->riskdata)):?>
                        &nbsp;&nbsp;&amp;&nbsp;&nbsp;
                        <?php endif; ?>
                        <?php if(is_array($item->riskdata)):?>  
                            <?php 
                                if ($item->params["nr_risks"] <= 1) {
                                    $Dtext = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_NAME_LABEL');
                                    $TTtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_RISK_LIST_LABEL');
                                } else {
                                    $Dtext = JText::_('COM_COSTBENEFITPROJECTION_RISKS_MENU_DEFAULT_TITLE');
                                    $TTtext = JText::_('COM_COSTBENEFITPROJECTION_INTERVENTION_RISKS_LIST_LABEL');
                                }
                                $list = '<ul>';
                                foreach ($item->riskdata as $risk){
                                    $list .= '<li>';
                                    $list .= $risk["name"] . '<br />';
                                   	$list .= '</li>';
                                }
                                $list .= '</ul>';
                                $d = ' <span style="cursor:help;" >[ '.$item->params["nr_risks"].' ] '.$Dtext.'</span> ';
                                $tt =  $TTtext;
                                $td = $list;
                                echo JHTML::tooltip($td, $tt, '', $d );
                            ?>
                        <?php endif; ?>   
                    <?php endif; ?>
                    </td>
                    <td class="center"><?php echo $item->owner; ?></td>
                    <td class="center">
						<?php if ($this->user['type'] == 'admin') : ?>
                            <a href="<?php echo $item->createduser; ?>">
                        <?php endif; ?>
                            <?php echo $item->created_by ?>
                        <?php if ($this->user['type'] == 'admin') : ?>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td class="center"><?php echo $item->created_on ?></td>
                    <td class="center">
						<?php if ($this->user['type'] == 'admin') : ?>
                            <a href="<?php echo $item->modifieduser; ?>">
                        <?php endif; ?>
                            <?php echo $item->modified_by ?>
                        <?php if ($this->user['type'] == 'admin') : ?>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td class="center"><?php echo $item->modified_on ?></td>
                    <td class="center">
                   	<?php if ($this->user['type'] == 'admin' || $this->user['type'] == 'country' || $this->user['id'] == JUserHelper::getProfile($item->owner_id)->gizprofile[serviceprovider]) : ?>
						<?php if ($item->checked_out) : ?>
                            <?php if ($item->checked_out == $this->user['id'] || $this->user['type'] == 'admin') : ?>
                            	<?php echo JHtml::_('jgrid.published', $item->published, $i, 'interventions.', true, 'cb'); ?>
                            <?php else: ?>
								&#35;
                            <?php endif; ?>
                        <?php else: ?>
                            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'interventions.', true, 'cb'); ?>
                        <?php endif; ?>
                    <?php else : ?>
                    	&#35;
                    <?php endif; ?>
                    </td>
                    <td class="center"><?php echo $item->intervention_id ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>