<?php
// --------------------------------------------------------------------------------
// Letterman Newsletter Component
//
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html
// --------------------------------------------------------------------------------
// $Id: contentRenderer.class.php 361 2008-07-07 11:43:32Z Suami $

// ensure this file is being included by a parent file
defined('_JEXEC') or die('Restricted access');

/* Load some basic necessities */
global $mainframe;

$database =& Jfactory::getDBO();

/* Get the user details */
$user = &JFactory::getUser();

// Load the content plugins
$botgroup = 'content';

/**
 * Here is a list of all content plugins,
 * which will be loaded by Communicator.
 * Add more by comma separating them and
 * enclosed in single quotes.
 */
$loadBots = "'geshi'";

$query = "SELECT folder, element, published, params"
. "\n FROM #__plugins"
. "\n WHERE access <= $user->gid"
. "\n AND folder = '".$botgroup."'"
. "\n AND element IN (".$loadBots.")"
. "\n ORDER BY ordering";
$database->setQuery( $query );
$contentbots = $database->loadObjectList();

/* Now load the plugins */
foreach( $contentbots as $mambot ) {
	cm_loadBot( $mambot->folder, $mambot->element, $mambot->published, $mambot->params );
}

// we need functions from the class HTML_content!
// require_once( JPATH_SITE.'/components/com_content/content.html.php');

/**
 * Loads the bot file
 * @param string The folder (group)
 * @param string The elements (name of file without extension)
 * @param int Published state
 * @param string The params for the bot
 */
function cm_loadBot( $folder, $element, $published, $params='' ) {
	global $mainframe;
	
	$path = JPATH_SITE . '/plugins/' . $folder . '/' . $element . '.php';
	if (file_exists( $path )) {
		JPluginHelper::importPlugin($folder, $element, false);
	}
}

/**
 * This class allows you to render content items 
 * just like Mambo and Joomla do on the frontpage
 * formerly a mambot for YaNC (render_content)
 * @author soeren
 * @author Tim v. Dongen
 * @copyright Soeren, Tim_Online
 * @since Letterman 1.2.1
 */
class cm_contentRenderer {
	/**
	 * This is the main function to render the content from an ID to HTML
	 *
	 * @param unknown_type $nl_content
	 * @return unknown
	 */
	function getContent( $nl_content ) {
		global $cm_params;
		/**
		 * usage: [CONTENT id=""]
		*/
		if( get_magic_quotes_gpc() ) {
			$nl_content = stripslashes( $nl_content );
		}
		$regex = '#\[CONTENT id="(.*?)"\]#s';
		
		$content['html_message'] = preg_replace_callback( $regex, 'cm_replaceContentHtml', nl2br($nl_content) );
		$content['message'] = preg_replace_callback( $regex, 'cm_replaceContentText', $nl_content );
		/**
		 * usage: [ATTACHMENT filename="{the communicator attachment_dir}/path/to/file"]
		*/
		if( !empty( $_POST['nl_attachments'])) {
			foreach( $_POST['nl_attachments'] as $file) {
				$att = '[ATTACHMENT filename="'.$cm_params->get('attachment_dir','/media').'/'.$file.'"]';
				$content['message'] .= $att;
			}
		}
		return $content;

	}
	function retrieveContent( $id ) {
		global $mainframe;
		
		$database =& Jfactory::getDBO();
		
		$query = "SELECT a.*, ROUND(v.rating_sum/v.rating_count) AS rating, v.rating_count, u.name AS author, u.usertype, cc.name AS category, s.name AS section, g.name AS groups, s.published AS sec_pub, cc.published AS cat_pub"
		. "\n FROM #__content AS a"
		. "\n LEFT JOIN #__categories AS cc ON cc.id = a.catid"
		. "\n LEFT JOIN #__sections AS s ON s.id = cc.section AND s.scope = 'content'"
		. "\n LEFT JOIN #__users AS u ON u.id = a.created_by"
		. "\n LEFT JOIN #__content_rating AS v ON a.id = v.content_id"
		. "\n LEFT JOIN #__groups AS g ON a.access = g.id"
		. "\n WHERE a.id = $id";
		$database->setQuery( $query );
		$row = $database->loadObject();
		
		if( $row ) {
			$params = new JParameter( $row->attribs );
			
			$params->def( 'link_titles', 	$mainframe->getCfg( 'link_titles' ) );
			$params->def( 'author', 		!$mainframe->getCfg( 'hideAuthor' ) );
			$params->def( 'createdate', 	!$mainframe->getCfg( 'hideCreateDate' ) );
			$params->def( 'modifydate', 	!$mainframe->getCfg( 'hideModifyDate' ) );
			$params->def( 'print', 			!$mainframe->getCfg( 'hidePrint' ) );
			$params->def( 'pdf', 			!$mainframe->getCfg( 'hidePdf' ) );
			$params->def( 'email', 			!$mainframe->getCfg( 'hideEmail' ) );
			$params->def( 'rating', 		$mainframe->getCfg( 'vote' ) );
			$params->def( 'icons', 			$mainframe->getCfg( 'icons' ) );
			$params->def( 'readmore', 		$mainframe->getCfg( 'readmore' ) );
			$params->def( 'item_title', 	1 );
			
			$params->set( 'intro_only', 	1 );
			$params->set( 'item_navigation', 0 );
			$params->def( 'back_button', 	0 );
			$params->def( 'image', 			1 );
			
			$row->params = $params;
			$row->text = $row->introtext;
		}
		
		return $row;
	}
}

