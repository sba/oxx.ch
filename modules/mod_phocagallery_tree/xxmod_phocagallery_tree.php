<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @module Phoca - Phoca Gallery Module
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @based on javascript: dTree 2.05 www.destroydrop.com/javascript/tree/
 * @copyright (c) 2002-2003 Geir Landrö
 */
 
defined('_JEXEC') or die('Restricted access');// no direct access

include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocagallery'.DS.'helpers'.DS.'phocagallery.php' );
//include_once( JPATH_SITE.DS.'components'.DS.'com_phocagallery'.DS.'helpers'.DS.'phocagallery.php' );

$user 		=& JFactory::getUser();
$db 		=& JFactory::getDBO();
$menu 		= &JSite::getMenu();
$document	= & JFactory::getDocument();
		
// Start CSS
$document->addStyleSheet(JURI::base(true).'/modules/mod_phocagallery_tree/assets/dtree.css');
$document->addScript( JURI::base(true) . '/modules/mod_phocagallery_tree/assets/dtree.js' );

//Image Path
$imgPath = JURI::base(true) . '/modules/mod_phocagallery_tree/assets/';
//Unique id for more modules
$treeId = "d".uniqid( "tree_" );


// Current category info
$id 	= JRequest::getVar( 'id', 0, '', 'int' );
$option = JRequest::getVar( 'option', 0, '', 'string' );
$view 	= JRequest::getVar( 'view', 0, '', 'string' );

if ( $option == 'com_phocagallery' && $view == 'category' ) {
	$categoryId = $id; 
} else {
	$categoryId = 0;
}

$hide_categories = '';
if ($params->get( 'hide_categories' ) != '') {
	$hide_categories = $params->get( 'hide_categories' );
}

// PARAMS - Hide categories
$hideCat		= trim( $hide_categories );
$hideCatArray	= explode( ';', $hide_categories );
$hideCatSql		= '';
if (is_array($hideCatArray)) {
	foreach ($hideCatArray as $value) {
		$hideCatSql .= ' AND cc.id != '. (int) trim($value) .' ';
	}
}


// PARAMS - Access Category - display category in category list, which user cannot access
$display_access_category = $params->get( 'display_access_category',0 );


// ACCESS - Only registered or not registered
$hideCatAccessSql = '';
if ($display_access_category == 0) {
 $hideCatAccessSql = ' AND cc.access <= '. $user->get('aid', 0);
} 

// All categories -------------------------------------------------------
$query = 'SELECT cc.title AS text, cc.id AS id, cc.parent_id as parentid, cc.alias as alias, cc.access as access, cc.params as params'
		. ' FROM #__phocagallery_categories AS cc'
		. ' WHERE cc.published = 1'
		. $hideCatSql
		. $hideCatAccessSql
		. ' ORDER BY cc.ordering';
		
$db->setQuery( $query );
$categories = $db->loadObjectList();


$unSet = 0;
foreach ($categories as $key => $category) { 
	// USER RIGHT - ACCESS =======================================
	$rightDisplay	= 1;
	
	if (isset($categories[$key]->params)) {
		$rightDisplay = PhocaGalleryHelper::getUserRight ($categories[$key]->params, 'accessuserid', $category->access, $user->get('aid', 0), $user->get('id', 0), $display_access_category);
	}
		
	if ($rightDisplay == 0) {
		unset($categories[$key]);
		$unSet = 1;
	}
	// ============================================================
}
if ($unSet == 1) {
	$categories = array_values($categories);
}	

// Categories tree
$tree = array();
$text = '';
$tree = categoryTree( $categories, $tree, 0, $text, $treeId );

// Create category tree
function categoryTree( $data, $tree, $id=0, $text='', $treeId )
{      
   foreach ( $data as $value )
   {   
      if ($value->parentid == $id)
      {
         $link = getLink ( $value->id );
		if (strlen($value->text)>4){
			$linktext = substr(addslashes($value->text), 10, 22);
		 $linktooltip = substr($value->text, 8,2) .'.'.substr($value->text, 5,2) .'.'.substr($value->text, 0,4) .' '. substr(addslashes($value->text), 10);
		}else {
			//jahr
			$linktext = addslashes($value->text);
			$linktooltip = '';
		}
         $showText =  $text . ''.$treeId.'.add('.$value->id.','.$value->parentid.',\''.$linktext.'\',\''.$link.'\',\''.$linktooltip.'\');'."\n";
         $tree[$value->id] = $showText;
         $tree = categoryTree($data, $tree, $value->id, '', $treeId);   
      }
   }
   return($tree);
}


// Create link (set Itemid)
function getLink( $id )
{
	// Set Itemid id, exists this link in Menu?
	$menu 			= &JSite::getMenu();
	$itemsCategory	= $menu->getItems('link', 'index.php?option=com_phocagallery&view=category&id='.(int) $id );
	$itemsCategories= $menu->getItems('link', 'index.php?option=com_phocagallery&view=categories');

	if(isset($itemsCategory[0])) {
		$itemId = $itemsCategory[0]->id;
		$alias 	= $itemsCategory[0]->alias;
	} else if(isset($itemsCategories[0])) {
		$itemId = $itemsCategories[0]->id;
		$alias 	= '';
	} else {
		$itemId = 0;
		$alias	= '';
	}
	
	if ($alias != '') {
		$catid_slug = $id . ':' . $alias;
	} else {
		$db 	=& JFactory::getDBO();
		$query 	= 'SELECT c.alias'.
				  ' FROM #__phocagallery_categories AS c' .
				  ' WHERE c.id = '. (int)$id;
		$db->setQuery( $query );
		$catid_alias = $db->loadObject();
	
		if (isset($catid_alias->alias) && $catid_alias->alias != '') {
			$catid_slug = (int)$id . ':'.$catid_alias->alias;
		} else {	
			$catid_slug = (int)$id;
		}
	}
	// Category
	$link = JRoute::_('index.php?option=com_phocagallery&view=category&id='. $catid_slug .'&Itemid='.$itemId);	
	return ($link);
}

// Categories (Head)
$menu 				= &JSite::getMenu();
$itemsCategories	= $menu->getItems('link', 'index.php?option=com_phocagallery&view=categories');
$linkCategories 	= '';
if(isset($itemsCategories[0])) {
	$itemId = $itemsCategories[0]->id;
	$linkCategories = JRoute::_('index.php?option=com_phocagallery&view=categories&Itemid='.$itemId);
}

// Create javascript code	
$jsTree = '';
foreach($tree as $key => $value)
{
	$jsTree .= $value ;
}

//  Output
$output ='<div style="text-align:left;">';
$output.='<div class="dtree">';
$output.='<script type="text/javascript">'."\n";
$output.='<!--'."\n";
$output.=''."\n";
$output.=''.$treeId.' = new dTree2548(\''.$treeId.'\', \''.$imgPath.'\');'."\n";
$output.=''."\n";
$output.=''.$treeId.'.add(0,-1,\' '.JText::_( 'Foto-Galerien' ).'\',\''.$linkCategories.'\',\'\');'."\n";
$output.=$jsTree;
$output.=''."\n";
$output.='document.write('.$treeId.');'."\n";
$output.=''.$treeId.'.openTo('. (int) $categoryId.',\'true\');'. "\n";
$output.=''."\n";
$output.='//-->'."\n";
$output.='</script>';
$output.='</div></div>';

require(JModuleHelper::getLayoutPath('mod_phocagallery_tree'));
/*echo "<pre>";
print_r($jsTree);*/
?>