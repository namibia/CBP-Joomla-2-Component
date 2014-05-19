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

// No direct access.
defined('_JEXEC') or die;

if($this->result->interventions){
	foreach ($this->scale as $scale){
		$intervention_number = 0;
		foreach ($this->result->interventions as $intervention){
			if($intervention->nr_found){
				$i =0;
				$rowArray = array();
				if(is_array($intervention->items) || is_object($intervention->items)){
					foreach ($intervention->items as $key => $item){
						$rowArray[] = array('c' => array(
								array('v' => $item->name),
								array('v' => round($item->{'cost_of_problem_'.$scale}), 'f' => $item->{'costmoney_of_problem_'.$scale}), 
								array('v' => $item->annual_cost, 'f' => $item->annual_costmoney), 
								array('v' => $item->{'net_benefit_'.$scale}, 'f' => $item->{'netmoney_benefit_'.$scale})
						));
						$i++;
					}
				}
				usort($rowArray, function($b, $a) {
					return $a['c'][3]['v'] - $b['c'][3]['v'];
				});
					
				$data = array(
						'cols' => array(
								array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'), 'type' => 'string'),
								array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_COST_PROBLEM'), 'type' => 'number'),
								array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_INTERVENTION'), 'type' => 'number'),
								array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_NET_BENEFIT'), 'type' => 'number')
						),
						'rows' => $rowArray
				);
				
				$height = ($i * 80)+100;
				$this->builder->load(json_encode($data));
				$options = array();
				$main_title = JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NAME_TITLE', $intervention->name);
				$title =  '';
				if($intervention->duration > 1){
					$title .= JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATIONS_TITLE', $intervention->duration); 
				} else {
					$title .= JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATION_TITLE', $intervention->duration);
				}  
				$title .= ' | ' . JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_COVERAGE_TITLE', round($intervention->coverage)). '%';
				
				$options =	array( 'title' => $main_title, 'colors' => array('#DC3912','#3366CC','#FF9900'), 'backgroundColor' => $this->Chart['backgroundColor'], 'width' => $this->Chart['width'], 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('textStyle' => $this->Chart['hAxis']['textStyle'], 'title' => $title, 'titleTextStyle' => $this->Chart['hAxis']['titleTextStyle']));
				
				echo $this->builder->draw('icb_'.$intervention_number.'_'.$scale, $options);
				$intervention_number++;
			} else {
				$no_intervention[] = $intervention->name;
			}
		}
	}
}

/* <h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_INTERVENTION_COST_BENEFIT_TITLE'); ?></h1> */
?>
<!-- This is the subnav containing the toggling elements -->
<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#chartICB'}">
    <li class="uk-active uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></a></li>
</ul>
<br/>
<?php if ($this->result->interventions) : ?>
<?php if(is_array($no_intervention)): ?>
    <div class="uk-alert" data-uk-alert>
        <a href="" class="uk-alert-close uk-close"></a>
        <p>The intervention<?php
            $no_intervention = array_unique($no_intervention);
            $size = sizeof($no_intervention);
            if($size > 1){ echo 's';}
            $a = 0;
            foreach($no_intervention as $name){
                if($a){
                    echo ', <strong>'.$name.'</strong>';
                } else {
                    echo ' named <strong>'.$name.'</strong>';
                }
                $a++;
            }
            
        ?> has no effect on your selected causes/risks</p>
    </div>
<?php endif; ?>
<!-- This is the container of the content items -->
<ul id="chartICB" class="uk-switcher">
    <?php foreach ($this->scale as $scale): ?>
	<?php $intervention_number = 0; ?>
       	<li class="<?php echo ($scale == 'unscaled') ? 'uk-active default' : 'scalling'; ?>" >
        	<?php foreach ($this->result->interventions as $intervention) :?>
				<?php if ($intervention->nr_found) : ?>
                    <div id="icb_<?php echo $intervention_number; ?>_<?php echo $scale; ?>" class="chart" style="height:100%; width:100%;"></div>
                    <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getPDF(document.getElementById('icb_<?php echo $intervention_number; ?>_<?php echo $scale; ?>'),'<?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_INTERVENTION_COST_BENEFIT_TITLE'); ?>'+' (<?php echo ($scale == 'unscaled') ? JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT') : JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>)');"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PDF_DOWNLOAD'); ?></button>
                <?php $intervention_number++; ?>
				<?php endif; ?>
            <?php endforeach; ?>
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
    <h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_INTERVENTION_SELECTED'); ?></h2>
<?php endif; ?>