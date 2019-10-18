<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
class PhocaGalleryHelperRender
{		
	function renderCommentJS($chars) {
		
		$tag = "<script type=\"text/javascript\">" 
		."function countChars() {" . "\n"
		."var maxCount	= ".$chars.";" . "\n"
		."var pfc 			= document.getElementById('phocagallery-comments-form');" . "\n"
		."var charIn		= pfc.phocagallerycommentseditor.value.length;" . "\n"
		."var charLeft	= maxCount - charIn;" . "\n"
		."" . "\n"
		."if (charLeft < 0) {" . "\n"
		."   alert('".JText::_('You have reached maximum limit of characters allowed')."');" . "\n"
		."   pfc.phocagallerycommentseditor.value = pfc.phocagallerycommentseditor.value.substring(0, maxCount);" . "\n"
		."	charIn	 = maxCount;" . "\n"
		."  charLeft = 0;" . "\n"
		."}" . "\n"
		."pfc.phocagallerycommentscountin.value	= charIn;" . "\n"
		."pfc.phocagallerycommentscountleft.value	= charLeft;" . "\n"
		."}" . "\n"
		
		."function checkCommentsForm() {" . "\n"
		."   var pfc = document.getElementById('phocagallery-comments-form');" . "\n"
		."   if ( pfc.phocagallerycommentstitle.value == '' ) {". "\n"
		."	   alert('". JText::_( 'Please enter a title' )."');". "\n"
		."     return false;" . "\n"
		."   } else if ( pfc.phocagallerycommentseditor.value == '' ) {". "\n"
		."	   alert('". JText::_( 'Please enter a comment' )."');". "\n"
		."     return false;" . "\n"
		."   } else {". "\n"
		."     return true;" . "\n"
		."   }" . "\n"
		."}". "\n"
		."</script>";
		
		return $tag;
	}
	
	function renderCategoryCSS($font_color, $background_color, $border_color, $imageBgCSS, $border_color_hover, $background_color_hover, $ol_fg_color, $ol_bg_color, $ol_tf_color, $ol_cf_color, $margin_box, $padding_box, $opacity = 0.8) {
		
		$opacityPer = (float)$opacity * 100;
		
		$tag = "<style type=\"text/css\">\n"
		." #phocagallery .name {color: $font_color ;}\n"
		." .phocagallery-box-file {background: $background_color ; border:1px solid $border_color;margin: ".$margin_box."px;padding: ".$padding_box."px;}\n"
		." .phocagallery-box-file-first { $imageBgCSS }\n"
		." .phocagallery-box-file:hover, .phocagallery-box-file.hover {border:1px solid $border_color_hover ; background: $background_color_hover ;}\n"
		/*
		." .ol-foreground { background-color: $ol_fg_color ;}\n"
		." .ol-background { background-color: $ol_bg_color ;}\n"
		." .ol-textfont { font-family: Arial, sans-serif; font-size: 10px; color: $ol_tf_color ;}"
		." .ol-captionfont {font-family: Arial, sans-serif; font-size: 12px; color: $ol_cf_color ; font-weight: bold;}"*/
		
		
		
		
		. ".bgPhocaClass{
			background:".$ol_bg_color.";
			filter:alpha(opacity=".$opacityPer.");
			opacity: ".$opacity.";
			-moz-opacity:".$opacity.";
			z-index:1000;
			}
			.fgPhocaClass{
			background:".$ol_fg_color.";
			filter:alpha(opacity=100);
			opacity: 1;
			-moz-opacity:1;
			z-index:1000;
			}
			.fontPhocaClass{
			color:".$ol_tf_color.";
			z-index:1001;
			}
			.capfontPhocaClass, .capfontclosePhocaClass{
			color:".$ol_cf_color.";
			font-weight:bold;
			z-index:1001;
			}"
		." </style>\n";
		
