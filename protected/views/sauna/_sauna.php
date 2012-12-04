    <div id="names_place">
        <div class="place_name">La Siesta</div>
    </div>
    
    <div id="time_list">
    
        <?$left_skala = ((int)date('H'))*60 + (int)date('i');?>
        <? $today = date('d',strtotime($date));    ?>
        <?if($today==date('d')){?>
            <div class="skala" style="left: <?=$left_skala?>px;"></div>
        <?}?>
        <div id="time_dates">
        <?        
        for($hour=0;$hour<24;$hour++)
        {
            if($hour<10) $hour = '0'.$hour;
            $time = "$hour:00";
            echo "<div class='time_block'>$time</div>";
        }
        ?>
        </div>
        <?
            $array_visitors = array();
           
              
            foreach ($visitors as $visitor)
            {
                
                $hour = date('H',strtotime($visitor->date_stay_begin));
                $day = date('d',strtotime($visitor->date_stay_begin));
                if($day==$today)
                {
                    $array_visitors[$hour]['begin']['day'] = $day;                
                    $array_visitors[$hour]['begin']['hour'] = $hour;
                    $array_visitors[$hour]['begin']['id'] = $visitor->id;
                    $array_visitors[$hour]['begin']['status'] = $visitor->status;
                    $array_visitors[$hour]['begin']['min'] = date('i',strtotime($visitor->date_stay_begin));
                }
                
                $hour = date('H',strtotime($visitor->date_stay_finish));
                $day_finish = date('d',strtotime($visitor->date_stay_finish));
                if($day_finish==$today)
                {
                    $array_visitors[$hour]['finish']['day'] = $day_finish;                
                    $array_visitors[$hour]['finish']['hour'] = $hour;
                    $array_visitors[$hour]['finish']['id'] = $visitor->id;
                    $array_visitors[$hour]['finish']['status'] = $visitor->status;
                    $array_visitors[$hour]['finish']['min'] = date('i',strtotime($visitor->date_stay_finish));
                    $statuses[$visitor->id] = $visitor->status;
                }
                
            }
      
        ?>
                
        <div class="line_info">        
        <?   
        
        $find_slise = Visitors::model()->find(array("condition"=>"date(date_stay_begin)<'$date' and (date(date_stay_finish)='$date' or date(date_stay_finish)>'$date')",'order'=>"date_stay_finish DESC"));
        
        $slide_id = (is_object($find_slise) ? $find_slise->id : false);  
        
        for($hour=0;$hour<24;$hour++)
        {
            
            $fill_min = 0;
            $quadrate = "";
            $border = '';
            
            $class = '';
            if($hour<10) $hour = "0$hour";
            
            if(count($array_visitors[$hour]['finish'])>0 and $today == $array_visitors[$hour]['finish']['day'])
            {
             
                    if($array_visitors[$hour]['finish']['min']!='00')
                    {
                        $link = CHtml::link('',array("/visitors/monitor/date/$date/time/$hour/id/{$array_visitors[$hour]['finish']['id']}"));// ссылка для мониторинга
                        $fill_min = $array_visitors[$hour]['finish']['min'];
                        switch ($array_visitors[$hour]['finish']['status'])
                        {
                            case 0:
                                $class = " reserve";
                            break;
                            case 1:
                                $class = " live";
                            break;
                            case 2:
                                $class = " prepay";
                            break;
                            case 3:
                                $class = " exit";
                            break;
                        }
                        
                        $quadrate .= "<div class='quadrate{$class}' alt='{$array_visitors[$hour]['finish']['id']}' style='width:{$fill_min}px;'>$link</div>";                       
                        
                        if($slide_id == $array_visitors[$hour]['finish']['id']) $slide_id = false;
                    }
                    else
                    {
                        if($slide_id == $array_visitors[$hour]['finish']['id']) $slide_id = false;
                    }  
                    
                    $class = '';                          
            }
         
            if(count($array_visitors[$hour]['begin'])>0 and $today  == $array_visitors[$hour]['begin']['day'])
            {
               
                if($fill_min==0)
                {
                   $fill_min = $array_visitors[$hour]['begin']['min'];
                   $link = CHtml::link('',array("/visitors/reserve/date/$date/time/$hour"));
                   $quadrate .= "<div class='quadrate' style='width:{$fill_min}px;'>$link</div>";
                   $link = CHtml::link('',array("/visitors/monitor/date/$date/time/$hour/id/{$array_visitors[$hour]['begin']['id']}"));// ссылка для мониторинга
                   $ostatok_v_minutah = 59-(int)$fill_min;
                   switch ($array_visitors[$hour]['begin']['status'])
                    {
                        case 0:
                                $class = " reserve";
                            break;
                            case 1:
                                $class = " live";
                            break;
                            case 2:
                                $class = " prepay";
                            break;
                            case 3:
                                $class = " exit";
                            break;
                    }
                    
                    $quadrate .= "<div class='quadrate border{$class}' alt='{$array_visitors[$hour]['begin']['id']}' style='width:{$ostatok_v_minutah}px;'>$link</div>";  
                    
                }
                else
                {
                     $promejyrok_free_time =  $array_visitors[$hour]['begin']['min']- $fill_min;
                     $fill_min = $array_visitors[$hour]['begin']['min'];
                     if($promejyrok_free_time>0)
                     {
                        $link = CHtml::link('',array("/visitors/reserve/date/$date/time/$hour"));
                        $quadrate .= "<div class='quadrate' style='width:{$promejyrok_free_time}px;'>$link</div>";
                     }
                     
                     $link = CHtml::link('',array("/visitors/monitor/date/$date/time/$hour/id/{$array_visitors[$hour]['begin']['id']}"));// ссылка для мониторинга
                   $ostatok_v_minutah = 59-(int)$fill_min;
                   switch ($array_visitors[$hour]['begin']['status'])
                    {
                            case 0:
                                $class = " reserve";
                            break;
                            case 1:
                                $class = " live";
                            break;
                            case 2:
                                $class = " prepay";
                            break;
                            case 3:
                                $class = " exit";
                            break;
                    }
                    
                    $quadrate .= "<div class='quadrate border{$class}' alt='{$array_visitors[$hour]['begin']['id']}' style='width:{$ostatok_v_minutah}px;'>$link</div>"; 
                }
                
                $slide_id = $array_visitors[$hour]['begin']['id'];
            }
            elseif($fill_min>0)
            {
                $link = CHtml::link('',array("/visitors/reserve/date/$date/time/$hour"));
                $ostatok_v_minutah = 59-(int)$fill_min;
                $quadrate .= "<div class='quadrate' style='width:{$ostatok_v_minutah}px;'>$link</div>";
            }
            
            
             
             if($fill_min==0)
             {
                if(is_numeric($slide_id))
                {
                    
                    if(is_array($statuses[$slide_id]))
                     $rly_status = $statuses[$slide_id];
                    else $rly_status = Visitors::model()->findByPk($slide_id)->status;
                      
                    
                    switch ($rly_status)
                    {
                        case 0:
                                $class = " reserve";
                            break;
                            case 1:
                                $class = " live";
                            break;
                            case 2:
                                $class = " prepay";
                            break;
                            case 3:
                                $class = " exit";
                            break;
                    }
                    $id = (is_numeric($array_visitors[$hour]['begin']['id']) ? $array_visitors[$hour]['begin']['id'] : $slide_id);
                    $link = CHtml::link('',array("/visitors/monitor/date/$date/time/$hour/id/{$id}"));// ссылка для мониторинга
                    $quadrate = "<div class='quadrate border{$class}' alt='{$slide_id}' style='width:59px;'>$link</div>"; 
                }
                else
                {
                    $link = CHtml::link('',array("/visitors/reserve/date/$date/time/$hour"));
                    $quadrate = "<div class='quadrate' style='width:59px;'>$link</div>";
                }
                
             }
             
            
            // echo ;
             
            if((isset($array_visitors[$hour+1]['begin']['id']) and $array_visitors[$hour+1]['begin']['min']=='00') or (isset($array_visitors[$hour+1]['finish']['id']) and $array_visitors[$hour+1]['finish']['id']=='00'))
            {
                $class = ' last_cell';
                $last_cell =false;
            }
           
            echo "<div class='reservation_slot{$class}'>$quadrate</div>";
        }
        ?>
        </div>
        
      
    </div>
    