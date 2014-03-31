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

?>
<div id="costbenefitprojection">
<form action="index.php?option=com_costbenefitprojection&help_id=<?php echo $this->item->help_id ?>"
	method="post" name="adminForm" class="form-validate">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset('essential') as $field): ?>
					<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
				<?php endforeach ?>
			</ul>

			<div class="clr"></div>
			<?php echo $this->form->getLabel('help_content'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('help_content'); ?>
		</fieldset>
	</div>
	<?php /*?><div class="width-40 fltrt">
		<fieldset class="adminform">
			<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset('optional') as $field): ?>
					<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
				<?php endforeach ?>
			</ul>

		</fieldset>
	</div><?php */?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
</div>