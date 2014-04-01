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
$results = $this->item['results'];

// default
if($results['interventions']){
	$intervention_number = 0;
	foreach ($results['interventions'] as $intervention){
		$chart = new Chartbuilder('BarChart');
		$i = 0;
		if(is_array($intervention['disease'])){
			foreach ($intervention['disease'] as $key => $item){
				$rowArray[$intervention_number][] = array('c' => array(
						array('v' => $item['name']),
						array('v' => (float)$item['intervention_annual_cost'], 'f' => $item['intervention_annual_cost_money']), 
						array('v' => (float)$item['intervention_annual_benefit'], 'f' => $item['intervention_annual_benefit_money']), 
						array('v' => (float)$item['intervention_annual_net_benefit'], 'f' => $item['intervention_annual_net_benefit_money'])
				));
				$i++;
			}
		}
		if(is_array($intervention['risk'])){
			foreach ($intervention['risk'] as $key => $item){
				$rowArray[$intervention_number][] = array('c' => array(
						array('v' => $item['name']),
						array('v' => (float)$item['intervention_annual_cost'], 'f' => $item['intervention_annual_cost_money']), 
						array('v' => (float)$item['intervention_annual_benefit'], 'f' => $item['intervention_annual_benefit_money']), 
						array('v' => (float)$item['intervention_annual_net_benefit'], 'f' => $item['intervention_annual_net_benefit_money'])
				));
				$i++;
			}
		}
		if(is_array($intervention['risk']) || is_array($intervention['disease'])){
			usort($rowArray[$intervention_number], function($b, $a) {
				return $a['c'][3]['v'] - $b['c'][3]['v'];
			});
			
			$data = array(
					'cols' => array(
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'), 'type' => 'string'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_INTERVENTION'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_BENEFIT'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_NET_BENEFIT'), 'type' => 'number')
					),
					'rows' => $rowArray[$intervention_number]
			);
			
			$height = ($i * 80)+100;
			$chart->load(json_encode($data));
			$options = array();
			$main_title = JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NAME_TITLE', $intervention['name']);
			$title =  '';
			if($intervention['intervention_duration'] > 1){
                $title .= JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATIONS_TITLE', $intervention['intervention_duration']); 
			} else {
				$title .= JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATION_TITLE', $intervention['intervention_duration']);
			} 
          	$title .= ' | ' . JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_COVERAGE_TITLE', $intervention['intervention_coverage']) . '%';
			$options = array( 'backgroundColor' => $this->Chart['backgroundColor'], 'title' => $main_title, 'colors' => array('#DC3912','#3366CC','#FF9900'), 'width' => $this->Chart['width'], 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('viewWindowMode' => 'max','textStyle' => array('color' => '#63B1F2'), 'title' => $title, 'titleTextStyle' => array('color' => '#63B1F2','fontSize' => 15)));
			
			echo $chart->draw('icb_'.$intervention_number.'_default_div', $options);
		}
		$intervention_number++;
	}
}

/* <h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_INTERVENTION_COST_BENEFIT_TITLE'); ?></h1> */
?>
<br/>
<?php if($results['interventions']): ?>
<div class="uk-grid">
    <div class="uk-width-medium-1-1">
        <div class="uk-panel uk-animation-slide-left" style="z-index: -1;"><p class="uk-text-large uk-text-center">Select Workplace Health and Wellness Interventions and see the projected annual benefit on the workplace.</p></div>
    </div>
</div>
<div class="uk-grid">
	<div class="uk-width-medium-2-10 uk-push-8-10">
    
        <div class="uk-panel uk-animation-slide-right">
            <p class="uk-text-small" style="z-index: -1;">Intervention Options</p>
            <ul id="int_select" data-uk-tab="{connect:'#tab-left-intervention'}" class="uk-tab uk-tab-right">
                <?php $i = 0; foreach ($results['interventions'] as $intervention) :?>
                    <?php if ($i == 0) : ?>
                        <li class="uk-active"><a class="uk-icon-check-square-o" href="#"> <?php echo $intervention['name']; ?></a></li>
                    <?php else: ?>
                        <li class=""><a class="uk-icon-square-o" href="#"> <?php echo $intervention['name']; ?></a></li>
                    <?php endif; ?>
                    <?php $i++; ?>
                <?php endforeach; ?>
            </ul>
         </div>

    </div>
    <div class="uk-width-medium-8-10 uk-pull-2-10 uk-animation-slide-left">

        <ul class="uk-switcher" id="tab-left-intervention">
            <!-- This is the container of the content items -->
            <?php $i = 0; foreach ($results['interventions'] as $intervention) :?>
                <?php if ($i == 0) : ?>
                    <li class="uk-active">
                    	<p class="uk-text-success uk-text-center">Potential Benefit of Workplace Health and Wellness Interventions</p>
                    	<div id="icb_<?php echo $i; ?>_default_div" class="chart" style="height:100%; width:100%;"></div>
                    </li>
                <?php else: ?>
                    <li class="">
                    	<p class="uk-text-success uk-text-center">Potential Benefit of Workplace Health and Wellness Interventions</p>
                    	<div id="icb_<?php echo $i; ?>_default_div" class="chart" style="height:100%; width:100%;"></div>
                    </li>
                <?php endif; ?>
                <?php $i++; ?>
            <?php endforeach; ?>
        </ul>

    </div>
</div>
<div class="uk-grid uk-animation-slide-right">    
    <div class="uk-width-medium-1-1 ">
    	<div class="uk-panel">
        	<p class="uk-text-info uk-text-center">Having seen the health priorities for your workforce, the tool outputs projections for how the interventions – which <b>you have designed</b>  - are expected to benefit the company financially.</p>
        	<p class="uk-text-info uk-text-center">Benefits are calculated based on the model projecting reductions in workdays lost due to sickness, presenteeism and death.</p>
        </div>
    </div>   
</div>
<?php else: ?>
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_INTERVENTION_SELECTED'); ?></h2>
<?php endif; ?>
<script type="text/javascript">
jQuery("#int_select a").click(function(){
	jQuery("#int_select a").removeClass("uk-icon-check-square-o").addClass("uk-icon-square-o");
	jQuery(this).removeClass("uk-icon-square-o").addClass("uk-icon-check-square-o");
});
</script>