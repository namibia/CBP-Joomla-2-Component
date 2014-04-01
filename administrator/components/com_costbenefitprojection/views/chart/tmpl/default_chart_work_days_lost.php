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
$chart = new Chartbuilder('BarChart');
$i =0;
if($results['disease']){
	foreach ($results['disease'] as $key => $item){
		$rowArray[] = array('c' => array(
						array('v' => $item['disease_name']), 
						array('v' => (float)$item['total_absence_days']), 
						array('v' => (float)$item['total_unproductive_days']), 
						array('v' => (int)0), 
						array('v' => (float)$item['total_death_absence_days']), 
						array('v' => (float)$item['total_days_lost']), 
						array('v' => (float)round(($item['total_days_lost'] / $results['totalDaysLost'])*100, 2), 'f' => (float)round(($item['total_days_lost'] / $results['totalDaysLost'])*100, 2).'%')
				));
		$i++;
	}
}
if($results['risk']){
	foreach ($results['risk'] as $key => $item){
		$rowArray[] = array('c' => array(
						array('v' => $item['risk_name']), 
						array('v' => (int)0), 
						array('v' => (int)0), 
						array('v' => (float)$item['total_risk_absence_days']), 
						array('v' => (int)0), 
						array('v' => (float)$item['total_risk_absence_days']), 
						array('v' => (float)round(($item['total_risk_absence_days'] / $results['totalDaysLost'])*100, 2),'f' => (float)round(($item['total_risk_absence_days'] / $results['totalDaysLost'])*100, 2).'%')
				));
		$i++;
	}
}
if($results['risk'] || $results['disease']){
	usort($rowArray, function($b, $a) {
		return $a['c'][5]['v'] - $b['c'][5]['v'];
	});
	
	$data = array(
			'cols' => array(
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'), 'type' => 'string'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DAYS_MORBIDITY_LABEL'), 'type' => 'number'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DAYS_PRESENTEEISM_LABEL'), 'type' => 'number'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DAYS_PRESENTEEISM_RISK_FACTOR_LABEL'), 'type' => 'number'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DAYS_MORTALITY_LABEL'), 'type' => 'number'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_TOTAL_LOST_DAYS_PER_DR_LABEL'), 'type' => 'number'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_PERCENT_TOTAL_LOST_DAYS_PER_DR_LABEL'), 'type' => 'number')
			),
			'rows' => $rowArray
	);
	
	$height = ($i * 110)+100;
	$chart->load(json_encode($data));
	$options = array( 'width' => 900, 'height' => $height, 'chartArea' => array('top' => 0, 'left' => 170, 'width' => 560), 'legend' => array( 'textStyle' => array('fontSize' => 10, 'color' => '#63B1F2')), 'vAxis' => array('textStyle' => array('color' => '#63B1F2')), 'hAxis' => array('textStyle' => array('color' => '#63B1F2'), 'title' => JText::_('COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_SUBTITLE'), 'titleTextStyle' => array('color' => '#63B1F2')));

echo $chart->draw('wdl_default_div', $options);
}

// HAS scaling factor
$chart = new Chartbuilder('BarChart');

$i =0;
if($results['disease']){
	foreach ($results['disease'] as $key => $item){
		$rowArray_HAS_SF[] = array('c' => array(
						array('v' => $item['disease_name']), 
						array('v' => (float)$item['total_absence_days_HAS_ISF']), 
						array('v' => (float)$item['total_unproductive_days_HAS_ISF']), 
						array('v' => (int)0), 
						array('v' => (float)$item['total_death_absence_days_HAS_MSF']), 
						array('v' => (float)$item['total_days_lost_HAS_SF']), 
						array('v' => (float)round(($item['total_days_lost_HAS_SF'] / $results['totalDaysLost_HAS_SF'])*100, 2), 'f' => (float)round(($item['total_days_lost_HAS_SF'] / $results['totalDaysLost_HAS_SF'])*100, 2).'%')
				));
		$i++;
	}
}
if($results['risk']){
	foreach ($results['risk'] as $key => $item){
		$rowArray_HAS_SF[] = array('c' => array(
						array('v' => $item['risk_name']), 
						array('v' => (int)0), 
						array('v' => (int)0), 
						array('v' => (float)$item['total_risk_absence_days_HAS_SF']), 
						array('v' => (int)0), 
						array('v' => (float)$item['total_risk_absence_days_HAS_SF']), 
						array('v' => (float)round(($item['total_risk_absence_days_HAS_SF'] / $results['totalDaysLost_HAS_SF'])*100, 2),'f' => (float)round(($item['total_risk_absence_days_HAS_SF'] / $results['totalDaysLost_HAS_SF'])*100, 2).'%')
				));
		$i++;
	}
}
if($results['risk'] || $results['disease']){
	usort($rowArray_HAS_SF, function($b, $a) {
		return $a['c'][5]['v'] - $b['c'][5]['v'];
	});
	
	$data = array(
			'cols' => array(
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'), 'type' => 'string'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DAYS_MORBIDITY_LABEL'), 'type' => 'number'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DAYS_PRESENTEEISM_LABEL'), 'type' => 'number'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DAYS_PRESENTEEISM_RISK_FACTOR_LABEL'), 'type' => 'number'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DAYS_MORTALITY_LABEL'), 'type' => 'number'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_TOTAL_LOST_DAYS_PER_DR_LABEL'), 'type' => 'number'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_PERCENT_TOTAL_LOST_DAYS_PER_DR_LABEL'), 'type' => 'number')
			),
			'rows' => $rowArray_HAS_SF
	);
	
	$height = ($i * 110)+100;
	$chart->load(json_encode($data));
	$options = array( 'width' => 900, 'height' => $height, 'chartArea' => array('top' => 0, 'left' => 170, 'width' => 560), 'legend' => array( 'textStyle' => array('fontSize' => 10, 'color' => '#63B1F2')), 'vAxis' => array('textStyle' => array('color' => '#63B1F2')), 'hAxis' => array('textStyle' => array('color' => '#63B1F2'), 'title' => JText::_('COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_SUBTITLE'), 'titleTextStyle' => array('color' => '#63B1F2')));
	
	echo $chart->draw('wdl_sf_div', $options);
}

