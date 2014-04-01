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

$i = 0;
if ($results['disease']){
	foreach ($results['disease'] as $key => $item){
		$rowArray[] = array('c' => array(
						array('v' => $item['disease_name']), 
						array('v' => (float)$item['total_cost'], 'f' => $item['total_cost_money'])
				));
		$i++;
	}
}
if ($results['risk']){
	foreach ($results['risk'] as $key => $item){
		$rowArray[] = array('c' => array(
						array('v' => $item['risk_name']),
						array('v' => (float)$item['total_cost_risk'],'f' => $item['total_cost_risk_money'])
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
					array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_TOTAL_COST_PER_DR_LABEL'), 'type' => 'number')
			),
			'rows' => $rowArray
	);
	
	$height = ($i * 40)+100;
	$title = JText::sprintf('COM_COSTBENEFITPROJECTION_CHARTS_COST_SUBTITLE', $this->item['data']['currency']['currency_name']);
	$chart->load(json_encode($data));
	$options = array( 'backgroundColor' => $this->Chart['backgroundColor'], 'width' => $this->Chart['width'], 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('textStyle' => $this->Chart['hAxis']['textStyle'], 'title' => $title, 'titleTextStyle' => $this->Chart['hAxis']['titleTextStyle']));
	
	echo $chart->draw('c_default_div', $options);
}
/* <h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_COST_TITLE'); ?></h1> */
?>
<br/>
<?php if ($results['risk'] || $results['disease']) : ?>
<div class="uk-grid">   
    <!-- This is the container of the content items -->
    <div class="uk-width-medium-3-10">
    	<div class="uk-text-large uk-text-muted uk-animation-slide-left"><i class="uk-icon-lightbulb-o"></i> NOTE</div>
    	<div class="uk-panel uk-panel-box uk-animation-slide-left" style="z-index: -1;">
        	<p>Here you can see an example of the type of analysis provided by the  tool. This shows the estimated cost to your organisation caused by different diseases, allowing you to determine priority health conditions for your organisation according to the basic data you entered.</p>
            <p>The full tool provides a more detailed analysis of days lost and costs based on epidemiological profiles which are tailored to reflect your particular workforce.</p>
        </div>
    </div>
    <div class="uk-width-medium-7-10 uk-animation-slide-right">
        <div id="c_default_div" class="chart" style="height:100%; width:100%;"></div>
    </div>
    
</div>
<?php else: ?>
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASE_RISK_SELECTED'); ?></h2>
<?php endif; ?>