<?php
/**
* enth?lt allgemeine Funktionen
*
* @package UNofficial Brazil 2014 betting centre
* @author Davide De Santis
*/


/**
* Sprache pr?fen und setzen
*/
$lang = $cfg_sprache_standard;
if(isset($_GET['lang']) && $_GET['lang']!='' && file_exists('lang/lang.'.$_GET['lang'].'.php')){
	$lang = $_GET['lang'];
} elseif(isset($_SESSION[$cfg_session]['lang']) && $_SESSION[$cfg_session]['lang']!='' && $_SESSION[$cfg_session]['lang']!=$cfg_sprache_standard && file_exists('lang/lang.'.$_SESSION[$cfg_session]['lang'].'.php')){
	$lang = $_SESSION[$cfg_session]['lang'];
} elseif(isset($_COOKIE[$cfg_cookie.'_lang']) && $_COOKIE[$cfg_cookie.'_lang']!='' && $_COOKIE[$cfg_cookie.'_lang']!=$cfg_sprache_standard && file_exists('lang/lang.'.$_COOKIE[$cfg_cookie.'_lang'].'.php')){
	$lang = $_COOKIE[$cfg_cookie.'_lang'];
}
$_SESSION[$cfg_session]['lang'] = $lang;
setcookie($cfg_cookie.'_lang', $lang, strtotime('+1 year', time()));

/**
* Datenbankverbindung aufbauen
*/
$glb_db = '';
if($cfg_application['online']){
	if(@mysql_connect($cfg_db['host'], $cfg_db['benutzer'], utf8_decode($cfg_db['kennwort']))){
		if(@mysql_select_db($cfg_db['datenbank'])){
			$glb_db = 'ok';
		} else {
			$glb_db = mysql_error();
		}
	} else {
		$glb_db = mysql_error();
	}
}

/*if($glb_db=='ok'){
	$sql = "SELECT MAX(spiel_datum) AS grp
			  FROM ".$cfg_db['prefix']."spiel
			 WHERE spiel_typ = 'G';";
	$query = mysql_query($sql);

	if($query && mysql_num_rows($query)==1){
		$grp = mysql_fetch_assoc($query);

		$cfg_gruppenphase = date('Y-m-d', strtotime($grp['grp'])).' 23:59:59';
	}
}*/

/**
* Formatiert ein String und ersetzt gewisse Zeichenketten
* @param string $string String
* @param array $search_o zu ersetzende Suchbegriffe (benutzerdefiniert)
* @param array $replace_o Ersatzwerte f?r $search_o
*/
function parse($string, $search_o = array(), $replace_o = array()){
	global $lg_application_name;
	global $cfg_application;
	global $cfg_replace;
	global $lg_arr_tage;
	global $lg_arr_monate;

	$return = $string;

	$search = array(
		'%APP_NAME%',
		'%APP_URL%',
	);
	$replace = array(
		$lg_application_name,
		$cfg_application['url_protocol'].'://'.$cfg_application['url'],
	);

	if(count($search_o)>0 && count($search_o)==count($replace_o)){
		foreach($search_o as $index => $s_o){
			$search[] = $s_o;
			$replace[] = $replace_o[$index];
		}
	}

	$return = (function_exists('str_ireplace')) ? str_ireplace($search, $replace, $return) : str_replace($search, $replace, $return);

	$return = str_replace($cfg_replace['tage'], $lg_arr_tage, $return);
	$return = str_replace($cfg_replace['monate'], $lg_arr_monate, $return);

	return $return;
}

/**
* Wandelt Sonderzeichen um (? = Oe, ? = oe, etc.)
* @param string $string String
*/
function parse_sonderzeichen($string){
	$return = $string;

	$search = array(
		'ä',
		'ö',
		'ü',
		'é',
		'è',
		'á',
		'à',
		'ñ',
		'ç',
	);
	$replace = array(
		'ae',
		'oe',
		'ue',
		'e',
		'e',
		'a',
		'a',
		'n',
		'c',
	);

	$return = (function_exists('str_ireplace')) ? str_ireplace($search, $replace, $return) : str_replace($search, $replace, $return);

	return $return;
}

