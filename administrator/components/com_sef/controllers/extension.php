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

class SEFControllerExtension extends SEFController
{
    function save()
    {
        JRequest::checkToken() or jexit( 'Invalid Token' );
        
        $model = $this->getModel('extension');

        if ($model->store()) {
            $msg = JText::_( 'Extension Saved' );
        } else {
            $msg = JText::_( 'Error Saving Extension' );
        }

        $redir = JRequest::getVar('redirto', '');
        $link = 'index.php?option=com_sef';
        if( !empty($redir) ) {
            $link .= '&'.$redir;
        }
        
        $this->setRedirect($link, $msg);
    }
    
    function cancel()
    {
        $redir = JRequest::getVar('redirto', '');
        $link = 'index.php?option=com_sef';
        if( !empty($redir) ) {
            $link .= '&'.$redir;
        }
        
        $this->setRedirect($link);
    }
}
?>
