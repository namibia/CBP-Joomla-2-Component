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

if($this->result->items){
	foreach ($scaled as $scale){
			$i =0;
			$rowArray = array();
			foreach ($this->result->items as $key => &$item){
				$rowArray[$i] = array('c' => array(
								array('v' => $item->details->name),
								array('v' => (float)round($item->{'subtotal_morbidity_'.$scale}, 2)), 
								array('v' => (float)round($item->{'subtotal_presenteeism_'.$scale}, 2)), 
								array('v' => (float)round($item->{'subtotal_days_lost_mortality_'.$scale}, 2)), 
								array('v' => (float)round($item->{'subtotal_days_lost_'.$scale}, 2))
						));
				$i++;
			}
			
			usort($rowArray, function($b, $a) {
				return $a['c'][4]['v'] - $b['c'][4]['v'];
			});
			
			$data = array(
					'cols' => array(
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'), 'type' => 'string'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DAYS_MORBIDITY_LABEL'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DAYS_PRESENTEEISM_LABEL'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DAYS_MORTALITY_LABEL'), 'type' => 'number'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_TOTAL_LOST_DAYS_PER_DR_LABEL'), 'type' => 'number')
					),
					'rows' => $rowArray
			);
			
			$height = ($i * 110)+50;
			$chart->load(json_encode($data));
			$options =	array( 'backgroundColor' => $this->Chart['backgroundColor'], 'width' => $this->Chart['width'], 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('textStyle' => $this->Chart['hAxis']['textStyle'], 'title' => JText::_('COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_SUBTITLE'), 'titleTextStyle' => $this->Chart['hAxis']['titleTextStyle']));
		
		echo $chart->draw('wdl_'.$scale, $options);
	}
}

?>
<div class="hide hidden" id="giz_wdl">
<div id="view_wdl">
    <div style="margin:0 auto; width: <?php echo $this->Chart['width']; ?>px; height: 100%;">
        <h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_TITLE'); ?></h1>
        <?php if ($this->result->items) : ?>
            <?php foreach ($scaled as $scale) :?>
                <div id="wdl_<?php echo $scale; ?>" class="<?php echo $scale; ?>" style="display: <?php echo ($scale == 'unscaled') ? 'box' : 'none'; ?>;"></div>
            <?php endforeach; ?>
        <?php else: ?>
            <h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASE_RISK_SELECTED'); ?></h2>
        <?php endif; ?>
    </div>
</div>
</div>