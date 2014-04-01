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

defined('_JEXEC') or die;

/**
 * Extended Utility class for the Users component.
 *
 * 
 * @package  com_costbenefitprojection
 * @since       2.5
 */
class JHtmlUsers
{
	/**
	 * Display an image.
	 *
	 * @param   string	$src  The source of the image
	 *
	 * @return  string  A <img> element if the specified file exists, otherwise, a null string
	 *
	 * @since   2.5
	 */
	public static function image($src)
	{
		$src = preg_replace('#[^A-Z0-9\-_\./]#i', '', $src);
		$file = JPATH_SITE . '/' . $src;

		jimport('joomla.filesystem.path');
		JPath::check($file);

		if (!file_exists($file))
		{
			return '';
		}

		return '<img src="' . JUri::root() . $src . '" alt="" />';
	}

	/**
	 * Displays an icon to add a note for this user.
	 *
	 * @param   integer  $userId  The user ID
	 *
	 * @return  string  A link to add a note
	 *
	 * @since   2.5
	 */
	public static function addNote($userId)
	{
		$title = JText::_('COM_COSTBENEFITPROJECTION_ADD_NOTE');

		return '<a href="' . JRoute::_('index.php?option=com_costbenefitprojection&task=note.add&u_id=' . (int) $userId) . '">'
				. JHtml::_('image', 'admin/note_add_16.png', 'COM_COSTBENEFITPROJECTION_NOTES', array('title' => $title), true) . '</a>';
	}

	/**
	 * Displays an icon to filter the notes list on this user.
	 *
	 * @param   integer  $count   The number of notes for the user
	 * @param   integer  $userId  The user ID
	 *
	 * @return	string  A link to apply a filter
	 *
	 * @since   2.5
	 */
	public static function filterNotes($count, $userId)
	{
		if (empty($count))
		{
			return '';
		}

		$title = JText::_('COM_COSTBENEFITPROJECTION_FILTER_NOTES');

		return '<a href="' . JRoute::_('index.php?option=com_costbenefitprojection&view=notes&filter_search=uid:' . (int) $userId) . '">'
				. JHtml::_('image', 'admin/filter_16.png', 'COM_COSTBENEFITPROJECTION_NOTES', array('title' => $title), true) . '</a>';
	}

	/**
	 * Displays a note icon.
	 *
	 * @param   integer  $count   The number of notes for the user
	 * @param   integer  $userId  The user ID
	 *
	 * @return	string  A link to a modal window with the user notes
	 *
	 * @since   2.5
	 */
	public static function notes($count, $userId)
	{
		if (empty($count))
		{
			return '';
		}

		$title = JText::plural('COM_COSTBENEFITPROJECTION_N_USER_NOTES', $count);

		return '<a class="modal"' .
			' href="' . JRoute::_('index.php?option=com_costbenefitprojection&view=notes&tmpl=component&layout=modal&u_id=' . (int) $userId) . '"' .
			' rel="{handler: \'iframe\', size: {x: 800, y: 450}}">' .
			JHtml::_('image', 'menu/icon-16-user-note.png', 'COM_COSTBENEFITPROJECTION_NOTES', array('title' => $title), true) . '</a>';
	}
}
