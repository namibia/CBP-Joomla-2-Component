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
			
			echo $chart->draw('save_'.$intervention_number.'_default_div', $options);
		}
		// HAS scaling factor
		$chart = new Chartbuilder('BarChart');
		
		$i_HAS_SF = 0;
		if(is_array($intervention['disease'])){
			
			foreach ($intervention['disease'] as $key => $item){
				$rowArray_HAS_SF[$intervention_number][] = array('c' => array(
								array('v' => $item['name']),
								array('v' => (float)$item['intervention_annual_cost'], 'f' => $item['intervention_annual_cost_money']), 
								array('v' => (float)$item['intervention_annual_benefit_HAS_SF'], 'f' => $item['intervention_annual_benefit_money_HAS_SF']), 
								array('v' => (float)$item['intervention_annual_net_benefit_HAS_SF'], 'f' => $item['intervention_annual_net_benefit_money_HAS_SF'])
						));
				$i_HAS_SF++;
			}
		}
		if(is_array($intervention['risk'])){
			foreach ($intervention['risk'] as $key => $item){
				$rowArray_HAS_SF[$intervention_number][] = array('c' => array(
								array('v' => $item['name']),
								array('v' => (float)$item['intervention_annual_cost'], 'f' => $item['intervention_annual_cost_money']), 
								array('v' => (float)$item['intervention_annual_benefit_HAS_SF'], 'f' => $item['intervention_annual_benefit_money_HAS_SF']), 
								array('v' => (float)$item['intervention_annual_net_benefit_HAS_SF'], 'f' => $item['intervention_annual_net_benefit_money_HAS_SF'])
						));
				$i_HAS_SF++;
			}
		}
		if(is_array($intervention['risk']) || is_array($intervention['disease'])){
			usort($rowArray_HAS_SF[$intervention_number], function($b, $a) {
				return $a['c'][3]['v'] - $b['c'][3]['v'];
			});
			
			$data = array(
					'cols' => array(
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'), 'type' => 'string'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_INTERVENTION'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_BENEFIT'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_NET_BENEFIT'), 'type' => 'number')
					),
					'rows' => $rowArray_HAS_SF[$intervention_number]
			);
			
			
			$height_HAS_SF = ($i_HAS_SF * 80)+100;
			$chart->load(json_encode($data));
			$options = array();
			$main_title =JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_NAME_TITLE', $intervention['name']);
			$title =  '';
			if($intervention['intervention_duration'] > 1){
                $title .= JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATIONS_TITLE', $intervention['intervention_duration']); 
			} else {
				$title .= JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_DURATION_TITLE', $intervention['intervention_duration']);
			} 
          	$title .= ' | ' . JText::sprintf('COM_COSTBENEFITPROJECTION_TABLES_INTERVENTION_COVERAGE_TITLE', $intervention['intervention_coverage']) . '%';
			$options = array( 'backgroundColor' => $this->Chart['backgroundColor'], 'title' => $main_title, 'colors' => array('#DC3912','#3366CC','#FF9900'), 'width' => $this->Chart['width'], 'height' => $height_HAS_SF, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('viewWindowMode' => 'max','textStyle' => array('color' => '#63B1F2'), 'title' => $title, 'titleTextStyle' => array('color' => '#63B1F2','fontSize' => 15)));
			
			echo $chart->draw('save_'.$intervention_number.'_sf_div', $options);
		}
		$intervention_number++;
	}
}

/* <h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_INTERVENTION_COST_BENEFIT_TITLE'); ?></h1> */
?>
<!-- This is the subnav containing the toggling elements -->
<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#chartSAVE'}">
    <li class="uk-active uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></a></li>
</ul>
<br/>
<?php if($results['interventions']): ?>
<!-- This is the container of the content items -->
<ul id="chartSAVE" class="uk-switcher">
    <li class="uk-active default" >
    	<?php $i = 0; $len = count($results['interventions']); foreach ($results['interventions'] as $intervention) :?>
        	<?php if ($i !== 0): ?>
            <br/>
            <?php endif; ?>
            <div id="save_<?php echo $i; ?>_default_div" class="chart" style="height:100%; width:100%;"></div>
            <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getPDF(document.getElementById('save_<?php echo $i; ?>_default_div'),'<?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_INTERVENTION_COST_BENEFIT_TITLE'); ?>' 
			+' (<?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>)');">
            <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PDF_DOWNLOAD'); ?></button>
            <?php if ($i !== $len - 1): ?>
            <hr class="uk-grid-divider">
            <?php endif; ?>
            <?php $i++; ?>
        <?php endforeach; ?>
    </li>
    <li class="scalling" >
    	<?php $i_HAS_SF = 0; $len = count($results['interventions']); foreach ($results['interventions'] as $intervention) :?>
            <?php if ($i_HAS_SF !== 0): ?>
            <br/>
            <?php endif; ?>
            <div id="save_<?php echo $i_HAS_SF; ?>_sf_div" class="chart" style="height:100%; width:100%;"></div>
            <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getPDF(document.getElementById('save_<?php echo $i_HAS_SF; ?>_sf_div'),'<?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_INTERVENTION_COST_BENEFIT_TITLE'); ?>' 
			+' (<?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>)');">
            <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PDF_DOWNLOAD'); ?></button>
            <?php if ($i_HAS_SF !== $len - 1): ?>
            <hr class="uk-grid-divider">
            <?php endif; ?>
            <?php $i_HAS_SF++; ?>
        <?php endforeach; ?>
    </li>
</ul> 
<?php else: ?>
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_INTERVENTION_SELECTED'); ?></h2>
<?php endif; ?>