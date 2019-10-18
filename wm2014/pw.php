<?php
$glb_kategorie = 'start';
$glb_seite = 'start';
$glb_menu = 'start';

$glb_no_infos = true;

include('lib/inc.init.php');

if($glb_eingeloggt){
	header('Location: index.php');
	exit();
}

$res_pw = '';
if(isset($_POST['action']) && $_POST['action']=='pw'){
	$data = array(
		'benutzer_benutzername' => addslashes($_POST['benutzer_benutzername']),
		'benutzer_email' => addslashes($_POST['benutzer_email']),
	);
	
	$res_pw = $cls_benutzer->pw($data);
}

include('header.php');

?>
<script type="text/javascript">
	function checkForm(){
		var obj = document.frmPW;
		var goOn = true;
		
		if(goOn && obj.benutzer_benutzername.value==''){
			obj.benutzer_benutzername.focus();
			goOn = false;
		}
		
		if(goOn && obj.benutzer_email.value==''){
			obj.benutzer_email.focus();
			goOn = false;
		}
		
		if(goOn){
			obj.indianajones.name = 'action';
			obj.submit();
		}
	}
</script>

<h1><?=parse($lg_kennwort_vergessen)?></h1>

<?php
if($res_pw=='ok'){
	$search = array('%ADRESSE%');
	$replace = array($_POST['benutzer_email']);
	$msg = parse('Das neue Kennwort wurde erfolgreich generiert und an die Adresse <b>%ADRESSE%</b> gesendet.', $search, $replace);
	msg($msg, 'erfolg', 0, 2);
	
	?>
	<a href="index.php">&laquo;<?=$lg_zurueck_startseite?></a>
	<?php
} else {
	?>
	<?=$lg_kennwort_vergessen_info?><br/><br/>

	<?php
	if($res_pw!=''){
		$msg = ($res_pw=='benutzer') ? $lg_kennwort_vergessen_error_benutzer : $lg_kennwort_vergessen_error;
		msg($msg, 'error', 0, 1);
	}

	$wert_benutzer_benutzername = (isset($_POST['benutzer_benutzername'])) ? $_POST['benutzer_benutzername'] : '';
	$wert_benutzer_email = (isset($_POST['benutzer_email'])) ? $_POST['benutzer_email'] : '';
	?>
	<form name="frmPW" action="pw.php" method="post">
		<input type="hidden" name="indianajones" value="pw"/>
		
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td><b><label for="benutzer_benutzername"><?=$lg_benutzername?>:</label></b></td>
			</tr>
			<tr>
				<td><input type="text" name="benutzer_benutzername" id="benutzer_benutzername" class="input" style="width: 250px;" tabindex="1" value="<?=$wert_benutzer_benutzername?>"/></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><b><label for="benutzer_email"><?=$lg_email?>:</label></b></td>
			</tr>
			<tr>
				<td><input type="text" name="benutzer_email" id="benutzer_email" class="input" style="width: 250px;" tabindex="2" value="<?=$wert_benutzer_email?>"/></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
					<?php
					button($lg_kennwort_senden.' &raquo;', '', 'checkForm()', 'gruen', false, 3);
					?>
				</td>
			</tr>
		</table>
	</form>
	<?php
}

include('footer.php');
?>