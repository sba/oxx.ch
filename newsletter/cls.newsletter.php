<?php
/**
* creation of ox html newsletter
*
* @package OX
* 
* @author Stefan Bauer <sbauer@oxx.ch>
* @copyright Copyright (c) 2013, stefan bauer
*/


class newsletter {
    
    /**
    * creates html header
    * @param string $monthtitle
    * @return html header
    */
    function getHeader($monthtitle){
        
        $intro = $this->getIntro();
        
        $doc = '<html><body>';
        $doc =  file_get_contents('html.header.inc');    
        $doc = str_replace('[MONTHTITLE]',$monthtitle,$doc);
        
        $doc = str_replace('[INTROTITLE]',utf8_encode($intro->Title),$doc);
        $doc = str_replace('[INTROCONTENT]',utf8_encode($intro->Content),$doc);
        
        return $doc;
    }
    
    /**
    * creates html footer
    * @return html footer
    */
    function getFooter(){
        $doc =  file_get_contents('html.footer.inc');    
        return $doc;
    }
    
    /**
    * returns html of all events
    * 
    * @param date $month as first day of month
    * @return string with html 
    */
    function getMonth($month){
        global $db;
        
        try {
            $doc = "";
            $events = $this->getEvents($month);
            $tmplEvent =  file_get_contents('html.event.inc');    
            
            if (is_array($events)){
	            foreach($events as $objEvent){
	            	$htmlEvent=$tmplEvent;
	            	if($objEvent->EventImageNewsletter!=""){
			        	$htmlEvent = str_replace('[EVENTIMAGE]','newsletter/'.$objEvent->EventImageNewsletter,$htmlEvent);
					} else {
						$htmlEvent = str_replace('[EVENTIMAGE]','programm/'.$objEvent->EventImage,$htmlEvent);
					}
			        $htmlEvent = str_replace('[TITLE]',$objEvent->Title,$htmlEvent);
			        $htmlEvent = str_replace('[SUBTITLE]',$objEvent->SubTitle,$htmlEvent);
			        //$eventDescription = str_replace('src="','src="../',$objEvent->EventDescription); //fix path to images
			        $eventDescription = preg_replace("/<img[^>]+\>/i", "",$objEvent->EventDescription); //remove images
			        $eventDescription = preg_replace("#<iframe[^>]+>.*?</iframe>#is", "",$eventDescription); //remove iframes, eg. youtube videos
			        $eventDescription = trim(preg_replace('/\s+/', ' ', $eventDescription)); //remove newlines in string, required that next regex to remove <br>'s will work
			        $eventDescription = preg_replace("#(( ){0,}<br( {0,})(/{0,1})>){1,}$#i", "",$eventDescription); //remove unnessesary <br>'s at the end of the text
			        $htmlEvent = str_replace('[EVENTDESCRIPTION]',$eventDescription, $htmlEvent);
			        $htmlEvent = str_replace('[EVENTDATE]',$this->getWeekdayGerman(date('w',strtotime($objEvent->EventDate)))
													 .', '.date('d.m.Y',strtotime($objEvent->EventDate)),$htmlEvent);
			        $htmlEvent = str_replace('[EVENTDOORS]',$objEvent->EventDoors,$htmlEvent);
			        $htmlEvent = str_replace('[EVENTURL]',$objEvent->EventURL,$htmlEvent);
					$doc .= $htmlEvent;	
	            }
			} else {
			    $htmlEvent = str_replace('[TITLE]','INVALID DATE',$tmplEvent);
			    $htmlEvent = str_replace('[SUBTITLE]','No events found',$htmlEvent);
			    $htmlEvent = str_replace('[EVENTIMAGE]','',$htmlEvent);
			    $htmlEvent = str_replace('[EVENTDESCRIPTION]','',$htmlEvent);
			    $htmlEvent = str_replace('[EVENTDATE]','',$htmlEvent);
				$doc .= $htmlEvent;					
			}
            
                    
        } catch(Exception $e) {
             echo $e->getMessage() .'<br>'."Error on line " . $e->getLine();
        }
        
        return $doc;
    }

