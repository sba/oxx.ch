<?php
/**
 * SEF component for Joomla! 1.5
 *
 * @author      ARTIO s.r.o.
 * @copyright   ARTIO s.r.o., http://www.artio.cz
 * @package     JoomSEF
 * @version     3.1.0
 */

// Security check to ensure this file is being included by a parent file.
defined('_JEXEC') or die('Restricted access');

define('_COM_SEF_CANT_UPGRADE', 'Cannot upgrade.<br />Either your JoomSEF version is up to date or its upgrade is no longer supported.');
define('_COM_SEF_CLEAN_CACHE_QUESTION', 'Cleaning the cache is recommended every time you change the setting of the cache or you edit any of your custom URLs. Are you sure you want to clean the cache?');
define('_COM_SEF_CONFIRM301', 'Your SEF link has changed. Do you wish to save the old one to Moved Permanently redirection table so it will be still working?');
define('_COM_SEF_DEF_404_MSG', '<h1>404: Not Found</h1><h4>Sorry, but the content you requested could not be found</h4>');
define('_COM_SEF_DELETE_FILTER_QUESTION', 'Are you sure you want to delete all the URLs matching selected filters? (All pages will be deleted.)');
define('_COM_SEF_DISABLED', 'NOTE: SEF support in Joomla is currently disabled. To use SEF, please enable it from the %sGlobal Configuration%s page.');
define('_COM_SEF_EMPTYURL', 'You must provide a URL for the redirection.');
define('_COM_SEF_FATAL_ERROR_HEADERS', 'FATAL ERROR: HEADER ALREADY SENT');
define('_COM_SEF_FILTERNEW_HLP', 'To filter shown URLs by Moved to URL, enter part of it into this field and hit ENTER.');
define('_COM_SEF_FILTEROLD_HLP', 'To filter shown URLs by Moved from URL, enter part of it into this field and hit ENTER.');
define('_COM_SEF_FILTERREAL_HLP', 'To filter shown URLs by original URL, enter part of it into this field and hit ENTER.');
define('_COM_SEF_FILTERSEF_HLP', 'To filter shown URLs by SEF URL, enter part of it into this field and hit ENTER.');
define('_COM_SEF_META_INFO', 'Please note that JoomSEF Mambot must be installed and published for meta tag functionality to be working.<br />You may also wish to deactivate Joomla! standard meta tag generation in your site global configuration.');
define('_COM_SEF_PURGE_URLS', 'Do you wish to purge automatically created URLs?');
define('_COM_SEF_PURGE_URLS_NOTE', 'Note: You may wish to delete existing auto-created URLs if you have reconfigured the way they should look. This will NOT delete any URLs stored as Custom.');
define('_COM_SEF_REINSTALL', 'You have uploaded the package with same version as your current JoomSEF, reinstall instead of upgrade has been initiated.');
define('_COM_SEF_TT_404PAGE', 'Static content page to use for 404 Not Found errors (published state does not matter)');
define('_COM_SEF_TT_404MSG', 'Set to Yes if you want the standard Joomla message to be also shown when 404.');
define('_COM_SEF_TT_USE404ITEMID', 'Whether to set the ItemID variable when Default 404 page is displayed.');
define('_COM_SEF_TT_SELECTITEMID', 'Use this selection list to choose the ItemID used according to a menu item.');
define('_COM_SEF_TT_ADDFILE', 'File name to place after a blank url / when no file exists.  Useful for bots that crawl your site looking for a specific file in that place but returns a 404 because there is none there.');
define('_COM_SEF_TT_ADV', '<b>use default handler</b><br/>process normally, if an SEF Advanced extension is present it will be used instead. <br/><b>nocache</b><br/>do not store in DB and create old style SEF URLs<br/><b>skip</b><br/>do not make SEF urls for this component<br/>');
define('_COM_SEF_TT_ADV_TITLE', 'Overrides the default menu title in URL. Leave blank for default behaviour.');
define('_COM_SEF_TT_APPEND_NONSEF', 'Excludes often changing variables from SEF URL and appends them as non-SEF query.<br />This will decrease database usage and also prevent duplicate URLs in some extensions.');
define('_COM_SEF_TT_DISABLENEWSEF', 'If set to yes, no new URLs will be generated and only those already in database will be used.');
define('_COM_SEF_TT_DONTREMOVESID', 'If set to yes, the sid variable will not be removed from SEF URL. This may help some components to work properly, but also can create duplicates with some others.');
define('_COM_SEF_TT_DONTSHOWTITLE', 'If checked, the menu title will not be present in URL at all (except the direct link to component).');
define('_COM_SEF_TT_ENABLED', 'If set to No the default SEF for Joomla/Mambo will be used');
define('_COM_SEF_TT_EXCLUDE_SOURCE', 'This will exclude information about link source (Itemid) from URL.<br />This may prevent duplicate URLs, but probably will limit your Joomla! functionality.');
define('_COM_SEF_TT_FRIENDTRIM_CHAR', 'Characters to trim from around the URL, separate with');
define('_COM_SEF_TT_HIDE_CAT', 'Set to yes to include the category name in url');
define('_COM_SEF_TT_IGNORE_SOURCE', 'When selected, only one URL will be generated for every page, even when there is more than one Itemid pointing to it.');
define('_COM_SEF_TT_JF_ALWAYS_USE_LANG', 'Always include language code in the generated URL.');
define('_COM_SEF_TT_JF_DOMAIN', 'Define the live site for each language (without the trailing slash).');
define('_COM_SEF_TT_JF_LANG_PLACEMENT', 'Where to add language constant in the generated URLs. Case <b>do not add</b> should be used only when path translation is active.');
define('_COM_SEF_TT_JF_TRANSLATE', 'Use JoomFish to translate SEF URLs titles.');
define('_COM_SEF_TT_LOWER', 'Convert all characters to lowercase characters in the URL');
define('_COM_SEF_TT_NONSEFREDIRECT', 'When someone types nonSEF URL in his browser he will be redirected to its SEF equivalent with Moved Permanently header.');
define('_COM_SEF_TT_PAGEREP_CHAR', 'Character to use to space page numbers away from the rest of the URL');
define('_COM_SEF_TT_PAGETEXT', 'Text to append to url for multiple pages. Use %s to insert page number, by default it will be at end. If a suffix is defined, it will be added to the end of this string.');
define('_COM_SEF_TT_REAPPEND_SOURCE', 'This setting reappends the Itemid to the SEF URL as query parameter.');
define('_COM_SEF_TT_RECORD_404', 'Should we store 404 page hits to DB? Disabling this will descrease the number of SQL queries performed by JoomSEF, however you will not be able to see hits to noexisting pages at your site.');
define('_COM_SEF_TT_REPLACE_CHAR', 'Character to use to replace unknown characters in URL');
define('_COM_SEF_TT_REPLACEMENTS', 'define how should non-ascii characters (or strings) be replaced.<br />Format is srcChar1|rplChar1, srcChar2|rplChar2, ...<br />Note that whitespace characters around &quot;,&quot; and &quot;|&quot; separators will be trimmed.');
define('_COM_SEF_TT_SHOW_SECT', 'Set to yes to include the section name in url');
define('_COM_SEF_TT_STRIP_CHAR', 'Characters to strip from the URL, separate with');
define('_COM_SEF_TT_SUFFIX', 'Extension to use for files. Leave blank to disable. A common entry here is html.');
define('_COM_SEF_TT_TRANSIT_SLASH', 'Do we accept both URLs that do or do not end with trainling slash valid?');
define('_COM_SEF_TT_USE_ALIAS', 'Set to yes to use the title_alias instead of title in the URL');
define('_COM_SEF_TT_USE_CACHE', 'If set to Yes, URLs will be saved to cache so less queries will be made to database.');
define('_COM_SEF_TT_USEMOVED', 'When you change the SEF url, it can be saved to redirection table and will remain working with Moved Permanently header.');
define('_COM_SEF_TT_USEMOVEDASK', 'If set to No, URL will be saved automatically anytime you change it.');
define('_COM_SEF_TT_SETQUERYSTRING', 'If set to yes, the server QUERY_STRING will be set according to parsed variables. May fix some redirection problems with VirtueMart.');
define('_COM_SEF_TT_PARSEJOOMLASEO', 'If set to yes, JoomSEF tries to parse the standard Joomla SEO links, which may display the wrong 404 page in some cases.');
?>