function cm_replaceContentHtml(&$matches){
	global $mainframe, $acl, $_VERSION;

	$id = intval($matches[1]);

	if($id != 0){

		// Editor usertype check
		$access = new stdClass();
		$access->canEdit = $access->canEditOwn = $access->canPublish = 0;
		
		$row = cm_contentRenderer::retrieveContent( $id );
		
		if ( $row ) {
			$params = $row->params;
			$_Itemid = $mainframe->getItemid( $row->id, $typed=1, $link=1, $bs=1, $bc=1, $gbs=1 );
			
			$mainframe->triggerEvent( 'onPrepareContent', array( &$row, &$params, 0 ), true );
			
			$intro_text = $row->text;
			
			if ( intval( $row->created ) != 0 ) {
				$create_date = JHTML::_('date', $row->created );
			}
			$content = '<table class="contentpaneopen'. $params->get( 'pageclass_sfx' ) .'">
			<tr>
			';
			ob_start();
			// displays Item Title
			HTML_content::Title( $row, $params, $access );
			
			$content .= ob_get_contents();
			ob_end_clean();
			$content .= '</tr>
			';
			  // displays Section & Category
			ob_start();
			HTML_content::Section_Category( $row, $params );

			// displays Author Name
			HTML_content::Author( $row, $params );

			// displays Created Date
			HTML_content::CreateDate( $row, $params );

			// displays Urls
			HTML_content::URL( $row, $params );
			$content .= ob_get_contents();
			ob_end_clean();
			
			$content .= '<tr>'
			. '  <td>' .( function_exists('ampReplace') ? ampReplace( $intro_text ) : $intro_text ). '</td>'
			. '</tr>'
			. '<tr>'
			. '		<td align="left" colspan="2">'
			. '		<a href="'. str_replace('administrator/', '', JURI::base()) . 'index.php?option=com_content&amp;task=view&amp;id='.$row->id
			. ($_Itemid ? '&amp;Itemid='.$_Itemid : "") . '" class="readon">'.
			JText::_('READ_MORE')
			. '		</a>'
			. '		</td>'
			. '	</tr>'
			. ' </table>';
			
			return stripslashes($content);
		}
	}
	else {
		return 'error retrieving Content ID: '.$id.'<br/>';
	}
}

function cm_replaceContentText(&$matches){
	global $mainframe;

	$id = intval($matches[1]);
	
	if($id != 0){
		$row = cm_contentRenderer::retrieveContent( $id );
		
		if ( $row ) {
			$params = $row->params;
			
			$_Itemid = $mainframe->getItemid( $row->id, $typed=1, $link=1, $bs=1, $bc=1, $gbs=1 );

			$mainframe->triggerEvent( 'onPrepareContent', array( &$row, &$params, 0 ), true );
			
			$intro_text = $row->text;
			$intro_text = strip_tags( $intro_text );
			
			$intro_text = cm_unHTMLSpecialCharsAll($intro_text);

			if ( intval( $row->created ) != 0 ) {
				$create_date = JHTML::_('date', $row->created );
			}

			$content = "\n" . $row->title
			. "\n\n(" .  JTEXT::_('WRITTEN_BY') . " ".( $row->created_by_alias ? $row->created_by_alias : $row->author )
			. " )\n" . $create_date
			. "\n\n" . $intro_text
			. "\n\n". JTEXT::_('READ_MORE')
			. ": \n". str_replace('administrator/', '', JURI::base()) . 'index.php?option=com_content&task=view&id='.$row->id
			. ($_Itemid ? '&Itemid='.$_Itemid : "") . "\n";
			
			return stripslashes($content);
		}
	}
}

function cm_unHTMLSpecialCharsAll($text) {
	//	return str_replace(array("&amp;","&quot;","&#039;","&lt;","&gt;","&nbsp;"), array("&","\"","'","<",">"," "), $text);
	return cm_deHTMLEntities($text);
}

/**
 * convert html special entities to literal characters
 */
