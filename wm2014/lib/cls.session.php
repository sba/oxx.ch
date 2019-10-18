<?php
class session {
	var $cls_settings = NULL;
	var $cls_sprache = NULL;
	var $cls_common = NULL;
	
	function eingeloggt(){
		global $cfg_session;
		
		$return = false;
		
		if(isset($_SESSION[$cfg_session]['login'])){
			$data = array(
				'benutzer_benutzername' => addslashes($_SESSION[$cfg_session]['login']['benutzer_benutzername']),
				'benutzer_kennwort' => $_SESSION[$cfg_session]['login']['benutzer_kennwort'],
			);
			
			$return = ($this->login($data, true)=='ok');
		}
		
		return $return;
	}
	
	function login($data, $isMD5 = false){
		global $cfg_db;
		global $cfg_session;
		global $cls_benutzer;
		global $lang;
		
		$return = 'error';
		
		$sql = "SELECT * 
				  FROM ".$cfg_db['prefix']."benutzer 
				 WHERE benutzer_benutzername = '".$data['benutzer_benutzername']."';";
		$query = mysql_query($sql);
		
		if($query && mysql_num_rows($query)==1){
			$benutzer = mysql_fetch_assoc($query);
			
			$kennwort = ($isMD5) ? $data['benutzer_kennwort'] : md5($data['benutzer_kennwort']);
			if($kennwort==$benutzer['benutzer_kennwort']){
				if($benutzer['benutzer_aktiv']==1){
					$return = 'ok';
					
					$benutzer = $cls_benutzer->get($benutzer['benutzer_id']);
					
					$_SESSION[$cfg_session]['login'] = $benutzer;
					
					$sql2 = "UPDATE ".$cfg_db['prefix']."benutzer 
								SET benutzer_sprache = '".$lang."' 
							  WHERE benutzer_id = ".$benutzer['benutzer_id'].";";
					$query2 = mysql_query($sql2);
				} else {
					$return = 'inaktiv';
				}
			} else {
				$return = 'pw';
			}
		} else {
			$return = 'benutzername';
		}
		
		return $return;
	}
	
	function logout(){
		global $cfg_session;
		
		if(isset($_SESSION[$cfg_session]['login'])){
			unset($_SESSION[$cfg_session]['login']);
		}
	}

}
?>