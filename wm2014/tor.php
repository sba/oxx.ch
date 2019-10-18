<?php
$glb_kategorie = 'tor';
$glb_seite = 'start';
$glb_menu = 'start';

$glb_no_infos = true;

include('lib/inc.init.php');

include('header.php');

?>
<h1><?=parse($lg_torschuetzen)?></h1>

<?php
$tore = get_torschuetze();

if(count($tore)){
	$tore_total = 0;
	
	foreach($tore as $anzahl => $spieler){
		$tore_total += ($anzahl * count($spieler));
	}
	?>
	<b><?=$lg_tore_total?>: <?=$tore_total?></b><br/><br/><br/>
	
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<colgroup>
			<col width="40"/>
			<col/>
		</colgroup>
		<?php
		$penalties_done = false;
		foreach($tore as $anzahl => $spieler){
			?>
			<tr>
				<td valign="top"><b><?=$anzahl?></b></td>
				<td valign="top">
					<?php
					foreach($spieler as $index => $s){
						$break = ($index==0) ? '' : '<br/><br/>';
						$penalties_string = '';
						
						if($s['penalties']>0){
							$penalties_string = ' ('.$s['penalties'].((!$penalties_done) ? ' '.$lg_penalties : '').')';
							$penalties_done = true;
						}
						?>
						<?=$break?><img src="layout/img/flags/<?=$s['spieler_land_code']?>.gif" align="top" border="0"/> <?=$s['spieler_name']?><?=$penalties_string?>
						<?php
					}
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="5"></td>
			</tr>
			<tr>
				<td colspan="2" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
			</tr>
			<tr>
				<td colspan="2" height="5"></td>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
} else {
	msg($lg_keine_tore, 'error', 0, 0);
}

include('footer.php');
?>