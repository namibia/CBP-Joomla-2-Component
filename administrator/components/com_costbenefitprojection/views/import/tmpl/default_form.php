<?php
/**
* 
* 	@version 	2.0.0 March 13, 2014
* 	@package 	Staff Health Cost Benefit Projection
* 	@editor  	Vast Development Method <http://www.vdm.io>
* 	@copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
* 	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
**/

// no direct access
defined('_JEXEC') or die;
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton) {
		var form = document.getElementById('adminForm');

		// do field validation
		if (form.install_package.value == ""){
			alert("<?php echo JText::_('COM_COSTBENEFITPROJECTION_IMPORT_INSTALL_PLEASE_SELECT_A_PACKAGE', true); ?>");
		} else if (form.import_type.value == ""){
			alert("<?php echo JText::_('COM_COSTBENEFITPROJECTION_SELECT_DATA_TYPE_ALERT', true); ?>");			
		} else if (form.import_year.value == "" || form.import_year.value == "YYYY"){
			alert("<?php echo JText::_('COM_COSTBENEFITPROJECTION_SET_YEAR_ALERT', true); ?>");			
		} else {
			form.installtype.value = 'upload';
			form.submit();
		}
	}

	Joomla.submitbuttonURL = function(pressbutton) {
		var form = document.getElementById('adminForm');

		// do field validation
		if (form.install_url.value == "" || form.install_url.value == "http://"){
			alert("<?php echo JText::_('COM_COSTBENEFITPROJECTION_INSTALL_ENTER_A_URL', true); ?>");
		} else if (form.import_type.value == ""){
			alert("<?php echo JText::_('COM_COSTBENEFITPROJECTION_SELECT_DATA_TYPE_ALERT', true); ?>");			
		} else if (form.import_year.value == "" || form.import_year.value == "YYYY"){
			alert("<?php echo JText::_('COM_COSTBENEFITPROJECTION_SET_YEAR_ALERT', true); ?>");			
		} else {
			form.installtype.value = 'url';
			form.submit();
		}
	}
</script>

<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_costbenefitprojection&view=import');?>" method="post" name="adminForm" id="adminForm">
	
    <div class="width-70 fltlft">
    	<fieldset class="uploadform">
            <legend><?php echo JText::_('COM_COSTBENEFITPROJECTION_SELECT_DATA_SETTINGS'); ?></legend>
            <label class="uk-form-label" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_DATA_TYPE'); ?></label>
            <select  name="import_type"  id="import_type" required class="required" >
                <option  selected="selected" value="">-- Please Select --</option>
                <option value="1">Cause</option>
                <option value="2">Risk</option>
                <!-- <option value="3">List of Causes & Risks</option> -->
             </select>
             <label class="uk-form-label" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_YEAR_TO_IMPORT'); ?></label>
             <input type="text" id="import_year" name="import_year" class="input_box" size="20" value="YYYY" />
        </fieldset>
	<?php /*?><div class="clr"></div>
		<fieldset class="uploadform">
			<legend><?php echo JText::_('COM_COSTBENEFITPROJECTION_UPLOAD_PACKAGE_FILE'); ?></legend>
			<label for="install_package"><?php echo JText::_('COM_COSTBENEFITPROJECTION_PACKAGE_FILE'); ?></label>
			<input class="input_box" id="install_package" name="install_package" type="file" size="57" />
			<input class="button" type="button" value="<?php echo JText::_('COM_COSTBENEFITPROJECTION_UPLOAD_AND_INSTALL'); ?>" onclick="Joomla.submitbutton()" />
        </fieldset><?php */?>
	<div class="clr"></div>
		<fieldset class="uploadform">
			<legend><?php echo JText::_('COM_COSTBENEFITPROJECTION_INSTALL_FROM_URL'); ?></legend>
			<label for="install_url"><?php echo JText::_('COM_COSTBENEFITPROJECTION_INSTALL_URL'); ?></label>
			<input type="text" id="install_url" name="install_url" class="input_box" size="70" value="http://" />
			<input type="button" class="button" value="<?php echo JText::_('COM_COSTBENEFITPROJECTION_INSTALL_BUTTON'); ?>" onclick="Joomla.submitbuttonURL()" />
		</fieldset>
		<input type="hidden" name="type" value="" />
		<input type="hidden" name="installtype" value="upload" />
		<input type="hidden" name="task" value="import.import" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>