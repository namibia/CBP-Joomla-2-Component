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
// load chart builder
$chart = new Chartbuilder('BarChart');

$scaled = array('unscaled','scaled');

if($this->result->interventions){
	$intervention_number = 0;
	foreach ($this->result->interventions as $intervention){
		foreach ($scaled as $scale){
		$i =0;
		$rowArray = array();
		if(is_array($intervention->items) || is_object($intervention->items)){
			foreach ($intervention->items as $key => &$item){
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
		$chart->load(json_encode($data));
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
		
		echo $chart->draw('icb_'.$intervention_number.'_'.$scale, $options);
		$intervention_number++;
		}
	}
}
?>
<div class="hide hidden" id="giz_icb">
<div id="view_icb">
    <div style="margin:0 auto; width: <?php echo $this->Chart['width']; ?>px; height: 100%;">
        <h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_INTERVENTION_COST_BENEFIT_TITLE'); ?></h1>
        
        <?php if ($this->result->interventions) : ?>
            <?php $intervention_number = 0; ?>
            <?php foreach ($this->result->interventions as $intervention): ?>
                <?php foreach ($scaled as $scale) :?>
                    <div id="icb_<?php echo $intervention_number; ?>_<?php echo $scale; ?>" class="<?php echo $scale; ?>" style="display: <?php echo ($scale == 'unscaled') ? 'box' : 'none'; ?>;"></div>
                    <?php $intervention_number++; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_INTERVENTION_SELECTED'); ?></h2>
		<?php endif; ?>
    </div>   
</div>
</div>