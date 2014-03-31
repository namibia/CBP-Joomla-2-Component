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

$params = $this->form->getFieldsets('params');
?>

<form action="index.php?option=com_costbenefitprojection&risk_id=<?php echo $this->item->risk_id ?>"
	method="post" name="adminForm" class="form-validate">
	<div class="width-60 fltlft">
		<legend><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELDSET_RISK'); ?></legend>
        <fieldset class="panelform">
			<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset('basic') as $field): ?>
					<li><?php echo $field->label; ?>
                    <div class="clr"></div>
					<?php echo $field->input; ?></li>
				<?php endforeach ?>
			</ul>
        </fieldset>
	</div>

	<div class="width-40 fltrt">
		<?php echo JHtml::_('sliders.start','attraction-sliders'); ?>

		<?php echo JHtml::_('sliders.panel', JText::_('COM_COSTBENEFITPROJECTION_FIELDSET_DETAILS'), 'details-panel'); ?>
		<fieldset class="panelform">
			<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset('details') as $field): ?>
					<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
				<?php endforeach ?>
			</ul>
		</fieldset>
		<?php foreach ($params as $name => $fieldset): ?>
                <?php echo JHtml::_('sliders.panel', JText::_($fieldset->label), $name.'-params');?>
            <?php if (isset($fieldset->description) && trim($fieldset->description)): ?>
                <p class="tip"><?php echo $this->escape(JText::_($fieldset->description));?></p>
            <?php endif;?>
                <fieldset class="panelform" >
                    <ul class="adminformlist">
            <?php foreach ($this->form->getFieldset($name) as $field) : ?>
                        <li><?php echo $field->label; ?><?php echo $field->input; ?></li>
            <?php endforeach; ?>
                    </ul>
                </fieldset>
        <?php endforeach; ?>

		<?php echo JHtml::_('sliders.end'); ?>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>