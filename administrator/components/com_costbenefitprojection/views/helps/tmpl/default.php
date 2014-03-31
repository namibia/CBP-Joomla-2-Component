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
defined( '_JEXEC' ) or die;

$items = $this->items;
?>

<div id="costbenefitprojection" >
<form action="index.php?option=com_costbenefitprojection"
	method="post" name="adminForm" class="form-validate">
<div class="width-100 fltlft">
    <fieldset class="adminform">
    	<h3 class="title">
            <span><?php echo JText::_('COM_COSTBENEFITPROJECTION_HELP_SUB_TITLE'); ?></span>
        </h3>
			<?php foreach ($items as $item): ?>
                <div class="icon-wrapper">
                    <div class="icon">
                        <a 	class="modal" 
                            href="<?php echo JRoute::_($item['url']); ?>" 
                            rel="{handler: 'iframe', size: {x: 640, y: 480}, onClose: function() {}}" 
                            title="View help page for <?php echo JText::_($item['name']); ?>"
                         >
                            <img alt="<?php echo JText::_($item['name']); ?>" src="<?php echo JURI::root().$item['img']; ?>">
                            <span><?php echo JText::_($item['name']); ?></span>
                        </a>
                    </div>
                </div>
            <?php endforeach ?>
    </fieldset>
</div>
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>
</div>