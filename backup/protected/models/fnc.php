<?php
class fnc
{
        public static function getMoneyTable($hotels,$days_back=0,$days_prev = 7)
        {
             if($_SESSION['access']>1) die("Нет доступа");
            
             
            $days_back = $days_back*-1;
            $table = '<div id="main_chess">';
   
                $table .= '<div class="chess_body">';
                 //Подругаем гостиницы
                    $table .= '<div class="left_part">';
                    $table .= "<div class='first_cell'></div>";
                               foreach($hotels as $hotel)
                                {
                                     $array_hotels[] = $hotel->id;
                                     $table .= "<div class='cell_hotel first_cell'>{$hotel->name}</div>";
                                  
                                }
                     
                    $table .= '</div>';            
                    $table .= '<div class="right_part">';
                        $table .= '<div class="scrolling_part">';
                        
                        $table .= '<div class="part_row dates">';
                             // Генерируем период
                        for($day=$days_back;$day<=$days_prev;$day++)
                        {                          
                            
                                $got_day = date('d.m.Y',mktime(0, 0, 0, date("m")  , date("d")+$day, date("Y"))); 
                                $array_dates[] = $got_day;   
                                $linkz = CHtml::link('', array('hotelOrder/report', 'date'=>$got_day, 'type'=>'by_day'),array('title'=>'Посмотреть отчёт за день'));               
                                $table .= "<div>$linkz$got_day</div>";
                            
                            
                        }
                        $table .= '</div>';
                        $b=0;
                        foreach ($array_hotels as $id_hotel)
                        {
                            $table .= '<div rel="'.$b.'" class="part_row">';    
                            $b++;
                            
                            $n=1;
                                foreach($array_dates as $choose_date)
                                {
                                        $double_cell='';
                                        $array_rels = '';
                                        $tmp_rel='';
                                        $busy_now='';
                                        $free_slots='';
                                        $tmp_id = $id_hotel;
                                        $today = date('d.m.Y');
                                        $current_date =  date('Y-m-d',strtotime($choose_date));
                                        $cnt_reserved = HotelOrder::model()->count("(date(date_stay_begin)<='$current_date' and '$current_date'<=date(date_stay_finish)) and id_hotel=$id_hotel");
                                        switch ($cnt_reserved)
                                        {
                                            case '2':
                                                $right_cell = HotelOrder::model()->find(array('condition'=>"date(date_stay_begin)='$current_date' and id_hotel=$id_hotel",'order'=>'date_stay_begin DESC'));
                                                $left_cell = HotelOrder::model()->find(array('condition'=>"date(date_stay_finish)='$current_date' and id_hotel=$id_hotel",'order'=>'date_stay_finish ASC'));
                                                
                                                $users_in_hotel_left = ClientHotel::model()->count(array('condition'=>"date(date_stay_begin)<='$current_date' and '$current_date'<=date_stay_finish and id_order = {$left_cell->id} and status=0"));
                                            //    $free_slots_left = $left_cell->places-$users_in_hotel_left;
//                                                if($free_slots_left<=0) $free_slots_left='';                                                 
                                                $users_in_hotel_right = ClientHotel::model()->count(array('condition'=>"date(date_stay_begin)<='$current_date' and '$current_date'<=date_stay_finish and id_order = {$right_cell->id} and status=0"));
                                              //  $free_slots_right = $right_cell->places-$users_in_hotel_right;
//                                                if($free_slots_right<=0) $free_slots_right=''; 
$ticks_left = Ticks::model()->count(array('condition'=>"date(date_period_begin)<='$current_date' and '$current_date'<=date_period_finish  and `cl`.id_order={$left_cell->id}",'join'=>'INNER JOIN `client_hotel` `cl` ON `cl`.id=id_clienthotel'));
                                                
                                                $free_slots_left = $users_in_hotel_left-$ticks_left;
                                                if($free_slots_left<=0) 
                                                {
                                                    $free_slots_left='';
                                                    $small_class_left = 'live_small'; 
                                                }
                                                else
                                                {
                                                    $small_class_left ='red_zone';
                                                }
                                                
                                                $ticks_right = Ticks::model()->count(array('condition'=>"date(date_period_begin)<='$current_date' and '$current_date'<=date_period_finish  and `cl`.id_order={$right_cell->id}",'join'=>'INNER JOIN `client_hotel` `cl` ON `cl`.id=id_clienthotel'));
                                                
                                                $free_slots_right = $users_in_hotel_right-$ticks_right;
                                                if($free_slots_right<=0) 
                                                {
                                                    $free_slots_right='';
                                                    $small_class_right = 'live_small'; 
                                                }
                                                else
                                                {
                                                    $small_class_right ='red_zone';
                                                }
                                                
                                                $small_link_right = CHtml::link($free_slots_right, array('/hotelOrder/monitoring', 'id'=>$right_cell->id, 'date'=>$choose_date),array('alt'=>$right_cell->id));
                                                $small_link_left = CHtml::link($free_slots_left, array('/hotelOrder/monitoring', 'id'=>$left_cell->id, 'date'=>$choose_date),array('alt'=>$left_cell->id));
                                                
                                                
                                                
                                              //  $busy_now='';
//                                                    if($right_cell->status==1) $small_class_right='reserve_small';
//                                                    else $small_class_right='live_small';
//                                                $busy_now='';
//                                                    if($left_cell->status==1) $small_class_left='reserve_small';
//                                                    else $small_class_left='live_small';
                                                    
                                                    
                                                $link='<div class="small left '.$small_class_left.'">'.$small_link_left.'</div>';
                                                $link.='<div class="small right '.$small_class_right.'">'.$small_link_right.'</div>';
                                            break;
                                            case '1':
                                                
                                                $full_cell = HotelOrder::model()->find("(date(date_stay_begin)<='$current_date' and '$current_date'<=date(date_stay_finish)) and id_hotel=$id_hotel");
                                                $users_in_hotel = ClientHotel::model()->count(array('condition'=>"date(date_stay_begin)<='$current_date' and '$current_date'<=date_stay_finish and id_order = {$full_cell->id} and status=0"));
                                                $ticks = Ticks::model()->count(array('condition'=>"date(date_period_begin)<='$current_date' and '$current_date'<=date_period_finish  and `cl`.id_order={$full_cell->id}",'join'=>'INNER JOIN `client_hotel` `cl` ON `cl`.id=id_clienthotel'));
                                                
                                                $free_slots = $users_in_hotel-$ticks;
                                                if($free_slots<=0) 
                                                {
                                                    $free_slots='';
                                                    $busy_now .=' live_now'; 
                                                }
                                                else
                                                {
                                                    $busy_now .=' red_zone';
                                                }
                                                $array_rels =  array('alt'=>$full_cell->id);
                                                $full_cell->date_stay_begin=date('d.m.Y',strtotime($full_cell->date_stay_begin));
                                                $full_cell->date_stay_finish=date('d.m.Y',strtotime($full_cell->date_stay_finish));
                                               
                                                if($full_cell->date_stay_finish==$choose_date)
                                                {
                                                    $busy_now='';
                                                         if($free_slots<=0) 
                                                        {
                                                            $free_slots='';
                                                            $small_class =' live_small'; 
                                                        }
                                                        else
                                                        {
                                                            $small_class =' red_zone';
                                                        }
                                                    
                                                    $small_link_left = CHtml::link($free_slots, array('/hotelOrder/monitoring', 'id'=>$full_cell->id, 'date'=>$choose_date),$array_rels);
                                                    $small_link_right = CHtml::link('', array('/hotelOrder/reserve', 'id'=>$tmp_id, 'date'=>$choose_date));
                                                    $link='<div class="small left '.$small_class.'">'.$small_link_left.'</div>';
                                                    $link.='<div class="small right free_small">'.$small_link_right.'</div>';
                                                }                                                
                                                elseif($full_cell->date_stay_begin==$choose_date)
                                                {
                                                    $busy_now='';
                                                     if($free_slots<=0) 
                                                        {
                                                            $free_slots='';
                                                            $small_class =' live_small'; 
                                                        }
                                                        else
                                                        {
                                                            $small_class =' red_zone';
                                                        }
                                                     $small_link_right = CHtml::link($free_slots, array('/hotelOrder/monitoring', 'id'=>$full_cell->id, 'date'=>$choose_date),$array_rels);
                                                    $small_link_left = CHtml::link('', array('/hotelOrder/reserve', 'id'=>$tmp_id, 'date'=>$choose_date));
                                                    $link='<div class="small left">'.$small_link_left.'</div>';
                                                    $link.='<div class="small right '.$small_class.'">'.$small_link_right.'</div>';
                                                }
                                                else
                                                {
                                                    
                                                    $link=CHtml::link($free_slots, array('/hotelOrder/monitoring', 'id'=>$full_cell->id, 'date'=>$choose_date),$array_rels);
                                                }
                                            break;
                                            case '0':
                                                if($choose_date==$today)
                                                $follow_link = '/hotelOrder/create';
                                                else
                                                $follow_link = '/hotelOrder/reserve';
                                                $link = CHtml::link($free_slots, array($follow_link, 'id'=>$tmp_id, 'date'=>$choose_date),$array_rels);
                                            break;
                                        }
                                         $table .= "<div rel='$n' class='call_fancy$busy_now'>$link</div>";
                                         $n++;
                                }
                               
                            $table .= '</div>';
                        }
                        
                        $table .= '</div>';                     
                    $table .= '</div>';
                
    
                $table .= '</div>';         
          

            
            $table .= '</div>';
            
            return $table;
        }
        
        
        public static function getTable($hotels,$days_back=0,$days_prev = 7)
        {
             
            $days_back = $days_back*-1;
            $table = '<div id="main_chess">';
   
                $table .= '<div class="chess_body">';
                 //Подругаем гостиницы
                    $table .= '<div class="left_part">';
                    $table .= "<div class='first_cell'></div>";
                               foreach($hotels as $hotel)
                                {
                                     $array_hotels[] = $hotel->id;
                                     if($hotel->dirty==1) $dirty=' dirty_room';
                                     else $dirty='';
                                     $table .= "<div class='cell_hotel first_cell$dirty'><a href='?r=hotels/dirty&id={$hotel->id}'></a>{$hotel->name}</div>";
                                  
                                }
                     
                    $table .= '</div>';            
                    $table .= '<div class="right_part">';
                        $table .= '<div class="scrolling_part">';
                        $table .= '<div class="relation_part">';
                        $table .= '<div class="part_row dates">';
                             // Генерируем период
                        for($day=$days_back;$day<=$days_prev;$day++)
                        {                          
                            
                                $got_day = date('d.m.Y',mktime(0, 0, 0, date("m")  , date("d")+$day, date("Y"))); 
                                $eng_day =  date('D',strtotime($got_day));
                                $day_rus = fnc::getRealDay($eng_day);                                
                                $array_dates[] = $got_day;   
                                $today_now = date('d.m.Y');
                                if($got_day==$today_now) $class='today';
                                else $class='';
                                $linkz = CHtml::link('', array('hotelOrder/report', 'date'=>$got_day, 'type'=>'by_day'),array('title'=>'Посмотреть отчёт за день'));               
                                $table .= "<div class='$class'>$linkz$got_day <br><span>$day_rus</span></div>";
                            
                            
                        }
                        $table .= '</div>';
                        $b=0;
                        $table .= '<div style="height:34px">';  
                        $table .= '</div>';                          
                        foreach ($array_hotels as $id_hotel)
                        {
                            $table .= '<div rel="'.$b.'" class="part_row">';    
                            $b++;
                            
                            $n=1;
                                foreach($array_dates as $choose_date)
                                {
                                        $double_cell='';
                                        $array_rels = '';
                                        $tmp_rel='';
                                        $busy_now='';
                                        $free_slots='';
                                        $tmp_id = $id_hotel;
                                        $today = date('d.m.Y');
                                        $current_date =  date('Y-m-d',strtotime($choose_date));
                                        $cnt_reserved = HotelOrder::model()->count("(date(date_stay_begin)<='$current_date' and '$current_date'<=date(date_stay_finish)) and id_hotel=$id_hotel");
                                        switch ($cnt_reserved)
                                        {
                                            case '2':
                                                $right_cell = HotelOrder::model()->find(array('condition'=>"date(date_stay_begin)='$current_date' and id_hotel=$id_hotel",'order'=>'date_stay_begin DESC'));
                                                $left_cell = HotelOrder::model()->find(array('condition'=>"date(date_stay_finish)='$current_date' and id_hotel=$id_hotel",'order'=>'date_stay_finish ASC'));
                                                
                                                $users_in_hotel_left = ClientHotel::model()->count(array('condition'=>"date(date_stay_begin)<='$current_date' and '$current_date'<=date_stay_finish and id_order = {$left_cell->id} and status=0"));
                                                $free_slots_left = $left_cell->places-$users_in_hotel_left;
                                                if($free_slots_left<=0) $free_slots_left='';                                                 
                                                $users_in_hotel_right = ClientHotel::model()->count(array('condition'=>"date(date_stay_begin)<='$current_date' and '$current_date'<=date_stay_finish and id_order = {$right_cell->id} and status=0"));
                                                
                                                 switch($left_cell->status)
                                                    {
                                                         case '2':
                                                                $free_slots_left='';
                                                                $small_class_left='was_users';
                                                        break;
                                                         case '1':
                                                                $small_class_left='reserve_small';
                                                        break;
                                                         case '0':
                                                                $small_class_left='live_small';  
                                                        break;
                                                    }
                                                    
                                                        switch($right_cell->status)
                                                    {
                                                         case '2':
                                                                $free_slots_right='';
                                                                $small_class_right='was_users';
                                                        break;
                                                         case '1':
                                                                $small_class_right='reserve_small';
                                                        break;
                                                         case '0':
                                                                $small_class_right='live_small';  
                                                        break;
                                                    }                                                                                                                                                
                                                $free_slots_right = $right_cell->places-$users_in_hotel_right;
                                                if($free_slots_right<=0) $free_slots_right=''; 
                                                $small_link_right = CHtml::link($free_slots_right, array('/hotelOrder/monitoring', 'id'=>$right_cell->id, 'date'=>$choose_date),array('alt'=>$right_cell->id));
                                                $small_link_left = CHtml::link($free_slots_left, array('/hotelOrder/monitoring', 'id'=>$left_cell->id, 'date'=>$choose_date),array('alt'=>$left_cell->id));
                                                $busy_now='';
                                       
                                                    
                                                                                                                                                            
                                                    
                                                    
                                                $link='<div class="small left '.$small_class_left.'">'.$small_link_left.'</div>';
                                                $link.='<div class="small right '.$small_class_right.'">'.$small_link_right.'</div>';
                                            break;
                                            case '1':
                                                
                                                $full_cell = HotelOrder::model()->find("(date(date_stay_begin)<='$current_date' and '$current_date'<=date(date_stay_finish)) and id_hotel=$id_hotel");
                                                $users_in_hotel = ClientHotel::model()->count(array('condition'=>"date(date_stay_begin)<='$current_date' and '$current_date'<=date_stay_finish and id_order = {$full_cell->id} and status=0"));
                                                $free_slots = $full_cell->places-$users_in_hotel;
                                                if($free_slots<=0) $free_slots=''; 
                                                $array_rels =  array('alt'=>$full_cell->id);
                                                $full_cell->date_stay_begin=date('d.m.Y',strtotime($full_cell->date_stay_begin));
                                                $full_cell->date_stay_finish=date('d.m.Y',strtotime($full_cell->date_stay_finish));
                                       
                                                switch($full_cell->status)
                                                    {
                                                         case '2':
                                                                $free_slots='';
                                                                $busy_now.=' was_users';
                                                        break;
                                                         case '1':
                                                                $busy_now.='  busy_cell';
                                                        break;
                                                         case '0':
                                                                $busy_now.=' live_now';  
                                                        break;
                                                    }
                                                if($full_cell->date_stay_finish==$choose_date)
                                                {
                                                    $busy_now='';
                                                   
                                                    switch($full_cell->status)
                                                    {
                                                         case '2':
                                                                $free_slots='';
                                                                $small_class='was_users';
                                                        break;
                                                         case '1':
                                                                $small_class='reserve_small';
                                                        break;
                                                         case '0':
                                                                $small_class='live_small';  
                                                        break;
                                                    }
                                                    
                                                    $small_link_left = CHtml::link($free_slots, array('/hotelOrder/monitoring', 'id'=>$full_cell->id, 'date'=>$choose_date),$array_rels);
                                                    $small_link_right = CHtml::link('', array('/hotelOrder/reserve', 'id'=>$tmp_id, 'date'=>$choose_date));
                                                    $link='<div class="small left '.$small_class.'">'.$small_link_left.'</div>';
                                                    $link.='<div class="small right free_small">'.$small_link_right.'</div>';
                                                }                                                
                                                elseif($full_cell->date_stay_begin==$choose_date)
                                                {
                                                    $busy_now='';
                                                    switch($full_cell->status)
                                                    {
                                                         case '2':
                                                                $free_slots='';
                                                                $small_class='was_users';
                                                        break;
                                                         case '1':
                                                                $small_class='reserve_small';
                                                        break;
                                                         case '0':
                                                                $small_class='live_small';  
                                                        break;
                                                    }
                                                     $small_link_right = CHtml::link($free_slots, array('/hotelOrder/monitoring', 'id'=>$full_cell->id, 'date'=>$choose_date),$array_rels);
                                                    $small_link_left = CHtml::link('', array('/hotelOrder/reserve', 'id'=>$tmp_id, 'date'=>$choose_date));
                                                    $link='<div class="small left">'.$small_link_left.'</div>';
                                                    $link.='<div class="small right '.$small_class.'">'.$small_link_right.'</div>';
                                                }
                                                else
                                                {
                                                    
                                                    $link=CHtml::link($free_slots, array('/hotelOrder/monitoring', 'id'=>$full_cell->id, 'date'=>$choose_date),$array_rels);
                                                }
                                            break;
                                            case '0':
                                                if($choose_date==$today)
                                                $follow_link = '/hotelOrder/create';
                                                else
                                                $follow_link = '/hotelOrder/reserve';
                                                $link = CHtml::link($free_slots, array($follow_link, 'id'=>$tmp_id, 'date'=>$choose_date),$array_rels);
                                            break;
                                        }
                                         $table .= "<div rel='$n' class='call_fancy$busy_now'>$link</div>";
                                         $n++;
                                }
                               
                            $table .= '</div>';
                        }
                         $table .= '</div>';          
                        $table .= '</div>';                     
                    $table .= '</div>';
                
    
                $table .= '</div>';         
          

            
            $table .= '</div>';
            
            return $table;
        }
        