		return $tag;
	}
	
	function renderIeHover() {
		
		$tag = "<!--[if IE]>\n<style type=\"text/css\">\n"
		."phocagallery-box-file{
background-color: expression(isNaN(this.js)?(this.js=1,
this.onmouseover=new Function(\"this.className+=' hover';\"),
this.onmouseout=new Function(\"this.className=this.className.replace(' hover','');\")):false););
}"
		." </style>\n<![endif]-->";
		
		return $tag;
	}
	
	function renderPicLens($categoryId) {
		$tag ="<link id=\"phocagallerypiclens\" rel=\"alternate\" href=\""
		.JURI::base(true)."/images/phocagallery/"
		.$categoryId.".rss\" type=\"application/rss+xml\" title=\"\" />"
	    ."<script type=\"text/javascript\" src=\"http://lite.piclens.com/current/piclens.js\"></script>"
		
		."<style type=\"text/css\">\n"
		." .mbf-item { display: none; }\n"
		." #phocagallery .mbf-item { display: none; }\n"
		." </style>\n";
		return $tag;
	
	}
	
	function renderDescriptionUploadJS($chars) {
		
		$tag = "<script type=\"text/javascript\"> \n"
		. "function OnUploadSubmit() { \n"
		. "document.getElementById('loading-label').style.display='block'; \n" 
		. "return true; \n"
		. "} \n"
		."function countCharsUpload() {" . "\n"
		."var maxCount	= ".$chars.";" . "\n"
		."var pfu 			= document.getElementById('uploadForm');" . "\n"
		."var charIn		= pfu.phocagalleryuploaddescription.value.length;" . "\n"
		."var charLeft	= maxCount - charIn;" . "\n"
		."" . "\n"
		."if (charLeft < 0) {" . "\n"
		."   alert('".JText::_('You have reached maximum limit of characters allowed')."');" . "\n"
		."   pfu.phocagalleryuploaddescription.value = pfu.phocagalleryuploaddescription.value.substring(0, maxCount);" . "\n"
		."	charIn	 = maxCount;" . "\n"
		."  charLeft = 0;" . "\n"
		."}" . "\n"
		."pfu.phocagalleryuploadcountin.value	= charIn;" . "\n"
		."pfu.phocagalleryuploadcountleft.value	= charLeft;" . "\n"
		."}" . "\n"
		. "</script>";
		
		return $tag;
	}
	
	function renderDescriptionCreateCatJS($chars) {
		
		$tag = "<script type=\"text/javascript\"> \n"
		."function countCharsCreateCat() {" . "\n"
		."var maxCount	= ".$chars.";" . "\n"
		."var pfcc 			= document.getElementById('phocagallery-create-cat-form');" . "\n"
		."var charIn		= pfcc.phocagallerycreatecatdescription.value.length;" . "\n"
		."var charLeft	= maxCount - charIn;" . "\n"
		."" . "\n"
		."if (charLeft < 0) {" . "\n"
		."   alert('".JText::_('You have reached maximum limit of characters allowed')."');" . "\n"
		."   pfcc.phocagallerycreatecatdescription.value = pfcc.phocagallerycreatecatdescription.value.substring(0, maxCount);" . "\n"
		."	charIn	 = maxCount;" . "\n"
		."  charLeft = 0;" . "\n"
		."}" . "\n"
		."pfcc.phocagallerycreatecatcountin.value	= charIn;" . "\n"
		."pfcc.phocagallerycreatecatcountleft.value	= charLeft;" . "\n"
		."}" . "\n"
		
		."function checkCreateCatForm() {" . "\n"
		."   var pfcc = document.getElementById('phocagallery-create-cat-form');" . "\n"
		."   if ( pfcc.categoryname.value == '' ) {". "\n"
		."	   alert('". JText::_( 'Please enter a category title' )."');". "\n"
		."     return false;" . "\n"
		//."   } else if ( pfcc.phocagallerycreatecatdescription.value == '' ) {". "\n"
		//."	   alert('". JText::_( 'Please enter a description' )."');". "\n"
		//."     return false;" . "\n"
		."   } else {". "\n"
		."     return true;" . "\n"
		."   }" . "\n"
		."}". "\n"
		. "</script>";
		
		return $tag;
	}
	
	function renderHighslideJS($front_modal_box_width, $front_modal_box_height) {
		$tag = '<script type="text/javascript">'
    
	      .' hs.objectLoadTime = \'after\';'
		  .' hs.graphicsDir = \''.JURI::base(true).'/components/com_phocagallery/assets/js/highslide/graphics/\';'
	      .' hs.outlineType = \'rounded-white\';'
	      .' hs.outlineWhileAnimating = true;'
		  .' hs.enableKeyListener = false;'
		  .' hs.minWidth = '.$front_modal_box_width.';'
		  .' hs.minHeight = '.$front_modal_box_height.';'
		  .' hs.fadeInOut = true;'
		  .'</script>';
	
		return $tag;
	}
	
	function renderHighslideImageJS() {
		$tag = '<script type="text/javascript">'
		
		  .' hs.graphicsDir = \''.JURI::base(true).'/components/com_phocagallery/assets/js/highslide/graphics/\';'
		  .' hs.align = \'center\';'
		  .' hs.transitions = [\'expand\', \'crossfade\'];'
		  .' hs.outlineType = \'rounded-white\';'
		  .' hs.fadeInOut = true;'
		  //.' hs.dimmingOpacity = 0.75;'
		    
		  
		  .' if (hs.addSlideshow) hs.addSlideshow({ '
		  //.'   slideshowGroup: \'group1\','
		  .'   interval: 5000,'
		  .'   repeat: false,'
		  .'   useControls: true,'
	      .'   fixedControls: false,'
		  .'     overlayOptions: {'
		  .'     	opacity: 1,'
		  .'     	position: \'top center\','
		  .'     	hideOnMouseOut: true'
		  .'	}'
		  .' });'
		  .'</script>';
		  
	
		return $tag;
	}
	
	function renderLightWindowJS() {
		$tag = '<script type="text/javascript">'
			.'var links = $$(\'%r%class%r%\');'
			.'links.each(function(link) {'
			.'	Event.observe(link, \'click\', function() {parent.myLightWindow.activate(null, link);}, false);'
			.'	link.onclick = function() {return false;};'
			.'});'		
		    .'</script>';
		return $tag;
	}
}