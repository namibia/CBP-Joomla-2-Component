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

// No direct access.
defined('_JEXEC') or die;

?>
<div class="uk-grid">
	<div class="uk-width-medium-1-1">
        <div class="uk-panel uk-panel-space" style="z-index: -1;">
        	<h3 class="uk-text-info uk-text-center uk-animation-scale-up">Enter your details here for access to the full tool and technical support in establishing your Cost-Benefit Profile for a healthier workplace.</h3>
        </div>
    </div>
</div>
<div class="uk-grid" data-uk-grid-match>
    <div class="uk-width-medium-1-2 uk-push-1-2 uk-animation-slide-bottom">
        <div class="uk-panel uk-panel-box" style="z-index: -1;">
            <p class="uk-text-large uk-text-muted uk-text-center uk-animation-scale-up">Please note that the tool itself is FREE TO USE</p>
            <p class="uk-text-large uk-text-info uk-animation-slide-bottom">Benefits of Registering:</p>
            <ul class="uk-list uk-list-line uk-list-space uk-animation-slide-bottom">
                <li>Access to Technical Support in using model and creating/running/monitoring workplace wellness programmes</li>
                <li>Use of full tool functions including:
                    <ul class="uk-list uk-list-striped">
                        <li>Create Epidemiological Profiles for diseases and risks affecting your company</li>
                        <li>Detailed information on projected work days lost and costs</li>
                        <li>Create and edit activities and wellness responses for your workplace</li>
                        <li>Access to Small Business Modelling</li>
                        <li>Results displayed by gender</li>
                    </ul>
                </li>
                <li>Network with other companies and wellness professionals</li>
            </ul>
            <?php if ($this->item['data']['publicName']): ?>
            <div class="uk-grid">
                <p class="uk-text-large uk-text-muted uk-text-center">Contact <?php echo $this->item['data']['publicName']; ?></p>
                <div class="uk-width-1-3">
                    <p><img data-uk-scrollspy="{cls:'uk-animation-fade', delay:500, repeat: true}" class="uk-thumbnail" src="<?php echo $this->item['data']['Gravatar']; ?>" title="<?php echo $this->item['data']['publicName']; ?>" alt="image" /></p>
                </div>
                <div class="uk-width-2-3">
                    <ul class="uk-list uk-list-line">
                        <?php if ($this->item['data']['publicEmail']): ?>
                            <li><i class="uk-icon-envelope-o" data-uk-scrollspy="{cls:'uk-animation-fade', delay:200, repeat: true}"></i> 
                            	<a href="mailto:<?php echo $this->item['data']['publicEmail']; ?>" target="_blank" title="email us"><?php echo $this->item['data']['publicEmail']; ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->item['data']['publicNumber']): ?>
                            <li><i class="uk-icon-phone" data-uk-scrollspy="{cls:'uk-animation-fade', delay:300, repeat: true}"></i> <?php echo $this->item['data']['publicNumber']; ?></li>
                        <?php endif; ?>
                        <?php if ($this->item['data']['publicAddress']): ?>
                            <li><i class="uk-icon-map-marker" data-uk-scrollspy="{cls:'uk-animation-fade', delay:400, repeat: true}"></i>  <?php echo $this->item['data']['publicAddress']; ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
	</div>
    <div class="uk-width-medium-1-2 uk-pull-1-2 uk-animation-slide-left">
    
		<?php if($this->loadModule['publicForm']) echo $this->loadModule['publicForm']; ?>
        
    </div>
</div>
<script type="text/javascript">

// set Countries
jQuery( document ).ready(function() {
	
	var hidden = '<?php echo $this->item['data']['visitor']; ?>';
	
	jQuery('#hidden').val(hidden);
	
	var Countries = {
	<?php $i=0;foreach($this->item['countries'] as $country): ?>
	<?php if(!$i):?>
		"<?php echo $country->value; ?>":"<?php echo $country->text; ?>"
	<?php else: ?>
		,"<?php echo $country->value; ?>":"<?php echo $country->text; ?>"
	<?php endif; ?>
	<?php $i++; ?>
	<?php endforeach; ?>
	};
	var selectedCountry = <?php echo $this->item['data']['country']; ?>;
	jQuery.each(Countries, function(key, value) {
		if (selectedCountry == key){
			jQuery('#Country')
				 .append(jQuery("<option></option>")
				 .attr("value",key)
				 .attr("selected","selected")
				 .text(value));
		}
	});
	
});

</script>