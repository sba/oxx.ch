<?php


defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class HTML_jcrawler {


	function showForm($option) {
	/* display start page */
	
		$http_host = substr(JURI::base(),0,strrpos(JURI::base(),"administrator")); 
		$sitemap_url = "/sitemap.xml";  
		
		$forbidden_types=array(".jpg",".jpeg",".gif",".png");
		
		$str_forbidden_types = arrToString($forbidden_types);
		$priority = 0.5;
		$str_exclude_names="";
		
		?>
    <script type="text/javascript">
<!--
function MM_showHideLayers() { //v9.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) 
  with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
//-->
    </script>
    
	<form action="index2.php" id="crawler_form" method="post" name="crawler_form">
	<fieldset style="padding: 10px; width:680px; border:2px solid #000099;">
	<legend style="color:#000099;"><b>Adapt this to your site</b></legend>
	<table border="0" cellpadding="5" cellspacing="0" width="670">
	  <tr>
		<td width="250" valign="top"><label for="idocument_root" accesskey="D"><b>Document root</b></label><br />
			<small>path on server</small></td>
		<td width="240">
			<input class="required" type="Text" name="document_root" id="idocument_root" align="LEFT" size="70" value="<?php echo JPATH_SITE ?>"/>
		</td>
	  </tr>	
	  <tr>
		<td width="250" valign="top"><label for="ihttp_host" accesskey="H"><strong>HTTP host (readonly)</strong></label><br />
			<small>the url of your website</small></td>
		<td width="240">
			<input class="required" type="Text" name="http_host" id="ihttp_host" align="LEFT" size="70" readonly="readonly" value="<?php echo $http_host ?>"/>
		</td>
	  </tr>
	  <tr>
		<td width="250" valign="top"><label for="forbidden_types" accesskey="F"><strong>Forbidden file types</strong></label><br />
			<small>files containing this file type will not be added to site index; use line break to separate entries</small></td>
		<td width="240">
			<textarea name="forbidden_types" cols="40" rows="10" id="forbidden_types"><?php echo $str_forbidden_types ?></textarea>
		</td>
	  </tr>
        <tr>
		<td width="250" valign="top"><label for="exclude_names" accesskey="e"><strong>Exclude list</strong></label><br />
			<small>URLs and their urls (recursive) containing this string will not be added to site index; use line break (no html) to separate entries<br /><br />Ensure that no "&ouml;", "&uuml;", "&auml; "&iuml;" etc. are in your url's, else add them to the exclude list</small></td>
		<td width="340">
			<textarea name="exclude_names" cols="60" rows="10" id="exclude_names"><?php echo $str_exclude_names ?></textarea>
		</td>
	  </tr>
	  <tr>
		<td width="250" valign="top"><label for="isitemap_file" accesskey="S"><strong>Sitemap url</strong></label><br />
			<small>Where to store sitemap file - relative to your document root; this must exist, be <font color="red"><strong>writetable</strong></font> and accessible for the google bot!</small></td>
		<td width="240">
			<input type="Text" name="sitemap_url" id="isitemap_url" align="LEFT" size="50" value="<?php echo $sitemap_url ?>"/>
		</td>
	  </tr>	
	  <tr>
		<td width="250" valign="top"><label for="ipriority" accesskey="P"><strong>Priority</strong></label><br />
			<small>from 0.0 to 1.0, e.g. 0.5</small></td>
		<td width="240">
			<input type="Text" name="priority" id="ipriority" align="LEFT" size="10" value="<?php echo $priority ?>"/>
		</td>
	  </tr>	
        <tr>
		<td width="250" valign="top"><label for="ifreq" accesskey="F"><strong>Change frequency</strong></label><br />
			<small>How frequently the page is likely to change</small></td>
		<td width="240">
			<select name="freq">
            	<option value="always">Always</option>
                <option value="hourly">Hourly</option>
                <option value="daily" selected="selected">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
                <option value="never">Never</option>
			</select>
		</td>
	  </tr>
        <tr>
		<td width="250" valign="top"><label for="irobots" accesskey="R"><strong>Modify <a href="<?php echo $http_host ?>robots.txt">robots.txt</a></strong></label><br />
			<small>file in joomla root, which contains the sitemap location. robots.txt must be <font color="red"><strong>writetable</strong></font>  </small></td>
		<td width="240">
			<input type="checkbox" name="robots" id="irobots" align="LEFT" value="1"/>
		</td>
	  </tr>	
	   <tr>
		<td width="250" valign="top"><label for="imethod" accesskey="M"><strong>Method</strong></label><br />
			<small>Select your favorite method to crawl with, if you don't now what it means, leave it on curl</small></td>
		<td width="240"><?php (!checkcurl())?print "<small><font color=\"red\"><strong>Curl is not available</strong></font></small><br />":null ?>
        				<?php (!checkfopen())?print "<small><font color=\"red\"><strong>fopen cannot connect to urls (PHP parameter allow_url_fopen is set to Off)</strong></font></small><br />":null ?>
			<select name="method">
   	      <option value="curl" selected="selected" onclick="MM_showHideLayers('maxconnections','','show')">curl</option>
                <option value="fopen" onclick="MM_showHideLayers('maxconnections','','hide')">fopen</option>
            </select>
		</td>
	  </tr>
      
         <tr>
		<td width="250" valign="top"><label for="ilevel" accesskey="L"><strong>Levels</strong></label><br />
			<small>How many levels the crawler crawls, more than 3 can cause a huge execution time and is not recommended for large sites</small></td>
		<td width="240">
			<select name="levels">
            	<option value="1">1</option>
                <option value="2">2</option>
            	<option value="3" selected="selected">3</option>
                <option value="4">4</option>
            	<option value="5">5</option>
                <option value="6">6</option>
            </select>
		</td>
	  </tr>
      
      <tr id="maxconnections">
		<td width="250" valign="top"><label for="imaxcon" accesskey="M"><strong>Max parallel connections</strong></label><br />
			<small>How many parallel connections are allowed. <strong>More connections need more memory. Only valid with curl</strong></small></td>
		<td width="240">
			<select name="maxcon">
            	<option value="5">5</option>
            	<option value="10">10</option>
                <option value="20">20</option>
           	  <option value="30">30</option>
              <option value="40" selected="selected">40</option>
            	<option value="50">50</option>
                <option value="80">80</option>
                <option value="100">100</option>
            </select>
		</td>
	  </tr>
      <tr><td colspan="2">
    <div class="info_message" id="info_message" style="visibility:hidden; width:40%; position:absolute; top:60%; left:40%; width:20%; height:20%; background-color:#E6E6E6; border:2px solid #e3e3e3; z-index:2;"><h1 style="padding:10px">Crawling ...</h1><br /><img style="padding:10px;" src="<?php echo $http_host ?>administrator/components/com_jcrawler/images/activity.gif" /></div></td></tr>
      <tr>
		<td>&nbsp;</td>
		<td><input name="submit" type="Submit" onclick="MM_showHideLayers('info_message','','show')" value="Start">
		 <small>Please be patient, this can take a while</small></td>
	  </tr>
	</table>
	</fieldset>
    
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="submit" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="client" value="<?php echo $client; ?>" />
		</form>

	<?php 
	HTML_jcrawler::footer($option);
	
			}    

function showNotifyForm($option, $sitemap_url) {  ?>
	        <div style="position:absolute; left:450px; width:220px; float:left; clear:right;">
<fieldset style="padding: 10; width:200px; border-color:#000099; border-width:2px; border-style:solid; ">
            	<legend style="color:#000099;">Options</legend>
        		<ul><li><a href="http://www.validome.org/google/validate?url=<?php echo $sitemap_url ?>&googleTyp=SITEMAP" target="_blank">Vadidate my sitemap</a></li>
					<li><a href="<?php echo $sitemap_url ?>" target="_blank">View my sitemap</a></li>
                    <li><a href="http://en.wikipedia.org/wiki/List_of_HTTP_status_codes" target="_blank">List of HTTP status codes</a></li>
                    <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=patrick%40support%2dmasters%2ech&item_name=Pixelschieber%20%2d%20JCrawler&no_shipping=0&no_note=1&tax=0&currency_code=EUR&lc=CH&bn=PP%2dDonationsBF&charset=UTF%2d8" target="_blank">Donate via PayPal</a></li>
                    <li><a href="http://www.google.com/support/webmasters/bin/topic.py?topic=8467" target="_blank">Official Sitemaps FAQ</a></li>
  </ul>
       	  </fieldset>
        </div>
	<div style="height:200px; width:300px; float:left;">
<form action="index2.php" method="post" name="Jcrawler" enctype="multipart/form-data">
	<fieldset style="padding: 10; width:300px; border-color:#000099; border-width:2px; border-style:solid; ">
	<legend style="color:#000099;"><b>Submit sitemap to</b></legend>
	<ul>
<li><input type="checkbox" name="url[]" checked="checked" value="http://www.google.com/webmasters/sitemaps/ping?sitemap=<?php echo urlencode($sitemap_url) ?>" /> Google</li>
<li><input type="checkbox" name="url[]" checked="checked" value="http://webmaster.live.com/ping.aspx?siteMap=<?php echo urlencode($sitemap_url) ?>" /> MSN</li>
<li><input type="checkbox" name="url[]" checked="checked" value="http://submissions.ask.com/ping?sitemap=<?php echo urlencode($sitemap_url) ?>" /> Ask.com</li>
<li><input type="checkbox" name="url[]" checked="checked" value="http://api.moreover.com/ping?u=<?php echo urlencode($sitemap_url) ?>" /> Moreover</li>
<li><input type="checkbox" name="url[]" checked="checked" value="http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=SitemapWriter&url=<?php echo urlencode($sitemap_url) ?>" /> Yahoo <br /></li>
                                                
         <br /><input type="Submit" value="Submit" name="submit"></ul>	</fieldset>
	
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="notify" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="client" value="<?php echo $client; ?>" />
		</form>
	</div>
    
 <?php   
 	HTML_jcrawler::footer($option);
        
 }

	function footer($option){
		 print "<div align=\"center\" style=\"clear:both;\"><a href=\"index2.php?option=".$option."&task=updatecheck\">Check for update</a><br />Copyright 2008 pixelschieber.ch. 
		<a target=\"_blank\" href=\"http://www.pixelschiber.ch/jcrawler\">pixelschieber.ch</a></div>";
	
	}


} // end class
?>