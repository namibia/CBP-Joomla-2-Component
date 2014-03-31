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
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');
$show = JComponentHelper::getParams('com_costbenefitprojection')->get('lvdm_text');
$w = 0;
for ($i = 1; $i <= 4; $i++) {
	// should we show the worker
	$showWorker[$i] = JComponentHelper::getParams('com_costbenefitprojection')->get('showWorker'.$i);
	if ( $showWorker[$i] == 1 || $showWorker[$i] == 3){
		$workers[$w]['name'] = JComponentHelper::getParams('com_costbenefitprojection')->get('nameWorker'.$i);
		$workers[$w]['title'] = JComponentHelper::getParams('com_costbenefitprojection')->get('titleWorker'.$i);
		// how to link to worker
		$useWorker[$i] = JComponentHelper::getParams('com_costbenefitprojection')->get('useWorker'.$i);
		if ($useWorker[$i] == 1){
			$workers[$w]['load'] = '<a href="mailto:' . JComponentHelper::getParams('com_costbenefitprojection')->get('emailWorker'.$i) . '" target="_blank">'
			. $workers[$w]['name'] . '</a>';
		} elseif ($useWorker[$i] == 2){
			$workers[$w]['load'] = '<a href="' . JComponentHelper::getParams('com_costbenefitprojection')->get('linkWorker'.$i) . '" target="_blank">'
			. $workers[$w]['name'] . '</a>';
		} else {
			$workers[$w]['load'] = $workers[$w]['name'];
		}
		
		$w++;
	}
}

$items = $this->items;
?>
<div id="costbenefitprojection" >
<form action="index.php?option=com_costbenefitprojection"
	method="post" name="adminForm" class="form-validate">
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <h3 class="title">
            <span><?php echo JText::_('COM_COSTBENEFITPROJECTION_OVERVIEW_RIGHT_TITLE'); ?></span>
        </h3>
			<?php foreach ($items as $item): ?>
                <div class="icon-wrapper">
                        <div class="icon">
                        <a href="<?php echo JRoute::_($item['url']); ?>">
                            <img alt="<?php echo JText::_($item['name']); ?>" src="<?php echo JURI::root().$item['img']; ?>">
                            <span><?php echo JText::_($item['name']); ?></span>
                        </a>
                    </div>
                </div>
            <?php endforeach ?>
    </fieldset>
</div>
<div class="width-50 fltrt">
    <fieldset class="adminform">
         <a target="_blank" style="float: right;"href="http://www.giz.de/" title="GIZ"><img src="../media/com_costbenefitprojection/images/jm_c.jpg" height="230"/></a>
                <h2><?php echo $this->xml->description; ?></h2>
                <ul class="menuList">
				<li>Current Installed Version <strong><span style="color:#DBA400;"><?php echo $this->xml->version; ?></span></strong></li>
                <li><?php echo $this->xml->copyright; ?> </li>
                <li>License <a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GNU/GPL</a> Commercial</li>
                <?php if ($workers): ?>
					<?php foreach ($workers as $worker): ?>
                    	<li><?php echo $worker['title']; ?> <?php echo $worker['load']; ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if ($show == 1) : ?>
                <li>Application Engineer <a href="http://<?php echo $this->xml->authorUrl; ?>" target="_blank"><?php echo $this->xml->author; ?></a></li>
                <?php endif; ?>
                </ul>
    </fieldset>
</div>
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>
</div>