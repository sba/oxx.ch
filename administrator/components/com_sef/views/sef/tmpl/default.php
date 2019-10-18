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
<table class="adminform">
   <tr>
      <td width="50%" valign="top">
         <table width="100%">
         <tr>
            <td align="center" height="90" width="90">
            <a href="index.php?option=com_sef&controller=config&task=edit" style="text-decoration:none;" title="Configure all ARTIO JoomSEF functionality">
            <img src="components/com_sef/assets/images/config.png" width="48" height="48" align="middle" border="0"/>
            <br />
            ARTIO JoomSEF<br/><?php echo JText::_('Configuration') ?>            </a>
            </td>

            <td align="center" height="90" width="90">
            <a href="index.php?option=com_sef&controller=info&task=help" style="text-decoration:none;" title="Need help with ARTIO JoomSEF?">
            <img src="components/com_sef/assets/images/support.png" width="48" height="48" align="middle" border="0"/>
            <br />
            ARTIO JoomSEF<br/><?php echo JText::_('Support') ?>            </a>
            </td>

            <td align="center" height="90" width="90">
            <a href="index.php?option=com_sef&controller=info&task=doc" style="text-decoration:none;" title="View ARTIO JoomSEF Project Summary and Documentation">
            <img src="components/com_sef/assets/images/docs.png" width="48" height="48" align="middle" border="0"/>
            <br />
            ARTIO JoomSEF<br/><?php echo JText::_('Documentation') ?>            </a>
            </td>
            
            <td align="center" height="90" width="90">
            </td>
            
         </tr>
         <tr>
            <td align="center" height="90" width="90">
            <a href="index.php?option=com_sef&controller=sefurls&viewmode=3" style="text-decoration:none;" title="View/Edit SEF Urls">
            <img src="components/com_sef/assets/images/urls.png" width="48" height="48" align="middle" border="0"/>
            <br />
            <?php echo JText::_('View/Edit').'<br />'.JText::_('SEF Urls') ?>            </a>
            </td>

            <td align="center" height="90" width="90">
            <a href="index.php?option=com_sef&controller=sefurls&viewmode=1" style="text-decoration:none;" title="View/Edit 404 Logs">
            <img src="components/com_sef/assets/images/logs.png" width="48" height="48" align="middle" border="0"/>
            <br />
            <?php echo JText::_('View/Edit').'<br />'.JText::_('404 Logs') ?>            </a>
            </td>

            <td align="center" height="90" width="90">
            <a href="index.php?option=com_sef&controller=sefurls&viewmode=2" style="text-decoration:none;" title="View/Edit Custom Redirects">
            <img src="components/com_sef/assets/images/custom.png" width="48" height="48" align="middle" border="0"/>
            <br />
            <?php echo JText::_('View/Edit').'<br />'.JText::_('Custom Redirects') ?>            </a>
            </td>
            
            <td align="center" height="90" width="90">
            <a href="index.php?option=com_sef&controller=movedurls" style="text-decoration:none;" title="View/Edit Moved Permanently Redirects">
            <img src="components/com_sef/assets/images/url_301.png" width="48" height="48" align="middle" border="0"/>
            <br />
            <?php echo JText::_('View/Edit').' '.JText::_('301 Urls') ?>            </a>
            </td>

         </tr>
         <tr>
            <td align="center" height="90" width="90">
            <a href="index.php?option=com_sef&controller=urls&task=purge&type=0&confirmed=0" style="text-decoration:none;" title="Purge SEF Urls">
            <img src="components/com_sef/assets/images/urls_del.png" width="48" height="48" align="middle" border="0"/>
            <br />
            <?php echo JText::_('Purge').'<br />'.JText::_('SEF Urls') ?>            </a>
            </td>

            <td align="center" height="90" width="90">
            <a href="index.php?option=com_sef&controller=urls&task=purge&type=1&confirmed=0" style="text-decoration:none;" title="Purge 404 Logs">
            <img src="components/com_sef/assets/images/logs_del.png" width="48" height="48" align="middle" border="0"/>
            <br />
            <?php echo JText::_('Purge').'<br />'.JText::_('404 Logs') ?>            </a>
            </td>

            <td align="center" height="90" width="90">
            <a href="index.php?option=com_sef&controller=urls&task=purge&type=2&confirmed=0" style="text-decoration:none;" title="Purge Custom Redirects">
            <img src="components/com_sef/assets/images/custom_del.png" width="48" height="48" align="middle" border="0"/>
            <br />
            <?php echo JText::_('Purge').'<br />'.JText::_('Custom Redirects') ?>            </a>
            </td>
            
            <td align="center" height="90" width="90">
            <a href="index.php?option=com_sef&controller=urls&task=purge&type=3&confirmed=0" style="text-decoration:none;" title="Purge Moved Permanently Redirects">
            <img src="components/com_sef/assets/images/url_301_del.png" width="48" height="48" align="middle" border="0"/>
            <br />
            <?php echo JText::_('Purge').' '.JText::_('301 Urls') ?>            </a>
            </td>

         </tr></table>
      </td>
      <td width="50%" valign="top" align="center">
      <table border="1" width="100%" class="adminform">
         <tr>
            <th class="cpanel" colspan="2">ARTIO JoomSEF</th>
         </tr>
         <tr>
            <td width="120" bgcolor="#F4F4F4"><?php echo JText::_('Installed version') ?>:</td>
            <td bgcolor="#F4F4F4">
                <a href="http://www.artio.net/en/joomla-extensions/artio-joomsef" target="_blank">
                <img src="components/com_sef/assets/images/box.png" align="middle" alt="JoomSEF logo" style="border: none; margin: 8px;" />
                </a><br />
                <a href="http://www.artio.net" target="_blank">ARTIO</a> JoomSEF v<?php echo SEFTools::getSEFVersion(); ?></td>
         </tr>
         <tr>
            <td bgcolor="#EFEFEF"><?php echo JText::_('Copyright') ?></td>
            <td bgcolor="#EFEFEF">&copy; 2006-2008 Artio s.r.o.</td>
         </tr>
         <tr>
            <td bgcolor="#F4F4F4"><?php echo JText::_('License') ?></td>
            <td bgcolor="#F4F4F4"><a href="http://www.artio.net/en/joomsef/artio-joomsef-license-and-pricing" target="_blank"><?php echo JText::_('Combined license') ?></a></td>
         </tr>
         <tr>
             <td bgcolor="#EFEFEF"><?php echo JText::_('Support us') ?></td>
            <td bgcolor="#EFEFEF">
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input name="cmd" type="hidden" value="_s-xclick"></input>
                <input name="submit" type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" title="Support JoomSEF"></input>
                <img src="https://www.paypal.com/en_US/i/scr/pixel.gif" border="0" alt="" width="1" height="1" />
                <input name="encrypted" type="hidden" value="-----BEGIN PKCS7-----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-----END PKCS7-----"></input>
                </form>
            </td>
         </tr>
      </table>
      </td>
   </tr>
</table>

<form action="index.php" method="post" name="adminForm" id="adminForm">

<?php echo $this->loadTemplate('extslist'); ?>

<input type="hidden" name="option" value="com_sef" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>