function int2money($int){
	return number_format((($int / 100)), 2, '.', "'");
}

function money2int($money){
	return (int)((double)$money * 100);
}

/**
* L?dt die Sprachen aus dem Verzeichnis lang/
*/
function get_sprachen(){
	$return = array();

	$dir = 'lang/';

	if(is_dir($dir)){
		$d = dir($dir);
		while(($entry=$d->read())!==false){
			if(preg_match('/lang\.(.*?){2,4}\.php/i', $entry)){
				$lang = str_replace(array('lang.', '.php'), array('', ''), $entry);
				$str = file_get_contents($dir.'lang.'.$lang.'.php');

				$matches = array();
				preg_match('/\* Sprachfile: (.*)/i', $str, $matches);

				if(count($matches)>1){
					$return[] = array(
						'kurz' => $lang,
						'name' => trim($matches[1]),
					);
				}
			}
		}
		$d->close();
	}

	return $return;
}

/**
* Gibt ein Array aus und formatiert die Ausgabe mit <pre>
* @param array|string $output Array oder String
*/
function print_arr($output){
	if(is_array($output)){
		?>
		<pre>
			<?php
			print_r($output);
			?>
		</pre>
		<?php
	} else {
		echo($output);
	}
}

/**
* Gibt die aktuelle URI zur?ck
*
* @param boolean $strip_get wenn true wird GET ignoriert
* @param boolean $strip_lang wenn true wird GET['lang'] entfernt
* @return string
*/
function get_URI($strip_get = false, $strip = array()){
	$return = '';

	if(!$strip_get){
		foreach($_GET as $key => $value){
			if(!in_array($key, $strip)){
				$return .= (($return!='') ? '&' : '?').$key.'='.$value;
			}
		}
	}

	$file = substr($_SERVER['PHP_SELF'], (strrpos($_SERVER['PHP_SELF'], '/') + 1));
	$return = $file.$return;

	return $return;
}