// HAS one episode
$chart = new Chartbuilder('BarChart');

$i =0;
if($results['disease']){
	foreach ($results['disease'] as $key => $item){
		$rowArray_HAS_OE[] = array('c' => array(
						array('v' => $item['disease_name']), 
						array('v' => (float)$item['total_absence_days_HAS_OE']), 
						array('v' => (float)$item['total_unproductive_days_HAS_OE']),
						array('v' => (float)$item['total_death_absence_days_HAS_OE']), 
						array('v' => (float)$item['total_days_lost_HAS_OE']), 
						array('v' => (float)round(($item['total_days_lost_HAS_OE'] / $results['totalDaysLost_HAS_OE'])*100, 2), 'f' => (float)round(($item['total_days_lost_HAS_OE'] / $results['totalDaysLost_HAS_OE'])*100, 2).'%')
				));
		$i++;
	}
	
	usort($rowArray_HAS_OE, function($b, $a) {
		return $a['c'][4]['v'] - $b['c'][4]['v'];
	});
	
	$data = array(
			'cols' => array(
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'), 'type' => 'string'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DAYS_MORBIDITY_LABEL'), 'type' => 'number'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DAYS_PRESENTEEISM_LABEL'), 'type' => 'number'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DAYS_MORTALITY_LABEL'), 'type' => 'number'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_TOTAL_LOST_DAYS_PER_DR_LABEL'), 'type' => 'number'),
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_PERCENT_TOTAL_LOST_DAYS_PER_DR_LABEL'), 'type' => 'number')
			),
			'rows' => $rowArray_HAS_OE
	);
	
	$height = ($i * 110)+100;
	$chart->load(json_encode($data));
	$options = array( 'width' => 900, 'height' => $height, 'chartArea' => array('top' => 0, 'left' => 170, 'width' => 560), 'legend' => array( 'textStyle' => array('fontSize' => 10, 'color' => '#63B1F2')), 'vAxis' => array('textStyle' => array('color' => '#63B1F2')), 'hAxis' => array('textStyle' => array('color' => '#63B1F2'), 'title' => JText::_('COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_SUBTITLE'), 'titleTextStyle' => array('color' => '#63B1F2')));
	
	echo $chart->draw('wdl_oe_div', $options);
}
?>
<div class="hide hidden" id="giz_wdl">
<div id="view_wdl" style="margin:0 auto; width: 900px; height: 100%;">
<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_TITLE'); ?></h1>
<div style="margin:0 auto; width: 900px;">
    <br/>
        <div class="switchbox" >
            <div class="control_switch_sf" >
                <div class="switch switch_sf" onclick="controlSwitch('switch_sf')" >
                    <span class="thumb"></span>
                    <input type="checkbox" />
                </div>
                <div class="label" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></div>
            </div>
            
            <div class="control_switch_oe" >
                <div class="switch switch_oe" onclick="controlSwitch('switch_oe')">
                    <span class="thumb"></span>
                    <input type="checkbox" />
                </div>
                <div class="label" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_USE_ONE_EPISODE'); ?></div>
             </div>
        </div>  
    <br/><br/>
</div>
<?php if ($results['risk'] || $results['disease']) : ?>
    <div id="wdl_default_div" class="item_default"></div>
    
    <div id="wdl_sf_div" class="hide item_sf"></div>
    
    <div id="wdl_oe_div" class="hide item_oe"></div>
<?php else: ?>
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASE_RISK_SELECTED'); ?></h2>
<?php endif; ?>
</div>
</div>