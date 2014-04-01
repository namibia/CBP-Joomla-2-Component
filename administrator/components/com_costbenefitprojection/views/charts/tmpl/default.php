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

$canDo = UsersHelper::getActions();

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>
<form action="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=charts');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('COM_COSTBENEFITPROJECTION_SEARCH_USERS'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_SEARCH_USERS'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_RESET'); ?></button>
		</div>
        <?php if ($this->user['type'] != 'service') : ?>
		<div class="filter-select fltrt">
			<label for="filter_state">
				<?php echo JText::_('COM_COSTBENEFITPROJECTION_FILTER_LABEL'); ?>
			</label>
			<?php if ($this->user['type'] == 'admin') : ?>
			<select name="filter.country" class="inputbox" onchange="this.form.submit()">
				<option value="0"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FILTER_COUNTRY');?></option>
				<?php echo JHtml::_('select.options', UsersHelper::getCountry(), 'value', 'text', $this->state->get('filter.country'));?> 
			</select>
            <?php endif ?>
            
            <?php if ($this->user['type'] == 'admin' || $this->user['type'] == 'country') : ?>
            <select name="filter.serviceProvider" class="inputbox" onchange="this.form.submit()">
				<option value="0"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FILTER_SERVICE_PROVIDERS');?></option>
				<?php if ($this->user['type'] == 'admin') : ?>
					<?php echo JHtml::_('select.options', UsersHelper::getServiceProviders(), 'value', 'text', $this->state->get('filter.serviceProvider'));?>
                <?php elseif ($this->user['type'] == 'country') : ?>
					<?php 
							$countryID = $this->user['country'];
							echo JHtml::_('select.options', UsersHelper::getServiceProviders($countryID), 'value', 'text', $this->state->get('filter.serviceProvider'));
					?> 
                <?php endif ?>
			</select>
             <?php endif ?>
		</div>
        <?php endif ?>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th class="left">
					<?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
                <th class="nowrap" width="12%">
					<?php echo JText::_('COM_COSTBENEFITPROJECTION_SERVICE_PROVIDERS'); ?>
				</th>
                <th class="nowrap" width="12%">
					<?php echo JText::_('COM_COSTBENEFITPROJECTION_COUNTRY'); ?>
				</th>
				<th class="nowrap" width="3%">
					<?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_ID_LABEL', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<a href="<?php echo $item->url; ?>" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_VIEW_USER_CHARTS') . $this->escape($item->name); ?>">
						<?php echo $this->escape($item->name); ?>
                    </a>
				</td>
                <td class="center">
					<?php echo $this->escape($item->serviceprovider); ?>
				</td>
                <td class="center">
					<?php echo $this->escape($item->country); ?>
				</td>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>