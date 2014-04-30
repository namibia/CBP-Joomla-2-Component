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
// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'diseasedata.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('Please complete required fields marked in red.'));?>');
		}
	}
</script>
<form action="index.php?option=com_costbenefitprojection&amp;id=<?php echo $this->item->id ?>"
	id="item-form" method="post" name="adminForm" class="form-validate">
	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELDSET_DISEASEDATA'); ?></legend>
			<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset('basic') as $field): ?>
					<li><?php echo $field->label; ?>
                    <div class="clr"></div>
					<?php echo $field->input; ?></li>
				<?php endforeach ?>
			</ul>

		</fieldset>
	</div>

	<div class="width-50 fltrt">
		<?php echo JHtml::_('sliders.start','attraction-sliders'); ?>

		<?php echo JHtml::_('sliders.panel', JText::_('COM_COSTBENEFITPROJECTION_FIELDSET_DETAILS'), 'params-panel'); ?>
		<fieldset class="panelform">
			<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset('params') as $field): ?>
					<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
				<?php endforeach ?>
			</ul>
		</fieldset>

		<?php echo JHtml::_('sliders.end'); ?>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<script>

<?php /*?>jQuery(document).ready(function() {
	var new_selection = '<?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_INCIDENCE_OR_PREVALENCE_SELECTION'); ?>';
	// change the text label based on selection
	jQuery('#jform_incidence_or_prevalence').change(function() {
		typeChecker();
	});
	typeChecker();
	function typeChecker() {
		var value = jQuery('#jform_incidence_or_prevalence option:selected').val();
		var text = jQuery('#jform_incidence_or_prevalence option:selected').text();
		var male_lbl = jQuery('#jform_incidence_rate_male-lbl').text();
		var female_lbl = jQuery('#jform_incidence_rate_female-lbl').text();
		if (value == 1){
			male_lbl = male_lbl.replace(new_selection, text);
			female_lbl = female_lbl.replace(new_selection, text);
			jQuery('#jform_incidence_rate_male-lbl').text(male_lbl);
			jQuery('#jform_incidence_rate_female-lbl').text(female_lbl);
		} else if (value == 2){
			male_lbl = male_lbl.replace(new_selection, text);
			female_lbl = female_lbl.replace(new_selection, text);
			jQuery('#jform_incidence_rate_male-lbl').text(male_lbl);
			jQuery('#jform_incidence_rate_female-lbl').text(female_lbl);		
		}
		new_selection = text;
	}
});<?php */?>

</script>