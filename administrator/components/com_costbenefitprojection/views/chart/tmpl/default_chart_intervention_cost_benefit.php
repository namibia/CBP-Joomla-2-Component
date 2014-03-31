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
			$options = array( 'title' => $main_title, 'colors' => array('#DC3912','#3366CC','#FF9900'), 'width' => 1000, 'height' => $height, 'chartArea' => array('top' => 20, 'left' => 180, 'width' => 560), 'legend' => array( 'textStyle' => array('fontSize' => 10, 'color' => '#63B1F2')), 'vAxis' => array('textStyle' => array('color' => '#63B1F2')), 'hAxis' => array('viewWindowMode' => 'max','textStyle' => array('color' => '#63B1F2'), 'title' => $title, 'titleTextStyle' => array('color' => '#63B1F2','fontSize' => 15)));
			
			echo $chart->draw('icb_'.$intervention_number.'_default_div', $options);
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
			$options = array( 'title' => $main_title, 'colors' => array('#DC3912','#3366CC','#FF9900'), 'width' => 1000, 'height' => $height_HAS_SF, 'chartArea' => array('top' => 20, 'left' => 180, 'width' => 560), 'legend' => array( 'textStyle' => array('fontSize' => 10, 'color' => '#63B1F2')), 'vAxis' => array('textStyle' => array('color' => '#63B1F2')), 'hAxis' => array('viewWindowMode' => 'max','textStyle' => array('color' => '#63B1F2'), 'title' => $title, 'titleTextStyle' => array('color' => '#63B1F2','fontSize' => 15)));
			
			echo $chart->draw('icb_'.$intervention_number.'_sf_div', $options);
		}
		// HAS one episode
		$chart = new Chartbuilder('BarChart');
		$i_HAS_OE = 0;
		if(is_array($intervention['disease'])){
			
			foreach ($intervention['disease'] as $key => $item){
				$rowArray_HAS_OE[$intervention_number][] = array('c' => array(
								array('v' => $item['name']),
								array('v' => (float)$item['intervention_annual_cost'], 'f' => $item['intervention_annual_cost_money']), 
								array('v' => (float)$item['intervention_annual_benefit_HAS_OE'], 'f' => $item['intervention_annual_benefit_money_HAS_OE']), 
								array('v' => (float)$item['intervention_annual_net_benefit_HAS_OE'], 'f' => $item['intervention_annual_net_benefit_money_HAS_OE'])
						));
						$i_HAS_OE++;
			}
			
			usort($rowArray_HAS_OE[$intervention_number], function($b, $a) {
				return $a['c'][3]['v'] - $b['c'][3]['v'];
			});
			
			$data = array(
					'cols' => array(
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'), 'type' => 'string'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_COST_INTERVENTION'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_ANNUAL_BENEFIT'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CT_INTERVENTION_NET_BENEFIT'), 'type' => 'number')
					),
					'rows' => $rowArray_HAS_OE[$intervention_number]
			);
			
			$height_HAS_OE = $i_HAS_OE * 80;
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
			$options = array( 'title' => $main_title, 'colors' => array('#DC3912','#3366CC','#FF9900'),'width' => 1000, 'height' => $height_HAS_OE, 'chartArea' => array('top' => 20, 'left' => 180, 'width' => 560), 'legend' => array( 'textStyle' => array('fontSize' => 10, 'color' => '#63B1F2')), 'vAxis' => array('textStyle' => array('color' => '#63B1F2')), 'hAxis' => array('viewWindowMode' => 'max','textStyle' => array('color' => '#63B1F2'), 'title' => $title, 'titleTextStyle' => array('color' => '#63B1F2','fontSize' => 15)));
			
			echo $chart->draw('icb_'.$intervention_number.'_oe_div', $options);
		}
		$intervention_number++;
	}
}
?>

<div class="hide hidden" id="giz_icb">
<div id="view_icb" style="margin:0 auto; width: 1000px; height: 100%;">
<h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_INTERVENTION_COST_BENEFIT_TITLE'); ?></h1>
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
<?php if($results['interventions']): ?>
	<?php $i = 0; ?>
		<?php foreach ($results['interventions'] as $intervention) :?>
            <div id="icb_<?php echo $i; ?>_default_div" class="item_default"></div>
            <?php $i++; ?>
        <?php endforeach; ?>
	<?php $i_HAS_SF = 0; ?>
		<?php foreach ($results['interventions'] as $intervention) :?>
            <div id="icb_<?php echo $i_HAS_SF; ?>_sf_div" class="hide item_sf"></div>
            <?php $i_HAS_SF++; ?>
        <?php endforeach; ?>
	<?php $i_HAS_OE = 0; ?>
		<?php foreach ($results['interventions'] as $intervention) :?>
            <div id="icb_<?php echo $i_HAS_OE; ?>_oe_div" class="hide item_oe"></div>
            <?php $i_HAS_OE++; ?>
        <?php endforeach; ?>
<?php else: ?>
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_INTERVENTION_SELECTED'); ?></h2>
<?php endif; ?>
</div>
</div>