function cm_deHTMLEntities($text) {
	$search = array(
	"'&(quot|#34);'i",
	"'&(amp|#38);'i",
	"'&(lt|#60);'i",
	"'&(gt|#62);'i",
	"'&(nbsp|#160);'i",   "'&(iexcl|#161);'i",  "'&(cent|#162);'i",   "'&(pound|#163);'i",  "'&(curren|#164);'i",
	"'&(yen|#165);'i",    "'&(brvbar|#166);'i", "'&(sect|#167);'i",   "'&(uml|#168);'i",    "'&(copy|#169);'i",
	"'&(ordf|#170);'i",   "'&(laquo|#171);'i",  "'&(not|#172);'i",    "'&(shy|#173);'i",    "'&(reg|#174);'i",
	"'&(macr|#175);'i",   "'&(neg|#176);'i",    "'&(plusmn|#177);'i", "'&(sup2|#178);'i",   "'&(sup3|#179);'i",
	"'&(acute|#180);'i",  "'&(micro|#181);'i",  "'&(para|#182);'i",   "'&(middot|#183);'i", "'&(cedil|#184);'i",
	"'&(supl|#185);'i",   "'&(ordm|#186);'i",   "'&(raquo|#187);'i",  "'&(frac14|#188);'i", "'&(frac12|#189);'i",
	"'&(frac34|#190);'i", "'&(iquest|#191);'i", "'&(Agrave|#192);'",  "'&(Aacute|#193);'",  "'&(Acirc|#194);'",
	"'&(Atilde|#195);'",  "'&(Auml|#196);'",    "'&(Aring|#197);'",   "'&(AElig|#198);'",   "'&(Ccedil|#199);'",
	"'&(Egrave|#200);'",  "'&(Eacute|#201);'",  "'&(Ecirc|#202);'",   "'&(Euml|#203);'",    "'&(Igrave|#204);'",
	"'&(Iacute|#205);'",  "'&(Icirc|#206);'",   "'&(Iuml|#207);'",    "'&(ETH|#208);'",     "'&(Ntilde|#209);'",
	"'&(Ograve|#210);'",  "'&(Oacute|#211);'",  "'&(Ocirc|#212);'",   "'&(Otilde|#213);'",  "'&(Ouml|#214);'",
	"'&(times|#215);'i",  "'&(Oslash|#216);'",  "'&(Ugrave|#217);'",  "'&(Uacute|#218);'",  "'&(Ucirc|#219);'",
	"'&(Uuml|#220);'",    "'&(Yacute|#221);'",  "'&(THORN|#222);'",   "'&(szlig|#223);'",   "'&(agrave|#224);'",
	"'&(aacute|#225);'",  "'&(acirc|#226);'",   "'&(atilde|#227);'",  "'&(auml|#228);'",    "'&(aring|#229);'",
	"'&(aelig|#230);'",   "'&(ccedil|#231);'",  "'&(egrave|#232);'",  "'&(eacute|#233);'",  "'&(ecirc|#234);'",
	"'&(euml|#235);'",    "'&(igrave|#236);'",  "'&(iacute|#237);'",  "'&(icirc|#238);'",   "'&(iuml|#239);'",
	"'&(eth|#240);'",     "'&(ntilde|#241);'",  "'&(ograve|#242);'",  "'&(oacute|#243);'",  "'&(ocirc|#244);'",
	"'&(otilde|#245);'",  "'&(ouml|#246);'",    "'&(divide|#247);'i", "'&(oslash|#248);'",  "'&(ugrave|#249);'",
	"'&(uacute|#250);'",  "'&(ucirc|#251);'",   "'&(uuml|#252);'",    "'&(yacute|#253);'",  "'&(thorn|#254);'",
	"'&(yuml|#255);'");
	$replace = array(
	"\"",
	"&",
	"<",
	">",
	" ",      chr(161), chr(162), chr(163), chr(164), chr(165), chr(166), chr(167), chr(168), chr(169),
	chr(170), chr(171), chr(172), chr(173), chr(174), chr(175), chr(176), chr(177), chr(178), chr(179),
	chr(180), chr(181), chr(182), chr(183), chr(184), chr(185), chr(186), chr(187), chr(188), chr(189),
	chr(190), chr(191), chr(192), chr(193), chr(194), chr(195), chr(196), chr(197), chr(198), chr(199),
	chr(200), chr(201), chr(202), chr(203), chr(204), chr(205), chr(206), chr(207), chr(208), chr(209),
	chr(210), chr(211), chr(212), chr(213), chr(214), chr(215), chr(216), chr(217), chr(218), chr(219),
	chr(220), chr(221), chr(222), chr(223), chr(224), chr(225), chr(226), chr(227), chr(228), chr(229),
	chr(230), chr(231), chr(232), chr(233), chr(234), chr(235), chr(236), chr(237), chr(238), chr(239),
	chr(240), chr(241), chr(242), chr(243), chr(244), chr(245), chr(246), chr(247), chr(248), chr(249),
	chr(250), chr(251), chr(252), chr(253), chr(254), chr(255));
	return $text = preg_replace($search, $replace, $text);
}