        public static function getTableLitte($hotels,$days_back=0,$days_prev = 7)
        {
             
            $days_back = $days_back*-1;
            $table = '<div id="main_chess">';
   
                $table .= '<div class="chess_body">';
                 //Подругаем гостиницы
                    $table .= '<div class="left_part">';
                    $table .= "<div class='first_cell'></div>";
                               foreach($hotels as $hotel)
                                {
                                     $array_hotels[] = $hotel->id;
                                     if($hotel->dirty==1) $dirty=' dirty_room';
                                     else $dirty='';
                                   //  $linky = "<a href='?r=hotels/dirty&id={$hotel->id}'></a>";
                                     $table .= "<div class='cell_hotel first_cell$dirty'>$linky{$hotel->name}</div>";
                                  
                                }
                     
                    $table .= '</div>';            
                    $table .= '<div class="right_part">';
                        $table .= '<div class="scrolling_part">';
                        $table .= '<div class="relation_part">';
                        $table .= '<div class="part_row dates">';
                             // Генерируем период
                        for($day=$days_back;$day<=$days_prev;$day++)
                        {                          
                            
                                $got_day = date('d.m.Y',mktime(0, 0, 0, date("m")  , date("d")+$day, date("Y"))); 
                                $eng_day =  date('D',strtotime($got_day));
                                $day_rus = fnc::getRealDay($eng_day);                                
                                $array_dates[] = $got_day;   
                                $today_now = date('d.m.Y');
                                if($got_day==$today_now) $class='today';
                                else $class='';
                              //  $linkz = CHtml::link('', array('hotelOrder/report', 'date'=>$got_day, 'type'=>'by_day'),array('title'=>'Посмотреть отчёт за день'));               
                                $table .= "<div class='$class'>$linkz$got_day <br><span>$day_rus</span></div>";
                            
                            
                        }
                        $table .= '</div>';
                        $b=0;
                        $table .= '<div style="height:34px">';  
                        $table .= '</div>';                          
                        foreach ($array_hotels as $id_hotel)
                        {
                            $table .= '<div rel="'.$b.'" class="part_row">';    
                            $b++;
                            
                            $n=1;
                                foreach($array_dates as $choose_date)
                                {
                                        $double_cell='';
                                        $array_rels = '';
                                        $tmp_rel='';
                                        $link = '';
                                        $busy_now='';
                                        $free_slots='';
                                        $tmp_id = $id_hotel;
                                        $today = date('d.m.Y');
                                        $current_date =  date('Y-m-d',strtotime($choose_date));
                                        $cnt_reserved = HotelOrder::model()->count("(date(date_stay_begin)<='$current_date' and '$current_date'<=date(date_stay_finish)) and id_hotel=$id_hotel");
                                        switch ($cnt_reserved)
                                        {
                                            case '2':
                                                $right_cell = HotelOrder::model()->find(array('condition'=>"date(date_stay_begin)='$current_date' and id_hotel=$id_hotel",'order'=>'date_stay_begin DESC'));
                                                $left_cell = HotelOrder::model()->find(array('condition'=>"date(date_stay_finish)='$current_date' and id_hotel=$id_hotel",'order'=>'date_stay_finish ASC'));
                                                
                                                $users_in_hotel_left = ClientHotel::model()->count(array('condition'=>"date(date_stay_begin)<='$current_date' and '$current_date'<=date_stay_finish and id_order = {$left_cell->id} and status=0"));
                                                $free_slots_left = $left_cell->places-$users_in_hotel_left;
                                                if($free_slots_left<=0) $free_slots_left='';                                                 
                                                $users_in_hotel_right = ClientHotel::model()->count(array('condition'=>"date(date_stay_begin)<='$current_date' and '$current_date'<=date_stay_finish and id_order = {$right_cell->id} and status=0"));
                                                
                                                 switch($left_cell->status)
                                                    {
                                                         case '2':
                                                                $free_slots_left='';
                                                                $small_class_left='was_users';
                                                        break;
                                                         case '1':
                                                                $small_class_left='reserve_small';
                                                        break;
                                                         case '0':
                                                                $small_class_left='live_small';  
                                                        break;
                                                    }
                                                    
                                                        switch($right_cell->status)
                                                    {
                                                         case '2':
                                                                $free_slots_right='';
                                                                $small_class_right='was_users';
                                                        break;
                                                         case '1':
                                                                $small_class_right='reserve_small';
                                                        break;
                                                         case '0':
                                                                $small_class_right='live_small';  
                                                        break;
                                                    }                                                                                                                                                
                                                $free_slots_right = $right_cell->places-$users_in_hotel_right;
                                                if($free_slots_right<=0) $free_slots_right=''; 
                                                $small_link_right = CHtml::link($free_slots_right, array('/hotelOrder/monitoring', 'id'=>$right_cell->id, 'date'=>$choose_date),array('alt'=>$right_cell->id));
                                                $small_link_left = CHtml::link($free_slots_left, array('/hotelOrder/monitoring', 'id'=>$left_cell->id, 'date'=>$choose_date),array('alt'=>$left_cell->id));
                                                $busy_now='';
                                       
                                                    
                                                                                                                                                            
                                                    
                                                    
                                                $link='<div class="small left '.$small_class_left.'"></div>';
                                                $link.='<div class="small right '.$small_class_right.'"></div>';
                                            break;
                                            case '1':
                                                
                                                $full_cell = HotelOrder::model()->find("(date(date_stay_begin)<='$current_date' and '$current_date'<=date(date_stay_finish)) and id_hotel=$id_hotel");
                                                $users_in_hotel = ClientHotel::model()->count(array('condition'=>"date(date_stay_begin)<='$current_date' and '$current_date'<=date_stay_finish and id_order = {$full_cell->id} and status=0"));
                                                $free_slots = $full_cell->places-$users_in_hotel;
                                                if($free_slots<=0) $free_slots=''; 
                                                $array_rels =  array('alt'=>$full_cell->id);
                                                $full_cell->date_stay_begin=date('d.m.Y',strtotime($full_cell->date_stay_begin));
                                                $full_cell->date_stay_finish=date('d.m.Y',strtotime($full_cell->date_stay_finish));
                                       
                                                switch($full_cell->status)
                                                    {
                                                         case '2':
                                                                $free_slots='';
                                                                $busy_now.=' was_users';
                                                        break;
                                                         case '1':
                                                                $busy_now.='  busy_cell';
                                                        break;
                                                         case '0':
                                                                $busy_now.=' live_now';  
                                                        break;
                                                    }
                                                if($full_cell->date_stay_finish==$choose_date)
                                                {
                                                    $busy_now='';
                                                   
                                                    switch($full_cell->status)
                                                    {
                                                         case '2':
                                                                $free_slots='';
                                                                $small_class='was_users';
                                                        break;
                                                         case '1':
                                                                $small_class='reserve_small';
                                                        break;
                                                         case '0':
                                                                $small_class='live_small';  
                                                        break;
                                                    }
                                                    
                                                    $small_link_left = CHtml::link($free_slots, array('/hotelOrder/monitoring', 'id'=>$full_cell->id, 'date'=>$choose_date),$array_rels);
                                                    $small_link_right = CHtml::link('', array('/hotelOrder/reserve', 'id'=>$tmp_id, 'date'=>$choose_date));
                                                    $link='<div class="small left '.$small_class.'"></div>';
                                                    $link.='<div class="small right free_small"></div>';
                                                }                                                
                                                elseif($full_cell->date_stay_begin==$choose_date)
                                                {
                                                    $busy_now='';
                                                    switch($full_cell->status)
                                                    {
                                                         case '2':
                                                                $free_slots='';
                                                                $small_class='was_users';
                                                        break;
                                                         case '1':
                                                                $small_class='reserve_small';
                                                        break;
                                                         case '0':
                                                                $small_class='live_small';  
                                                        break;
                                                    }
                                                     $small_link_right = CHtml::link($free_slots, array('/hotelOrder/monitoring', 'id'=>$full_cell->id, 'date'=>$choose_date),$array_rels);
                                                    $small_link_left = CHtml::link('', array('/hotelOrder/reserve', 'id'=>$tmp_id, 'date'=>$choose_date));
                                                    $link='<div class="small left"></div>';
                                                    $link.='<div class="small right '.$small_class.'"></div>';
                                                }
                                                else
                                                {
                                                    
                                                    //$link=CHtml::link($free_slots, array('/hotelOrder/monitoring', 'id'=>$full_cell->id, 'date'=>$choose_date),$array_rels);
                                                }
                                            break;
                                            case '0':
                                                if($choose_date==$today)
                                                $follow_link = '/hotelOrder/create';
                                                else
                                                $follow_link = '/hotelOrder/reserve';
                                              //  $link = CHtml::link($free_slots, array($follow_link, 'id'=>$tmp_id, 'date'=>$choose_date),$array_rels);
                                            break;
                                        }
                                         $table .= "<div rel='$n' class='call_fancy$busy_now'>$link</div>";
                                         $n++;
                                }
                               
                            $table .= '</div>';
                        }
                         $table .= '</div>';          
                        $table .= '</div>';                     
                    $table .= '</div>';
                
    
                $table .= '</div>';         
          

            
            $table .= '</div>';
            
            return $table;
        }
        
