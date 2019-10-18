<?php

/*

 * @package Joomla 1.5

 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.

 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php

 *

 * @component Phoca Gallery

 * @copyright Copyright (C) Jan Pavelka www.phoca.cz

 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL

 */



// Check to ensure this file is included in Joomla!

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');

/**

 * Phoca Gallery View Gallery Detail View

 */

class PhocaGalleryViewMap extends JView

{

	/**

	 * Method to display the Phoca Gallery View Category

	 * @param template $tpl

	 */

	function display($tpl = null)

	{

		global $mainframe;

		

		// PLUGIN WINDOW - we get information from plugin

		$get				= '';

		$get['map']		= JRequest::getVar( 'map', '', 'get', 'string' );

		

		$document	= & JFactory::getDocument();		

		$params		= &$mainframe->getParams();

		

		// START CSS

		$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/phocagallery.css');

		

		// PARAMS - Open window parameters - modal popup box or standard popup window

		$detail_window = $params->get( 'detail_window', 0 );

		

		// Plugin information

		if (isset($get['map']) && $get['map'] != '') {

			$detail_window = $get['map'];

		}

		

		

		// Standard popup window

		if ($detail_window == 1) {

			$detail_window_close 	= 'window.close();';

			$detail_window_reload	= 'window.location.reload(true);';

		} else {//modal popup window

			$detail_window_close 	= 'window.parent.document.getElementById(\'sbox-window\').close();';

			$detail_window_reload	= 'window.location.reload(true);';

		}

		

		// PARAMS - Display Description in Detail window - set the font color

		$detail_window_background_color = $params->get( 'detail_window_background_color', '#ffffff' );

		$description_lightbox_font_color = $params->get( 'description_lightbox_font_color', '#ffffff' );

		$description_lightbox_bg_color = $params->get( 'description_lightbox_bg_color', '#000000' );

		$description_lightbox_font_size = $params->get( 'description_lightbox_font_size', 12 );

		

	



		// NO SCROLLBAR IN DETAIL WINDOW

		$document->addCustomTag( "<style type=\"text/css\"> \n" 

			." html,body, .contentpane{overflow:hidden;background:".$detail_window_background_color.";} \n" 

			." center, table {background:".$detail_window_background_color.";} \n" 

			." #sbox-window {background-color:#fff100;padding:5px} \n" 

			." </style> \n");

		

		

		// PARAMS - Get image height and width

		$front_modal_box_width 		= $params->get( 'front_modal_box_width', 680 );

		$front_modal_box_height 	= $params->get( 'front_modal_box_height', 560 );

		$front_popup_window_width 	= $params->get( 'front_popup_window_width', 680 );

		$front_popup_window_height 	= $params->get( 'front_popup_window_height', 560 );

		

		if ($detail_window == 1) {

			$windowWidth	= $front_popup_window_width;

			$windowHeight	= $front_popup_window_height;

		} else {//modal popup window

			$windowWidth	= $front_modal_box_width;

			$windowHeight	= $front_modal_box_height;

		}

		

		$large_map_width 			= (int)$windowWidth - 20;

		$large_map_height 			= (int)$windowHeight - 20;

		

		$google_maps_api_key 		= $params->get( 'google_maps_api_key', '' );

		

		

		// MODEL

		$model		= &$this->getModel();

		$map		= $model->getData();

	

		

		// ASIGN

		$this->assignRef( 'windowwidth' 				, $windowWidth);

		$this->assignRef( 'windowheight' 				, $windowHeight);

		$this->assignRef( 'detailwindowbackgroundcolor' , $detail_window_background_color);

		$this->assignRef( 'boxlargewidth' 				, $front_modal_box_width);

		$this->assignRef( 'boxlargeheight' 				, $front_modal_box_height);

		$this->assignRef( 'map'							, $map );

		$this->assignRef( 'googlemapsapikey'			, $google_maps_api_key );

		$this->assignRef( 'largemapwidth'				, $large_map_width );

		$this->assignRef( 'largemapheight'				, $large_map_height );

		parent::display($tpl);

	}

}

