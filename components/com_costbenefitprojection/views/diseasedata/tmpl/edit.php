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
<div id="costbenefitprojection">
    <form action="index.php?option=com_costbenefitprojection&amp;id=<?php echo $this->item->id ?>"
        method="post" name="adminForm" class="form-validate uk-form">
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
        <button onclick="Joomla.submitbutton('diseasedata.save')" class="uk-button uk-button-primary"><i class="uk-icon-save"></i> Save</button> 
        <button onclick="Joomla.submitbutton('diseasedata.cancel')" class="uk-button"><i class="uk-icon-times"></i> Close</button>
        <input type="hidden" name="task" value="" />
        <?php if ($this->tmpl == 'component'): ?><input type="hidden" name="tmpl" value="component" /><?php endif; ?>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>