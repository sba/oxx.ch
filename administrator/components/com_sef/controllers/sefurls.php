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

class SEFControllerSEFUrls extends SEFController
{
    /**
     * constructor (registers additional tasks to methods)
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        
        $this->registerTask('add', 'edit');
    }

    function display()
    {
        JRequest::setVar( 'view', 'sefurls' );
        
        parent::display();
    }
    
    function edit()
    {
        JRequest::setVar( 'view', 'sefurl' );
        JRequest::setVar( 'hidemainmenu', 1 );
        
        parent::display();
    }
    
    function save()
    {
        $model = $this->getModel('sefurl');

        if ($model->store()) {
            $msg = JText::_( 'URL Saved' );
        } else {
            $msg = JText::_( 'Error Saving URL' );
        }

        $this->setRedirect('index.php?option=com_sef&controller=sefurls', $msg);
    }
    
    function remove()
    {
		$model = $this->getModel('sefurl');
		
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More URLs Could not be Deleted' );
		} else {
			$msg = JText::_( 'URL(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_sef&controller=sefurls', $msg );
    }
    
    function deleteFiltered()
    {
        $model = $this->getModel('sefurls');
        
		if(!$model->deleteFiltered()) {
			$msg = JText::_( 'Error: One or More URLs Could not be Deleted' );
		} else {
			$msg = JText::_( 'URL(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_sef&controller=sefurls', $msg );
    }
    
    function cancel()
    {
        $this->setRedirect( 'index.php?option=com_sef&controller=sefurls' );
    }
    
    function showimport()
    {
        JRequest::setVar( 'view', 'importexport' );
        
        parent::display();
    }
    
    function import()
    {
        $model = $this->getModel('sefurls');
        
		if(!$model->import()) {
			$msg = JText::_( 'Error: URLs could not be imported.' );
		} else {
			$msg = JText::_( 'URL(s) Imported Successfuly' );
		}

		$this->setRedirect( 'index.php?option=com_sef&controller=sefurls', $msg );
    }
    
    function exportsel() {
        $model =& $this->getModel('sefurls');
        
        $where = $model->_getWhereIds();
        
		if(!$model->export($where)) {
			$msg = JText::_( 'Error: URLs could not be exported.' );
		} else {
			$msg = JText::_( 'URL(s) Exported' );
		}

		$this->setRedirect( 'index.php?option=com_sef&controller=sefurls', $msg );
    }
    
    function exportall() {
        $model =& $this->getModel('sefurls');
        
        $where = $model->_getWhere();
        
		if(!$model->export($where)) {
			$msg = JText::_( 'Error: URLs could not be exported.' );
		} else {
			$msg = JText::_( 'URL(s) Exported' );
		}

		$this->setRedirect( 'index.php?option=com_sef&controller=sefurls', $msg );
    }
    
}
?>