/**
* Ruft L?nder aus der Datenbank ab
* @param string $land_code Code eines einzelnen Landes
* @param string $gruppe Code einer einzelnen gruppe
* @param boolean $gruppiert Resultate gruppiert ausgeben
*/
function get_land($land_code = '', $gruppe = '', $gruppiert = false, $zusatz = true, $alle = false){
	global $cfg_db;
	global $lang;

	$return = array();

	$where = ($land_code!='') ? "WHERE land_code = '".$land_code."' " : "";
	$where .= ($gruppe!='') ? (($where=='') ? "WHERE" : "AND")." land_gruppe = '".$gruppe."' " : "";

	$sql = "";
	if($alle){
		$sql = "SELECT *
				  FROM land
				 ".$where.";";
	} else {
		$sql = "SELECT *
				  FROM ".$cfg_db['prefix']."land
			 LEFT JOIN land USING(land_code)
				 ".$where.";";
	}
	$query = mysql_query($sql);

	while($land = mysql_fetch_assoc($query)){
		$feld = 'land_name_'.$lang;

		if((isset($land[$feld]) && $land[$feld]!='') || (isset($land[$feld]) && $land[$feld]=='' && isset($lg_liste_land[$land['land_code']]))){
			$land['land_name'] = (!isset($land[$feld]) || $land[$feld]=='') ? $lg_liste_land[$land['land_code']] : $land[$feld];
			$land['land_sortierwert'] = parse_sonderzeichen(strtolower($land['land_name']));
			$land['land_gruppe'] = (isset($land['land_gruppe'])) ? $land['land_gruppe'] : '';

			if($zusatz){
				$land['land_spieler'] = array();

				$sql2 = "SELECT *
						   FROM ".$cfg_db['prefix']."spieler
						  WHERE spieler_land_code = '".$land['land_code']."'
					   ORDER BY spieler_nr ASC,
								spieler_name ASC;";
				$query2 = mysql_query($sql2);

				while($spieler = mysql_fetch_assoc($query2)){
					$spieler['spieler_name'] = stripslashes($spieler['spieler_name']);

					$land['land_spieler'][] = $spieler;
				}

				$land['land_spiele'] = array();
				$land['land_gespielt'] = 0;
				$land['land_siege'] = 0;
				$land['land_unentschieden'] = 0;
				$land['land_niederlagen'] = 0;
				$land['land_punkte'] = 0;
				$land['land_tore'] = 0;
				$land['land_gegentore'] = 0;

				$where3 = ($gruppiert) ? "AND spiel_typ = 'G' " : "";
				$sql3 = "SELECT *
						   FROM ".$cfg_db['prefix']."spiel
						  WHERE (spiel_home_land_code = '".$land['land_code']."'
							 OR spiel_away_land_code = '".$land['land_code']."')
							".$where3."
					   ORDER BY spiel_datum ASC;";
				$query3 = mysql_query($sql3);

				while($spiel = mysql_fetch_assoc($query3)){
					$land['land_spiele'][] = $spiel;

					if($spiel['spiel_home_res']>=0){
						$land['land_gespielt']++;

						$is_home = ($spiel['spiel_home_land_code']==$land['land_code']);
						$total_home = ($spiel['spiel_home_res'] + $spiel['spiel_home_ext'] + $spiel['spiel_home_penalty']);
						$total_away = ($spiel['spiel_away_res'] + $spiel['spiel_away_ext'] + $spiel['spiel_away_penalty']);
						$total_team = ($is_home) ? $total_home : $total_away;
						$total_other = ($is_home) ? $total_away : $total_home;

						$land['land_siege'] += ($total_team>$total_other) ? 1 : 0;
						$land['land_unentschieden'] += ($total_team==$total_other) ? 1 : 0;
						$land['land_niederlagen'] += ($total_team<$total_other) ? 1 : 0;

						$land['land_punkte'] += ($spiel['spiel_typ']=='G') ? (($total_team>$total_other) ? 3 : (($total_team==$total_other) ? 1 : 0)) : 0;

						$typ_home = ($spiel['spiel_home_ext']>=0) ? 'ext' : 'res';
						$typ_away = ($spiel['spiel_away_ext']>=0) ? 'ext' : 'res';

						$land['land_tore'] += (($is_home) ? $spiel['spiel_home_'.$typ_home] : $spiel['spiel_away_'.$typ_away]);
						$land['land_gegentore'] += (($is_home) ? $spiel['spiel_away_'.$typ_away] : $spiel['spiel_home_'.$typ_home]);
					}
				}

				$land['land_differenz'] = ($land['land_tore'] - $land['land_gegentore']);
			}

			$return[] = $land;
		}
	}

	if($land_code==''){
		$arr_land_id = array();
		$arr_land_code = array();
		$arr_land_name = array();
		$arr_land_sortierwert = array();
		$arr_land_gruppe = array();
		$arr_land_spieler = array();
		$arr_land_spiele = array();
		$arr_land_gespielt = array();
		$arr_land_siege = array();
		$arr_land_unentschieden = array();
		$arr_land_niederlagen = array();
		$arr_land_punkte = array();
		$arr_land_tore = array();
		$arr_land_gegentore = array();
		$arr_land_differenz = array();
		$arr_land_position = array();
		foreach($return as $key => $row){
			$arr_land_id[$key] = $row['land_id'];
			$arr_land_code[$key] = $row['land_code'];
			$arr_land_name[$key] = $row['land_name'];
			$arr_land_sortierwert[$key] = $row['land_sortierwert'];
			$arr_land_gruppe[$key] = $row['land_gruppe'];
			if($zusatz){
				$arr_land_spieler[$key] = $row['land_spieler'];
				$arr_land_spiele[$key] = $row['land_spiele'];
				$arr_land_gespielt[$key] = $row['land_gespielt'];
				$arr_land_siege[$key] = $row['land_siege'];
				$arr_land_unentschieden[$key] = $row['land_unentschieden'];
				$arr_land_niederlagen[$key] = $row['land_niederlagen'];
				$arr_land_punkte[$key] = $row['land_punkte'];
				$arr_land_tore[$key] = $row['land_tore'];
				$arr_land_gegentore[$key] = $row['land_gegentore'];
				$arr_land_differenz[$key] = $row['land_differenz'];
				$arr_land_position[$key] = $row['land_position'];
			}
		}

		if($gruppiert){
			array_multisort($arr_land_gruppe, SORT_ASC, $arr_land_position, $arr_land_punkte, SORT_DESC, $arr_land_gespielt, SORT_ASC, $arr_land_differenz, SORT_DESC, $arr_land_tore, SORT_DESC, $arr_land_gegentore, SORT_ASC, $arr_land_sortierwert, SORT_ASC, $arr_land_name, SORT_ASC, $arr_land_code, SORT_ASC, $arr_land_id, SORT_ASC, $arr_land_spieler, $arr_land_spiele, $arr_land_siege, $arr_land_unentschieden, $arr_land_niederlagen, $return);

			$temp = array();
			foreach($return as $land){
				$temp[$land['land_gruppe']][] = $land;
			}
			$return = $temp;
		} else {
			if($zusatz){
				array_multisort($arr_land_sortierwert, SORT_ASC, $arr_land_gruppe, SORT_ASC, $arr_land_name, SORT_ASC, $arr_land_code, SORT_ASC, $arr_land_id, SORT_ASC, $arr_land_spieler, $arr_land_spiele, $arr_land_gespielt, $arr_land_punkte, $arr_land_tore, $arr_land_gegentore, $arr_land_differenz, $arr_land_siege, $arr_land_unentschieden, $arr_land_niederlagen, $return);
			} else {
				array_multisort($arr_land_sortierwert, SORT_ASC, $arr_land_gruppe, SORT_ASC, $arr_land_name, SORT_ASC, $arr_land_code, SORT_ASC, $arr_land_id, SORT_ASC, $return);
			}
		}
	}

	if($land_code!='' && count($return)==1){
		$return = $return[0];
	}

	return $return;
}

