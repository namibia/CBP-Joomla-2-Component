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
<form action="index.php?option=com_costbenefitprojection&amp;view=risksdata" method="post" name="adminForm" id="adminForm">

	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="Search" />

			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>

		<div class="filter-select fltrt">
             	<label for="filter_state">
                    <?php echo JText::_('COM_COSTBENEFITPROJECTION_FILTER_RISKSSDATA_LABEL'); ?>
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
				<th><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_RISK_NAME_LABEL', 'a.risk_name', $listDirn, $listOrder) ?></th>
               	<th width="22%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_OWNER'); ?></th>
                <th width="7%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_RISK_PM_SHORT', 't.prevalence_male', $listDirn, $listOrder);
								$d = ' <span style="cursor:help;" >&nbsp;&nbsp;<b><span style="color:#008ED4;">&#105;</span></b>&nbsp;</span>';
								$tt = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_PM_LABEL'); 
								$td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_PM_DESC');
								echo JHTML::tooltip($td, $tt, '', $d );
								?></th>
                <th width="7%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_RISK_PF_SHORT', 't.prevalence_female', $listDirn, $listOrder);
								$d = ' <span style="cursor:help;" >&nbsp;&nbsp;<b><span style="color:#008ED4;">&#105;</span></b>&nbsp;</span>';
								$tt = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_PF_LABEL'); 
								$td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_PF_DESC');
								echo JHTML::tooltip($td, $tt, '', $d );
								?></th>
                <th width="7%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_RISK_AUDM_SHORT', 't.annual_unproductive_male', $listDirn, $listOrder);
								$d = ' <span style="cursor:help;" >&nbsp;&nbsp;<b><span style="color:#008ED4;">&#105;</span></b>&nbsp;</span>';
								$tt = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_AUDM_LABEL'); 
								$td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_AUDM_DESC');
								echo JHTML::tooltip($td, $tt, '', $d );
								?></th>
              	<th width="7%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_RISK_AUDF_SHORT', 't.annual_unproductive_female', $listDirn, $listOrder);
								$d = ' <span style="cursor:help;" >&nbsp;&nbsp;<b><span style="color:#008ED4;">&#105;</span></b>&nbsp;</span>';
								$tt = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_AUDF_LABEL'); 
								$td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_AUDF_DESC');
								echo JHTML::tooltip($td, $tt, '', $d );
								?></th>
                <th width="7%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_RISK_RFPM_SHORT', 't.prevalence_scaling_factor_male', $listDirn, $listOrder);
								$d = ' <span style="cursor:help;" >&nbsp;&nbsp;<b><span style="color:#008ED4;">&#105;</span></b>&nbsp;</span>';
								$tt = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_RFPM_LABEL'); 
								$td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_RFPM_DESC');
								echo JHTML::tooltip($td, $tt, '', $d );
								?></th>
                <th width="7%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_RISK_RFPF_SHORT', 't.prevalence_scaling_factor_female', $listDirn, $listOrder);
								$d = ' <span style="cursor:help;" >&nbsp;&nbsp;<b><span style="color:#008ED4;">&#105;</span></b>&nbsp;</span>';
								$tt = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_RFPF_LABEL'); 
								$td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_RFPF_DESC');
								echo JHTML::tooltip($td, $tt, '', $d );
								?></th>
                <th width="7%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_RISK_USFM_SHORT', 't.unproductive_scaling_factor_male', $listDirn, $listOrder);
								$d = ' <span style="cursor:help;" >&nbsp;&nbsp;<b><span style="color:#008ED4;">&#105;</span></b>&nbsp;</span>';
								$tt = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_USFM_LABEL'); 
								$td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_USFM_DESC');
								echo JHTML::tooltip($td, $tt, '', $d );
								?></th>
                <th width="7%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_RISK_USFF_SHORT', 't.unproductive_scaling_factor_female', $listDirn, $listOrder);
								$d = ' <span style="cursor:help;" >&nbsp;&nbsp;<b><span style="color:#008ED4;">&#105;</span></b>&nbsp;</span>';
								$tt = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_USFF_LABEL'); 
								$td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_RISK_USFF_DESC');
								echo JHTML::tooltip($td, $tt, '', $d );
								?></th>
                				
                <th width="4%"><?php echo JHtml::_('grid.sort', 'JSTATUS', 't.published', $listDirn, $listOrder) ?></th>
                <th width="2%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_ID_LABEL', 't.id', $listDirn, $listOrder) ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="13">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($this->items as $i => $item): ?>
				<tr class="row<?php echo $i % 2 ?>">
					<td class="center">
						<?php if ($item->checked_out) : ?>
                            <?php if ($item->checked_out == $this->user['id'] || $this->user['type'] == 'admin') : ?>
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            <?php else: ?>
                            &#35;
                            <?php endif; ?>
                        <?php else: ?>
                        	<?php if ($this->user['type'] == 'service' && $item->itemOwner['type'] == 'country'): ?>
                            &#35;
                            <?php else: ?>
                            	<?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
					<td>
						<?php if ($item->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->name, $item->checked_out_time, 'risksdata.'); ?>
							<?php if ($item->checked_out == $this->user['id']) : ?>
                                <a href="index.php?option=com_costbenefitprojection&amp;task=riskdata.edit&amp;id=<?php echo $item->id; ?>">
                                    <?php echo $this->escape($item->risk_name); ?>
                                </a>
                            <?php else: ?>
                                <?php echo $this->escape($item->risk_name); ?>
                            <?php endif; ?>
						<?php else: ?>
							<?php if ($this->user['type'] == 'service' && $item->itemOwner['type'] == 'country'): ?>
                                <?php echo $this->escape($item->risk_name); ?>
                            <?php else: ?>
                                <a href="index.php?option=com_costbenefitprojection&amp;task=riskdata.edit&amp;id=<?php echo $item->id; ?>">
                                    <?php echo $this->escape($item->risk_name); ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td class="center"><?php echo $item->owner; ?></td>
                    <td class="center"><?php echo $item->prevalence_male; ?></td>
                    <td class="center"><?php echo $item->prevalence_female; ?></td>
                    <td class="center"><?php echo $item->annual_unproductive_male; ?></td>
                    <td class="center"><?php echo $item->annual_unproductive_female; ?></td>
                    <td class="center"><?php echo $item->prevalence_scaling_factor_male; ?></td>
                    <td class="center"><?php echo $item->prevalence_scaling_factor_female; ?></td>
                    <td class="center"><?php echo $item->unproductive_scaling_factor_male; ?></td>
                    <td class="center"><?php echo $item->unproductive_scaling_factor_female; ?></td>
					<td class="center">
						<?php if ($item->checked_out) : ?>
                            <?php if ($item->checked_out == $this->user['id'] || $this->user['type'] == 'admin') : ?>
                            	<?php echo JHtml::_('jgrid.published', $item->published, $i, 'risksdata.', true, 'cb'); ?>
                            <?php else: ?>
								&#35;
                            <?php endif; ?>
                        <?php else: ?>
                        	<?php if ($this->user['type'] == 'service' && $item->itemOwner['type'] == 'country'): ?>
								&#35;
                            <?php else: ?>
                            	<?php echo JHtml::_('jgrid.published', $item->published, $i, 'risksdata.', true, 'cb'); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $item->id; ?></td>
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