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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$canDo = UsersHelper::getActions();

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'user.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
			Joomla.submitform(task, document.getElementById('user-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="user-form" class="form-validate" enctype="multipart/form-data">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_COSTBENEFITPROJECTION_USER_ACCOUNT_DETAILS'); ?></legend>
			<ul class="adminformlist">
			<?php foreach($this->form->getFieldset('user_details') as $field) :?>
				<li><?php echo $field->label; ?>
				<?php echo $field->input; ?></li>
			<?php endforeach; ?>
			</ul>
		</fieldset>
	</div>
	<div class="width-40 fltrt">
		<?php
		echo JHtml::_('sliders.start');
		foreach ($fieldsets as $fieldset) :
			if ($fieldset->name == 'user_details') :
				continue;
			endif;
			echo JHtml::_('sliders.panel', JText::_($fieldset->label), $fieldset->name);
		?>
        <fieldset class="panelform">
		<ul class="adminformlist">
		<?php foreach($this->form->getFieldset($fieldset->name) as $field): ?>
			<?php if ($field->hidden): ?>
				<?php echo $field->input; ?>
			<?php else: ?>
				<li><?php echo $field->label; ?>
				<?php echo $field->input; ?></li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
		</fieldset>
		<?php endforeach; ?>
        <?php if ($this->item->id): ?>
			<?php  echo JHtml::_('sliders.panel', JText::_('COM_COSTBENEFITPROJECTION_GRAVATAR_IMAGE'), gravatar); ?>
            <div style="padding: 10px;">
                <div style="width:100%; padding: 10px;"><img src="<?php echo $this->gravatar; ?>" style="border: 3px #CCC solid;" /></div>
                <div style="width:100%;"><p><?php echo JText::_('COM_COSTBENEFITPROJECTION_GRAVATAR_UPDATE_IMAGE'); ?> <a href="http://gravatar.com" target="_blank" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_GRAVATAR_UPDATE_IMAGE_LINK'); ?>">Gravatar.com</a></p></div>
            </div>
        <?php endif; ?>
		<?php echo JHtml::_('sliders.end'); ?>

		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>