        public static function getStatus($status)
        {
             $items = array(0=>'Заселение в',1=>'Бронирование');             
             return $items[$status];
        }
        
        public static function getHotelCategory($id_category)
        {
            switch ($id_category)
            {
                case 1:
                return array(1=>'1',2=>'2',3=>'3');
                break;
                
                case 2:
                return array(1=>'1',2=>'2',3=>'3',4=>'4',5=>'5');
                break;
                
                case 3:
                return array(1=>'1',2=>'2',3=>'3',4=>'4',5=>'5',6=>'6');
                break;
            }
        }
        
        public static function getCategory()
        {
            return array(0=>'Выберите квартиру',1=>'Однокомнатные',2=>'Двухкомнатные',3=>'Трехкомнатные');
        }
        
        public static function getRealWord($number,$string_for_1='день',$string_for_2='дня',$string_for_6='дней')
        {
           

            if($number<=20)
            {
                switch ($number)
                {
                    case 1:
                    $number .= ' '.$string_for_1; 
                    break;                   
                    case 2:
                    $number .= ' '.$string_for_2; 
                    break;
                    case 3:
                    $number .= ' '.$string_for_2; 
                    break;
                    case 4:
                    $number .= ' '.$string_for_2; 
                    break;
                    default:
                    $number .= ' '.$string_for_6; 
                    break;
                }
            }
            else
            {
                switch (substr($number,0,-1))
                {
                    case 1:
                    $number .= ' '.$string_for_1; 
                    break;
                    case 5:
                    $number .= ' '.$string_for_1; 
                    break;
                    case 2:
                    $number .= ' '.$string_for_2; 
                    break;
                    case 3:
                    $number .= ' '.$string_for_2; 
                    break;
                    case 4:
                    $number .= ' '.$string_for_2; 
                    break;
                    default:
                    $number .= ' '.$string_for_6; 
                    break;
                }
            }
            return $number;
        }
        
        
        
        
        public static function loadCalendar($id_order,$now_mounth=false,$now_year=false)
        {
           $list_mounth = array('Jan'=>'Январь','Feb'=>'Февраль','Mar'=>'Март','Apr'=>'Апрель','May'=>'Май','Jun'=>'Июнь','Jul'=>'Июль','Aug'=>'Август','Sep'=>'Сентябрь','Oct'=>'Октябрь','Nov'=>'Ноябрь','Dec'=>'Декабрь');
            if(!isset($id_order)) die('Cant load My calendar');
            $order = HotelOrder::model()->findByPk($id_order);            
          //  $days_list = array('Mon'=>'Пн','Tue'=>'Вт','Wed'=>'Ср','Thu'=>'Чт','Fri'=>'Пт','Sat'=>'Сб','Sun'=>'Вс');
            $days_list_number = array('Mon'=>'1','Tue'=>'2','Wed'=>'3','Thu'=>'4','Fri'=>'5','Sat'=>'6','Sun'=>'7');
            if(!$now_mounth) $now_mounth = date('m');
            if(!$now_year) $now_year = date('Y');
           
           
            $days_cnt = cal_days_in_month(CAL_GREGORIAN, $now_mounth, $now_year);
            
            for($i=0;$i<$days_cnt;$i++)
            {
                $day_of_week = date('D',strtotime("+$i day".'01.'.$now_mounth.'.'.$now_year));                
                $date_for_mysql = date('Y-m-d',strtotime("+$i day".'01.'.$now_mounth.'.'.$now_year));   
                $full_date = date('d.m.Y',strtotime("+$i day".'01.'.$now_mounth.'.'.$now_year));  
                $num = $days_list_number[$day_of_week];
                $days[][$day_of_week] = $full_date; 
            }
            $word_mn = date('M');
            $mn = $list_mounth[$word_mn];
            echo '<div class="calendar">
<div class="head_cal">'.$mn.' '.$now_year.'</div>';
            $ORDER = HotelOrder::model()->findByPk($id_order);
            foreach ($days as $cur_day)
            {
                foreach ($cur_day as $key=>$day)
                {
                    $class='';
                    $week = self::getRussianDay($key);
                    $current_day =  date('d',strtotime($day));
                    $current_date =  date('Y-m-d',strtotime($day));
                    $ORDER->date_stay_begin =  date('Y-m-d',strtotime($ORDER->date_stay_begin));
                    $ORDER->date_stay_finish =  date('Y-m-d',strtotime($ORDER->date_stay_finish));                                        
                    if($ORDER->date_stay_begin<=$current_date and $current_date<=$ORDER->date_stay_finish)
                       echo '<div class="column_cal"><div class="thead_cell">'.$week.'</div><div class="cell_cal busy_date"><a href="/?r=hotelOrder/monitoring&id='.$id_order.'&date='.$day.'">'.$current_day.'</a></div></div>';
                       else
                       echo '<div class="column_cal"><div class="thead_cell">'.$week.'</div><div class="cell_cal">'.$current_day.'</div></div>';
                                    
                }                
                            
            }            
echo '</div>';
           // echo $days = idate("d", $full_date);

        }
        
