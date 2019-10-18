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

class SEFViewUpgrade extends JView
{
	function __construct($config = null)
	{
		parent::__construct($config);
		//$this->_addPath('template', $this->_basePath.DS.'views'.DS.'templates');
	}

	function display($tpl = null)
	{
		JToolBarHelper::title( JText::_( 'JoomSEF' ).' '.JText::_('Upgrade Manager') );
		
		JToolBarHelper::back('Back', 'index.php?option=com_sef');
		
		$exts = $this->get('UpgradeExts');
		$this->assignRef('extensions', $exts);
		
		$oldVer = SEFTools::getSEFVersion();
		$this->assignRef('oldVer', $oldVer);
		
		$newVer = $this->get('newSEFVersion');
		$this->assignRef('newVer', $newVer);

		parent::display($tpl);
	}

	function showMessage()
	{
	    JToolBarHelper::title( JText::_( 'JoomSEF' ).' '.JText::_('Upgrade Manager') );
	    
	    JToolBarHelper::back('Continue', 'index.php?option=com_sef');
	    
	    $this->setLayout('message');
	    parent::display();
	}
}
