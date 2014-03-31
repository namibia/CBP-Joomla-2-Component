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

<form action="index.php?option=com_costbenefitprojection&amp;id=<?php echo $this->item->id ?>"
	method="post" name="adminForm" class="form-validate">
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

		<?php echo JHtml::_('sliders.panel', JText::_('COM_COSTBENEFITPROJECTION_FIELDSET_DETAILS'), 'details-panel'); ?>
		<fieldset class="panelform">
			<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset('details') as $field): ?>
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

jQuery(document).ready(function() {
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
});

</script>