/**
* Ruft Spieler aus der Datenbank ab
*/
function get_spieler($spieler_id = 0, $laender = array()){
	global $cfg_db;

	$return = array();

	$where = ($spieler_id!=0) ? "WHERE spieler_id = ".$spieler_id." " : "";
	$where .= (count($laender)>0) ? (($where!='') ? "AND" : "WHERE")." spieler_land_code IN('".implode("','", $laender)."') " : "";

	$sql = "SELECT *
			  FROM ".$cfg_db['prefix']."spieler
			 ".$where."
		  ORDER BY spieler_land_code ASC,
				   spieler_nr ASC,
				   spieler_name ASC;";
	$query = mysql_query($sql);

	while($spieler = mysql_fetch_assoc($query)){
		$spieler['spieler_name'] = stripslashes($spieler['spieler_name']);

		$return[] = $spieler;
	}

	if($spieler_id!=0 && count($return)==1){
		$return = $return[0];
	}

	return $return;
}

/**
* Ruft Spiele aus der Datenbank ab
*/
function get_spiel($spiel_gruppe = '', $land_code = '', $spiel_id = 0, $tore = true){
	global $cfg_db;
	global $lang;
	global $lg_liste_land;

	$return = array();

	$where = ($spiel_gruppe!='') ? "WHERE spiel_gruppe = '".$spiel_gruppe."' " : "";
	$where .= ($land_code!='') ? (($where=='') ? "WHERE" : "AND")." (spiel_home_land_code = '".$land_code."' OR spiel_away_land_code = '".$land_code."') " : "";
	$where .= ($spiel_id!=0) ? (($where=='') ? "WHERE" : "AND")." spiel_id = ".$spiel_id." " : "";
	$sql = "SELECT spiel.*,
				   land_home.land_name_".$lang." AS name_home,
				   land_away.land_name_".$lang." AS name_away,
				   stadion.stadion_ort,
				   stadion.stadion_name, 
				   stadion.stadion_zeitzone_differenz
			  FROM ".$cfg_db['prefix']."spiel AS spiel
		 LEFT JOIN land AS land_home ON(spiel.spiel_home_land_code = land_home.land_code)
		 LEFT JOIN land AS land_away ON(spiel.spiel_away_land_code = land_away.land_code)
		 LEFT JOIN ".$cfg_db['prefix']."stadion AS stadion ON(spiel.spiel_stadion_id = stadion.stadion_id)
			 ".$where."
		  ORDER BY spiel_datum ASC;";
	$query = mysql_query($sql);

	while($spiel = mysql_fetch_assoc($query)){
		$spiel['spiel_home_land_name'] = (isset($spiel['name_home']) && $spiel['name_home']!='') ? $spiel['name_home'] : $lg_liste_land[$spiel['spiel_home_land_code']];
		$spiel['spiel_away_land_name'] = (isset($spiel['name_away']) && $spiel['name_away']!='') ? $spiel['name_away'] : $lg_liste_land[$spiel['spiel_away_land_code']];

		if($tore){
			$spiel['tore'] = array();

			$sql2 = "SELECT *
					   FROM ".$cfg_db['prefix']."tor
				  LEFT JOIN ".$cfg_db['prefix']."spieler ON(tor_spieler_id = spieler_id)
					  WHERE tor_spiel_id = ".$spiel['spiel_id']."
				   ORDER BY tor_ext ASC,
							tor_minute ASC;";
			$query2 = mysql_query($sql2);

			while($tor = mysql_fetch_assoc($query2)){
				$tor['spieler_name'] = stripslashes($tor['spieler_name']);

				$spiel['tore'][] = $tor;
			}
		}

		$return[] = $spiel;
	}

	if($spiel_id!=0 && count($return)==1){
		$return = $return[0];
	}

	return $return;
}

