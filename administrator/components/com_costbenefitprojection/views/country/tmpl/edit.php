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

?>
<form action="index.php?option=com_costbenefitprojection&country_id=<?php echo $this->item->country_id ?>"
	method="post" name="adminForm" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset('essential') as $field): ?>
					<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
				<?php endforeach ?>
			</ul>

			<div class="clr"></div>
			<?php echo $this->form->getLabel('country_description'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('country_description'); ?>
		</fieldset>
	</div>
	<div class="width-40 fltrt">
		<fieldset class="adminform">
			<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset('optional') as $field): ?>
					<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
				<?php endforeach ?>
			</ul>

		</fieldset>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>