        public static function getRussianDay($day)
        {
            $days_list = array('Mon'=>'Пн','Tue'=>'Вт','Wed'=>'Ср','Thu'=>'Чт','Fri'=>'Пт','Sat'=>'Сб','Sun'=>'Вс');
            return $days_list[$day];
        }
        
        public static function getInviters()
        {

            return array('Офис 1','Офис 2','Водитель','Безнал','Другие');
        }
        
             public static function getInviters_report($id_invite=false)
        {
           
            $list = array('Офис 1','Офис 2','Водитель','Безнал','Другие');
            if($id_invite or $id_invite==0)
            {
                return $list[$id_invite];
            }
            else
            return $list;
        }
        
        public static function generateDatesWay($date_begin,$date_finish)
        {
            $dates = array();
            $date_start = $date_begin;
            $date_begin = date('Y-m-d',strtotime($date_begin));
            $date_begin = strtotime($date_begin);
            $date_finish = date('Y-m-d',strtotime($date_finish));
            $date_finish = strtotime($date_finish);
            $cnt_days = ($date_finish-$date_begin)/86400;        // Промежуток в днях
            
            $result = '';
            $result .= '<div id="generated_way">';
            if($cnt_days<1)
            {
                 $cnt_days=1;
                 $dates[]=date('Y-m-d',strtotime($date_start));
            }
            else
            {
                     for($i=1;$i<=$cnt_days;$i++)
                {
                    $dates[]=date('Y-m-d',strtotime("+$i day".$date_start));
                }
            }
            
            $result .= '<div class="way_boxs">';
            foreach($dates as $key=>$day)
            {
                $result .= '<div class="box switch on">';
                $result .= "<label><input name='Ticks[days_list][$key]' checked='checked' type='checkbox' value='$day'>$day</label>";
                $result .= '</div>';    
            }
            $result .= '</div>';
              
            $result .= '</div>';    
                  
            return $result;
        }
        
        public static function intervalDays($date_begin,$date_finish)
        {
            if($date_begin!='' and $date_finish!='')
            {
                $date_begin = date('Y-m-d',strtotime($date_begin));
                $date_begin = strtotime($date_begin);           
                $date_finish = date('Y-m-d',strtotime($date_finish));
                $date_finish = strtotime($date_finish);
                $cnt_days = ($date_finish-$date_begin)/86400;        // Промежуток в днях
                if($cnt_days<1) $cnt_days=1;
                return $cnt_days;
            }
            else return 0;
            
        }
        
        public static function mpr($array)
        {
              echo  "<pre>";
                print_r($array);
               echo  "</pre>";
              
        }
        
        public static function getRealDay($day)
        {
            $days = array("Mon"=>"Понедельник", "Tue"=>"Вторник", "Wed"=>"Среда", "Thu"=>"Четверг", "Fri"=>"Пятница", "Sat"=>"Суббота", "Sun"=>"Воскресенье");
            return $days[$day];
        }
        
          public static function getHotelType()
        {
            return array('Гостиница','ТУЦ');
        }
        
      
}