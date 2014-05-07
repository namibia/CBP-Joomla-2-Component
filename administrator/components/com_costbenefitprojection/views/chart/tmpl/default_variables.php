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

?>
<div class="hide hidden" id="giz_variables">
    <h1>Variables</h1>
    <div style="margin:0 auto; width: 1000px; height: 100%;">
    <?php 
        echo '<pre><div style="font-size:16px;">';
        print_r($this->result);
        echo '</div></pre>';
    ?>
    </div>
</div>