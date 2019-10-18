<?php
/**
 * SEF component for Joomla! 1.5
 *
 * @author      ARTIO s.r.o.
 * @copyright   ARTIO s.r.o., http://www.artio.cz
 * @package     JoomSEF
 * @version     3.1.0
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

//require_once(JPATH_COMPONENT.DS.'classes'.DS.'button.php');

class SEFViewSEF extends JView
{
	function __construct($config = null)
	{
		parent::__construct($config);
		$this->_addPath('template', $this->_basePath.DS.'views'.DS.'templates');
	}

	function display($tpl = null)
	{
		JToolBarHelper::title( JText::_('JoomSEF'), 'generic.png' );
		
		$bar = & JToolBar::getInstance();

		$bar->appendButton('Confirm', JText::_(_COM_SEF_CLEAN_CACHE_QUESTION), 'cancel_f2', 'Clean Cache', 'cleancache', false, false);
		JToolBarHelper::spacer();
		JToolBarHelper::custom('installext', 'install', '', 'Install', false);
		$bar->appendButton('Confirm', 'Are you sure you want to uninstall selected extension?', 'uninstall', 'Uninstall', 'uninstallext', true, false);
		JToolBarHelper::editList('editext');
		JToolBarHelper::spacer();
		JToolBarHelper::custom('showupgrade', 'upgrade', '', 'Upgrade', false);
		
		$exts = $this->get('extensions', 'extensions');
		$this->assignRef('extensions', $exts);

		parent::display($tpl);
	}
}
