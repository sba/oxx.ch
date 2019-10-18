<?php
$glb_kategorie = 'profil';
$glb_seite = 'start';
$glb_menu = 'start';

$glb_no_infos = true;

include('lib/inc.init.php');

if(!$glb_eingeloggt){
	header('Location: index.php');
	exit();
}

$res_profil = '';
if(isset($_POST['action']) && $_POST['action']=='profil'){
	$data = array(
		'benutzer_id' => $_SESSION[$cfg_session]['login']['benutzer_id'],
		'benutzer_email' => addslashes($_POST['benutzer_email']),
		'benutzer_kennwort' => $_POST['benutzer_kennwort'],
		'benutzer_geschlecht' => $_POST['benutzer_geschlecht'],
		'benutzer_land_code' => $_POST['benutzer_land_code'],
	);
	
	$res_profil = $cls_benutzer->profil($data);
	
	if($res_profil=='ok'){
		$_SESSION[$cfg_session]['temp'] = array(
			'action' => 'profil',
			'res' => 'ok',
		);
		
		header('Location: profil.php');
		exit();
	}
}

include('header.php');

?>
<script type="text/javascript">
	function checkForm(){
		var obj = document.frmProfil;
		var goOn = true;
		
		if(goOn && obj.benutzer_email.value==''){
			obj.benutzer_email.focus();
			goOn = false;
			alert('<?=$lg_email_fehlt?>');
		}
		
		if(goOn && (obj.benutzer_kennwort.value!='' || obj.benutzer_kennwort2.value!='')){
			if(goOn && obj.benutzer_kennwort.value==''){
				obj.benutzer_kennwort.focus();
				goOn = false;
				alert('<?=$lg_kennwort_fehlt?>');
			}
			
			if(goOn && obj.benutzer_kennwort2.value==''){
				obj.benutzer_kennwort2.focus();
				goOn = false;
				alert('<?=$lg_kennwort2_fehlt?>');
			}
			
			if(goOn && obj.benutzer_kennwort.value!=obj.benutzer_kennwort2.value){
				obj.benutzer_kennwort2.focus();
				goOn = false;
				alert('<?=$lg_kennwort_wiederholung_falsch?>');
			}
		}
		
		if(goOn){
			obj.submit();
		}
	}
	
	function check_flagge(){
		document.getElementById('img_flagge').src = 'layout/img/flags/'+document.getElementById('benutzer_land_code').value+'.gif';
		
		document.getElementById('div_flagge').style.display = '';
	}
	
	function hide_flagge(){
		document.getElementById('div_flagge').style.display = 'none';
	}
</script>

<h1><?=parse($lg_profil)?></h1>

<?php
if($res_profil!=''){
	$msg = ($res_profil=='email') ? $lg_email_vergeben : $lg_error_speichern;
	msg($msg, 'error', 0, 2);
}

if(isset($_SESSION[$cfg_session]['temp']) && $_SESSION[$cfg_session]['temp']['action']=='profil'){
	$msg = 'Die Änderungen wurden erfolgreich gespeichert.';
	msg($msg, 'erfolg', 0, 2);
	
	unset($_SESSION[$cfg_session]['temp']);
}

$wert_benutzer_email = (isset($_POST['benutzer_email'])) ? $_POST['benutzer_email'] : $_SESSION[$cfg_session]['login']['benutzer_email'];
$wert_benutzer_land_code = (isset($_POST['benutzer_land_code'])) ? $_POST['benutzer_land_code'] : $_SESSION[$cfg_session]['login']['benutzer_land_code'];
$wert_benutzer_geschlecht_1 = ((isset($_POST['benutzer_geschlecht']) && $_POST['benutzer_geschlecht']==1) || (!isset($_POST['benutzer_geschlecht']) && $_SESSION[$cfg_session]['login']['benutzer_geschlecht']==1)) ? ' checked="checked"' : '';
$wert_benutzer_geschlecht_0 = ((isset($_POST['benutzer_geschlecht']) && $_POST['benutzer_geschlecht']==0) || (!isset($_POST['benutzer_geschlecht']) && $_SESSION[$cfg_session]['login']['benutzer_geschlecht']==0)) ? ' checked="checked"' : '';
?>
<form name="frmProfil" action="profil.php" method="post">
	<input type="hidden" name="action" value="profil"/>
	
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<colgroup>
			<col width="250"/>
			<col/>
		</colgroup>
		<tr>
			<td><b><?=$lg_benutzername?>:</b></td>
			<td><label for="benutzer_email"><b><?=$lg_email?>:</b></label></td>
		</tr>
		<tr>
			<td><b><?=$_SESSION[$cfg_session]['login']['benutzer_benutzername']?></b></td>
			<td><input type="text" name="benutzer_email" id="benutzer_email" class="input" style="width: 230px;" value="<?=$wert_benutzer_email?>"/></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2"><label for="benutzer_land_code"><b><?=$lg_herkunft?>:</b></label></td>
		</tr>
		<tr>
			<td>
				<select name="benutzer_land_code" id="benutzer_land_code" class="input" onchange="check_flagge();">
					<?php
					$laender = get_land('', '', false, false, true);
					foreach($laender as $land){
						$sel = ($land['land_code']==$wert_benutzer_land_code) ? ' selected="selected"' : '';
						?>
						<option value="<?=$land['land_code']?>"<?=$sel?>><?=$land['land_name']?></option>
						<?php
					}
					?>
				</select>
			</td>
			<td><div id="div_flagge" style="display: none;"><img src="" onerror="hide_flagge();" id="img_flagge"/></div></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2"><label for="benutzer_geschlecht"><b><?=$lg_geschlecht?>:</b></label></td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="radio" name="benutzer_geschlecht" id="benutzer_geschlecht_1" value="1"<?=$wert_benutzer_geschlecht_1?>/> <label for="benutzer_geschlecht_1"><img src="layout/img/male.png" width="15" height="15" alt="<?=$lg_maennlich?>" title="<?=$lg_maennlich?>"/></label>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="benutzer_geschlecht" id="benutzer_geschlecht_0" value="0"<?=$wert_benutzer_geschlecht_0?>/> <label for="benutzer_geschlecht_0"><img src="layout/img/female.png" width="15" height="15" alt="<?=$lg_weiblich?>" title="<?=$lg_weiblich?>"/></label>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2"><?=$lg_profil_kennwort_info?></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td><label for="benutzer_kennwort"><b><?=$lg_kennwort?>:</b></label></td>
			<td><label for="benutzer_kennwort2"><b><?=$lg_kennwort_wiederholen?>:</b></label></td>
		</tr>
		<tr>
			<td><input type="password" name="benutzer_kennwort" id="benutzer_kennwort" class="input" style="width: 230px;" value=""/></td>
			<td><input type="password" name="benutzer_kennwort2" id="benutzer_kennwort2" class="input" style="width: 230px;" value=""/></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" height="1" style="background-image: url(layout/img/ln/ln_punkte_rot.gif);"></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php
				button($lg_speichern.' &raquo;', '', 'checkForm()');
				?>
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript">
	check_flagge();
</script>
<?php

include('footer.php');
?>