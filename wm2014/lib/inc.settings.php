<?php
$cfg_application = array(
	'version' => '3.5',
	'url_protocol' => 'http',
	'url' => 'www.oxx.ch/wm2014',

	'online' => true,

	'copyright' => 'created by <a href="http://www.davidedesantis.net" target="_blank">davidedesantis.net</a>',
);

$cfg_db = array(
	'host' => 'davidedesantis.net',
	'benutzer' => 'WC14',
	'kennwort' => 'SXoQ4hQh-7',
	'datenbank' => 'usr_web41_5',
	'prefix' => 'brazil2014_',
);

$cfg_sprache_standard = 'de';
$cfg_benutzer_quelle = 'oxx'; // dds oder oxx
$cfg_session = 'oxxbrazil2014';
$cfg_cookie = 'oxxbrazil2014';

$cfg_email = array(
	'from' => 'brazil2014@oxx.ch',
	'fromName' => 'OX Brazil 2014 betting centre',
	'to' => 'brazil2014@oxx.ch',
	'toName' => 'OX',
);

$cfg_replace = array(
	'tage' => array(
		'Sunday',
		'Monday',
		'Tuesday',
		'Wednesday',
		'Thursday',
		'Friday',
		'Saturday',
	),
	'monate' => array(
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December',
	),
);

$cfg_einsatz = array(
	'on' => false,
	'betrag' => 2500,
	'anteil' => array(
		50,
		25,
		15,
		5,
		5
	),
	'restrict_registration' => true,
	'hide_footer' => true,
);

$cfg_limit_spiel = '-2 hours';
$cfg_mehrere_zeitzonen = true;
$cfg_gruppenphase = '2014-06-12 22:00:00';

$cfg_google_analytics = array(
	'on' => false,
	'id' => '',
);
?>