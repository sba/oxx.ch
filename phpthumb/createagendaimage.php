<?php
	 
function createAgendaImage($agendaimage, $eventid){
	//get the agenda-image. if not exist create thumb
	$agendaimage_png = str_replace('.jpg','.png', $agendaimage);

	$agendapath = 'images/programm/';
	$agendapath_png = 'images/programm/agenda_thumbnails/';

	if (file_exists($agendapath_png. $eventid . '.png')){
		//agend-image is already generated
		$agendaimage_png = $agendapath_png. $eventid . '.png';
		
		//damit es möglich ist ein bild auszuwechseln wenn dieses bereits geniereit ist, müssen wir das id.png bild zuerst löschen
		if ($agendaimage=='- aktuelles Bild loeschen.jpg'){
			unlink($agendaimage_png);
			$agendaimage_png = false;
		}
	} else {
		if (file_exists($agendapath. $agendaimage)){
			//create png-thumbnail with transparent rounded corners
			require_once('phpthumb/phpthumb.class.php');
			$phpThumb = new phpThumb();

			$thumbnail_width = 135;
			$capture_raw_data = false; // set to true to insert to database rather than render to screen or file (see below)
			$phpThumb->resetObject();  // this is very important when using a single object to process multiple images
			$phpThumb->setSourceFilename($agendapath. $agendaimage); 
			$phpThumb->setParameter('w', $thumbnail_width);
			$phpThumb->setParameter('fltr','ric|12|12');
			$phpThumb->setParameter('f','png');
			$phpThumb->setParameter('config_output_format', 'png');
			$phpThumb->setParameter('config_imagemagick_path', '/usr/local/bin/convert');

			$output_filename = $agendapath_png. $eventid . '.png';
			if ($phpThumb->GenerateThumbnail()) {
				if ($phpThumb->RenderToFile($output_filename)){
					$agendaimage_png = $output_filename;
				} else {
					$output_filename = false;
				}
			} else {
				$output_filename = false;
			}
		} else {
			$agendaimage_png = false;
		}
	}
	return $agendaimage_png ;
}
?>