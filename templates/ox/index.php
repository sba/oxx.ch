<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >

<!-- ==========================================================================|
|             (c) Copyright 2008 OX.Kultur im Ochsen, Zofingen                 |
|==============================================================================|
|                                                                              |
|            (__)                                                              |
|            (oo)       Layout & Design: Lukas Geissmann                       |
|      /======\/        Programmierung:  Stefan Bauer                          |
|     / |    ||                                                                |
|    *  /\===/\         CMS: Joomla! / Open Source Matters                     |
|       ~~   ~~                                                                |
|                                                                              |
|=========================================================================== -->

<head>


	<jdoc:include type="head" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/ox/css/template.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/ox/css/styles.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/ox/css/print.css" type="text/css" media="print" />
    
	<!--[if lte IE 6]>
		<link href="templates/<?php echo $this->template ?>/css/ie6.css" rel="stylesheet" type="text/css" />
	<![endif]-->

	<script type="text/javascript">
	<!--
	function MM_swapImgRestore() { //v3.0
	  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
	}
	function MM_preloadImages() { //v3.0
	  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
		var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
		if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
	}
	
	function MM_findObj(n, d) { //v4.01
	  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	  if(!x && d.getElementById) x=d.getElementById(n); return x;
	}
	
	function MM_swapImage() { //v3.0
	  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
	   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
	}
	//-->
	</script>
    <script type="text/javascript" src="http://konami-js.googlecode.com/svn/trunk/konami.js"></script>
    <script type="text/javascript">konami = new Konami; konami.code = function(){alert("OX ROCKS")}; konami.load()</script>
	<!-- facebook like-button Open Graph Tags -->
	<meta property="og:title" content="<?=$mainframe->getPageTitle();?>" />
	<meta property="og:type" content="bar" />
	<meta property="og:url" content="<?=JURI::current();?>" />
	<meta property="og:image" content="http://www.oxx.ch/images/ox.jpg" />
	<meta property="og:site_name" content="OX. Kultur im Ochsen" />
	<meta property="fb:admins" content="1427973006" />
</head>

<body onload="MM_preloadImages('templates/ox/images/programm.png','templates/ox/images/verein.png','templates/ox/images/galerie.png','templates/ox/images/kontakt.png','templates/ox/images/verein_pr.png','templates/ox/images/galerie_pr.png','templates/ox/images/kontakt_pr.png','templates/ox/images/programm_ver.png','templates/ox/images/galerie_ver.png','templates/ox/images/kontakt_ver.png','templates/ox/images/verein_gal.png','templates/ox/images/programm_gal.png','templates/ox/images/kontakt_gal.png','templates/ox/images/programm_kon.png','templates/ox/images/verein_kon.png','templates/ox/images/galerie_kon.png')">

<div id="background_top">  
   <div id="border">
	 <div id="header">			  
		<div id="logo"><img src="/templates/ox/images/logo_02.png" id="img_logo" width="175" height="152" border="0" alt="logo" usemap="#Map4" />
			<map name="Map4"><area shape="rect" coords="22,25,154,120" href="index.php" title="Zur Startseite" alt="Logo" /></map>
	   </div>
	   
	   <hr class="invisible" />
		 <h1 class="invisible">Navigation:</h1>  
		 <div id="navigation">

<?php if($this->countModules('subnav_programm')) : ?><img src="templates/ox/images/programm.png" width="434" height="152" border="0" usemap="#Map" name="navigation" />

			<map name="Map" id="Map">
				<area shape="poly" coords="137,1,179,-1,58,152,31,134" href="index.php?option=com_content&id=55&layout=eventsblog&view=category&Itemid=58" alt="programm" onMouseOver="document.navigation.src='/templates/ox/images/programm.png'" onMouseOut="document.navigation.src='/templates/ox/images/programm.png'" />
				<area shape="poly" coords="95,132,119,153,241,1,198,1" href="index.php?option=com_content&id=34&layout=blog&view=category&Itemid=78" alt="verein"  onMouseOver="document.navigation.src='/templates/ox/images/verein_pr.png'" onMouseOut="document.navigation.src='/templates/ox/images/programm.png'" />
				<area shape="poly" coords="262,0,303,1,180,152,155,133" href="index.php?option=com_content&view=article&id=64&Itemid=53" alt="galerie" onMouseOver="document.navigation.src='/templates/ox/images/galerie_pr.png'" onMouseOut="document.navigation.src='/templates/ox/images/programm.png'" />
				<area shape="poly" coords="320,1,361,1,244,152,217,133" href="index.php?option=com_content&view=article&id=48&Itemid=62" alt="kontakt"  onMouseOver="document.navigation.src='/templates/ox/images/kontakt_pr.png'" onMouseOut="document.navigation.src='/templates/ox/images/programm.png'" />
			</map>	


