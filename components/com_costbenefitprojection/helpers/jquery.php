<?php
defined( '_JEXEC' ) or die;

class CostbenefitprojectionJQuery
{
	function already_loaded()
	{
		$document = JFactory::getDocument();

		$head_data = $document->getHeadData();

		foreach (array_keys($head_data['scripts']) as $script) {
			if (stristr($script, 'jquery')) {
				return true;
			}
		}

		return false;
	}
}