/**
* Ruft Torsch?tzen aus der Datenbank ab
*/
function get_torschuetze(){
	global $cfg_db;

	$return = array();

	$sql = "SELECT s.*,
				   (
					  SELECT COUNT(tor_id) AS total
						FROM ".$cfg_db['prefix']."tor AS t
					   WHERE tor_spieler_id = s.spieler_id
						 AND tor_own = 0
				   ) AS tore,
				   (
					  SELECT COUNT(tor_id) AS total
						FROM ".$cfg_db['prefix']."tor AS t
					   WHERE tor_spieler_id = s.spieler_id
						 AND tor_own = 0
						 AND tor_penalty = 1
				   ) AS penalties
		 FROM ".$cfg_db['prefix']."spieler AS s
			 WHERE (
					  SELECT COUNT(tor_id) AS total
						FROM ".$cfg_db['prefix']."tor AS t
					   WHERE tor_spieler_id = s.spieler_id
						 AND tor_own = 0
				   ) > 0
		  ORDER BY tore DESC,
				   penalties ASC,
				   spieler_name ASC;";
	$query = mysql_query($sql);

	while($spieler = mysql_fetch_assoc($query)){
		$spieler['spieler_name'] = stripslashes($spieler['spieler_name']);

		$return[$spieler['tore']][] = $spieler;
	}

	return $return;
}

/**
* Pr?ft ob eine Gruppe wirklich existiert
* @param string $gruppe zu pr?fende Gruppe
*/
function check_gruppe($gruppe){
	global $cfg_db;

	$return = false;

	$sql = "SELECT COUNT(land_gruppe) AS total
			  FROM ".$cfg_db['prefix']."land
			 WHERE land_gruppe = '".$gruppe."';";
	$query = mysql_query($sql);

	while($gruppe = mysql_fetch_assoc($query)){
		$return = ($gruppe['total']>0);
	}

	return $return;
}

/**
* Pr?ft ob ein Land wirklich existiert
* @param string $gruppe zu pr?fende Gruppe
*/
function check_land_code($land_code){
	global $cfg_db;

	$return = false;

	$sql = "SELECT COUNT(land_code) AS total
			  FROM ".$cfg_db['prefix']."land
			 WHERE land_code = '".$land_code."';";
	$query = mysql_query($sql);

	while($land = mysql_fetch_assoc($query)){
		$return = ($land['total']>0);
	}

	return $return;
}

