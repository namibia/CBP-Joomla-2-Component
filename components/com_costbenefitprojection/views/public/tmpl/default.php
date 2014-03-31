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
defined( '_JEXEC' ) or die;

?>
<div id="loading" style="height:200px; width:100%">
	<div style="margin:0 auto; width:180px; height:24px; padding: 5px;">
    	<p style="text-align:center;"><i class="uk-icon-cog uk-icon-spin"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_PLEASE_WAIT'); ?></p>
    </div>
</div>
<div id="costbenefitprojection" style="display:none;"><!-- MAIN DIV -->
<?php if ($this->item['form']): ?>
<div data-uk-grid-margin="" class="uk-margin uk-grid">
	<div class="uk-width-medium-1"><br/>
        <form action="index.php?option=com_costbenefitprojection&view=public"
            method="post" class="form-validate uk-form uk-form-horizontal">
        
            <fieldset>
                <div class="uk-form-row">
                    <label class="uk-form-label" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_COUNTRY'); ?></label>
                    <select  name="country"  id="country">
                        <?php $i=0;foreach($this->item['countries'] as $country): ?>
                        <?php if(!$i):?>
                            <option  selected="selected" value="<?php echo $country->value; ?>"><?php echo $country->text; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $country->value; ?>"><?php echo $country->text; ?></option>
                        <?php endif; ?>
                        <?php $i++; ?>
                        <?php endforeach; ?>
                     </select>
                 </div>
                 <div class="uk-form-row">
                    <label class="uk-form-label" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_NUMBER_EMPLOYEES'); ?></label>
                    <input type="text" name="employees">
                </div>
                <div class="uk-form-row">
                    <label class="uk-form-label" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_AVERAGE_SALARY'); ?></label>
                    <input type="text" name="salary">
                </div>
                <div class="uk-form-row">
                    <label class="uk-form-label" ></label>
                    <button class="uk-button"><?php echo JText::_('COM_COSTBENEFITPROJECTION_SUBMIT'); ?></button>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<?php else: ?>
<br/>
<div class="uk-grid">
	<div class="uk-width-medium-1-1">

        <ul class="uk-tab uk-tab-grid uk-animation-slide-top" data-uk-tab="{connect:'#tab-public'}" >
            <li class="uk-active uk-width-1-3"><a href="">Annual Cost</a></li>
            <li class="uk-width-1-3"><a href="">Annual Costs Saved</a></li>
            <li class="uk-width-1-3"><a href="">Full Access</a></li>
        </ul>
        
        <ul class="uk-switcher" id="tab-public" data-uk-grid-margin>
            <li class="uk-active"><?php echo $this->loadTemplate('chart_cost'); ?></li>
            <li class="" ><?php echo $this->loadTemplate('chart_intervention_cost_benefit'); ?></li>
            <li class="" ><?php echo $this->loadTemplate('contact_form'); ?></li>
        </ul>
    
    </div>
</div>
<br/>
<div class="uk-panel uk-width-1-2 uk-container-center uk-text-center" data-uk-scrollspy="{cls:'uk-animation-fade', delay:300, repeat: true}">
	<a data-uk-offcanvas="{target:'#offcanvas-info'}" href="" class="uk-button uk-button-small"><i class="uk-icon-cog uk-icon-spin"></i> <?php echo JText::_('COM_COSTBENEFITPROJECTION_TOOL'); ?></a>
</div>
<?php endif; ?>
</div><!-- MAIN DIV -->

<div class="uk-offcanvas" id="offcanvas-info">
	<div class="uk-offcanvas-bar uk-offcanvas-bar-flip">
        <div class="uk-panel">
            <div class="uk-animation-fade uk-animation-scale-down">
            	<img style="display: block; margin-left: auto; margin-right: auto; vertical-align: middle;" src="images/box.png" alt="box" />
            </div>
            <p class="uk-text-small uk-text-bold">
                <i class="uk-icon-cog uk-icon-spin"></i> <?php echo $this->xml->description; ?>
            </p>
            <ul class="uk-list uk-list-striped">
                <li class="uk-text-small"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CURRENT_INSTALLED_VERSION'); ?> <strong><span style="color:#DBA400;"><?php echo $this->xml->version; ?></span></strong></li>
                <li class="uk-text-small"><?php echo $this->xml->copyright; ?> </li>
                <li class="uk-text-small"><?php echo JText::_('COM_COSTBENEFITPROJECTION_LICENSE'); ?> <a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank"><?php echo JText::_('COM_COSTBENEFITPROJECTION_GNU_GPL'); ?></a> <?php echo JText::_('COM_COSTBENEFITPROJECTION_COMMERCIAL'); ?></li>
                <?php if ($this->workers): ?>
					<?php foreach($this->workers as $worker): ?>
                        <li class="uk-text-small"><?php echo $worker['title']; ?>
                            <br/><?php echo $worker['load']; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if ($this->params->get('lvdm_front') == 1) : ?>
                <li class="uk-text-small"><?php echo JText::_('COM_COSTBENEFITPROJECTION_APPLICATION_ENGINEER'); ?> <br/><a href="http://<?php echo $this->xml->authorUrl; ?>" target="_blank"><?php echo $this->xml->author; ?></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">


// page loading pause
jQuery(window).load(function() {
	jQuery('#loading').fadeOut( 'fast', function() {
		jQuery('#costbenefitprojection').fadeIn('fast');
	});
});

</script>