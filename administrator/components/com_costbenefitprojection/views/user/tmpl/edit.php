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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$canDo = UsersHelper::getActions();
$document = JFactory::getDocument();

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();
// See if sets should show.
$number_show 	= false;
$other_show 	= false;
$diseases_show 	= false;
$age_groups_set = 'var age_groups_set = false;';
foreach($this->form->getFieldset('gizprofile') as $field) {
	if (strpos($field->name,'percent_') !== false) {
		$number_show = true;
		$age_groups_set = 'var age_groups_set = true;';
	} elseif (strpos($field->name,'percent_') === false && strpos($field->name,'diseases') === false){
		$other_show = true;
	} elseif (strpos($field->name,'diseases') !== false){
		$diseases_show = true;		
	}
}
$document->addScriptDeclaration($age_groups_set);
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'user.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
			var work_days = parseFloat(jQuery('#jform_gizprofile_working_days').val());
			if(task == 'user.cancel'){
				Joomla.submitform(task, document.getElementById('user-form'));				
			} else if(work_days > 365){
				alert('Work days should not exceeded 365 days.');
				jQuery('#jform_gizprofile_working_days').val('');
			} else if (work_days < 200){
				alert('Work days should not be below 200 days.');
				jQuery('#jform_gizprofile_working_days').val('');
			} else {
				Joomla.submitform(task, document.getElementById('user-form'));
			}
		} else {
			alert('Please complete required fields marked in red.');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="user-form" class="form-validate" enctype="multipart/form-data">
	<div class="width-40 fltlft">
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
	<div class="width-60 fltrt">
		<?php echo JHtml::_('sliders.start'); ?>
        <?php if ($other_show): ?>
			<?php echo JHtml::_('sliders.panel', JText::_('Details'), 'gizprofile'); ?>
			<fieldset class="panelform">
                <ul class="adminformlist">
                <?php foreach($this->form->getFieldset('gizprofile') as $field): ?>
                    <?php if ($field->hidden): ?>
                        <?php echo $field->input; ?>
                    <?php elseif (strpos($field->name,'percent_') === false && strpos($field->name,'diseases') === false): ?>
                        <li><?php echo $field->label; ?>
                        <?php echo $field->input; ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            </fieldset>
        <?php endif; ?>
        <?php if ($number_show): ?>
			<?php echo JHtml::_('sliders.panel', JText::_('Age Groups Percentages'), 'gizprofile'); ?>
            <fieldset class="panelform">
            <ul class="adminformlist" id="age_groups_set_male">
            <?php foreach($this->form->getFieldset('gizprofile') as $field): ?>
                <?php if (strpos($field->name,'percent_Males') !== false): ?>
                    <li><?php echo $field->label; ?>
                    <?php echo $field->input; ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
            </ul>
            <ul class="adminformlist">
            <?php foreach($this->form->getFieldset('gizprofile') as $field): ?>
                <?php if (strpos($field->name,'percent_Spacer') !== false): ?>
                    <li><?php echo $field->label; ?>
                    <?php echo $field->input; ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
            </ul>
            <ul class="adminformlist" id="age_groups_set_female">
            <?php foreach($this->form->getFieldset('gizprofile') as $field): ?>
                <?php if (strpos($field->name,'percent_Females') !== false): ?>
                    <li><?php echo $field->label; ?>
                    <?php echo $field->input; ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
            </ul>
            </fieldset>
        <?php endif; ?>
        <?php if ($diseases_show): ?>
			<?php echo JHtml::_('sliders.panel', JText::_('Cause/Risk Selection'), 'gizprofile'); ?>
            <fieldset class="panelform">
            <ul class="adminformlist">
            <?php foreach($this->form->getFieldset('gizprofile') as $field): ?>
                <?php if (strpos($field->name,'diseases') !== false): ?>
                    <li><?php echo $field->label; ?>
                    <?php echo $field->input; ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
            </ul>
            </fieldset>
        <?php endif; ?>
        
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
<script type="text/javascript">
jQuery(document).ready(function() {

	// check the number if Age Group is set
	if(age_groups_set){
		// set the array of number fields
		var malefields = [<?php
			$i = 0;
			foreach($this->form->getFieldset('gizprofile') as $field) {
				if (strpos($field->name,'percent_Males_') !== false) {
					if ($i){
						echo ', "#'.$field->id.'"';
					} else {
						echo '"#'.$field->id.'"';
					}
					$i++;
				}
			}
		?>];
		var femalefields = [<?php
			$i = 0;
			foreach($this->form->getFieldset('gizprofile') as $field) {
				if (strpos($field->name,'percent_Females_') !== false) {
					if ($i){
						echo ', "#'.$field->id.'"';
					} else {
						echo '"#'.$field->id.'"';
					}
					$i++;
				}
			}
		?>];
		// check values of age groups at page load
		ageChecker('M');
		ageChecker('F');
		// Male checking
		jQuery.each(malefields, function(index, value) {
			jQuery(value).change(function() {
				ageChecker('M');
			});
			jQuery(value).click(function() {
				ageChecker('M');
			});
		});
		// Female checking
		jQuery.each(femalefields, function(index, value) {
			jQuery(value).change(function() {
				ageChecker('F');
			});
			jQuery(value).click(function() {
				ageChecker('F');
			});
		});
	}
	function ageChecker(type) {
		
		// count male values
		var male_percent = 0;
		jQuery.each(malefields, function(index, value) {
			var next = parseFloat(jQuery(value).val());
			if (isNaN(next)){
				next = 0;
			}
			male_percent = next+male_percent;			
		});
		// count female values
		var female_percent = 0;
		jQuery.each(femalefields, function(index, value) {
			var next = parseFloat(jQuery(value).val());
			if (isNaN(next)){
				next = 0;
			}
			female_percent = next+female_percent;			
		});
		
		if (male_percent == 100 && female_percent == 100){
			jQuery('#toolbar-apply').show();
			jQuery('#toolbar-save').show();
			jQuery('#toolbar-save-copy').show();
			jQuery('#toolbar-save-new').show();
		}
		
		if (type == 'M'){
			if(male_percent > 100){
				var note = '<li id="maleNote" style="color:red;"><i>You have exceeded 100% limit for <b>Male</b> age groups.<br/> Please reduce the percentage<br/> down from <b>' + male_percent + '%</b> to a 100%.</i></li>';
				var noteReplace = '<i>You have exceeded 100% limit for <b>Male</b> age groups.<br/> Please reduce the percentage<br/> down from <b>' + male_percent + '%</b> to a 100%.</i>';
				jQuery('#toolbar-apply').hide();
				jQuery('#toolbar-save').hide();
				jQuery('#toolbar-save-copy').hide();
				jQuery('#toolbar-save-new').hide();
				jQuery('#toLittleNoteMale').remove();
				if (!jQuery('#maleNote').length){
					jQuery('#toolbar ul').prepend(note);
				} else {
					jQuery('#maleNote').html(noteReplace);
					jQuery('#maleNote').css({"color":"red"});
				}
				
				var male_note = '<h2 id="percentMaleNote" style="color:red;">Male Age Groups Percent: ' + male_percent + '%</h2>';
				var male_noteReplace = 'Male Age Groups Percent: ' + male_percent + '%';
				if (!jQuery('#percentMaleNote').length){
					jQuery('#age_groups_set_male').prepend(male_note);
				} else {
					jQuery('#percentMaleNote').html(male_noteReplace);
					jQuery('#percentMaleNote').css({"color":"red"});
				}
				// mark field red
				jQuery.each(malefields, function(index, value) {
					jQuery(value).css({"border":"1px solid red", "color":"red"});
				});
				
			} else if(male_percent < 100){
				var note = '<li id="maleNote" style="color:blue;"><i>You need a total of 100% for <b>Male</b> age groups.<br/> Please increase the percentage<br/> from <b>' + male_percent + '%</b> to a 100%.</i></li>';
				var noteReplace = '<i>You need a total of 100% for <b>Male</b> age groups.<br/> Please increase the percentage<br/> from <b>' + male_percent + '%</b> to a 100%.</i>';
				jQuery('#toolbar-apply').hide();
				jQuery('#toolbar-save').hide();
				jQuery('#toolbar-save-copy').hide();
				jQuery('#toolbar-save-new').hide();
				if (!jQuery('#maleNote').length){
					jQuery('#toolbar ul').prepend(note);
				} else {
					jQuery('#maleNote').html(noteReplace);
					jQuery('#maleNote').css({"color":"blue"});
				}
				
				var male_note = '<h2 id="percentMaleNote" style="color:blue;">Male Age Groups Percent: ' + male_percent + '%</h2>';
				var male_noteReplace = 'Male Age Groups Percent: ' + male_percent + '%';
				if (!jQuery('#percentMaleNote').length){
					jQuery('#age_groups_set_male').prepend(male_note);
				} else {
					jQuery('#percentMaleNote').html(male_noteReplace);
					jQuery('#percentMaleNote').css({"color":"blue"});
				}
				// mark field blue
				jQuery.each(malefields, function(index, value) {
					jQuery(value).css({"border":"1px solid #4285F4", "color":"blue"});
				});
			} else if (male_percent == 100){
				// mark field red
				jQuery.each(malefields, function(index, value) {
					jQuery(value).css({"border":"1px solid #C0C0C0", "color":"black"});
				});
				// remove male top notice
				jQuery('#maleNote').remove();
				// update group notice
				var male_note = '<h2 id="percentMaleNote" style="color:black;">Male Age Groups Percent: ' + male_percent + '%</h2>';
				var male_noteReplace = 'Male Age Groups Percent: ' + male_percent + '%';
				if (!jQuery('#percentMaleNote').length){
					jQuery('#age_groups_set_male').prepend(male_note);
				} else {
					jQuery('#percentMaleNote').html(male_noteReplace);
					jQuery('#percentMaleNote').css({"color":"black"});
				}
			}
		} else if (type == 'F'){
			if(female_percent > 100){
				var note = '<li id="femaleNote" style="color:red;"><i>You have exceeded 100% limit for <b>Female</b> age groups.<br/> Please reduce the percentage<br/> down from <b>' + female_percent + '%</b> to a 100%.</i></li>';
				var noteReplace = '<i>You have exceeded 100% limit for <b>Female</b> age groups.<br/> Please reduce the percentage<br/> down from <b>' + female_percent + '%</b> to a 100%.</i>';
				jQuery('#toolbar-apply').hide();
				jQuery('#toolbar-save').hide();
				jQuery('#toolbar-save-copy').hide();
				jQuery('#toolbar-save-new').hide();
				if (!jQuery('#femaleNote').length){
					jQuery('#toolbar ul').prepend(note);
				} else {
					jQuery('#femaleNote').html(noteReplace);
					jQuery('#femaleNote').css({"color":"red"});
				}
				
				var female_note = '<h2 id="percentFemaleNote" style="color:red;">Female Age Groups Percent: ' + female_percent + '%</h2>';
				var female_noteReplace = 'Female Age Groups Percent: ' + female_percent + '%';
				if (!jQuery('#percentFemaleNote').length){
					jQuery('#age_groups_set_female').prepend(female_note);
				} else {
					jQuery('#percentFemaleNote').html(female_noteReplace);
					jQuery('#percentFemaleNote').css({"color":"red"});
				}
				// mark field red
				jQuery.each(femalefields, function(index, value) {
					jQuery(value).css({"border":"1px solid red", "color":"red"});
				});
				
			} else if(female_percent < 100){
				var note = '<li id="femaleNote" style="color:blue;"><i>You need a total of 100% for <b>Female</b> age groups.<br/> Please increase the percentage<br/> from <b>' + female_percent + '%</b> to a 100%.</i></li>';
				var noteReplace = '<i>You need a total of 100% for <b>Female</b> age groups.<br/> Please increase the percentage<br/> from <b>' + female_percent + '%</b> to a 100%.</i>';
				jQuery('#toolbar-apply').hide();
				jQuery('#toolbar-save').hide();
				jQuery('#toolbar-save-copy').hide();
				jQuery('#toolbar-save-new').hide();
				jQuery('#toMuchNoteFemale').remove();
				if (!jQuery('#femaleNote').length){
					jQuery('#toolbar ul').prepend(note);
				} else {
					jQuery('#femaleNote').html(noteReplace);
					jQuery('#femaleNote').css({"color":"blue"});
				}
				
				var female_note = '<h2 id="percentFemaleNote" style="color:blue;">Female Age Groups Percent: ' + female_percent + '%</h2>';
				var female_noteReplace = 'Female Age Groups Percent: ' + female_percent + '%';
				if (!jQuery('#percentFemaleNote').length){
					jQuery('#age_groups_set_female').prepend(female_note);
				} else {
					jQuery('#percentFemaleNote').html(female_noteReplace);
					jQuery('#percentFemaleNote').css({"color":"blue"});
				}
				// mark field blue
				jQuery.each(femalefields, function(index, value) {
					jQuery(value).css({"border":"1px solid #4285F4", "color":"blue"});
				});
			} else if (female_percent == 100){
				// mark field red
				jQuery.each(femalefields, function(index, value) {
					jQuery(value).css({"border":"1px solid #C0C0C0", "color":"black"});
				});
				// remove female top notice
				jQuery('#femaleNote').remove();
				// update group notice
				var female_note = '<h2 id="percentFemaleNote" style="color:black;">Female Age Groups Percent: ' + female_percent + '%</h2>';
				var female_noteReplace = 'Female Age Groups Percent: ' + female_percent + '%';
				if (!jQuery('#percentFemaleNote').length){
					jQuery('#age_groups_set_female').prepend(female_note);
				} else {
					jQuery('#percentFemaleNote').html(female_noteReplace);
					jQuery('#percentFemaleNote').css({"color":"black"});
				}
			}
		}
		
		//console.log(male_percent);
		//console.log(female_percent);
	}
});
</script>