/**
* Ruft den Finalsieger ab
*/
function get_final_sieger(){
	global $cfg_db;

	$return = '';

	$sql = "SELECT *
			  FROM ".$cfg_db['prefix']."spiel
			 WHERE spiel_typ = 'F'
			   AND spiel_home_res >= 0
			   AND spiel_away_res >= 0;";
	$query = mysql_query($sql);

	if($query && mysql_num_rows($query)==1){
		$spiel = mysql_fetch_assoc($query);

		$total_spiel_home = (($spiel['spiel_home_ext']>=0) ? $spiel['spiel_home_ext'] : $spiel['spiel_home_res']);
		$total_spiel_away = (($spiel['spiel_away_ext']>=0) ? $spiel['spiel_away_ext'] : $spiel['spiel_away_res']);

		$total_spiel_home_pen = ($total_spiel_home + (($spiel['spiel_home_penalty']>=0) ? $spiel['spiel_home_penalty'] : 0));
		$total_spiel_away_pen = ($total_spiel_away + (($spiel['spiel_away_penalty']>=0) ? $spiel['spiel_away_penalty'] : 0));

		$total_spiel = ($total_spiel_home + $total_spiel_away);
		$total_spiel_pen = ($total_spiel_home_pen + $total_spiel_away_pen);

		$sieger_spiel = ($total_spiel_home_pen>$total_spiel_away_pen) ? 'home' : (($total_spiel_home_pen<$total_spiel_away_pen) ? 'away' : 'none');
		$sieger_spiel_code = ($sieger_spiel=='home') ? $spiel['spiel_home_land_code'] : (($sieger_spiel=='away') ? $spiel['spiel_away_land_code'] : 'none');

		$return = $sieger_spiel_code;
	}

	return $return;
}

/**
* Ruft die Anzahl gespielter Spiele ab
*/
function get_spiele_anzahl(){
	global $cfg_db;

	$return = array(
		'gespielt' => 0,
		'ext' => 0,
		'penalty' => 0,
	);

	$sql = "SELECT *
			  FROM ".$cfg_db['prefix']."spiel
			 WHERE spiel_home_res >= 0
			   AND spiel_away_res >= 0;";
	$query = mysql_query($sql);

	while($spiel = mysql_fetch_assoc($query)){
		$return['gespielt']++;

		if($spiel['spiel_home_ext']>=0 && $spiel['spiel_away_ext']>=0){
			$return['ext']++;
		}

		if($spiel['spiel_home_penalty']>=0 && $spiel['spiel_away_penalty']>=0){
			$return['penalty']++;
		}
	}

	return $return;
}

