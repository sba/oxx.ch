<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?=parse('%APP_NAME%')?></title>

		<link rel="stylesheet" type="text/css" href="layout/css.css">

		<script type="text/javascript" src="lib/inc.scripts.js"></script>

		<!-- jQuery -->
		<script type="text/javascript" src="lib/jQuery/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="lib/jQuery/jquery.scrollTo-min.js"></script>
		<script type="text/javascript" src="lib/jQuery/beautytip/jquery.bt.min.js"></script>
		<script type="text/javascript" src="lib/jQuery/excanvas.js"></script>
		<link rel="stylesheet" type="text/css" href="lib/jQuery/beautytip/jquery.bt.css">

		<script type="text/javascript">
			var prel = new Image();
			prel.src = 'layout/img/preloader.gif';

			$(document).ready(function(){
				preloader();

				<?php
				if(isset($reload_tips) && $reload_tips===true){
					?>
					setTimeout('reload_tips()', 1000);
					<?php
				}
				?>
			});
		</script>

		<?php
		if($cfg_google_analytics['on']){
			?>
			<!-- GOOGLE ANALYTICS -->
			<script type="text/javascript">

				  var _gaq = _gaq || [];
				  _gaq.push(['_setAccount', '<?=$cfg_google_analytics['id']?>']);
				  _gaq.push(['_trackPageview']);

				  (function() {
				    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				  })();
			</script>
			<?php
		}
		?>
	</head>
	<body id="body_main" class="bodyPreload">
		<div id="div_ladebalken" class="ladebalken"><?=parse($lg_preloader)?></div>
		<div id="div_inhalt" style="display: none;">
			<a name="top"></a>

			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td height="30" class="navigation">
						<table height="30" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<?php
								if($glb_db=='ok'){
									$class = ($glb_kategorie=='start' || $glb_kategorie=='reg') ? 'buttonHover' : 'button';
									$str = ($glb_eingeloggt) ? $lg_uebersicht : $lg_login.' / '.$lg_registrieren;
									?>
									<td id="nav_start" class="<?=$class?>" onclick="getLink('index.php');" onmouseover="navigation('nav_start', '<?=$class?>');" onmouseout="navigation('nav_start', '<?=$class?>');">
										<a href="index.php"><?=parse($str)?></a>
									</td>
									<?php
									if($glb_eingeloggt && (!$cfg_einsatz['on'] || $_SESSION[$cfg_session]['login']['benutzer_einsatz']>=$cfg_einsatz['betrag'])){
										$class = ($glb_kategorie=='tip') ? 'buttonHover' : 'button';
										?>
										<td id="nav_tip" class="<?=$class?>" onclick="getLink('tip.php');" onmouseover="navigation('nav_tip', '<?=$class?>');" onmouseout="navigation('nav_tip', '<?=$class?>');">
											<a href="tip.php"><?=parse($lg_tips_abgeben)?></a>
										</td>
										<?php
									}
									$class = ($glb_kategorie=='spielplan') ? 'buttonHover' : 'button';
									?>
									<td id="nav_spielplan" class="<?=$class?>" onclick="getLink('spielplan.php');" onmouseover="navigation('nav_spielplan', '<?=$class?>');" onmouseout="navigation('nav_spielplan', '<?=$class?>');">
										<a href="spielplan.php"><?=parse($lg_spielplan)?></a>
									</td>
									<?php
									$class = ($glb_kategorie=='gruppe') ? 'buttonHover' : 'button';
									?>
									<td id="nav_gruppe" class="<?=$class?>" onclick="getLink('gruppe.php');" onmouseover="navigation('nav_gruppe', '<?=$class?>');" onmouseout="navigation('nav_gruppe', '<?=$class?>');">
										<a href="gruppe.php"><?=parse($lg_gruppen)?></a>
									</td>
									<?php
									$class = ($glb_kategorie=='tor') ? 'buttonHover' : 'button';
									?>
									<td id="nav_tor" class="<?=$class?>" onclick="getLink('tor.php');" onmouseover="navigation('nav_tor', '<?=$class?>');" onmouseout="navigation('nav_tor', '<?=$class?>');">
										<a href="tor.php"><?=parse($lg_torschuetzen)?></a>
									</td>
									<?php
									$class = ($glb_kategorie=='rangliste') ? 'buttonHover' : 'button';
									?>
									<td id="nav_rangliste" class="<?=$class?>" onclick="getLink('rangliste.php');" onmouseover="navigation('nav_rangliste', '<?=$class?>');" onmouseout="navigation('nav_rangliste', '<?=$class?>');">
										<a href="rangliste.php"><?=parse($lg_rangliste)?></a>
									</td>
									<?php
									$class = ($glb_kategorie=='punkte') ? 'buttonHover' : 'button';
									?>
									<td id="nav_punkte" class="<?=$class?>" onclick="getLink('punkte.php');" onmouseover="navigation('nav_punkte', '<?=$class?>');" onmouseout="navigation('nav_punkte', '<?=$class?>');">
										<a href="punkte.php"><?=parse($lg_regeln.' & '.$lg_punktvergabe)?></a>
									</td>
									<?php
									if($glb_eingeloggt){
										$class = ($glb_kategorie=='profil') ? 'buttonHover' : 'button';
										?>
										<td id="nav_profil" class="<?=$class?>" onclick="getLink('profil.php');" onmouseover="navigation('nav_profil', '<?=$class?>');" onmouseout="navigation('nav_profil', '<?=$class?>');">
											<a href="profil.php"><?=parse($lg_profil)?></a>
										</td>
										<?php
										$class = 'button';
										?>
										<td id="nav_logout" class="<?=$class?>" onclick="getLink('index.php?a=logout');" onmouseover="navigation('nav_logout', '<?=$class?>');" onmouseout="navigation('nav_logout', '<?=$class?>');">
											<a href="index.php?a=logout"><?=parse($lg_logout)?></a>
										</td>
										<?php
									}
								}
								?>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="90">
						<?php
						if($glb_eingeloggt){
							?>
							<!-- KONAMI CODE -->
							<script type="text/javascript" src="http://konami-js.googlecode.com/svn/trunk/konami.js"></script>
							<script type="text/javascript">
								konami = new Konami()
								konami.code = function(){
									alert("<?=$lg_cheaten_verboten?>");
								}

								konami.load();
							</script>
							<?php
						}
						?>

						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="header">
							<colgroup>
								<col width="645"/>
								<col/>
							</colgroup>
							<tr>
								<td class="left">
									<img src="layout/img/logo.png" width="422" height="63" class="logo" border="0" alt="<?=parse($lg_application_name)?>" title="<?=parse($lg_application_name)?>"/>
								</td>
								<td class="right">
									<?php
									$sprachen = get_sprachen();

									if(count($sprachen)>1){
										foreach($sprachen as $index => $sprache){
											$uri = $glb_uri.((!strstr($glb_uri, '?')) ? '?' : '&').'lang='.$sprache['kurz'];
											$trennzeichen = ($index>0) ? '&nbsp;|&nbsp;&nbsp;' : '';

											if($sprache['kurz']!=$lang){
												?>
												<?=$trennzeichen?><a href="<?=$uri?>"><?=$sprache['name']?></a>
												<?php
											} else {
												?>
												<?=$trennzeichen?><span style="color: #FFFFFF; font-weight: normal;"><?=$sprache['name']?></span>
												<?php
											}
										}
									} else {
										?>
										&nbsp;
										<?php
									}
									?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<colgroup>
								<?php
								$untermenu_datei = (isset($glb_menu)) ? 'nav_'.$glb_menu.'.php' : '';
								
								if($glb_menu=='start' && $glb_eingeloggt && $cfg_einsatz['on'] && $_SESSION[$cfg_session]['login']['benutzer_einsatz']>=$cfg_einsatz['betrag']){
									$untermenu_datei = 'nav_start_money.php';
								}
								
								$untermenu = (isset($glb_menu) && file_exists($untermenu_datei));

								if($untermenu){
									?>
									<col width="264"/>
									<col width="50"/>
									<?php
								}
								?>
								<col/>
							</colgroup>
							<tr>
								<?php
								if($untermenu){
									?>
									<td valign="top">
										<table width="264" border="0" cellpadding="0" cellspacing="0" class="box">
											<colgroup>
												<col width="32"/>
												<col width="200"/>
												<col width="32"/>
											</colgroup>
											<tr>
												<td class="tl"><img src="layout/img/space.gif" width="32" height="32" border="0" alt=""/></td>
												<td class="top"><img src="layout/img/space.gif" width="200" height="32" border="0" alt=""/></td>
												<td class="tr"><img src="layout/img/space.gif" width="32" height="32" border="0" alt=""/></td>
											</tr>
											<tr>
												<td class="left"><img src="layout/img/space.gif" width="32" height="32" border="0" alt=""/></td>
												<td class="middle">
												<?php
												include($untermenu_datei);
												?>
												</td>
												<td class="right"><img src="layout/img/space.gif" width="32" height="32" border="0" alt=""/></td>
											</tr>
											<tr>
												<td class="bl"><img src="layout/img/space.gif" width="32" height="32" border="0" alt=""/></td>
												<td class="bottom"><img src="layout/img/space.gif" width="200" height="32" border="0" alt=""/></td>
												<td class="br"><img src="layout/img/space.gif" width="32" height="32" border="0" alt=""/></td>
											</tr>
										</table>
									</td>
									<td>&nbsp;</td>
									<?php
								}
								?>
								<td valign="top">
									<table width="100%" border="0" cellpadding="0" cellspacing="0" class="box">
										<colgroup>
											<col width="32"/>
											<col/>
											<col width="32"/>
										</colgroup>
										<tr>
											<td class="tl"><img src="layout/img/space.gif" width="32" height="32" border="0" alt=""/></td>
											<td class="top"><img src="layout/img/space.gif" width="32" height="32" border="0" alt=""/></td>
											<td class="tr"><img src="layout/img/space.gif" width="32" height="32" border="0" alt=""/></td>
										</tr>
										<tr>
											<td class="left"><img src="layout/img/space.gif" width="32" height="32" border="0" alt=""/></td>
											<td class="middle">