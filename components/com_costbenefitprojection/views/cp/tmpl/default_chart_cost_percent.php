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
$chart = new Chartbuilder('BarChart');

$i = 0;
if ($results['disease']){
	foreach ($results['disease'] as $key => $item){
		$rowArray[] = array('c' => array(
						array('v' => $item['disease_name']),
						array('v' => (float)round(($item['total_cost'] / $results['totalCost'])*100, 2), 'f' => (float)round(($item['total_cost'] / $results['totalCost'])*100, 2).'%')
				));
		$i++;
	}
}
if ($results['risk']){
	foreach ($results['risk'] as $key => $item){
		$rowArray[] = array('c' => array(
						array('v' => $item['risk_name']),
						array('v' => (float)round(($item['total_cost_risk'] / $results['totalCost'])*100, 2),'f' => (float)round(($item['total_cost_risk'] / $results['totalCost'])*100, 2).'%')
				));
		$i++;
	}
}
if ($results['risk'] || $results['disease']){
	usort($rowArray, function($b, $a) {
		return $a['c'][1]['v'] - $b['c'][1]['v'];
	});
	
	$data = array(
			'cols' => array(
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'), 'type' => 'string'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_PERCENT_TOTAL_COST_PER_DR_LABEL'), 'type' => 'number')
			),
			'rows' => $rowArray
	);
	
	$height = ($i * 70)+100;
	$chart->load(json_encode($data));
	$options = array( 'backgroundColor' => $this->Chart['backgroundColor'], 'width' => $this->Chart['width'], 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('textStyle' => $this->Chart['hAxis']['textStyle'], 'title' => JText::_('COM_COSTBENEFITPROJECTION_CHARTS_COST_PERCENT_SUBTITLE'), 'titleTextStyle' => $this->Chart['hAxis']['titleTextStyle']));
	
	echo $chart->draw('cp_default_div', $options);
}
// HAS scaling factor
$chart = new Chartbuilder('BarChart');

$i = 0;
if ($results['disease']){
	foreach ($results['disease'] as $key => $item){
		$rowArray_HAS_SF[] = array('c' => array(
						array('v' => $item['disease_name']), 
						array('v' => (float)round(($item['total_cost_HAS_SF'] / $results['totalCost_HAS_SF'])*100, 2), 'f' => (float)round(($item['total_cost_HAS_SF'] / $results['totalCost_HAS_SF'])*100, 2).'%')
				));
		$i++;
	}
}
if ($results['risk']){
	foreach ($results['risk'] as $key => $item){
		$rowArray_HAS_SF[] = array('c' => array(
						array('v' => $item['risk_name']), 
						array('v' => (float)round(($item['total_cost_risk_HAS_SF'] / $results['totalCost_HAS_SF'])*100, 2),'f' => (float)round(($item['total_cost_risk_HAS_SF'] / $results['totalCost_HAS_SF'])*100, 2).'%')
				));
		$i++;
	}
}
if ($results['risk'] || $results['disease']){
	usort($rowArray_HAS_SF, function($b, $a) {
		return $a['c'][1]['v'] - $b['c'][1]['v'];
	});
	
	$data = array(
			'cols' => array(
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'), 'type' => 'string'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_PERCENT_TOTAL_COST_PER_DR_LABEL'), 'type' => 'number')
			),
			'rows' => $rowArray_HAS_SF
	);
	
	
	$height = ($i * 70)+100;
	$chart->load(json_encode($data));
	$options = array( 'backgroundColor' => $this->Chart['backgroundColor'],  'width' => $this->Chart['width'], 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('textStyle' => $this->Chart['hAxis']['textStyle'], 'title' => JText::_('COM_COSTBENEFITPROJECTION_CHARTS_COST_PERCENT_SUBTITLE'), 'titleTextStyle' => $this->Chart['hAxis']['titleTextStyle']));
	
	echo $chart->draw('cp_sf_div', $options);
}
// HAS one episode
$chart = new Chartbuilder('BarChart');

$i = 0;
if ($results['disease']){
	foreach ($results['disease'] as $key => $item){
		$rowArray_HAS_OE[] = array('c' => array(
						array('v' => $item['disease_name']), 
						array('v' => (float)round(($item['total_cost_HAS_OE'] / $results['totalCost_HAS_OE'])*100, 2), 'f' => (float)round(($item['total_cost_HAS_OE'] / $results['totalCost_HAS_OE'])*100, 2).'%')
				));
		$i++;
	}
	
	usort($rowArray_HAS_OE, function($b, $a) {
		return $a['c'][1]['v'] - $b['c'][1]['v'];
	});
	
	$data = array(
			'cols' => array(
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'), 'type' => 'string'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_PERCENT_TOTAL_COST_PER_DR_LABEL'), 'type' => 'number')
			),
			'rows' => $rowArray_HAS_OE
	);
	
	$height = ($i * 70)+100;
	$chart->load(json_encode($data));
	$options = array( 'backgroundColor' => $this->Chart['backgroundColor'],  'width' => $this->Chart['width'], 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('textStyle' => $this->Chart['hAxis']['textStyle'], 'title' => JText::_('COM_COSTBENEFITPROJECTION_CHARTS_COST_PERCENT_OE_SUBTITLE'), 'titleTextStyle' => $this->Chart['hAxis']['titleTextStyle']));
	
	echo $chart->draw('cp_oe_div', $options);
}
/* <h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_COST_PERCENT_TITLE'); ?></h1> */
?>
<!-- This is the subnav containing the toggling elements -->
<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#chartCP'}">
    <li class="uk-active uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?></a></li>
</ul>
<br/>
<?php if ($results['risk'] || $results['disease']) : ?>
<!-- This is the container of the content items -->
<ul id="chartCP" class="uk-switcher">
    <li class="uk-active default" >
    	<div id="cp_default_div" class="chart" style="height:100%; width:100%;"></div>
        <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getPDF(document.getElementById('cp_default_div'),'<?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_COST_PERCENT_TITLE'); ?>' 
		+' (<?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>)');">
        <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PDF_DOWNLOAD'); ?></button>
    </li>
    <li class="scalling" >
    	<div id="cp_sf_div" class="chart" style="height:100%; width:100%;"></div>
        <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getPDF(document.getElementById('cp_sf_div'),'<?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_COST_PERCENT_TITLE'); ?>' 
		+' (<?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>)');">
        <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PDF_DOWNLOAD'); ?></button>
    </li>
    <li class="episode" >
		<div id="cp_oe_div" class="chart" style="height:100%; width:100%;"></div>
        <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getPDF(document.getElementById('cp_oe_div'),'<?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_COST_PERCENT_TITLE'); ?>' 
		+' (<?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?>)');">
        <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PDF_DOWNLOAD'); ?></button>
    </li>
</ul>  
<?php else: ?>
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASE_RISK_SELECTED'); ?></h2>
<?php endif; ?>