<?php
/**
* initialisiert Funktionen und Klassen
*
* @package UNofficial Brazil 2014 betting centre
* @author Davide De Santis
*/

session_start();

$pfad = (isset($pfad)) ? $pfad : '';

include($pfad.'lib/inc.settings.php');
include($pfad.'lib/inc.common.php');

include($pfad.'lang/lang.'.$cfg_sprache_standard.'.php');
if(isset($_SESSION[$cfg_session]['lang']) && $_SESSION[$cfg_session]['lang']!=$cfg_sprache_standard && $glb_kategorie!='ajax'){
	include($pfad.'lang/lang.'.$_SESSION[$cfg_session]['lang'].'.php');
}
include($pfad.'lang/lang.mails.php');

include($pfad.'lib/cls.session.php');
include($pfad.'lib/cls.benutzer.php');
require($pfad.'lib/cls.phpmailer.php');
include($pfad.'lib/cls.mailer.php');

$cls_session = new session();
$cls_benutzer = new benutzer();
$cls_mailer = new mailer();

$glb_datei = get_URI(true);
$glb_uri = get_URI(false, array('lang'));

// wenn Sprache als GET-Variable gesetzt, Seite neu laden
if(isset($_GET['lang'])){
	header('Location: '.$glb_uri);
	exit();
}

if($glb_db!='ok' && $glb_datei!='index.php'){
	header('Location: index.php');
	exit();
}

$glb_eingeloggt = (!isset($glb_no_login) && $glb_db=='ok' && $cls_session->eingeloggt());
$glb_admin = ($glb_eingeloggt && $_SESSION[$cfg_session]['login']['benutzer_admin']==1);

if($glb_kategorie!='ajax' && $glb_db=='ok' && !isset($glb_no_infos)){
	$glb_final_sieger = get_final_sieger();
	$glb_schuetzen = array();
	$glb_meiste_tore = 0;
	$glb_meiste_tore_land_codes = array();
	$glb_meiste_gegentore = 0;
	$glb_meiste_gegentore_land_codes = array();
	$glb_wenigste_tore = 9999;
	$glb_wenigste_tore_land_codes = array();
	$glb_wenigste_gegentore = 9999;
	$glb_wenigste_gegentore_land_codes = array();

	if($glb_final_sieger!=''){
		$laender = get_land();
		foreach($laender as $land){
			if($land['land_tore']>0 && $land['land_tore']>=$glb_meiste_tore){
				if($land['land_tore']>$glb_meiste_tore){
					$glb_meiste_tore_land_codes = array();
				}
				$glb_meiste_tore_land_codes[] = $land['land_code'];
				$glb_meiste_tore = $land['land_tore'];
			}			
			if($land['land_gegentore']>0 && $land['land_gegentore']>=$glb_meiste_gegentore){
				if($land['land_gegentore']>$glb_meiste_gegentore){
					$glb_meiste_gegentore_land_codes = array();
				}
				
				$glb_meiste_gegentore_land_codes[] = $land['land_code'];
				$glb_meiste_gegentore = $land['land_gegentore'];
			}
			
			if($land['land_tore']>=0 && $land['land_tore']<=$glb_wenigste_tore){
				if($land['land_tore']<$glb_wenigste_tore){
					$glb_wenigste_tore_land_codes = array();
				}
				
				$glb_wenigste_tore_land_codes[] = $land['land_code'];
				$glb_wenigste_tore = $land['land_tore'];
			}
			
			if($land['land_gegentore']>=0 && $land['land_gegentore']<=$glb_wenigste_gegentore){
				if($land['land_gegentore']<$glb_wenigste_gegentore){
					$glb_wenigste_gegentore_land_codes = array();
				}
				
				$glb_wenigste_gegentore_land_codes[] = $land['land_code'];
				$glb_wenigste_gegentore = $land['land_gegentore'];
			}
		}

		$tore = get_torschuetze();
		foreach($tore as $anzahl => $spieler){
			foreach($spieler as $s){
				$glb_schuetzen[] = $s['spieler_id'];
			}
			
			break;
		}
	}
}
?>