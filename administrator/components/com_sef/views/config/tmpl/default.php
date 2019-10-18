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
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<table class="adminheading">
		<tr><th>
		
		<?php
		$config =& JFactory::getConfig();
		$sefConfig =& SEFConfig::getConfig();
		$lists = $this->lists;
		$sef_config_file = JPATH_COMPONENT . DS . 'configuration.php';
		echo 'ARTIO JoomSEF ' . JText::_('Configuration').(file_exists($sef_config_file) ? (is_writable($sef_config_file) ? (' <b><font color="green">'.JText::_('Writeable').'</font></b>') : (' <b><font color="red">'.JText::_('Unwriteable').'</font></b>')) : (' <b><font color="red">'.JText::_('Using Default Values').'</font></b>'));
		?>
		
		</th></tr>
		</table>
		<?php if (!$config->getValue('sef')) {
		    echo '<p class="error">' . JText::sprintf(_COM_SEF_DISABLED, '<a href="index.php?option=com_config">', '</a>') . '</p>';
		}
		$x = 0;
	       	?>
	        <script language="Javascript">
	        function submitbutton(pressbutton) {
	            if (pressbutton == 'save') {
	                var purge = confirm("<?php echo JText::_(_COM_SEF_PURGE_URLS) . '\n\n' . JText::_(_COM_SEF_PURGE_URLS_NOTE); ?>");
	                if (purge) document.getElementById("purge").value = "1";
	            }

	            <?php
	            jimport( 'joomla.html.editor' );
	            $editor =& JFactory::getEditor();
	            echo $editor->save( 'introtext' );
	            ?>
	            submitform(pressbutton);
	        }
			</script>
		<div class="col width-50">
		  <fieldset class="adminform">
		      <legend><?php echo JText::_('Basic Configuration'); ?></legend>
		      <table class="adminform">
    	        <?php //Dit zit hier niet goed; ?>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td width="200"><?php echo JText::_('Enabled');?>?</td>
    	            <td><?php echo $lists['enabled'];?></td>
    	            <td width="5"><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_ENABLED),JText::_('Enabled'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Disable creation of new SEF URLs?');?></td>
    	            <td><?php echo $lists['disableNewSEF']; ?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_DISABLENEWSEF),JText::_('Disable creation of new SEF URLs?'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Replacement character');?></td>
    	            <td><input type="text" name="replacement" value="<?php echo $sefConfig->replacement;?>" size="1" maxlength="1"></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_REPLACE_CHAR),JText::_('Replacement character'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Page spacer character');?></td>
    	            <td><input type="text" name="pagerep" value="<?php echo $sefConfig->pagerep;?>" size="1" maxlength="1"></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_PAGEREP_CHAR),JText::_('Page spacer character'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Strip characters');?></td>
    	            <td><input type="text" name="stripthese" value="<?php echo $sefConfig->stripthese;?>" size="60" maxlength="255"></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_STRIP_CHAR).' |',JText::_('Strip characters'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Trim friendly characters');?></td>
    	            <td><input type="text" name="friendlytrim" value="<?php echo $sefConfig->friendlytrim;?>" size="60" maxlength="255"></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_FRIENDTRIM_CHAR),JText::_('Trim friendly characters'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Use Title Alias');?>?</td>
    	            <td><?php echo $lists['useAlias'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_USE_ALIAS),JText::_('Use Title Alias'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('File suffix');?></td>
    	            <td><input type="text" name="suffix" value="<?php echo $sefConfig->suffix; ?>" size="6" maxlength="6"></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_SUFFIX),JText::_('File suffix'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Default index file');?></td>
    	            <td><input type="text" name="addFile" value="<?php echo $sefConfig->addFile; ?>" size="60" maxlength="60"></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_ADDFILE),JText::_('Default index file'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Page text');?></td>
    	            <td><input type="text" name="pagetext" value="<?php echo $sefConfig->pagetext; ?>" size="10" maxlength="20"></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_PAGETEXT),JText::_('Page text'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('All lowercase');?>?</td>
    	            <td><?php echo $lists['lowerCase'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_LOWER),JText::_('All lowercase'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Show Section');?>?</td>
    	            <td><?php echo $lists['showSection'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_SHOW_SECT), JText::_('Show Section'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Show Category');?>?</td>
    	            <td><?php echo $lists['showCat'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_HIDE_CAT), JText::_('Show Category'));?></td>
    	        </tr>
		      </table>
		  </fieldset>
		  
		  <fieldset class="adminform">
		      <legend><?php echo JText::_('Advanced Configuration');?></legend>
		      <table class="adminform">
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td width="200" valign="top"><?php echo JText::_('Non-ascii char replacements');?></td>
    	            <td><textarea name="replacements" cols="40" rows="5"><?php echo $sefConfig->replacements;?></textarea></td>
    	            <td width="5" valign="top"><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_REPLACEMENTS), JText::_('Non-ascii char replacements'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Exclude source info (Itemid)');?></td>
    	            <td><?php echo $lists['excludeSource'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_EXCLUDE_SOURCE), JText::_('Exclude source info (Itemid)'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Reappend source (Itemid)');?></td>
    	            <td><?php echo $lists['reappendSource'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_REAPPEND_SOURCE), JText::_('Reappend source (Itemid)'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Ignore multiple sources (Itemids)');?></td>
    	            <td><?php echo $lists['ignoreSource'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_IGNORE_SOURCE), JText::_('Ignore multiple sources (Itemids)'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Append non-SEF variables to URL');?></td>
    	            <td><?php echo $lists['appendNonSef'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_APPEND_NONSEF), JText::_('Append non-SEF variables to URL'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Be tolerant to trailing slash?');?></td>
    	            <td><?php echo $lists['transitSlash'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_TRANSIT_SLASH), JText::_('Be tolerant to trailing slash?'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Redirect nonSEF URLs to SEF?');?></td>
    	            <td><?php echo $lists['nonSefRedirect'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_NONSEFREDIRECT), JText::_('Redirect nonSEF URLs to SEF?'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Use Moved Permanently redirection table?');?></td>
    	            <td><?php echo $lists['useMoved'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_USEMOVED), JText::_('Use Moved Permanently redirection table?'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Ask before saving URL to Moved Permanently table?');?></td>
    	            <td><?php echo $lists['useMovedAsk'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_USEMOVEDASK), JText::_('Ask before saving URL to Moved Permanently table?'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Do not remove SID from SEF URL?');?></td>
    	            <td><?php echo $lists['dontRemoveSid'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_DONTREMOVESID), JText::_('Do not remove SID from SEF URL?'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Set server QUERY_STRING?');?></td>
    	            <td><?php echo $lists['setQueryString'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_SETQUERYSTRING), JText::_('Set server QUERY_STRING?'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Parse Joomla SEO links?');?></td>
    	            <td><?php echo $lists['parseJoomlaSEO'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_PARSEJOOMLASEO), JText::_('Parse Joomla SEO links?'));?></td>
    	        </tr>
		      </table>
		  </fieldset>
		  
		  <fieldset class="adminform">
		      <legend><?php echo JText::_('Cache Configuration');?></legend>
		      <table class="adminform">
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td width="200"><?php echo JText::_('Use cache?');?></td>
    	            <td><?php echo $lists['useCache'];?></td>
    	            <td width="5"><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_USE_CACHE), JText::_('Use cache?'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Maximum cache size');?>:</td>
    	            <td><?php echo $lists['cacheSize'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_('How many URLs can be saved in cache.'), JText::_('Maximum cache size'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Minimum cache hits count');?>:</td>
    	            <td><?php echo $lists['cacheMinHits'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_('How many hits must URL have to be saved in cache.'), JText::_('Minimum cache hits count'));?></td>
    	        </tr>
		      </table>
		  </fieldset>

          <?php if (is_dir(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_joomfish')) { ?>
		  <fieldset class="adminform">
		      <legend><?php echo JText::_('JoomFish Related Configuration');?></legend>
		      <table class="adminform">
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td width="200"><?php echo JText::_('Language integration');?></td>
    	            <td><?php echo $lists['langPlacement'];?></td>
    	            <td width="5"><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_JF_LANG_PLACEMENT), JText::_('Language integration'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Always use language?');?></td>
    	            <td><?php echo $lists['alwaysUseLang'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_JF_ALWAYS_USE_LANG), JText::_('Always use language?'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Translate URLs?');?></td>
    	            <td><?php echo $lists['translateNames'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_JF_TRANSLATE), JText::_('Translate URLs?'));?></td>
    	        </tr>
    	        <?php
    	        if( isset($lists['langDomains']) ) {
    	            ?>
            	    <tr<?php $x++; echo (($x %2) ? '':' class="row1"' ); ?>>
            	        <td valign="top"><?php echo JText::_('Domain configuration'); ?></td>
            	        <td><?php echo $lists['langDomains']; ?></td>
            	        <td valign="top"><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_JF_DOMAIN), JText::_('Domain configuration'));?></td>
            	    </tr>
            	    <?php
    	        }
    	        ?>
		      </table>
		  </fieldset>
          <?php } ?>

		  <fieldset class="adminform">
		      <legend><?php echo JText::_('Component Configuration');?></legend>
                <table class="adminform" width="100%">
           	        <tr>
           	            <th width="200"></th>
           	            <th><?php echo JText::_('Handling').JHTML::_('tooltip', JText::_(_COM_SEF_TT_ADV),JText::_('Handling')); ?></th>
           	            <th><?php echo JText::_('Custom title').JHTML::_('tooltip', JText::_(_COM_SEF_TT_ADV_TITLE),JText::_('Custom title')); ?></th>
           	            <th width="5" align="left"><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_DONTSHOWTITLE), JText::_('Do not show menu title in URL')); ?></th>
           	        </tr>
                	<?php
                	foreach($lists['adv_config'] as $key => $option) {
                	    $x++;
                	    echo '<tr'.(($x % 2) ? '':' class="row1"' ).">\n";
                	    echo '<td>'.$key."</td>\n";
                	    echo '<td>'.$option."</td>\n";
                	    echo '<td>'.$lists['titles'][$key]."</td>\n";
                	    echo '<td>'.$lists['dontshow'][$key]."</td>\n";
                	    echo "</tr>\n";
                	}
                	?>
           	    </table>
		  </fieldset>
        </div>

		<div class="col width-50">
		  <fieldset class="adminform">
		      <legend><?php echo JText::_('404 Page'); ?></legend>
		      <table class="adminform">
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td width="200"><?php echo JText::_('Show page');?></td>
    	            <td><?php echo $lists['page404'];?></td>
    	            <td width="5"><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_404PAGE), JText::_('404 Page'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td width="200"><?php echo JText::_('Show 404 Message');?></td>
    	            <td><?php echo $lists['msg404'];?></td>
    	            <td width="5"><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_404MSG), JText::_('Show 404 Message'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td><?php echo JText::_('Record 404 page hits?');?></td>
    	            <td><?php echo $lists['record404'];?></td>
    	            <td><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_RECORD_404), JText::_('Record 404 page hits?'));?></td>
    	        </tr>
		      </table>
		  </fieldset>
		  
		  <fieldset class="adminform">
		      <legend><?php echo JText::_('Default 404 Page');?></legend>
    		  <?php
    		  // parameters : hidden field, content, width, height, cols, rows
    		  jimport( 'joomla.html.editor' );
    		  $editor =& JFactory::getEditor();
    		  echo $editor->display('introtext', $lists['txt404'], '450', '150', '50', '11');
    		  ?>
		  </fieldset>
		  
		  <fieldset class="adminform">
		      <legend><?php echo JText::_('Default 404 Page').' '.JText::_('ItemID');?></legend>
		      <table class="adminform">
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td width="200" valign="top"><?php echo JText::_('Use ItemID for Default 404 Page');?></td>
    	            <td><?php echo $lists['use404itemid'];?></td>
    	            <td width="5" valign="top"><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_USE404ITEMID), JText::_('Use ItemID for Default 404 Page'));?></td>
    	        </tr>
    	        <tr<?php $x++; echo (($x % 2) ? '':' class="row1"' );?>>
    	            <td width="200" valign="top"><?php echo JText::_('Select ItemID');?></td>
    	            <td><?php echo $lists['itemid404'];?></td>
    	            <td width="5" valign="top"><?php echo JHTML::_('tooltip', JText::_(_COM_SEF_TT_SELECTITEMID), JText::_('Select ItemID'));?></td>
    	        </tr>
		      </table>
		  </fieldset>
		</div>
		
		<div class="clr"></div>
		
<input type="hidden" name="id" value="" />
<input type="hidden" name="section" value="config" />
<input type="hidden" name="purge" id="purge" value="0" />
<input type="hidden" name="option" value="com_sef" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="config" />
</form>