function generate_code($length = 8){
	$return = '';

	$zeichen = array('!', '?', '-', '_', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
	$methode = array('upper', 'lower');

	while(strlen($return)<$length){
		$bisher = strlen($return);

		$rand = rand((($bisher>0 && $bisher<($length - 1)) ? 4 : 0), (count($zeichen) - 1));
		$methode = rand(0, 1);

		$char = ($methode==0) ? strtoupper($zeichen[$rand]) : strtolower($zeichen[$rand]);

		$return .= $char;
	}

	return $return;
}

function update_spiel($data){
	global $cfg_db;

	$return = 'error';

	$sql_t = "BEGIN;";
	$query_t = mysql_query($sql_t);

	if($query_t){
		$sql = "UPDATE ".$cfg_db['prefix']."spiel
				   SET spiel_home_land_code = '".$data['spiel_home_land_code']."',
					   spiel_away_land_code = '".$data['spiel_away_land_code']."',
					   spiel_home_res = ".$data['spiel_home_res'].",
					   spiel_away_res = ".$data['spiel_away_res'].",
					   spiel_home_ext = ".$data['spiel_home_ext'].",
					   spiel_away_ext = ".$data['spiel_away_ext'].",
					   spiel_home_penalty = ".$data['spiel_home_penalty'].",
					   spiel_away_penalty = ".$data['spiel_away_penalty'].",
					   spiel_gelb = ".$data['spiel_gelb'].",
					   spiel_rot = ".$data['spiel_rot']."
				 WHERE spiel_id = ".$data['spiel_id'].";";
		$query = mysql_query($sql);

		if($query){
			$sql2 = "DELETE FROM ".$cfg_db['prefix']."tor
					  WHERE tor_spiel_id = ".$data['spiel_id'].";";
			$query2 = mysql_query($sql2);

			if($query2){
				$alle_ok = true;

				foreach($data['tore'] as $tor){
					$sql3 = "INSERT INTO ".$cfg_db['prefix']."tor
									 SET tor_spiel_id = ".$data['spiel_id'].",
										 tor_spieler_id = ".$tor['tor_spieler_id'].",
										 tor_minute = ".$tor['tor_minute'].",
										 tor_penalty = ".$tor['tor_penalty'].",
										 tor_own = ".$tor['tor_own'].",
										 tor_ext = ".$tor['tor_ext'].";";
					$query3 = mysql_query($sql3);

					if(!$query3 || mysql_affected_rows()<1){
						$alle_ok = false;
						break;
					}
				}

				if($alle_ok){
					$return = 'ok';
				}
			}
		}

		if($return=='ok'){
			$sql_t = "COMMIT;";
			$query_t = mysql_query($sql_t);
		} else {
			$sql_t = "ROLLBACK;";
			$query_t = mysql_query($sql_t);
		}
	}

	return $return;
}

/**
* Gibt einen Button aus
* @param string $label Beschriftung
* @param string $seite Link zu einer Seite
* @param string $js JavaScript
* @param string $col Button-Farbe
* @param boolean $off inaktiv oder aktiv
* @param integer $tab Tabulator-Reihenfolge
* @param integer $ident Einger?ckt
*/
function button($label, $seite = '', $js = '', $col = 'gruen', $off = false, $tab = 0, $ident = 0, $groesse = 0){
	$onclick = $js.(($js!='') ? ';' : '').(($seite!='') ? "document.location.href = '".$seite."';" : '');
	$href = ($onclick!='') ? 'javascript: '.$onclick : '';
	$onclick = (!$off) ? ' onclick="return '.$onclick.'"': '';
	$tabindex = ($tab>0) ? ' tabindex="'.$tab.'"' : '';

	$col = ($off) ? 'inaktiv' : $col;
	$text_col = ($off) ? '#414141' : (($col=='blau' || $col=='rot') ? '#FFFFFF' : '#000000');

	$groesse = ($groesse>0) ? ' width="'.$groesse.'"' : '';
	?>
	<table<?=$groesse?> border="0" cellpadding="0" cellspacing="0" class="btn"<?=$onclick?>>
		<tr>
			<?php
			if($ident>0){
				?>
				<td width="<?=$ident?>">&nbsp;</td>
				<?php
			}
			?>
			<td class="left" style="background-image: url(layout/img/btn/btn_<?=$col?>_l.gif);"></td>
			<td class="middle" style="background-image: url(layout/img/btn/btn_<?=$col?>_m.gif); color: <?=$text_col?>;">
				<?=$label?>
			</td>
			<td class="right" style="background-image: url(layout/img/btn/btn_<?=$col?>_r.gif);"></td>
		</tr>
	</table>
	<?php
}

/**
* Gibt eine Meldung aus
* @param string $msg Meldung
* @param string $typ erfolg f?r Erfolgsmeldung, error f?r Fehlermeldung
* @param integer $br_before Zeilenumbr?che VOR der Meldung
* @param integer $br_after Zeilenumbr?che NACH der Meldung
*/
function msg($msg, $typ = 'erfolg', $br_before = 0, $br_after = 0, $rtl = false){
	$br_before = str_repeat('<br/>', $br_before);
	$br_after = str_repeat('<br/>', $br_after);
	?>
	<?=$br_before?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<?php
			if(!$rtl){
				?>
				<td width="25" valign="top"><img src="layout/img/<?=$typ?>.png" width="17" height="17" alt="" title="" border="0"/></td>
				<td class="<?=$typ?>">
					<?=$msg?>
				</td>
				<?php
			} else {
				?>
				<td class="<?=$typ?>" align="right">
					<?=$msg?>
				</td>
				<td width="25" align="right" valign="top"><img src="layout/img/<?=$typ?>.png" width="17" height="17" alt="" title="" border="0"/></td>
				<?php
			}
			?>
		</tr>
	</table>
	<?=$br_after?>
	<?php
}
?>