<?php elseif($this->countModules('subnav_verein')) : ?><img src="templates/ox/images/verein.png" width="434" height="152" border="0" usemap="#Map" name="navigation" />

			<map name="Map" id="Map">
			   <area shape="poly" coords="137,1,179,-1,58,152,31,134" href="index.php?option=com_content&id=55&layout=eventsblog&view=category&Itemid=58" alt="programm" onMouseOver="document.navigation.src='/templates/ox/images/programm_ver.png'" onMouseOut="document.navigation.src='/templates/ox/images/verein.png'" />
			   <area shape="poly" coords="95,132,119,153,241,1,198,1" href="index.php?option=com_content&id=34&layout=blog&view=category&Itemid=78" alt="verein"  onMouseOver="document.navigation.src='/templates/ox/images/verein.png'" onMouseOut="document.navigation.src='/templates/ox/images/verein.png'" />
			   <area shape="poly" coords="262,0,303,1,180,152,155,133" href="index.php?option=com_content&view=article&id=64&Itemid=53" alt="galerie" onMouseOver="document.navigation.src='/templates/ox/images/galerie_ver.png'" onMouseOut="document.navigation.src='/templates/ox/images/verein.png'" />
			   <area shape="poly" coords="320,1,361,1,244,152,217,133" href="index.php?option=com_content&view=article&id=48&Itemid=62" alt="kontakt"  onMouseOver="document.navigation.src='/templates/ox/images/kontakt_ver.png'" onMouseOut="document.navigation.src='/templates/ox/images/verein.png'" />
			</map>	


<?php elseif($this->countModules('subnav_galerie')) : ?><img src="templates/ox/images/galerie.png" width="434" height="152" border="0" usemap="#Map" name="navigation" />

			<map name="Map" id="Map">
			   <area shape="poly" coords="137,1,179,-1,58,152,31,134" href="index.php?option=com_content&id=55&layout=eventsblog&view=category&Itemid=58" alt="programm" onMouseOver="document.navigation.src='/templates/ox/images/programm_gal.png'" onMouseOut="document.navigation.src='/templates/ox/images/galerie.png'" />
			   <area shape="poly" coords="95,132,119,153,241,1,198,1" href="index.php?option=com_content&id=34&layout=blog&view=category&Itemid=78" alt="verein"  onMouseOver="document.navigation.src='/templates/ox/images/verein_gal.png'" onMouseOut="document.navigation.src='/templates/ox/images/galerie.png'" />
			   <area shape="poly" coords="262,0,303,1,180,152,155,133" href="index.php?option=com_content&view=article&id=64&Itemid=53" alt="galerie" onMouseOver="document.navigation.src='/templates/ox/images/galerie.png'" onMouseOut="document.navigation.src='/templates/ox/images/galerie.png'" />
			   <area shape="poly" coords="320,1,361,1,244,152,217,133" href="index.php?option=com_content&view=article&id=48&Itemid=62" alt="kontakt"  onMouseOver="document.navigation.src='/templates/ox/images/kontakt_gal.png'" onMouseOut="document.navigation.src='/templates/ox/images/galerie.png'" />
			</map>	

<?php elseif($this->countModules('subnav_kontakt')) : ?><img src="templates/ox/images/kontakt.png" width="434" height="152" border="0" usemap="#Map" name="navigation" />

			<map name="Map" id="Map">
			   <area shape="poly" coords="137,1,179,-1,58,152,31,134" href="index.php?option=com_content&id=55&layout=eventsblog&view=category&Itemid=58" alt="programm" onMouseOver="document.navigation.src='/templates/ox/images/programm_kon.png'" onMouseOut="document.navigation.src='/templates/ox/images/kontakt.png'" />
			   <area shape="poly" coords="95,132,119,153,241,1,198,1" href="index.php?option=com_content&id=34&layout=blog&view=category&Itemid=78" alt="verein"  onMouseOver="document.navigation.src='/templates/ox/images/verein_kon.png'" onMouseOut="document.navigation.src='/templates/ox/images/kontakt.png'" />
			   <area shape="poly" coords="262,0,303,1,180,152,155,133" href="index.php?option=com_content&view=article&id=64&Itemid=53" alt="galerie" onMouseOver="document.navigation.src='/templates/ox/images/galerie_kon.png'" onMouseOut="document.navigation.src='/templates/ox/images/kontakt.png'" />
			   <area shape="poly" coords="320,1,361,1,244,152,217,133" href="index.php?option=com_content&view=article&id=48&Itemid=62" alt="kontakt"  onMouseOver="document.navigation.src='/templates/ox/images/kontakt.png'" onMouseOut="document.navigation.src='/templates/ox/images/kontakt.png'" />
			</map>	

