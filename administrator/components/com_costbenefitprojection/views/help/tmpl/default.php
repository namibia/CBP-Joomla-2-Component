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
JHTML::_('behavior.tooltip');
?>
<style type="text/css">
.Img {
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
}
.LefT {
    float: left;
	margin: 5px 10px 5px 0px;
}
.RighT {
    float: right;
	margin: 5px 0px 5px 10px;
}
.contentpane{
    margin: 5px;
    padding: 0px;
}
h1 {
    margin: 10px 0;
}
h2 {
	color: #808080;
} 
</style>
<div id="costbenefitprojection" class="backend_help">
<h1><?php echo $this->item->help_for; ?></h1>
<?php echo $this->item->help_content; ?></div>
<script>
var div = document.getElementById("element-box");
var vdmText = document.createTextNode('<?php echo $this->xml->name . " " . $this->xml->version; ?>');
var vdmDiv = document.createElement('div');
vdmDiv.style.cssText = 'text-align:center; font-size:10px; margin: 10px;';
vdmDiv.appendChild(vdmText);
div.appendChild(vdmDiv);
</script>