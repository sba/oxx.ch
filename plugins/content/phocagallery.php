<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @plugin Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );
//include_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocagallery'.DS.'assets'.DS.'phoca_config.php' );
include_once( JPATH_SITE.DS.'components'.DS.'com_phocagallery'.DS.'helpers'.DS.'phocagallery.php' );

class plgContentPhocaGallery extends JPlugin
{
	var $_pgcss 		= 0;
	var $_pgiecss		= 0;
	var $_pgcustomcss2	= 0;
	var $_pgcount		= 0;
	
	function _setCss () {
		$this->_pgcss = 1;
	}
	
	function _setIeCss () {
		$this->_pgiecss = 1;
	}
	
	function _setCustomCss2 () {
		$this->_pgcustomcss2 = 1;
	}
	
	function _setCount () {
		$this->_pgcount = (int)$this->_pgcount + 1;
	}
	
	function plgContentPhocaGallery( &$subject, $params )
	{
            parent::__construct( $subject, $params  );

    }

	function onPrepareContent( &$article, &$params, $limitstart )
	{
		$user =& JFactory::getUser();
		$gid = $user->get('aid', 0);
		$db =& JFactory::getDBO();
		$menu 	= &JSite::getMenu();
		$document	= & JFactory::getDocument();
		
		
		
		// Start CSS
		if ($this->_pgcss == 0) {
			$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/phocagallery.css');
			$this->_setCss();
		}
		// Start Plugin
		$regex_one		= '/({phocagallery\s*)(.*?)(})/si';
		$regex_all		= '/{phocagallery\s*.*?}/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$article->text,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);
		$customCSS		= '';
		$customCSS2		= '';
		
		for($i = 0; $i < $count_matches; $i++)
		{
			
			$this->_setCount();
			// Plugin variables
			$view 					= '';
			$catid					= 0;
			$imageid				= 0;
			$imagerandom			= 0;
			$imageshadow			= 'none';
			$limitstart				= 0;
			$limitcount				= 0;
			$switch_width			= 640;
			$switch_height			= 480;
			$basic_image_id			= 0;
			$enable_switch			= 0;
			
			$displayname 			= 1;
			$displayicondetail 		= 1;
			$displayicondownload 	= 1;
			$detail_window 			= 0;
			$detail_buttons			= 1;
			$hidecategories			= '';
			
			$namefontsize			= 12;
			$namenumchar			= 11;
			
			$displaydescription		= 0;
			$descriptionheight		= 16;
			
			// CSS
			$font_color 			= '#135cae';
			$background_color 		= '#fcfcfc';
			$background_color_hover = '#f5f5f5';
			$image_background_color = '#f5f5f5';
			$border_color 			= '#e8e8e8';
			$border_color_hover 	= '#135cae';
			
			$float					= '';
			
			// Image categories
			$img_cat				= 1;
			$img_cat_size			= 'small';
			
			// Get plugin parameters
			$phocagallery	= $matches[0][$i][0];
			preg_match($regex_one,$phocagallery,$phocagallery_parts);
			$parts			= explode("|", $phocagallery_parts[2]);
			$values_replace = array ("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");

			foreach($parts as $key => $value)
			{
				$values = explode("=", $value, 2);
				
				foreach ($values_replace as $key2 => $values2)
				{
					$values = preg_replace($values2, '', $values);
				}
				
				// Get plugin parameters from article
					 if($values[0]=='view')				{$view					= $values[1];}
				else if($values[0]=='categoryid')		{$catid					= $values[1];}
				else if($values[0]=='imageid')			{$imageid				= $values[1];}
				else if($values[0]=='imagerandom')		{$imagerandom			= $values[1];}
				else if($values[0]=='imageshadow')		{$imageshadow			= $values[1];}
				else if($values[0]=='limitstart')		{$limitstart			= $values[1];}
				else if($values[0]=='limitcount')		{$limitcount			= $values[1];}
				else if($values[0]=='detail')			{$detail_window			= $values[1];}
				else if($values[0]=='displayname')		{$displayname			= $values[1];}
				else if($values[0]=='displaydetail')	{$displayicondetail		= $values[1];}
				else if($values[0]=='displaydownload')	{$displayicondownload	= $values[1];}
				else if($values[0]=='displaybuttons')	{$detail_buttons		= $values[1];}
				
				else if($values[0]=='namefontsize')		{$namefontsize			= $values[1];}
				else if($values[0]=='namenumchar')		{$namenumchar			= $values[1];}
				
				else if($values[0]=='displaydescription'){$displaydescription	= $values[1];}
				else if($values[0]=='descriptionheight'){$descriptionheight		= $values[1];}
				else if($values[0]=='hidecategories')	{$hidecategories		= $values[1];}
				
				// CSS
				else if($values[0]=='fontcolor')		{$font_color				= $values[1];}
				else if($values[0]=='bgcolor')			{$background_color			= $values[1];}
				else if($values[0]=='bgcolorhover')		{$background_color_hover	= $values[1];}
				else if($values[0]=='imagebgcolor')		{$image_background_color	= $values[1];}
				else if($values[0]=='bordercolor')		{$border_color				= $values[1];}
				else if($values[0]=='bordercolorhover')	{$border_color_hover		= $values[1];}
				
				else if($values[0]=='float')			{$float	= $values[1];}
				
				//Image categories
				else if($values[0]=='imagecategories')		{$img_cat				= $values[1];}
				else if($values[0]=='imagecategoriessize')	{$img_cat_size			= $values[1];}
				else if($values[0]=='switchwidth')			{$switch_width			= $values[1];}
				else if($values[0]=='switchheight')			{$switch_height			= $values[1];}
				else if($values[0]=='basicimageid')			{$basic_image_id		= $values[1];}
				else if($values[0]=='enableswitch')			{$enable_switch			= $values[1];}
			}
			
			
			// CSS will be added
			$icss = $this->_pgcount;
			// Add custom CSS for every image (every image can have other CSS, Hover doesn't work in IE6)
			$customCSS	.= " .pgplugin".$icss." {border:1px solid $border_color ; background: $background_color ;}\n"
						." .pgplugin".$icss.":hover, .pgplugin".$i.".hover {border:1px solid $border_color_hover ; background: $background_color_hover ;}\n";
			
			

									
			// Set Output (Modal box or Standard Popup box)
			JHTML::_('behavior.modal', 'a.modal-button');

			$button = new JObject();
			$button->set('name', 'image');
			
			$paramsC = JComponentHelper::getParams('com_phocagallery') ;
			// Get thumbnail sizes from params
			$small_image_width			=	50;
			$small_image_height			=	50;
			$medium_image_width			=	100;
			$medium_image_height		=	100;
			$front_modal_box_width		=	680;
			$front_modal_box_height		=	560;
			$front_popup_window_width	=	680;
			$front_popup_window_height	=	560;
			if ($paramsC->get( 'medium_image_width' ) != '') {
				$medium_image_width = $paramsC->get( 'medium_image_width' );
			}
			if ($paramsC->get( 'medium_image_height' ) != '') {
				$medium_image_height = $paramsC->get( 'medium_image_height' );
			}
			if ($paramsC->get( 'front_modal_box_width' ) != '') {
				$front_modal_box_width = $paramsC->get( 'front_modal_box_width' );
			}
			if ($paramsC->get( 'front_modal_box_height' ) != '') {
				$front_modal_box_height = $paramsC->get( 'front_modal_box_height' );
			}
			if ($paramsC->get( 'front_popup_window_width' ) != '') {
				$front_popup_window_width = $paramsC->get( 'front_popup_window_width' );
			}
			if ($paramsC->get( 'front_popup_window_height' ) != '') {
				$front_popup_window_height = $paramsC->get( 'front_popup_window_height' );
			}
			if ($paramsC->get( 'small_image_width' ) != '') {
			$small_image_width = $paramsC->get( 'small_image_width' );
			}
			if ($paramsC->get( 'small_image_height' ) != '') {
				$small_image_height = $paramsC->get( 'small_image_height' );
			}


			if ($displaydescription == 1) {
				$front_popup_window_height	= $front_popup_window_height + $descriptionheight;
				$front_modal_box_height		= $front_modal_box_height + $descriptionheight;
			}
			if ($detail_buttons != 1) {
				$front_popup_window_height	= $front_popup_window_height - 45;
				$front_modal_box_height		= $front_modal_box_height - 45;
			}
			
			
			if ($detail_window == 1)//standard popup window - get this from paramaters
			{
				$button->set('methodname', 'js-button');
				$button->set('options', "window.open(this.href,'win2','width=".$front_popup_window_width.",height=".$front_popup_window_height.",menubar=no,resizable=yes'); return false;");
				
			}
			else //modal popup box
			{
			/*	$button->set('modal', true);
				$button->set('methodname', 'modal-button');
				$button->set('options', "{handler: 'iframe', size: {x: ".$front_modal_box_width.", y: ".$front_modal_box_height."}}");*/
				
				
				//Parameters
				$modal_box_overlay_color = '#000000';
				if ($paramsC->get( 'modal_box_overlay_color' ) != ''){$modal_box_overlay_color = $paramsC->get( 'modal_box_overlay_color' );}
				
				$modal_box_overlay_opacity = 0.7;
				if ($paramsC->get( 'modal_box_overlay_opacity' ) != ''){$modal_box_overlay_opacity = $paramsC->get( 'modal_box_overlay_opacity' );}
				
				$modal_box_border_color = '#000000';
				if ($paramsC->get( 'modal_box_border_color' ) != ''){$modal_box_border_color = $paramsC->get( 'modal_box_border_color' );}
				
				$modal_box_border_width = '10';
				if ($paramsC->get( 'modal_box_border_width' ) != ''){$modal_box_border_width = $paramsC->get( 'modal_box_border_width' );}
				
				
				
				$button->set('modal', true);
				$button->set('methodname', 'modal-button');
				$button->set('options', "{handler: 'iframe', size: {x: ".$front_modal_box_width.", y: ".$front_modal_box_height."}, overlayOpacity: ".$modal_box_overlay_opacity."}");
				
			//	$document->addCustomTag( "<style type=\"text/css\"> \n" 
			// only one code for all
			if ($customCSS2 == '') {
				$customCSS2 .= " #sbox-window {background-color:".$modal_box_border_color.";padding:".$modal_box_border_width."px} \n"
							." #sbox-overlay {background-color:".$modal_box_overlay_color.";} \n";
			}
			//	." </style> \n");
				

				
			}
			// End open window parameters
			
			$output	='';
			$output .= '<div class="phocagallery">' . "\n";
			
			
			//--------------------------
			// DISPLAYING OF CATEGORIES (link doesn't work if there is no menu link)
			//--------------------------
			
			
			$hideCat		= trim( $hidecategories );
			$hideCatArray	= explode( ';', $hidecategories );
			$hideCatSql		= '';
			if (is_array($hideCatArray)) {
				foreach ($hideCatArray as $value) {
					$hideCatSql .= ' AND cc.id != '. (int) trim($value) .' ';
				}
			}
			// by vogo
			$uniqueCatSql	= '';
			if ($catid > 0) {
				$uniqueCatSql	= ' AND cc.id = '. $catid .'';	
			}
			
			if ($view == 'categories')
			{
				//CATEGORIES
				$queryc = 'SELECT cc.*, a.catid, COUNT(a.id) AS numlinks,'
				. ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(\':\', cc.id, cc.alias) ELSE cc.id END as slug'
				. ' FROM #__phocagallery_categories AS cc'
				. ' LEFT JOIN #__phocagallery AS a ON a.catid = cc.id'
				. ' WHERE a.published = 1'
				. ' AND cc.published = 1'
				. $hideCatSql
				. $uniqueCatSql
				. ' GROUP BY cc.id'
				. ' ORDER BY cc.ordering';

				//SUBCATEGORIES
				$querysc = 'SELECT cc.title AS text, cc.id AS value, cc.parent_id as parentid'
				. ' FROM #__phocagallery_categories AS cc'
				. ' WHERE cc.published = 1'
				. ' ORDER BY cc.ordering';

				
				$data_outcome = '';
				$data_outcome_array = '';
			
				$db->setQuery($queryc);
				$outcome_data = $db->loadObjectList();
			
				$db->setQuery($querysc);
				$outcome_subcategories = $db->loadObjectList();
			
				$tree = array();
				$text = '';
				$tree = PhocaGalleryHelperFront::CategoryTree($outcome_subcategories, $tree, 0, $text);
			
				$outcome_subcategories = PhocaGalleryHelperFront::CategoryTreeCreating($outcome_subcategories, $tree, 0);
				
				foreach ($outcome_subcategories as $key => $value)
				{
					foreach ($outcome_data as $key2 => $value2)
					{
						if ($value->value == $value2->id)
						{
							
							$data_outcome 					= new JObject();
							$data_outcome->id				= $value2->id;
							$data_outcome->parent_id		= $value2->parent_id;
							$data_outcome->title			= $value->text;
							$data_outcome->name				= $value2->name;
							$data_outcome->alias			= $value2->alias;
							$data_outcome->image			= $value2->image;
							$data_outcome->section			= $value2->section;
							$data_outcome->image_position	= $value2->image_position;
							$data_outcome->description		= $value2->description;
							$data_outcome->published		= $value2->published;
							$data_outcome->editor			= $value2->editor;
							$data_outcome->ordering			= $value2->ordering;
							$data_outcome->access			= $value2->access;
							$data_outcome->count			= $value2->count;
							$data_outcome->params			= $value2->params;
							$data_outcome->catid			= $value2->catid;
							$data_outcome->numlinks			= $value2->numlinks;
							$data_outcome->slug				= $value2->slug;
							$data_outcome->link				= "";
							$data_outcome->filename			= "";
							$data_outcome->linkthumbnailpath	= "";
							
							//FILENAME
							$queryfn = 'SELECT filename AS filename FROM #__phocagallery WHERE catid='.$value2->id.' AND published=1 ORDER BY ordering LIMIT 1';
							$db->setQuery($queryfn);
							$outcome_filename	    = $db->loadObjectList();
							$data_outcome->filename	= $outcome_filename[0]->filename;
							$data_outcome_array[] 	= $data_outcome;
						}	
					}
				}
			
				
				
				if ($img_cat == 1)
				{
					$medium_image_height	= $medium_image_height + 18;
					$medium_image_width 	= $medium_image_width + 18;
					$small_image_width		= $small_image_width +18;
					$small_image_height		= $small_image_height +18;
						
					$output .= '<table border="0">';
					foreach ($data_outcome_array as $category)
					{
						// -------------------------------------------------------------- SEF PROBLEM
						// Is there a Itemid for category
						$items	 = $menu->getItems('link', 'index.php?option=com_phocagallery&view=category&id='. $category->id);
						$itemscat= $menu->getItems('link', 'index.php?option=com_phocagallery&view=categories');
						
						if(isset($itemscat[0]))
						{
							$itemid = $itemscat[0]->id;
							$category->link = JRoute::_('index.php?option=com_phocagallery&view=category&id='. $category->slug.'&Itemid='.$itemid );
						}
						else if(isset($items[0]))
						{
							$itemid = $items[0]->id;
							$category->link = JRoute::_('index.php?option=com_phocagallery&view=category&id='. $category->slug.'&Itemid='.$itemid );
						}
						else
						{							
							$itemid = 0;
							//$category->link = JRoute::_('index.php?option=com_phocagallery&view=category&id='. $category->slug.'&Itemid='.$itemid );
							$category->link = JRoute::_('index.php?option=com_phocagallery&view=category&id='. $category->slug );
							
						}
						// ---------------------------------------------------------------------------------

						$imgCatSizeHelper = 'small';
						
						$mediumCSS 	= 'background: url(\''.JURI::base(true).'/components/com_phocagallery/assets/images/shadow1.'.PhocaGalleryHelperFront::getFormatIconComponent().'\') 50% 50% no-repeat;height:'.$medium_image_height	.'px;width:'.$medium_image_width.'px;';
						$smallCSS	= 'background: url(\''.JURI::base(true).'/components/com_phocagallery/assets/images/shadow3.'.PhocaGalleryHelperFront::getFormatIconComponent().'\') 50% 50% no-repeat;height:'.$small_image_height	.'px;width:'.$small_image_width.'px;';
						
						switch ($img_cat_size)
						{	
							case 'mediumfoldershadow':			
								$imageBg = $mediumCSS;
								$imgCatSizeHelper = 7;
							break;
							
							case 'smallfoldershadow':
								$imageBg = $smallCSS;
								$imgCatSizeHelper = 6;
							break;
							
							case 'mediumshadow':		
								$imageBg = $mediumCSS;
								$imgCatSizeHelper = 5;
							break;
							
							case 'smallshadow':
								$imageBg = $smallCSS;
								$imgCatSizeHelper = 4;
							break;
							
							case 'mediumfolder':
								$imageBg = '';
								$imgCatSizeHelper = 3;
							break;
							
							case 'smallfolder':
							default:
								$imageBg = '';
								$imgCatSizeHelper = 2;
							break;
							
							case 'medium':
								$imageBg = '';
								$imgCatSizeHelper = 1;
							break;
							
							case 'small':
							default:
								$imageBg = '';
								$imgCatSizeHelper = 0;
							break;
						}
						
						
						$file_thumbnail = PhocaGalleryHelperFront::displayFileOrNoImageCategories($category->filename, $imgCatSizeHelper);
						$category->linkthumbnailpath = $file_thumbnail['rel'];
						
						//Output
						$output .= '<tr>';
						$output .= '<td align="center" valign="middle" style="'.$imageBg.'"><a href="'.$category->link.'">'
						       // .JHTML::_( 'image.site', $category->linkthumbnailpath, '', '', '', $category->title, 'style="border:0"' )
							   .'<img src="'.$category->linkthumbnailpath.'" alt="'.$category->title.'" style="border:0" />'
							   .'</a></td>';
						$output .= '<td><a href="'.$category->link.'" class="category'.$this->params->get( 'pageclass_sfx' ).'">'.$category->title.'</a>&nbsp;';
						$output .= '<span class="small">('.$category->numlinks.')</span></td>';
						$output .= '</tr>';
					}
					$output .= '</table>';
				}
				else
				{
					$output .= '<ul>';
					
					foreach ($data_outcome_array as $category)
					{
						// -------------------------------------------------------------- SEF PROBLEM
						// Is there a Itemid for category
						$items	 = $menu->getItems('link', 'index.php?option=com_phocagallery&view=category&id='. $category->id);
						$itemscat= $menu->getItems('link', 'index.php?option=com_phocagallery&view=categories');
						
						if(isset($itemscat[0]))
						{
							$itemid = $itemscat[0]->id;
							$category->link = JRoute::_('index.php?option=com_phocagallery&view=category&id='. $category->slug.'&Itemid='.$itemid );
						}
						else if(isset($items[0]))
						{
							$itemid = $items[0]->id;
							$category->link = JRoute::_('index.php?option=com_phocagallery&view=category&id='. $category->slug.'&Itemid='.$itemid );
						}
						else
						{
							$itemid = 0;
							$category->link = JRoute::_('index.php?option=com_phocagallery&view=category&id='. $category->slug );
						}
						// ---------------------------------------------------------------------------------
					
					
						$output .='<li>'
								 .'<a href="'.$category->link.'" class="category'.$params->get( 'pageclass_sfx' ).'">'
								 . $category->title.'</a>&nbsp;<span class="small">('.$category->numlinks.')</span>'
								 .'</li>';
					}
					$output .= '</ul>';
				}
			}
				
			
			//-----------------------
			// DISPLAYING OF IMAGES
			//-----------------------
			if ($view == 'category')
			{
				$where = '';
				// Only one image
				if ($imageid > 0) {
					$where = ' AND id = '. $imageid;
				}
				
				// Random image
				if ($imagerandom == 1 && $catid > 0) {
					
					$query = 'SELECT id' .
					' FROM #__phocagallery' .
					' WHERE catid = '.(int) $catid .
					' AND published = 1' .
					' ORDER BY RAND()';
			
					$db->setQuery($query);
					$idQuery =& $db->loadObject();
					if (!empty($idQuery)) {
						$where = ' AND id = '. $idQuery->id;
					}
				}
				
				$limit = '';
				// Count of images (LIMIT 0, 20)
				if ($limitcount > 0) {
					$limit = ' LIMIT '.$limitstart.', '.$limitcount;
				}
				
			
				$query = 'SELECT *' .
			' FROM #__phocagallery' .
			' WHERE catid = '.(int) $catid .
			' AND published = 1' . $where .
			' ORDER BY ordering' . $limit;
			
			
				$db->setQuery($query);
				$category =& $db->loadObjectList();
				
				// current category info
				$query = 'SELECT c.*,' .
					' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug '.
					' FROM #__phocagallery_categories AS c' .
					' WHERE c.id = '. (int) $catid;
				//	' AND c.section = "com_phocagallery"';
	
				$db->setQuery($query, 0, 1);
				$category_info = $db->loadObject();
				
				// Output
				


				foreach ($category as $image)
				{
					$image->slug 				= $image->id.'-'.$image->alias;
					
					// Get file thumbnail or No Image
					$file_thumbnail 			= PhocaGalleryHelperFront::displayFileOrNoImage($image->filename, 'medium');
					$image->linkthumbnailpath 		= $file_thumbnail['rel'];
					$image->linkthumbnailpathabs 	= $file_thumbnail['abs'];
					
					
					// -------------------------------------------------------------- SEF PROBLEM
					// Is there a Itemid for category
					$items	 = $menu->getItems('link', 'index.php?option=com_phocagallery&view=category&id='.$category_info->id);
					$itemscat= $menu->getItems('link', 'index.php?option=com_phocagallery&view=categories');
					
					
					if(isset($itemscat[0]))
					{
						$itemid = $itemscat[0]->id;
						$image->link = JRoute::_('index.php?option=com_phocagallery&view=detail&catid='. $category_info->slug .'&id='. $image->slug .'&Itemid='.$itemid . '&tmpl=component&detail='.$detail_window.'&buttons='.$detail_buttons );
					}
					else if(isset($items[0]))
					{
						$itemid = $items[0]->id;
						$image->link = JRoute::_('index.php?option=com_phocagallery&view=detail&catid='. $category_info->slug .'&id='. $image->slug .'&Itemid='.$itemid . '&tmpl=component&detail='.$detail_window.'&buttons='.$detail_buttons );
					}
					else
					{
						$itemid = 0;
						$image->link = JRoute::_('index.php?option=com_phocagallery&view=detail&catid='. $category_info->slug .'&id='. $image->slug . '&tmpl=component&detail='.$detail_window.'&buttons='.$detail_buttons );
					}
					// ---------------------------------------------------------------------------------
					
					
					
					// Float
					$float_code	= '';
					if ($float != '') {
						$float_code = 'position:relative;float:'.$float.';';
					}

					// Maximum size of module image is 100 x 100
					jimport( 'joomla.filesystem.file' );
					$imageWidth 	= 100;
					$imageHeight	= 100;
					if (JFile::exists($image->linkthumbnailpathabs))
					{
						list($width, $height) = GetImageSize( $image->linkthumbnailpath );
						$imageWidth 	= $width;
						$imageHeight	= $height;
					}
					
					// Height of box and float = CSS style
					if ($imageHeight > $imageWidth) {
						if ($imageHeight < 100) {
							$imageHeight = 100;
						}
						$imageWidth = $imageHeight;
					}
					if ($imageWidth > $imageHeight) {
						if ($imageWidth < 100) {
							$imageWidth = 100;
						}
						$imageHeight = $imageWidth;
					}
					
					$boxImageHeight = $imageHeight;
					$boxImageWidth = $imageWidth + 20;
					

					if ($displayname == 1) {
						$boxImageHeight = $boxImageHeight + 20;
					}
					
					if ( $displayicondetail == 1 || $displayicondownload == 1 ) {
						$boxImageHeight = $boxImageHeight + 20;
					}
					
					if ( $imageshadow != 'none' ) {		
						$boxImageHeight 		= $boxImageHeight + 18;
						$imageHeight			= $imageHeight + 18;
						$imageWidth 			= $imageWidth + 18;
						$image_background_color	= 'url(\''.JURI::base(true).'/components/com_phocagallery/assets/images/'.$imageshadow.'.'.PhocaGalleryHelperFront::getFormatIconComponent().'\') 0 0 no-repeat;';
					}
					

					$output .= '<div class="phocagallery-box-file pgplugin'.$icss.'" style="height:'. $boxImageHeight .'px; width:'. $boxImageWidth.'px;'.$float_code.'">' . "\n";
					$output .= '<center>'  . "\n"
							  .'<div class="phocagallery-box-file-first" style="background: '.$image_background_color.';height:'.$imageHeight.'px;width:'.$imageWidth.'px;">' . "\n"
							  .'<div class="phocagallery-box-file-second">' . "\n"
							  .'<div class="phocagallery-box-file-third">' . "\n"
							  .'<center>' . "\n"
							  .'<a class="'.$button->methodname.'" title="'.$image->title.'" href="'. JRoute::_($image->link).'"'; 
					
					if ($detail_window == 1)
					{
						$output .= ' onclick="'.$button->options.'"';// Standard Popup window
					}
					else
					{
						$output .= ' rel="'.$button->options.'"';// Modal box
					}
					
					// Enable the switch image
					if ($enable_switch == 1) {
						$output .=' onmouseover="PhocaGallerySwitchImage(\'PhocaGalleryobjectPicture\', \''. str_replace('phoca_thumb_m_','phoca_thumb_l_', JURI::root() . $image->linkthumbnailpath).'\');" onmouseout="PhocaGallerySwitchImage(\'PhocaGalleryobjectPicture\', \''. str_replace('phoca_thumb_m_','phoca_thumb_l_', JURI::root() . $image->linkthumbnailpath).'\');"';
					
					//$output .=' onmouseover="PhocaGallerySwitchImage(\'PhocaGalleryobjectPicture\', \''. str_replace('phoca_thumb_m_','phoca_thumb_l_', JURI::root() . $image->linkthumbnailpath).'\');"';
						
					}
					
					$output .= ' >' . "\n";
					//$output .= JHTML::_( 'image.site', $image->linkthumbnailpath, '', '', '', $image->title );
					$output .= '<img src="'.$image->linkthumbnailpath.'" alt="'.$image->title.'" />';
					$output .='</a>'
							 .'</center>' . "\n"
							 .'</div>' . "\n"
							 .'</div>' . "\n"
							 .'</div>' . "\n"
						     .'</center>' . "\n";

					if ($displayname == 1)
					{
						$output .= '<div class="name" style="color: '.$font_color.' ;font-size:'.$namefontsize.'px;margin-top:5px;font-style:italic;font-weight:bold;text-align:center;">'.PhocaGalleryHelperFront::wordDelete($image->title, $namenumchar, '...').'</div>';
					}
		
					if ($displayicondetail == 1 || $displayicondownload == 1)
					{
						$output .= '<div class="detail" style="text-align:right">';
						
						if ($displayicondetail == 1)
						{
							$output .= '<a class="'.$button->methodname.'" title="'. JText::_('Image Detail').'" href="'.JRoute::_($image->link).'"';
							if ($detail_window == 1)
							{
								$output .= ' onclick="'. $button->options.'"';
							}
							else
							{
								$output .= ' rel="'. $button->options.'"';
							}
							
							$output .= ' >';
							//$output .= JHTML::_('image', 'components/com_phocagallery/assets/images/icon-view....', $image->title);
							$output .= '<img src="components/com_phocagallery/assets/images/icon-view.'.PhocaGalleryHelperFront::getFormatIconComponent().'" alt="'.$image->title.'" />';
							$output .= '</a>';
						}
						
						if ($displayicondownload == 1)
						{
							$output .= '<a class="'. $button->methodname.'" title="'. JText::_('Image Download').'" href="'. JRoute::_($image->link . '&amp;phocadownload=1').'"';
							if ($detail_window == 1)
							{
								$output .= ' onclick="'. $button->options.'"';
							}
							else
							{
								$output .= ' rel="'. $button->options.'"';
							}
							$output .= ' >';
							//$output .= JHTML::_('image', 'components/com_phocagallery/assets/images/icon-download....', $image->title);
							$output .= '<img src="components/com_phocagallery/assets/images/icon-download.'.PhocaGalleryHelperFront::getFormatIconComponent().'" alt="'.$image->title.'" />';
							$output .= '</a>';
						
						}
						
						$output .= '</div>';
						if ($float == '')
						{
							$output .= '<div style="clear:both"> </div>';
						}
						
					}
					
					$output .= '</div>';
					
				}
							
			}
			
			//--------------------------
			// DISPLAYING OF SWITCHIMAGE
			//--------------------------
			if ($view == 'switchimage') {
			
				$imagePathFront	= PhocaGalleryHelperFront::getPathSet();
				$waitImage 		= $imagePathFront['front_image'] . 'icon-switch.gif';
				$basicImage		= $imagePathFront['front_image'] . 'phoca_thumb_l_no_image.' . PhocaGalleryHelperFront::getFormatIcon();
				
				if ($basic_image_id > 0) {
				
					$query = 'SELECT *' .
					' FROM #__phocagallery' .
					' WHERE id = '.(int) $basic_image_id;
			
			
				$db->setQuery($query);
				$basicImageArray =& $db->loadObject();
				
				$fileBasicThumb = PhocaGalleryHelperFront::getThumbnailName($basicImageArray->filename, 'large');
				$basicImage  	= $fileBasicThumb['rel'];
				}
				
				$switchHeight 	= $switch_height;//$this->switchheight;
				$switchCenterH	= ($switchHeight / 2) - 18;
				$switchWidth 	= $switch_width;//$this->switchwidth;
				$switchCenterW	= ($switchWidth / 2) - 18;
				
				$document->addCustomTag(PhocaGalleryHelperFront::switchImage($waitImage));
				
				$switchHeight	= $switchHeight + 5;
			
				$output .='<div><center class="main-switch-image" style="margin:0px;padding:7px 5px 7px 5px;margin-bottom:15px;"><table border="0" cellspacing="5" cellpadding="5" style="border:1px solid #c2c2c2;"><tr><td align="center" valign="middle" style="text-align:center;width:'. $switchWidth .'px;height:'. $switchHeight .'px; background: url(\''. JURI::root().'components/com_phocagallery/assets/images/icon-switch.gif\') '.$switchCenterW.'px '.$switchCenterH.'px no-repeat;margin:0px;padding:0px;">
'.JHTML::_( 'image.site', $basicImage , '', '', '', '', ' id="PhocaGalleryobjectPicture"  border="0"' ).'
</td></tr></table></center></div>';

			}
			
			//--------------------------
			// DISPLAYING OF Clear Both
			//--------------------------
			if ($view == 'clearboth') {
				$output .= '<div style="clear:both"> </div>';
			}
			if ($view == 'clearright') {
				$output .= '<div style="clear:right"> </div>';
			}
			if ($view == 'clearleft') {
				$output .= '<div style="clear:left"> </div>';
			}
			
			
			$output .= '</div>';
			if ($float == '')
			{
				$output .= '<div style="clear:both"> </div>';
			}
			
			$article->text = preg_replace($regex_all, $output, $article->text, 1);
			
		}
		
		
		// CUSTOM CSS 2 - For all items it will be the same
		if ($this->_pgcustomcss2 == 0) {
			$document->addCustomTag( "<style type=\"text/css\">\n"
								. $customCSS2 . "\n"
								." </style>\n");
			$this->_setCustomCss2();
		}
		
		// All custom CSS tags will be added into one CSS area
		$document->addCustomTag( "<style type=\"text/css\">\n"
								. $customCSS . "\n"
								." </style>\n");	
		// CSS FOR IE (ONLY ONE TIME)// There can be possible problems ... with overwriting by standard CSS
		if ($this->_pgiecss == 0) {
			$document->addCustomTag("<!--[if IE]>\n<link rel=\"stylesheet\" href=\"".JURI::base(true)."/components/com_phocagallery/assets/phocagalleryieall.css\" type=\"text/css\" />\n<![endif]-->");
			$this->_setIeCss();
		}	
		return true;
	}

}
?>