<?php else: ?>	 

		 <img src="templates/ox/images/navigation.png" width="434" height="152" border="0" usemap="#Map" name="navigation" />
		
			<map name="Map" id="Map">
				<area shape="poly" coords="137,1,179,-1,58,152,31,134" href="index.php?option=com_content&id=55&layout=eventsblog&view=category&Itemid=58" alt="programm" onMouseOver="document.navigation.src='/templates/ox/images/programm.png'" onMouseOut="document.navigation.src='/templates/ox/images/navigation.png'" />
				<area shape="poly" coords="95,132,119,153,241,1,198,1" href="index.php?option=com_content&id=34&layout=blog&view=category&Itemid=78" alt="verein"  onMouseOver="document.navigation.src='/templates/ox/images/verein.png'" onMouseOut="document.navigation.src='/templates/ox/images/navigation.png'" />
				<area shape="poly" coords="262,0,303,1,180,152,155,133" href="index.php?option=com_content&view=article&id=64&Itemid=53" alt="galerie" onMouseOver="document.navigation.src='/templates/ox/images/galerie.png'" onMouseOut="document.navigation.src='/templates/ox/images/navigation.png'" />
				<area shape="poly" coords="320,1,361,1,244,152,217,133" href="index.php?option=com_content&view=article&id=48&Itemid=62" alt="kontakt"  onMouseOver="document.navigation.src='/templates/ox/images/kontakt.png'" onMouseOut="document.navigation.src='/templates/ox/images/navigation.png'" />
			</map>	
 <?php endif; ?>	
		 </div>
	 </div>

	  <hr class="invisible" />
	  <div id="content_container">
		 <div id="content_left">
		   <h1 class="invisible">Content left:</h1>
			<br />
			<?php if($this->countModules('subnav_programm')) : ?>
			   <div class="box_container subnav">
					 <div class="box_top"></div>
					 <div class="box_content subnav">
					   <jdoc:include type="modules" name="subnav_programm" />
					 </div>
					 <div class="box_bottom"></div>				   
			   </div>
			   <br />
			<?php endif; ?>	 
			<?php if($this->countModules('subnav_verein')) : ?>
			   <div class="box_container subnav">
					 <div class="box_top"></div>
					 <div class="box_content subnav">
					   <jdoc:include type="modules" name="subnav_verein" />
					 </div>
					 <div class="box_bottom"></div>				   
			   </div><br />
			<?php endif; ?>	 
			<?php if($this->countModules('subnav_galerie')) : ?>
			   <div class="box_container subnav">
					 <div class="box_top"></div>
					 <div class="box_content subnav">
					   <jdoc:include type="modules" name="subnav_galerie" />
					 </div>
					 <div class="box_bottom"></div>				   
			   </div><br />
			<?php endif; ?>	 
			<?php if($this->countModules('subnav_kontakt')) : ?>
			   <div class="box_container subnav">
					 <div class="box_top"></div>
					 <div class="box_content subnav">
					   <jdoc:include type="modules" name="subnav_kontakt" />
					 </div>
					 <div class="box_bottom"></div>				   
			   </div>
		   <br />  
			<?php endif; ?>	  
			<?php if($this->countModules('subnav_user')) : ?>		 
			   <div class="box_container subnav">			  
					 <div class="box_top"></div>
					 <div class="box_content subnav">									   
					   <jdoc:include type="modules" name="subnav_user" />				  
					 </div>
					 <div class="box_bottom"></div>		   
			   </div>
		   <br />
			  <?php endif; ?>
			  <?php if($this->countModules('left')) : ?>				
					   <jdoc:include type="modules" name="left" />
			  <?php endif; ?>

	 </div>
	  
	 <hr class="invisible" />
		 
	<div id="maincontent">	
	  <h1 class="invisible">Maincontent:</h1>
 
			   <?php if($this->countModules('content_top')) : ?>
							<jdoc:include type="modules" name="content_top" />		 
			   <?php endif; ?>					  
			 
			  <jdoc:include type="component" />
	</div>
	 
	<hr class="invisible" />
	 <div id="content_right">

	 <h1 class="invisible">Content right:</h1> 
	 <br />
		 <?php if($this->countModules('right')) : ?>		   
				<jdoc:include type="modules" name="right" />					
		  <?php endif; ?>

	 </div>   
  <br clear="all" /> 
  </div>
  <hr class="invisible" />	
	 
	  <?php 
	  
	  $bilder=array("illustration1.png","illustration2.png","illustration3.png","illustration4.png","illustration5.png" );
	  mt_srand ((double)microtime()*1000000);
	  $zahl = mt_rand(0,(count($bilder) - 1));	?>
		  
	 <div id="footer" style="background:url(/templates/ox/images/<?php echo $bilder[$zahl] ?>)">			  
		 <h1 class="invisible">Footer: <?=$agent?></h1>		 
			<div id="sponsors">
				<?php 
				//block sponsors for ie6-users an below (png-fix does not work)
				$regex = "MSIE [1-6]";
				$agent = $_SERVER['HTTP_USER_AGENT'];
				if(!(eregi( $regex, $agent ) || $agent === "" )) { ?>

				<jdoc:include type="modules" name="footer" />
				<?php }?>
			</div>	
	 </div>

  </div>
</div>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-2260396-13");
pageTracker._trackPageview();
</script>

</body>
</html>
