<?php
jimport( 'joomla.application.component.view');

/*ACHTUNG es werden alle Listen hier geladen 
...nix MVC... gab irgendwelche Probleme mit SEF und es wurde immer dieselbe Liste angezeigt... 
aber so funktioniert es ...*/

class HofViewHof extends JView
{
	function display($tpl = null)
	{
		JHTML::_('behavior.tooltip');
			
		$db = JFactory::getDBO();		
		
        //Create bands-list
        $query = "select 
					catid,
					content_id, 
					date, 
					event, 
					bandname, 
					together_with, 
					type 
				  from 
					jos_hall_of_fame 
				  where 
					type = 'Konzert'
					and bandname <> '' 
				  order by 
					bandname, date";
		$db->setQuery($query);
		$bands = $db->loadRowList();
		foreach($bands as $band){
			$hof_class= ($band[2]>=date('Y-m-d')) ? ($band[2]==date('Y-m-d'))?'halloffame_today':'halloffame_highlite'  : 'halloffame';
			$datum = date("d.m.y", strtotime($band[2]));
			
			$str2band = ($band[5] =='')?'':'Zusammen mit: '.$band[5];
			$bandlist .= '<div class="'.$hof_class.'"><span class="hasTip" title="'.$band[4].' :: '.$datum.'<br>'.$str2band.'"><a href="index.php?option=com_content&catid='.$band[0].'&id='.$band[1].'&layout=oxevent&view=article"><span stype="white-space:nowrap;">'.$band[4].'</span></a></span></div>'."\n";
		}
        
        $this->assignRef( 'bands',    $bandlist );

        
        // Create djs-list
		$list .= '<div style="display:block">';
		
		$db = JFactory::getDBO();		
		$query = "select 
					catid,
					content_id, 
					date,
					event, 
					bandname, 
					together_with, 
					type 
				  from 
					jos_hall_of_fame 
				  where 
					type = 'Disco' 
					and bandname <> ''
				  order by 
					bandname, date";
		$db->setQuery($query);
		$bands = $db->loadRowList();
        $i=0;
        $groupclass="halloffame";
		foreach($bands as $band){
			$hof_class= ($band[2]>=date('Y-m-d')) ? ($band[2]==date('Y-m-d'))?'halloffame_today':'halloffame_highlite'  : 'halloffame';
            if($hof_class!="halloffame"){
                $groupclass=$hof_class;
            }
			$datum = date("d.m.y", strtotime($band[2]));

            $str2band = ($band[5] =='')?'':'Zusammen mit: '.$band[5];
            $event = '<div class="hof_event '.$hof_class.'"><span class="hasTip" title="'.$band[3].' :: '.$datum.'<br>'.$str2band.'"><a href="index.php?option=com_content&catid='.$band[0].'&id='.$band[1].'&layout=oxevent&view=article"><span stype="white-space:nowrap;">'.$band[4].'</span></a></span></div>'."\n";
            
            if($lastband==$band[4]){
                $j++;
            } else {
                $i++;
                $j=0;
                $groupclass="halloffame";
            }
            $lastband=$band[4];
            
            $djs[$i]['events'][$j]=$event;
            $djs[$i]['name']=str_replace(' ', '&nbsp;', $band[4]);
            $djs[$i]['groupclass']=$groupclass;
            
		}
        
        //output
        foreach($djs as $dj){
            if(count($dj['events'])==1){
                $djlist.=str_replace("hof_event ", "",$dj['events'][0]);
            } else {
                $djlist.='<div class="hof_group"><div class="'.$dj['groupclass'].' hof_grouptitle">'.$dj['name']."&nbsp;[".count($dj['events'])."]</div>";
                foreach($dj['events'] as $event){
                    $djlist.=$event;
                }
                $djlist.='</div>';
            }
        }
        
        
        $this->assignRef( 'djs',    $djlist );


        // Create movies-list
        $list = '<div style="display:block">'."\n";
		
		$db = JFactory::getDBO();		
		$query = "select 
					catid,
					content_id, 
					date, 
					event, 
					bandname, 
					together_with, 
					type 
				  from 
					jos_hall_of_fame 
				  where 
					type = 'Film'
					and bandname <> '' 
				  order by 
					bandname, date";
		$db->setQuery($query);
		$bands = $db->loadRowList();
		foreach($bands as $band){
			$hof_class= ($band[2]>=date('Y-m-d')) ? ($band[2]==date('Y-m-d'))?'halloffame_today':'halloffame_highlite'  : 'halloffame';
			$datum = date("d.m.y", strtotime($band[2]));
			
			if (strlen(str_replace(',', '', $band[5]))==strlen($band[5])) {
				$str2band = ($band[5] =='')?'':'Zweiter Film: '.$band[5];
			}else{
				$str2band = ($band[5] =='')?'':'Weitere Filme: '.$band[5];
			}
			
			$movielist .= '<div class="'.$hof_class.'"><span class="hasTip" title="'.$band[3].' :: '.$datum.'<br>'.$str2band.'"><a href="index.php?option=com_content&catid='.$band[0].'&id='.$band[1].'&layout=oxevent&view=article"><span stype="white-space:nowrap;">'.$band[4].'</span></a></span></div>'."\n";
		}
        		
		$this->assignRef( 'movies',	$movielist );
        

		parent::display($tpl);
	}
}
?>
