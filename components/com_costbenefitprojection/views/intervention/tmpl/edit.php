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

$params = $this->form->getFieldsets('params');
?>
<div id="costbenefitprojection">
    <form action="index.php?option=com_costbenefitprojection&intervention_id=<?php echo $this->item->intervention_id ?>"
        method="post" name="adminForm" class="form-validate uk-form">
        <div class="width-30 fltlft">
            <legend><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELDSET_INTERVENTION'); ?></legend>
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
         <div class="width-30 fltlft">   
            <legend><?php echo JText::_('COM_COSTBENEFITPROJECTION_INT_SETTINGS_FIELDSET_LABEL'); ?></legend>
            <fieldset class="panelform">
                <ul class="adminformlist">
                    <?php foreach ($this->form->getFieldset('details') as $field): ?>
                        <li><?php echo $field->label; ?>
                        <?php echo $field->input; ?></li>
                    <?php endforeach ?>
                </ul>
            </fieldset>
        </div>
    
        <div class="width-40 fltrt">		
                <?php foreach ($params as $name => $fieldset): ?>
                    <legend><?php echo JText::_($fieldset->label); ?></legend>
                <?php if (isset($fieldset->description) && trim($fieldset->description)): ?>
                    <p class="tip"><?php echo $this->escape(JText::_($fieldset->description));?></p>
                <?php endif;?>
                    <fieldset id="DRS" class="panelform" >
                        <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset($name) as $field) : ?>
                            <li><?php echo $field->label; ?><?php echo $field->input; ?></li>
                <?php endforeach; ?>
                        </ul>
                    </fieldset>
            <?php endforeach; ?>
        </div>
        <div id="toolbar">
            <?php if(!$this->item->intervention_id):?> 
            	<button id="toolbar-apply" onclick="Joomla.submitbutton('intervention.apply')" class="uk-button uk-button-success"><i class="uk-icon-plus"></i> Apply</button>
            <?php else: ?>
                <button id="toolbar-save" onclick="Joomla.submitbutton('intervention.save')" class="uk-button uk-button-primary"><i class="uk-icon-save"></i> Save & Close</button>
            <?php endif;?>
        </div>
        <?php if ($this->tmpl == 'component'): ?><input type="hidden" name="tmpl" value="component" /><?php endif; ?>
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
<script>

jQuery(document).ready(function() {

	// check the type selected and show or hide the needed fields
	jQuery('#jform_type').change(function() {
		typeChecker();
	});
	typeChecker();
	function typeChecker() {
		var type = jQuery('#jform_type').val();
		if (type == 1){
			jQuery('#jform_cluster_id-lbl').hide();
			jQuery('#jform_cluster_id').hide();
			jQuery('#jform_disease_id-lbl').show();
			jQuery('#jform_disease_id').show();
			jQuery('#jform_risk_id-lbl').show();
			jQuery('#jform_risk_id').show();
		} else if (type == 2){
			jQuery('#jform_disease_id-lbl').hide();
			jQuery('#jform_disease_id').hide();
			jQuery('#jform_risk_id-lbl').hide();
			jQuery('#jform_risk_id').hide();
			jQuery('#jform_cluster_id-lbl').show();
			jQuery('#jform_cluster_id').show();
			
		}
	}
	
	// check if a var is empty 
	function isEmpty(obj) {
		if(isSet(obj)) {
			if (obj.length && obj.length > 0) { 
				return false;
			}
		
			for (var key in obj) {
				if (hasOwnProperty.call(obj, key)) {
					return false;
				}
			}
		}
	return true;    
	};

	function isSet(val) {
		if ((val != undefined) && (val != null)){
			return true;
		}
	return false;
	};
	
	// hide the type field based on disease-risk selection
	hideTypes();
	jQuery('#jform_disease_id input').change(function() {
	   hideTypes();
	});
	jQuery('#jform_risk_id input').change(function() {
	   hideTypes();
	});
	jQuery('#jform_cluster_id input').change(function() {
	   hideTypes();
	});
	function hideTypes() {         

		 var diseaseVals= [];
		 var riskVals= [];
		 var clusterVals = [];
		 jQuery('#jform_disease_id :checked').each(function() {
		   diseaseVals.push(jQuery(this).val());
		 });
		 jQuery('#jform_risk_id :checked').each(function() {
		   riskVals.push(jQuery(this).val());
		 });
		 jQuery('#jform_cluster_id :checked').each(function() {
		   clusterVals.push(jQuery(this).val());
		 });
		 
		 var joined = diseaseVals+riskVals+clusterVals;
		 if (!isEmpty(joined)) {
			jQuery('#jform_type').hide();
			if (!jQuery('#jform_type_ready').length){
				type = jQuery('#jform_type').val();
				if (type == 1){
					var add = '<div id="jform_type_ready" style="color:#A3A3A3"><i>Single</i></div>';
				} else if (type == 2){
					var add = '<div id="jform_type_ready" style="color:#A3A3A3"><i>Cluster</i></div>';
				}
				jQuery('#jform_type-lbl').append(add);
			}
		} else if (isEmpty(joined)) {
			jQuery('#jform_type').show();
			jQuery('#jform_type_ready').remove()
		}
	}
	// disable the check box once a cluster of interventions has been selected
	if (jQuery('#DRS').length){
		jQuery("#jform_cluster_id :checkbox").click(function(e) {
			e.preventDefault();
		});
	}
	// check the values of the Disease-Risk settings
	checkValues();
	jQuery("#DRS :input[type=text]").change(function() {
	   checkValues();
	});
	function checkValues() {
		var allClear = 0;
		jQuery("#DRS :input[type=text]").each(function(){
			var value = jQuery(this).val();
			if (value.indexOf('&') !== -1 ){
				jQuery(this).css({"border":"1px solid red", "color":"red"});
				allClear += 1;	
			} else if (value.indexOf('&') === -1 ){
				jQuery(this).css({"border":"1px solid #C0C0C0", "color":"black"});
			}			
		});
		if (allClear === 0){
			jQuery('#toolbar-save').show();
			jQuery('#inputNote').remove();
		} else {
			var note = '<li id="inputNote" style="color:red;"><i>Values cross match between<br />selected interventions<br />please update all in red!</i></li>';
			jQuery('#toolbar-save').hide();
			if (!jQuery('#inputNote').length){
				jQuery('#toolbar').prepend(note);
			}
		}
	}	
});

</script>