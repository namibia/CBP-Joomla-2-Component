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

jimport('joomla.application.component.helper');

$canDo = UsersHelper::getActions();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$loggeduser = JFactory::getUser();
$loggedusergroup = JUserHelper::getUserGroups($loggeduser->id);

$AppGroups['admin'] = JComponentHelper::getParams('com_costbenefitprojection')->get('admin');
$AppGroups['country'] = JComponentHelper::getParams('com_costbenefitprojection')->get('country');
$AppGroups['service'] = JComponentHelper::getParams('com_costbenefitprojection')->get('service');
$AppGroups['client'] = JComponentHelper::getParams('com_costbenefitprojection')->get('client');
$AppGroups['basic'] = JComponentHelper::getParams('com_costbenefitprojection')->get('basic');

$admin_user = (count(array_intersect($AppGroups['admin'], $loggedusergroup))) ? true : false;
$country_user = (count(array_intersect($AppGroups['country'], $loggedusergroup))) ? true : false;
$service_user = (count(array_intersect($AppGroups['service'], $loggedusergroup))) ? true : false;
 
?>

<form action="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=users');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('COM_COSTBENEFITPROJECTION_SEARCH_USERS'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_SEARCH_USERS'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_RESET'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<label for="filter_state">
				<?php echo JText::_('COM_COSTBENEFITPROJECTION_FILTER_LABEL'); ?>
			</label>

			<select name="filter_state" class="inputbox" onchange="this.form.submit()">
				<option value="*"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FILTER_STATE');?></option>
				<?php echo JHtml::_('select.options', UsersHelper::getStateOptions(), 'value', 'text', $this->state->get('filter.state'));?>
			</select>
			<?php if ($admin_user) : ?>
			<select name="filter.country" class="inputbox" onchange="this.form.submit()">
				<option value="0"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FILTER_COUNTRY');?></option>
				<?php echo JHtml::_('select.options', UsersHelper::getCountry(), 'value', 'text', $this->state->get('filter.country'));?> 
			</select>
            <?php endif ?>
            
            <?php if ($admin_user || $country_user) : ?>
            <select name="filter.serviceProvider" class="inputbox" onchange="this.form.submit()">
				<option value="0"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FILTER_SERVICE_PROVIDERS');?></option>
				<?php if ($admin_user) : ?>
					<?php echo JHtml::_('select.options', UsersHelper::getServiceProviders(), 'value', 'text', $this->state->get('filter.serviceProvider'));?>
                <?php elseif ($country_user) : ?>
					<?php 
							$countryID = JUserHelper::getProfile($loggeduser->id)->gizprofile[country];
							echo JHtml::_('select.options', UsersHelper::getServiceProviders($countryID), 'value', 'text', $this->state->get('filter.serviceProvider'));
					?> 
                <?php endif ?>
			</select>
             <?php endif ?>

			<select name="filter_range" id="filter_range" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_OPTION_FILTER_DATE');?></option>
				<?php echo JHtml::_('select.options', Usershelper::getRangeOptions(), 'value', 'text', $this->state->get('filter.range'));?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="left">
					<?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="3%">
					<?php echo JText::_('COM_COSTBENEFITPROJECTION_GRAVATAR'); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_USERNAME', 'a.username', $listDirn, $listOrder); ?>
				</th>
                <th class="nowrap" width="3%">
					<?php echo JText::_('COM_COSTBENEFITPROJECTION_VIEW_MEMBER_CT'); ?>
				</th>
                <th class="nowrap" width="7%">
					<?php echo JText::_('COM_COSTBENEFITPROJECTION_SERVICE_PROVIDERS'); ?>
				</th>
                <th class="nowrap" width="7%">
					<?php echo JText::_('COM_COSTBENEFITPROJECTION_COUNTRY'); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JText::_('COM_COSTBENEFITPROJECTION_HEADING_GROUPS'); ?>
				</th>
				<th class="nowrap" width="15%">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_EMAIL', 'a.email', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_HEADING_LAST_VISIT_DATE', 'a.lastvisitDate', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_HEADING_REGISTRATION_DATE', 'a.registerDate', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="3%">
					<?php echo JHtml::_('grid.sort', 'COM_COSTBENEFITPROJECTION_FIELD_ID_LABEL', 'a.id', $listDirn, $listOrder); ?>
				</th>
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
			<?php foreach ($this->items as $i => $item) :
                $canEdit	= $canDo->get('core.edit');
                $canChange	= $loggeduser->authorise('core.edit.state',	'com_costbenefitprojection');
                // If this group is super admin and this user is not super admin, $canEdit is false
                if ((!$loggeduser->authorise('core.admin')) && JAccess::check($item->id, 'core.admin')) {
                    $canEdit	= false;
                    $canChange	= false;
                }
                if($item->serviceprovider){
					$usergroup 	= JUserHelper::getUserGroups($item->id);
					$basicgroup = (count(array_intersect($AppGroups['basic'], $usergroup))) ? true : false;
					if(!$basicgroup){
						$per = JUserHelper::getProfile($item->id)->gizprofile["per"];
						if($per == NULL){
							$per = 1;
						}
					} else {
						$per = 1;
					}
                } else {
                    $per = 1;
                }
            ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php if ($canEdit && $per == 1) : ?>
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($canEdit && $per == 1) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&task=user.edit&id='.(int) $item->id); ?>" title="<?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_EDIT_USER', $this->escape($item->name)); ?>">
						<?php echo $this->escape($item->name); ?></a> 
					<?php else : ?>
						<?php echo $this->escape($item->name); ?>
					<?php endif; ?>
					
				</td>
                <td class="center">
					<a href="http://gravatar.com/" target="_blank" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_GRAVATAR_UPDATE_IMAGE_LINK'); ?>"><?php echo $item->gravatar; ?></a>
				</td>
				<td class="center">
					<?php echo $this->escape($item->username); ?>
				</td>
                <td class="center">
                <?php if ($this->escape($item->serviceprovider)) : ?>
                	<a href="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=chart&id='.(int) $item->id); ?>" title="<?php echo JText::sprintf('COM_COSTBENEFITPROJECTION_VIEW_MEMBER_CHARTS', $this->escape($item->name)); ?>">&equiv;</a>
                <?php endif; ?>
                </td>
                <td class="center">
                 	<?php if ($this->escape($item->serviceprovider)) : ?>
					<?php echo $this->escape($item->serviceprovider); ?>
                    <?php else : ?>
                    <?php echo ' '; ?>
                    <?php endif; ?>
				</td>
                <td class="center">
					<?php echo $this->escape($item->country); ?>
				</td>
				<td class="center">
					<?php if (substr_count($item->group_names, "\n") > 1) : ?>
						<span class="hasTip" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_HEADING_GROUPS').'::'.nl2br($item->group_names); ?>"><?php echo JText::_('COM_COSTBENEFITPROJECTION_USERS_MULTIPLE_GROUPS'); ?></span>
					<?php else : ?>
						<?php echo nl2br($item->group_names); ?>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->email); ?>
				</td>
				<td class="center">
					<?php if ($item->lastvisitDate!='0000-00-00 00:00:00'):?>
						<?php echo JHtml::_('date', $item->lastvisitDate, 'Y-m-d H:i:s'); ?>
					<?php else:?>
						<?php echo JText::_('JNEVER'); ?>
					<?php endif;?>
				</td>
				<td class="center">
					<?php echo JHtml::_('date', $item->registerDate, 'Y-m-d H:i:s'); ?>
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