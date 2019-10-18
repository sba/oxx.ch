<?php
class mailer {

	function send($from = '', $fromName = '', $to = '', $betreff, $text, $html = true, $flags = array(), $pfad = ''){
		global $cfg_email;
		global $lg_disclaimer;
		global $lg_disclaimer_text;

		$return = 'error';

		$mail = new PHPMailer();
		$to = ($to=='') ? $cfg_email['to'] : $to;
		$mail->AddAddress($to);
		$mail->From = ($from=='') ? $cfg_email['from'] : $from;
		$mail->FromName = ($fromName=='') ? $cfg_email['fromName'] : $fromName;
		$mail->AddReplyTo($mail->From, $mail->FromName);

		$mail->IsHTML($html);

		$mail->Subject = $betreff;
		$mail->Body = $text;

		if($html){
			$pfad_bild = (file_exists($pfad.'layout/img/ln/ln_punkte_rot.gif')) ? $pfad : '../';

			foreach($flags as $flag){
				$mail->AddEmbeddedImage($pfad_bild.'layout/img/flags/'.$flag.'.gif', 'flag_'.$flag, 'flag_'.$flag.'.gif', 'base64', 'image/gif');
			}

			$mail->Body = '
			<html>
				<head>
					<style type="text/css">
						body, td {
							font-family: Arial;
							font-size: 12px;
							font-color: #000000;
						}

						b, body b, td b {
							color: #D81313;
						}

						.disclaimer {
							color: #ACACAC;
						}

						.disclaimer b {
							color: #808080;
						}
					</style>
				</head>
				<body>
					'.$text.'<br/><br/><br/>

					<div class="disclaimer">
						<b>'.$lg_disclaimer.'</b><br/>
						'.str_replace('<br/><br/>', '<br/>', $lg_disclaimer_text).'
					</div>
				</body>
			</html>
			';
		}

		$return = ($mail->Send()) ? 'ok' : 'error';

		if($cfg_email['to']!=$to){
			$this->send($from, $fromName, $cfg_email['to'], $betreff, $text, $html, $flags, $pfad);
		}

		return $return;
	}

	function get_mail_text($typ = 'pw', $data){
		$return = '';

		switch($typ){
			case 'pw':
				$return = $this->get_mail_text_pw($data);
				break;
			case 'reg':
				$return = $this->get_mail_text_reg($data);
				break;
			case 'reminder':
				$return = $this->get_mail_text_reminder($data);
				break;
		}

		return $return;
	}

	function get_mail_text_pw($data){
		global $lg_kennwort_vergessen_mail_text;

		$return = $lg_kennwort_vergessen_mail_text;

		$search = array(
			'%BENUTZERNAME%',
			'%KENNWORT%',
		);
		$replace = array(
			$data['benutzername'],
			$data['kennwort'],
		);

		$return = parse($return, $search, $replace);

		return $return;
	}

	function get_mail_text_reg($data){
		global $lg_registrieren_mail_text;

		$return = $lg_registrieren_mail_text;

		$search = array(
			'%BENUTZERNAME%',
			'%KENNWORT%',
		);
		$replace = array(
			$data['benutzer_benutzername'],
			$data['benutzer_kennwort_klartext'],
		);

		$return = parse($return, $search, $replace);

		return $return;
	}