/**
* Utility class for writing the HTML for content
* @package Joomla
* @subpackage Content
*/
class HTML_content {
   /**
   * Writes Title
   */
   function Title( &$row, &$params, &$access ) {
      if ( $params->get( 'item_title' ) ) {
         if ( $params->get( 'link_titles' ) && $row->link_on != '' ) {
            ?>
            <td class="contentheading<?php echo $params->get( 'pageclass_sfx' ); ?>" width="100%">
               <a href="<?php echo $row->link_on;?>" class="contentpagetitle<?php echo $params->get( 'pageclass_sfx' ); ?>">
                  <?php echo $row->title;?></a>
            </td>
            <?php
         } else {
            ?>
            <td class="contentheading<?php echo $params->get( 'pageclass_sfx' ); ?>" width="100%">
               <?php echo $row->title;?>
            </td>
            <?php
         }
      }
   }

   /**
   * Writes Container for Section & Category
   */
   function Section_Category( &$row, &$params ) {
      if ( $params->get( 'section' ) || $params->get( 'category' ) ) {
         ?>
         <tr>
            <td>
         <?php
      }

      // displays Section Name
      HTML_content::Section( $row, $params );

      // displays Section Name
      HTML_content::Category( $row, $params );

      if ( $params->get( 'section' ) || $params->get( 'category' ) ) {
         ?>
            </td>
         </tr>
      <?php
      }
   }

   /**
   * Writes Section
   */
   function Section( &$row, &$params ) {
      if ( $params->get( 'section' ) ) {
            ?>
            <span>
               <?php
               echo $row->section;
               // writes dash between section & Category Name when both are active
               if ( $params->get( 'category' ) ) {
                  echo ' - ';
               }
               ?>
            </span>
         <?php
      }
   }

   /**
   * Writes Category
   */
   function Category( &$row, &$params ) {
      if ( $params->get( 'category' ) ) {
         ?>
         <span>
            <?php
            echo $row->category;
            ?>
         </span>
         <?php
      }
   }

   /**
   * Writes Author name
   */
   function Author( &$row, &$params ) {
      if ( ( $params->get( 'author' ) ) && ( $row->author != '' ) ) {
         ?>
         <tr>
            <td width="70%" align="left" valign="top" colspan="2">
               <span class="small">
			   	<?php echo JTEXT::_('WRITTEN_BY') . ' '.( $row->created_by_alias ? $row->created_by_alias : $row->author ); ?>
               </span>
               &nbsp;&nbsp;
            </td>
         </tr>
         <?php
      }
   }


   /**
   * Writes Create Date
   */
   function CreateDate( &$row, &$params ) {
      $create_date = null;

      if ( intval( $row->created ) != 0 ) {
         $create_date = JHTML::_('date', $row->created );
      }

      if ( $params->get( 'createdate' ) ) {
         ?>
         <tr>
            <td valign="top" colspan="2" class="createdate">
               <?php echo $create_date; ?>
            </td>
         </tr>
         <?php
      }
   }

   /**
   * Writes URL's
   */
   function URL( &$row, &$params ) {
      if ( $params->get( 'url' ) && $row->urls ) {
         ?>
         <tr>
            <td valign="top" colspan="2">
               <a href="http://<?php echo $row->urls ; ?>" target="_blank">
                  <?php echo $row->urls; ?></a>
            </td>
         </tr>
         <?php
      }
   }

   /**
   * Writes Modified Date
   */
   function ModifiedDate( &$row, &$params ) {
      $mod_date = null;

      if ( intval( $row->modified ) != 0) {
         $mod_date = JHTML::_('date', $row->modified );
      }

      if ( ( $mod_date != '' ) && $params->get( 'modifydate' ) ) {
         ?>
         <tr>
            <td colspan="2" align="left" class="modifydate">
				<?php echo JTEXT::_('LAST_UPDATED'); ?> ( <?php echo $mod_date; ?> )
            </td>
         </tr>
         <?php
      }
   }

   /**
   * Writes Readmore Button
   */
   function ReadMore ( &$row, &$params ) {
      if ( $params->get( 'readmore' ) ) {
         if ( $params->get( 'intro_only' ) && $row->link_text ) {
            ?>
            <tr>
               <td align="left" colspan="2">
                  <a href="<?php echo $row->link_on;?>" class="readon<?php echo $params->get( 'pageclass_sfx' ); ?>">
                     <?php echo $row->link_text;?></a>
               </td>
            </tr>
            <?php
         }
      }
   }
}
?>