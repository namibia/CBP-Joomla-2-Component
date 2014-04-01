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
<form action="index.php?option=com_costbenefitprojection&view=helpsedit" method="post" name="adminForm" id="adminForm">

	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="Search" />

			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		
		<div class="filter-select fltrt">

			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
		</div>
	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
				<th><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_HELPEDIT_FOR_NAME_LABEL', 'help_for', $listDirn, $listOrder) ?></th>
				<th width="15%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_HELPEDIT_GROUP_LABEL', 'help_group', $listDirn, $listOrder) ?></th>
                <th width="15%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_HELPEDIT_LOCATION_LABEL', 'location', $listDirn, $listOrder) ?></th>
                <th width="10%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_CREATEDBY_LABEL', 'created_by', $listDirn, $listOrder) ?></th>
				<th width="10%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_CREATEDON_LABEL', 'created_on', $listDirn, $listOrder) ?></th>
                <th width="10%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_MODIFIEDBY_LABEL', 'modified_by', $listDirn, $listOrder) ?></th>
				<th width="10%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_MODIFIEDON_LABEL', 'modified_on', $listDirn, $listOrder) ?></th>
				<th width="2%"><?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder) ?></th>
                <th width="2%"><?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_ID_LABEL', 'help_id', $listDirn, $listOrder) ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($this->items as $i => $item): ?>
				<tr class="row<?php echo $i % 2 ?>">
                	<td class="center">
						<?php if ($item->checked_out) : ?>
                            <?php if ($item->checked_out == $this->user['id']) : ?>
                            	<?php echo JHtml::_('grid.id', $i, $item->help_id); ?>
                            <?php else: ?>
                            	&#35;
                            <?php endif; ?>
                        <?php else: ?>
                        	<?php echo JHtml::_('grid.id', $i, $item->help_id); ?>
                        <?php endif; ?>
                    </td>
					<td>
						<?php if ($item->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->name, $item->checked_out_time, 'helpsedit.'); ?>
                            <?php if ($item->checked_out == $this->user['id']) : ?>
                           		<a href="<?php echo $item->url; ?>">
                                     <?php echo $this->escape($item->help_for) ?>
                                </a>
							<?php else: ?>
                                 <?php echo $this->escape($item->help_for) ?>
                            <?php endif; ?>
						<?php else: ?>
							<a href="<?php echo $item->url; ?>">
                            	 <?php echo $this->escape($item->help_for) ?>
                            </a>
                        <?php endif; ?>
					</td>
					<td><?php echo $this->escape($item->help_group); ?></td>
                    <td><?php echo $this->escape($item->location); ?></td>
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
						<?php if ($item->checked_out) : ?>
                            <?php if ($item->checked_out == $this->user['id']) : ?>
                            	<?php echo JHtml::_('jgrid.published', $item->published, $i, 'helpsedit.', true, 'cb'); ?>
                            <?php else: ?>
								&#35;
                            <?php endif; ?>
                        <?php else: ?>
                            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'helpsedit.', true, 'cb'); ?>
                        <?php endif; ?>
                    </td>
                    <td class="center"><?php echo $item->help_id ?></td>
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