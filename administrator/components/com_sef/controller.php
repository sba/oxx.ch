<?php
/**
 * SEF component for Joomla! 1.5
 *
 * @author      ARTIO s.r.o.
 * @copyright   ARTIO s.r.o., http://www.artio.cz
 * @package     JoomSEF
 * @version     3.1.0
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class SEFController extends JController
{
    function __construct()
    {
        parent::__construct();
    }

    function display()
    {
        $model =& $this->getModel('extensions');
        $view =& $this->getView('sef', 'html', 'sefview');
        $view->setModel($model);

        parent::display();
    }

    function editExt()
    {
        JRequest::setVar('view', 'extension');
        JRequest::setVar('hidemainmenu', 1);

        parent::display();
    }

    function installExt()
    {
        JRequest::setVar('view', 'install');

        $model =& $this->getModel('extensions');
        $view =& $this->getView('install', 'html', 'sefview');
        $view->setModel($model);

        parent::display();
    }

    function doInstall()
    {
        JRequest::checkToken() or jexit( 'Invalid Token' );

        $model =& $this->getModel('extension');
        $view =& $this->getView('install', 'html', 'sefview');
        
        $model->install();
        
        $view->setModel($model, true);
        $view->showMessage();
    }

    function uninstallExt()
    {
        JRequest::checkToken() or jexit( 'Invalid Token' );

        $model =& $this->getModel('extension');

        if( $model->delete() ) {
            $msg = 'Extension Uninstalled';
        } else {
            $msg = 'Error Uninstalling Extension';
        }

        $redir = JRequest::getVar('redirto', '');
        $link = 'index.php?option=com_sef';
        if( !empty($redir) ) {
            $link .= '&'.$redir;
        }

        $this->setRedirect($link, $msg);
    }
    
    function showUpgrade()
    {
        JRequest::setVar('view', 'upgrade');
        
        parent::display();
    }
    
    function doUpgrade()
    {
        JRequest::checkToken() or jexit( 'Invalid Token' );
        
        $model =& $this->getModel('upgrade');
        $result = $model->upgrade();
        $model->setState('result', $result);
        
        $view =& $this->getView('upgrade', 'html', 'sefview');
        $view->setModel($model, true);
        $view->showMessage();
    }

    function cleanCache()
    {
        require_once(JPATH_COMPONENT.DS.'controllers'.DS.'urls.php');
        $controller = new SEFControllerURLs();
        $controller->execute( 'cleancache' );
        $this->setRedirect('index.php?option=com_sef', JText::_('Cache Cleaned'));
    }
}
?>
