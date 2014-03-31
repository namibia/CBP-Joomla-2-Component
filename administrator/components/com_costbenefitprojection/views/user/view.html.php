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

class CostbenefitprojectionViewUser extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;
	protected $xml;
	protected $gravatar;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$manifestUrl = JPATH_ADMINISTRATOR."/components/com_costbenefitprojection/manifest.xml";
		$this->xml = simplexml_load_file($manifestUrl);
		
		// Get data from the model
		$this->form			= $this->get('Form');
		$this->item			= $this->get('Item');
		$this->state		= $this->get('State');
		$this->gravatar		= $this->getGravatar($this->item->email);
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->form->setValue('password',		null);
		$this->form->setValue('password2',	null);
		
		$this->addToolbar();
		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', 1);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$canDo		= UsersHelper::getActions();
		$isProfile = $this->item->id == $user->id;
		
		JToolBarHelper::title(JText::_($isNew ? 'COM_COSTBENEFITPROJECTION_VIEW_NEW_USER_TITLE' : ($isProfile ? 'COM_COSTBENEFITPROJECTION_VIEW_EDIT_PROFILE_TITLE' : 'COM_COSTBENEFITPROJECTION_VIEW_EDIT_USER_TITLE')), $isNew ? 'user-add' : ($isProfile ? 'user-gizprofile' : 'user-edit'));
		if ($canDo->get('core.edit')||$canDo->get('core.create')) {
			JToolBarHelper::apply('user.apply');
			if(!$isNew){
				JToolBarHelper::save('user.save');
			}
		}
		if ($canDo->get('core.create')&&$canDo->get('core.manage')) {
			if(!$isNew){
				JToolBarHelper::save2new('user.save2new');
			}
		}
		if (empty($this->item->id))  {
			JToolBarHelper::cancel('user.cancel');
		} else {
			JToolBarHelper::cancel('user.cancel');
		}
		JToolBarHelper::divider();

		require_once JPATH_COMPONENT.DS.'helpers'.DS.'toolbar.php';
		// helper toolbar
		CostbenefitprojectionToolbarHelper::help('member');
		// Sub menu		
		CostbenefitprojectionToolbarHelper::addToolbar($this->getName());
	}
	
	/**
	 * Get either a Gravatar URL or complete image tag for a specified email address.
	 *
	 * @param string $email The email address
	 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
	 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
	 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
	 * @param boole $img True to return a complete IMG tag False for just the URL
	 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
	 * @return String containing either just a URL or a complete image tag
	 * @source http://gravatar.com/site/implement/images/php/
	 */
	protected function getGravatar( $email, $s = 180, $d = 'identicon', $r = 'g', $img = false, $atts = array() ) 
	{
		$url = 'http://www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$s&d=$d&r=$r";
		if ( $img ) {
			$url = '<img src="' . $url . '"';
			foreach ( $atts as $key => $val )
				$url .= ' ' . $key . '="' . $val . '"';
			$url .= ' />';
		}
		return $url;
	}
	
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		// Add Theme to Page
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'theme.php' );
		// The CSS for Theme
		if ($vdmTheme == 1){
			$this->document->addStyleSheet(JURI::base() . '../media/com_costbenefitprojection/css/theme.css');
		}
		// The  JS
		$this->document->addScript(JURI::base() . '../media/com_costbenefitprojection/js/jquery-1.10.2.min.js');
		$this->document->addScriptDeclaration($theme);
		
		// Load the tooltip behavior.
		JHtml::_('behavior.tooltip');
		JHtml::_('behavior.formvalidation');          
	}
}