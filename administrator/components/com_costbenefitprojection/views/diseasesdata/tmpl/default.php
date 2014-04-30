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
defined('_JEXEC') or die;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>
<form action="index.php?option=com_costbenefitprojection&amp;view=diseasesdata" method="post" name="adminForm" id="adminForm">

	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="Search" />

			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>

		<div class="filter-select fltrt">
        	<label for="filter_state">
				<?php echo JText::_('COM_COSTBENEFITPROJECTION_FILTER_DISEASESDATA_LABEL'); ?>
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
                <th><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_REF_LABEL', 'a.ref', $listDirn, $listOrder) ?></th>
				<th><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_NAME_LABEL', 'a.disease_name', $listDirn, $listOrder) ?></th>
                <th><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_CATEGORY_LABEL', 'c.diseasecategory_name', $listDirn, $listOrder) ?></th>
                <th><?php echo JText::_('COM_COSTBENEFITPROJECTION_OWNER'); ?></th>
                
                <th width="7%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_YLD_SCALING_FACTOR_MALE_LABEL'); ?></th>
                <th width="7%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_YLD_SCALING_FACTOR_FEMALE_LABEL'); ?></th>
                
                <th width="7%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_MSFM_LABEL'); ?></th>
                <th width="7%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_MSFF_LABEL'); ?></th>
                
                <th width="7%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_PSFM_LABEL'); ?></th>
                <th width="7%"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_PSFF_LABEL'); ?></th>
				
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
			<?php if ($this->items) : ?>
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
                    <td class="center"><?php echo $this->escape($item->ref); ?></td>
					<td>
						<?php if ($item->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->name, $item->checked_out_time, 'diseasesdata.'); ?>
                            <?php if ($item->checked_out == $this->user['id']) : ?>
                            	<a href="index.php?option=com_costbenefitprojection&amp;task=diseasedata.edit&amp;id=<?php echo $item->id; ?>">
                                    <?php echo $this->escape($item->disease_name); ?>
                                </a>
							<?php else: ?>
                            	<?php echo $this->escape($item->disease_name); ?>
                            <?php endif; ?>
						<?php else: ?>
							<?php if ($this->user['type'] == 'service' && $item->itemOwner['type'] == 'country'): ?>
                                <?php echo $this->escape($item->disease_name); ?>
                            <?php else: ?>
                             	<a href="index.php?option=com_costbenefitprojection&amp;task=diseasedata.edit&amp;id=<?php echo $item->id; ?>">
                                    <?php echo $this->escape($item->disease_name); ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td class="center"><?php echo $this->escape($item->diseasecategory_name); ?></td>
                    <td class="center"><?php echo $item->owner; ?></td>
                    <td class="center"><?php echo $item->params['yld_scaling_factor_Males']; ?></td>
                    <td class="center"><?php echo $item->params['yld_scaling_factor_Females']; ?></td>
                    <td class="center"><?php echo $item->params['mortality_scaling_factor_Males']; ?></td>
                    <td class="center"><?php echo $item->params['mortality_scaling_factor_Females']; ?></td>
                    <td class="center"><?php echo $item->params['presenteeism_scaling_factor_Males']; ?></td>
                    <td class="center"><?php echo $item->params['presenteeism_scaling_factor_Females']; ?></td>
					<td class="center">
						<?php if ($item->checked_out) : ?>
                            <?php if ($item->checked_out == $this->user['id'] || $this->user['type'] == 'admin') : ?>
                            	<?php echo JHtml::_('jgrid.published', $item->published, $i, 'diseasesdata.', true, 'cb'); ?>
                            <?php else: ?>
								&#35;
                            <?php endif; ?>
                        <?php else: ?>
                        	<?php if ($this->user['type'] == 'service' && $item->itemOwner['type'] == 'country'): ?>
								&#35;
                            <?php else: ?>
                            	<?php echo JHtml::_('jgrid.published', $item->published, $i, 'diseasesdata.', true, 'cb'); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $item->id; ?></td>
				</tr>
			<?php endforeach ?>
            <?php endif ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>