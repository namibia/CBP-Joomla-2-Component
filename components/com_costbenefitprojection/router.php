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
defined( '_JEXEC' ) or die;

jimport('joomla.database.databasequery');

function CostbenefitprojectionBuildRoute(&$query)
{
	$segments = array();

	if (isset($query['view'])) {
		if ($query['view'] == 'category' || $query['view'] == 'data') {
			$segments[] = CostbenefitprojectionGetAlias($query['id'], $query['view']);
			unset($query['id']);
		}

		// look up Itemid
		$query['Itemid'] = CostbenefitprojectionGetItemid($query['view']);

		unset($query['view']);
	}

	return $segments;
}

function CostbenefitprojectionParseRoute($segments)
{
	$vars = array();

	$item = JFactory::getApplication()->getMenu()->getActive();

	if (isset($item)) {
		$vars['view'] = $item->query['view'];
	}

	if (count($segments) == 1) {
		$vars['id'] = CostbenefitprojectionGetIDFromAlias($segments[0], $vars['view']);
	}

	return $vars;
}

function CostbenefitprojectionGetAlias($id, $view)
{
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);

	if ($view == 'category') {
		$query->select('category_alias');
		$query->from('#__costbenefitprojection_categories');
		$query->where('category_id = ' . $id);
	} else if ($view == 'data') {
		$query->select('data_alias');
		$query->from('#__costbenefitprojection_datas');
		$query->where('data_id = ' . $id);
	} else {
		return '';
	}

	return $db->setQuery($query)->loadResult();
}

function CostbenefitprojectionGetIDFromAlias($alias, $view)
{
	$db = JFactory::getDbo();

	$query = $db->getQuery(true);

	$alias = str_replace(':', '-', $alias);
	$alias = $db->getEscaped($alias);

	if ($view == 'category') {
		$query->select('category_id');
		$query->from('#__costbenefitprojection_categories');
		$query->where("category_alias = '{$alias}'");
	} else if ($view == 'data') {
		$query->select('data_id');
		$query->from('#__costbenefitprojection_datas');
		$query->where("data_alias = '{$alias}'");
	} else {
		return 0;
	}

	return $db->setQuery($query)->loadResult();
}

function CostbenefitprojectionGetItemid($view)
{
	$db = JFactory::getDbo();

	$db_query = $db->getQuery(true);
	$db_query->select('id')
			->from('#__menu')
			->where("link = 'index.php?option=com_costbenefitprojection&view=" . $view . "' AND client_id = 0");

	$db->setQuery($db_query);

	return $db->loadResult();
}