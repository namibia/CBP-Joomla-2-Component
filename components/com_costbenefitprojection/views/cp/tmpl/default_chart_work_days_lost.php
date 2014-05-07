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

if($this->result->items){
	foreach ($this->scale as $scale){
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
			$this->builder->load(json_encode($data));
			$options =	array( 'backgroundColor' => $this->Chart['backgroundColor'], 'width' => $this->Chart['width'], 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('textStyle' => $this->Chart['hAxis']['textStyle'], 'title' => JText::_('COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_SUBTITLE'), 'titleTextStyle' => $this->Chart['hAxis']['titleTextStyle']));
		
		echo $this->builder->draw('wdl_'.$scale, $options);
	}
}

/* <h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_TITLE'); ?></h1> */
?>
<!-- This is the subnav containing the toggling elements -->
<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#chartWDL'}">
    <li class="uk-active uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></a></li>
</ul>
<br/>
<?php if ($this->result->items) : ?>
<!-- This is the container of the content items -->
<ul id="chartWDL" class="uk-switcher">
    <?php foreach ($this->scale as $scale) :?>
        <li class="<?php echo ($scale == 'unscaled') ? 'uk-active default' : 'scalling'; ?>" >
            <div id="wdl_<?php echo $scale; ?>" class="chart" style="height:100%; width:100%;"></div>
            <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" onclick="getPDF(document.getElementById('wdl_<?php echo $scale; ?>'),'<?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_WORK_DAYS_LOST_TITLE'); ?>'+' (<?php echo ($scale == 'unscaled') ? JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT') : JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?>)');"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PDF_DOWNLOAD'); ?></button>
        </li> 
    <?php endforeach; ?>
</ul>
<?php else: ?>
	<h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASE_RISK_SELECTED'); ?></h2>
<?php endif; ?> 