	function get_mail_text_reminder($data){
		global $cfg_sprache_standard;
		global $cfg_limit_spiel;
		global $cfg_gruppenphase;
		global $lg_mails;

		$return = $lg_mails[$data['benutzer_sprache']]['reminder']['text'];

		$str_gruppenphase = '';
		if($data['gruppenphase']){
			$gruppenphase_bis = $lg_mails[$data['benutzer_sprache']]['bis'].' '.date('d.m.Y H:i', strtotime($cfg_gruppenphase));

			$str_gruppenphase .= '
			<b>'.$lg_mails[$data['benutzer_sprache']]['gruppenphase'].'</b><br/>
			<i>'.$gruppenphase_bis.'</i>
			';

			if(count($data['spiele']['heute'])>0 || count($data['spiele']['morgen'])>0){
				$str_gruppenphase .= '
				<br/><br/><br/>
				';
			}
		}

		$str_spiele = '';
		if(count($data['spiele']['heute'])>0 || count($data['spiele']['morgen'])>0){
			$str_spiele = '
			<b>'.$lg_mails[$data['benutzer_sprache']]['spiele'].':</b><br/><br/>

			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<colgroup>
					<col width="30"/>
					<col width="110"/>
					<col width="45"/>
					<col width="160"/>
					<col width="28"/>
					<col width="15"/>
					<col width="28"/>
					<col/>
				</colgroup>
				';
				$counter = 0;
				foreach($data['spiele'] as $typ => $spiele){
					if(count($spiele)==0){
						continue;
					}

					$str_titel = ($typ=='heute') ? date('d.m.Y', time()) : date('d.m.Y', strtotime('+1 day', time()));

					if($counter>0){
						$str_spiele .= '
							<tr>
								<td colspan="8">&nbsp;</td>
							</tr>
						';
					}

					$str_spiele .= '
						<tr>
							<td colspan="8"><b>'.$str_titel.'</b></td>
						</tr>
						<tr>
							<td colspan="8" height="3"></td>
						</tr>
						<tr>
							<td colspan="8" height="1" style="background-color: #D81313;"></td>
						</tr>
						<tr>
							<td colspan="8" height="3"></td>
						</tr>
					';

					foreach($spiele as $spiel){
						$spiel_grp = ($spiel['spiel_typ']=='G') ? $lg_mails[$data['benutzer_sprache']]['gruppe_kurz'].' '.$spiel['spiel_gruppe'] : $lg_mails[$data['benutzer_sprache']]['liste_final'][$spiel['spiel_typ']];
						$stadion_zeitzone_differenz = (!is_null($spiel['stadion_zeitzone_differenz'])) ? (int)$spiel['stadion_zeitzone_differenz'] : 0;
						$spiel_zeit = date('H:i', strtotime($stadion_zeitzone_differenz+' '.(($stadion_zeitzone_differenz==1 || $stadion_zeitzone_differenz==-1) ? 'hour' : 'hours'), strtotime($spiel['spiel_datum'])));
						$spiel_home_name = $lg_mails[$data['benutzer_sprache']]['liste_land'][$spiel['spiel_home_land_code']];
						$spiel_away_name = $lg_mails[$data['benutzer_sprache']]['liste_land'][$spiel['spiel_away_land_code']];

						$str_datum = ($typ=='heute') ? 'H:i' : 'd.m.Y H:i';
						$spiel_bis = $lg_mails[$data['benutzer_sprache']]['bis'].' '.date($str_datum, strtotime($cfg_limit_spiel, strtotime($stadion_zeitzone_differenz+' '.(($stadion_zeitzone_differenz==1 || $stadion_zeitzone_differenz==-1) ? 'hour' : 'hours'), strtotime($spiel['spiel_datum']))));

						$str_spiele .= '
						<tr>
							<td>'.$spiel['spiel_id'].'</td>
							<td>'.$spiel_grp.'</td>
							<td>'.$spiel_zeit.'</td>
							<td align="right"><b>'.$spiel_home_name.'</b></td>
							<td align="right"><img src="cid:flag_'.$spiel['spiel_home_land_code'].'" alt="'.$spiel_home_name.'" title="'.$spiel_home_name.'"/></td>
							<td align="center">-</td>
							<td><img src="cid:flag_'.$spiel['spiel_away_land_code'].'" alt="'.$spiel_away_name.'" title="'.$spiel_away_name.'"/></td>
							<td><b>'.$spiel_away_name.'</b></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td colspan="7"><i>'.$spiel_bis.'</i></td>
						</tr>
						<tr>
							<td colspan="8" height="3"></td>
						</tr>
						<tr>
							<td colspan="8" height="1" style="background-color: #D81313;"></td>
						</tr>
						<tr>
							<td colspan="8" height="3"></td>
						</tr>
						';
					}

					$counter++;
				}
				$str_spiele .= '
			</table>
			';
		}

		$search = array(
			'%BENUTZERNAME%',
			'%GRUPPENPHASE%',
			'%SPIELE%',
		);
		$replace = array(
			$data['benutzer_benutzername'],
			$str_gruppenphase,
			$str_spiele,
		);

		$return = parse($return, $search, $replace);

		return $return;
	}

}