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

class SEFViewExtension extends JView
{
	function display($tpl = null)
	{
		// Get data from the model
		$extension =& $this->get('extension');
		$this->assignRef('extension', $extension);
		
		JToolBarHelper::title( JText::_( 'SEF Extension' ).' <small><small>'.JText::_( 'Edit' ).' [ ' . $extension->name . ' ]</small></small>' );
		
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JHTML::_('behavior.tooltip');
		
		$redir = JRequest::getVar('redirto', '');
		$this->assignRef('redirto', $redir);
		
		parent::display($tpl);
	}
}
