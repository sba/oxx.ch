<?php
/**
 * Gästebuch Module Entry Point
 * 
 * @package    OXX
 * @subpackage Modules
 * @license        GNU/GPL, see LICENSE.php
 * mod_guestbook is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );

$gb = modGBHelper::getGB( $params );
require( JModuleHelper::getLayoutPath( 'mod_guestbook' ) );
?>