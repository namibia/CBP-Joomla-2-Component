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
								array('v' => round(($item->{'subtotal_days_lost_'.$scale} / $this->result->totals->{'total_days_lost_'.$scale})*100), 'f' => (float)round(($item->{'subtotal_days_lost_'.$scale} / $this->result->totals->{'total_days_lost_'.$scale})*100,3).'%')
						));
				$i++;
			}
			
			usort($rowArray, function($b, $a) {
				return $a['c'][1]['v'] - $b['c'][1]['v'];
			});
			
			$data = array(
					'cols' => array(
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'), 'type' => 'string'),
							array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_PERCENT_TOTAL_LOST_DAYS_PER_DR_LABEL'), 'type' => 'number')
					),
					'rows' => $rowArray
			);
			
			$height = ($i * 70)+30;
			$chart->load(json_encode($data));
			$options =	array( 'backgroundColor' => $this->Chart['backgroundColor'], 'width' => $this->Chart['width'], 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('textStyle' => $this->Chart['hAxis']['textStyle'], 'title' => JText::_('COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_PERCENT_SUBTITLE'), 'titleTextStyle' => $this->Chart['hAxis']['titleTextStyle']));
		
		echo $chart->draw('wdlp_'.$scale, $options);
	}
}

?>
<div class="hide hidden" id="giz_wdlp">
<div id="view_wdlp">
    <div style="margin:0 auto; width: <?php echo $this->Chart['width']; ?>px; height: 100%;">
        <h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_PERCENT'); ?></h1>
        <?php if ($this->result->items) : ?>
            <?php foreach ($scaled as $scale) :?>
                <div id="wdlp_<?php echo $scale; ?>" class="<?php echo $scale; ?>" style="display: <?php echo ($scale == 'unscaled') ? 'box' : 'none'; ?>;"></div>
            <?php endforeach; ?>
        <?php else: ?>
            <h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASE_RISK_SELECTED'); ?></h2>
        <?php endif; ?>
    </div>
</div>
</div>