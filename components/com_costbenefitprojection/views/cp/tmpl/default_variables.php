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

?>
<div class="hide hidden" id="giz_variables">
    <h1>Variables</h1>
    <div style="margin:0 auto; width: 1000px; height: 100%;">
    <?php 
        echo '<pre><div style="font-size:16px;">';
        print_r($this->item);
        echo '</div></pre>';
    ?>
    </div>
</div>