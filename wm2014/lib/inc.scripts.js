function preloader(){
	document.getElementById('div_ladebalken').style.display = 'none';
	document.getElementById('div_inhalt').style.display = '';
	document.getElementById('body_main').className = 'body';
}

function navigation(id, typ){
	if(typ=='button'){
		document.getElementById(id).className = (document.getElementById(id).className=='button') ? 'buttonHover' : 'button';
	}
}

function getLink(seite){
	document.location.href = seite;
}

var req_save_tips = null;
function ajax_save_tips(benutzer_id, benutzer_tip_sieger_land_code, benutzer_tip_scorer_spieler_id, benutzer_tip_meiste_tore_land_code, benutzer_tip_meiste_gegentore_land_code, benutzer_tip_wenigste_tore_land_code, benutzer_tip_wenigste_gegentore_land_code, tip_spiele, tip_home_res, tip_away_res, tip_home_ext, tip_away_ext, tip_home_penalty, tip_away_penalty, tip_gelb, tip_rot){
	try {
		req_save_tips = new ActiveXObject('Msxml2.XMLHTTP');
	} catch(e){
		try {
			req_save_tips = new ActiveXObject('Microsoft.XMLHTTP');
		} catch(oc) {
			req_save_tips = null;
		}
	}
	
	if(!req_save_tips && typeof XMLHttpRequest!='undefined'){
		req_save_tips = new XMLHttpRequest();
	}
	
	if(req_save_tips!=null){
		req_save_tips.onreadystatechange = result_save_tips;
		req_save_tips.open('GET', 'ajax/save_tips.php?benutzer_id='+benutzer_id+'&benutzer_tip_sieger_land_code='+benutzer_tip_sieger_land_code+'&benutzer_tip_scorer_spieler_id='+benutzer_tip_scorer_spieler_id+'&benutzer_tip_meiste_tore_land_code='+benutzer_tip_meiste_tore_land_code+'&benutzer_tip_meiste_gegentore_land_code='+benutzer_tip_meiste_gegentore_land_code+'&benutzer_tip_wenigste_tore_land_code='+benutzer_tip_wenigste_tore_land_code+'&benutzer_tip_wenigste_gegentore_land_code='+benutzer_tip_wenigste_gegentore_land_code+'&tip_spiele='+tip_spiele+'&tip_home_res='+tip_home_res+'&tip_away_res='+tip_away_res+'&tip_home_ext='+tip_home_ext+'&tip_away_ext='+tip_away_ext+'&tip_home_penalty='+tip_home_penalty+'&tip_away_penalty='+tip_away_penalty+'&tip_gelb='+tip_gelb+'&tip_rot='+tip_rot, true);
		req_save_tips.send(null);
	}
}

function result_save_tips(){
	if(req_save_tips.readyState == 4 && req_save_tips.status == 200){
		do_buttons(true, req_save_tips.responseText);
	}
}