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
				$rowArray[] = array('c' => array(
								array('v' => $item->details->name), 
								array('v' => $item->{'subtotal_cost_morbidity_'.$scale}, 'f' => $item->{'subtotal_costmoney_morbidity_'.$scale}), 
								array('v' => $item->{'subtotal_cost_presenteeism_'.$scale}, 'f' => $item->{'subtotal_costmoney_presenteeism_'.$scale}), 
								array('v' => $item->{'subtotal_cost_mortality_'.$scale}, 'f' => $item->{'subtotal_costmoney_mortality_'.$scale}), 
								array('v' => $item->{'subtotal_cost_'.$scale}, 'f' => $item->{'subtotal_costmoney_'.$scale})
						));
				$i++;
			}

		usort($rowArray, function($b, $a) {
			return $a['c'][4]['v'] - $b['c'][4]['v'];
		});
	
		$data = array(
				'cols' => array(
						array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_DISEASE_AND_RISK_FACKTOR_NAMES_LABEL'), 'type' => 'string'),
						array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_COST_MORBIDITY_LABEL'), 'type' => 'number'),
						array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_COST_PRESENTEEISM_LABEL'), 'type' => 'number'),
						array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_COST_MORTALITY_LABEL'), 'type' => 'number'),
						array('id' => '', 'label' => JText::_('COM_COSTBENEFITPROJECTION_CC_TOTAL_COST_PER_DR_LABEL'), 'type' => 'number')
				),
				'rows' => $rowArray
		);
		
		$height = ($i * 110)+50;
		$title = JText::sprintf('COM_COSTBENEFITPROJECTION_CHARTS_COST_SUBTITLE', $this->result->user->currency_name); 
		$this->builder->load(json_encode($data));
		$options =	array( 'backgroundColor' => $this->Chart['backgroundColor'], 'width' => $this->Chart['width'], 'height' => $height, 'chartArea' => $this->Chart['chartArea'], 'legend' => $this->Chart['legend'], 'vAxis' => $this->Chart['vAxis'], 'hAxis' => array('textStyle' => $this->Chart['hAxis']['textStyle'], 'title' => $title, 'titleTextStyle' => $this->Chart['hAxis']['titleTextStyle']));
		
		echo $this->builder->draw('c_'.$scale, $options);
	}
}
/* <h1><?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_COST_TITLE'); ?></h1> */
?>
<!-- This is the subnav containing the toggling elements -->
<?php if($this->not_basic) : ?>
<ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#chartC'}">
    <li class="uk-active uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?></a></li>
    <li class="uk-animation-scale-up uk-text-small"><a href=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_INCLUDE_SCALING_FACTORS'); ?></a></li>
</ul>
<br/>
<?php endif; ?>
<?php if ($this->result->items) : ?>
<!-- This is the container of the content items -->
<ul id="chartC" class="uk-switcher">
	<?php foreach ($this->scale as $scale) :?>
        <li class="<?php echo ($scale == 'unscaled') ? 'uk-active default' : 'scalling'; ?>" >
        	<div id="c_<?php echo $scale; ?>" class="chart" style="height:100%; width:100%;"></div>
            <button class="uk-button uk-button-primary uk-button-mini uk-hidden-small" 
            onclick="getPDF(document.getElementById('c_<?php echo $scale; ?>'),'<?php echo JText::_('COM_COSTBENEFITPROJECTION_CHARTS_COST_TITLE'); ?>' 
            +' (<?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_DEFAULT'); ?>)');">
            <?php echo JText::_('COM_COSTBENEFITPROJECTION_CT_PDF_DOWNLOAD'); ?></button>
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
    <h2><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DISEASE_RISK_SELECTED'); ?></h2>
<?php endif; ?>