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

jimport('joomla.application.component.controlleradmin');

class CostbenefitprojectionControllerUsers extends JControllerAdmin
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * 
	 */
	protected $text_prefix = 'COM_COSTBENEFITPROJECTION_USERS';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @return  UsersControllerUsers
	 *
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('block',		'changeBlock');
		$this->registerTask('unblock',		'changeBlock');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 */
	public function getModel($name = 'User', $prefix = 'CostbenefitprojectionModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Method to change the block status on a record.
	 *
	 * @return  void
	 *
	 */
	public function changeBlock()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		$values	= array('block' => 1, 'unblock' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_COSTBENEFITPROJECTION_USERS_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->block($ids, $value))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				if ($value == 1)
				{
					$this->setMessage(JText::plural('COM_COSTBENEFITPROJECTION_N_USERS_BLOCKED', count($ids)));
				}
				elseif ($value == 0)
				{
					$this->setMessage(JText::plural('COM_COSTBENEFITPROJECTION_N_USERS_UNBLOCKED', count($ids)));
				}
			}
		}

		$this->setRedirect('index.php?option=com_categories&extension&view=users');
	}

	/**
	 * Method to activate a record.
	 *
	 * @return  void
	 *
	 */
	public function activate()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$ids	= JRequest::getVar('cid', array(), '', 'array');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_COSTBENEFITPROJECTION_USERS_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->activate($ids))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				$this->setMessage(JText::plural('COM_COSTBENEFITPROJECTION_N_USERS_ACTIVATED', count($ids)));
			}
		}

		$this->setRedirect('index.php?option=com_categories&extension&view=users');
	}
}