    /**
    * returns array with events object
    * 
    * @param date $month
    * @return array event-object
    */
    private function getEvents($month){
        global $db;
        
        try{           
            $datMonth = strtotime($month);
            $datNextMonth = date('Y-m-d', mktime(0,0,0,date('m',$datMonth)+1,date('d',$datMonth),date('Y',$datMonth)));
            
            $sql = 'SELECT * FROM (
                        SELECT 
                            cc.title AS category
                            , a.id
                            , a.title
                            , a.introtext
                            , a.fulltext
                            , a.state
                            , a.catid
                            , a.attribs
                            , SUBSTRING(a.attribs, 12, 10) AS eventdate 
                        FROM
                            jos_content AS a 
                            LEFT JOIN jos_categories AS cc 
                                ON a.catid = cc.id 
                            LEFT JOIN jos_groups AS g 
                                ON a.access = g.id 
                        WHERE a.state = 1 
                            AND (
                                publish_up = "0000-00-00 00:00:00" 
                                OR publish_up <= NOW()
                            ) 
                            AND (
                                publish_down = "0000-00-00 00:00:00" 
                                OR publish_down >= NOW()
                            ) 
                            AND catid IN (58,59,60)
                        ORDER BY eventdate ) AS ox_events
                        WHERE   eventdate >= "'.$month.'" AND  eventdate < "'.$datNextMonth.'"';
            
            $query = $db->prepare($sql);
            $query->execute();
            $rows = $query->fetchAll();
            $query->closeCursor();

            foreach($rows as $row){    
                $attribs= $this->parseAttribs( $row['attribs']);

                $event = new stdClass();
                $event->Title = $this->encode($row['title']);
                //$event->SubTitle = utf8_encode(strip_tags($row['introtext']));
                $event->SubTitle = utf8_encode($row['introtext']);
                $event->EventDate = $this->encode($attribs['event_date']);
                $event->EventDate2 = $this->encode($attribs['event_date2']);
                $event->EventLiveFrom = "";
                $event->EventLiveTo = "";
                $event->EventType = $this->encode($attribs['event_type']);
                $event->EventIntro = $this->encode($attribs['event_intro']);
                $event->EventDoors = $this->encode($attribs['event_doors']);
                $event->EventImage =$this->encode($attribs['event_image']);
                $event->EventImageNewsletter =$this->encode($attribs['event_imagenewsletter']);
                //$event->EventDescription = utf8_encode(strip_tags($row['fulltext']));
                $event->EventDescription = utf8_encode($row['fulltext']);
                $event->EventURL = 'http://www.oxx.ch/index.php?option=com_content&catid='.$row['catid'].'&id='.$row['id'].'&layout=oxevent&view=article&Itemid=107';
                
                $events[++$i]= $event;
                
            }

			return  $events;

        } catch(Exception $e) {
            echo $e->getMessage() .'<br>'."Error on line " . $e->getLine();
        }
    }

    /**
    * returns intro-text from specific joomla-module
    * 
    * @return object html with intro-content (title and content)
    */
	function getIntro(){
        global $db;
        
        try{           
            
            $sql = 'SELECT title, content FROM jos_modules WHERE id = 104';
            
            $query = $db->prepare($sql);
            $query->execute();
            $row = $query->fetch();
            $query->closeCursor();

            $intro = new stdClass();
            $intro->Title = str_replace('(Newsletter-Intro)','',$row['title']);
            $intro->Content = $row['content'];

			return $intro;

        } catch(Exception $e) {
            echo $e->getMessage() .'<br>'."Error on line " . $e->getLine();
        }		
	}
    
    
    /**
    * save mofly-content as html file
    * 
    * @param string $doc containing complete file
    * @param string $filename    name of file to write output to
    * @return true on success
    */
    function saveHtml($doc, $filename ) {
        try {
            if(file_put_contents($filename, $doc)===false){
                return false;
            } else {
                return true;
            }
        } catch(Exception $e) {
            return false;
        }
    }
    
    
    function encode($string){
        return htmlspecialchars(utf8_encode($string));
    }
    
    function parseAttribs($attribs){
        $pairs = explode("\n", $attribs);
        foreach($pairs as $pair){
            $attrib = explode("=",$pair,2);
            $return[$attrib[0]] = $attrib[1];
        }
        
        return $return;
    }
	
    /**
    * returns german weekday
    * 
    * @param int $num_weekday as of date('w')
    * @return string So - Mo
    */
	function getWeekdayGerman($num_weekday){
		$german = array(
			0 => 'Sonntag'
			,1 => 'Montag'
			,2 => 'Dienstag'
			,3 => 'Mittwoch'
			,4 => 'Donnerstag'
			,5 => 'Freitag'
			,6 => 'Samstag'
		);
		
		return $german[$num_weekday];
	}
               
}


//instantiate class
$cls_newsletter = new newsletter();

?>
