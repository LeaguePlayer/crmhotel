<?php
class fnc
{
        public static function definePlatformPC()
        {
            if(strpos($_SERVER['HTTP_USER_AGENT'],'Windows')===false)
             $result = false;  // ПЛАНШЕТ
             else $result = true; // PC
             return $result;
        }
        public static function getTable($days_back=0,$days_prev = 7,$left = -352)
        {
        
            $platform = self::definePlatformPC();
            $changer = Yii::app()->session['tyc_only'];
            $switch_homes = Yii::app()->session['all_homes'];
            if($switch_homes==1) $addCondition_home = " and `t`.id  not in (select distinct id_hotel from `hotel_order` where date(date_stay_begin)<=date(now()) and now()<=date_stay_finish and date(broken_finish)<date(now()) and (status = 0 or status =5 or status =4 or status =3) )";
            else $addCondition_home = "";
            
            $days_back = $days_back*-1;
            $today = date('d.m.Y');
             $sql_date_begin = date('Y-m-d',strtotime($days_back.' days '.$today));
             $sql_date_finish = date('Y-m-d',strtotime($days_prev.' days '.$today));
          
            
            if($changer==1)
                $hotels = Hotels::model()->cache(1999)->findAll(array('order'=>"default_type asc,id_cat asc,name asc",'condition'=>"1=1",'join'=>"inner join `hotel_order` `ho` on (`t`.id=`ho`.id_hotel and TYC=1 and `ho`.status!=2  and  ( ('$sql_date_begin'<=date(`ho`.date_stay_begin) and '$sql_date_finish'>=date(`ho`.date_stay_finish)) or ('$sql_date_finish'>=date(`ho`.date_stay_begin) and '$sql_date_finish'<=date(`ho`.date_stay_finish))  or ('$sql_date_begin'>=date(`ho`.date_stay_begin) and '$sql_date_begin'<=date(`ho`.date_stay_finish)) )) or `t`.default_type=1",'group'=>'`t`.id'));
            else
//                $hotels = Hotels::model()->cache(1999)->findAll(array('order'=>"default_type asc,id_cat asc,name asc",'condition'=>"default_type=$changer  $addCondition_home",'join'=>"left join `hotel_order` `ho` on `ho`.id_hotel = `t`.id",'group'=>"`t`.id"));
                $hotels = Hotels::model()->cache(1999)->findAll(array('order'=>"default_type asc,id_cat asc,name asc",'condition'=>"1=1 $addCondition_home",'join'=>"inner join `hotel_order` `ho` on (`t`.id=`ho`.id_hotel and TYC=0 and `ho`.status!=2  and  ( ('$sql_date_begin'<=date(`ho`.date_stay_begin) and '$sql_date_finish'>=date(`ho`.date_stay_finish)) or ('$sql_date_finish'>=date(`ho`.date_stay_begin) and '$sql_date_finish'<=date(`ho`.date_stay_finish))  or ('$sql_date_begin'>=date(`ho`.date_stay_begin) and '$sql_date_begin'<=date(`ho`.date_stay_finish)) )) or `t`.default_type=0",'group'=>'`t`.id'));
            
            
            if(count($hotels)==0)
                {
                    echo "<div style='text-align:center;padding-bottom:15px;'>Работает в режиме без фильрации</div>";
                    $hotels = Hotels::model()->cache(1999)->findAll(array('order'=>"default_type asc,id_cat asc,name asc"));
                }
                
              
            if(!is_numeric($left)) $left = -352;
             $first_show = false;
            
            $number=0;
            
            $table = '<div id="main_chess">';
   
                $table .= '<div class="chess_body">';
                 //Подругаем гостиницы
                    $table .= '<div class="left_part">';
                    $table .= "<div class='first_cell'></div>";
                    
                               foreach($hotels as $hotel)
                                { 
                                    $number++;
                                    if(!$first_show and $hotel->default_type==1)
                                    {
                                        $table .= "<span style='height:34px;display:block;'></span>";
                                        $first_show=true;
                                    }
                                
                                 
                                     
                                     $array_hotels[] = $hotel->id;
                                     $def_type[] = $hotel->default_type;
                                     
                                     if($hotel->dirty==1) $dirty='<span class="dirty_room"></span>';
                                     else $dirty='';
                                     
                                     if($hotel->bell!='') $message = '<span class="bell"></span>';
                                     else $message = '';
                                     
                                     if($hotel->quest!='') $quest = '<span class="remont"></span>';
                                     else $quest='';
                                     
                                     if($hotel->admin_message!='') $admin_message = '<span class="admin_message"></span>';
                                     else $admin_message='';
                                     
                                     if(!empty($quest) or !empty($message) or !empty($dirty) or !empty($admin_message))
                                        $inform_div = "<div class='head_icons'>{$dirty}{$message}{$quest}{$admin_message}</div>";
                                     else $inform_div = "";
                                     
                                     
                                    
                                     $hotel->name = str_replace(' ','_',$hotel->name);
                                     if($platform) $addClass=' pc';
                                     $wifi = ($hotel->wifi==1 ? ' wifi' : '');
                                     $table .= "<div class='col_$number cell_hotel{$addClass} first_cell{$wifi}'>{$inform_div}<a href='/hotels/dirty/id/{$hotel->id}'></a>{$hotel->id_cat}_{$hotel->name}</div>";
                                  
                                }
                                
                                $first_show=false;
                     
                    $table .= '</div>';            
                    $table .= '<div class="right_part">';
                        $table .= '<div class="scrolling_part" style="left: '.$left.'px;">';
                        $table .= '<div class="relation_part">';
                        $table .= '<div class="part_row dates">';
                            $table .= '<div class="part_row_dates_rel">';
                                $table .= '<div id="scroll_day" class="part_row_dates_abs"  style="left: '.$left.'px;">';
                                 // Генерируем период
                            for($day=$days_back;$day<=$days_prev;$day++)
                            {
                                
                                    $got_day = date('d.m.Y',mktime(0, 0, 0, date("m")  , date("d")+$day, date("Y"))); 
                                    $eng_day =  date('D',strtotime($got_day));
                                    $day_rus = fnc::getRealDay($eng_day);                                
                                    $array_dates[] = $got_day;   
                                    
                                    if($got_day==$today) $class='today';
                                    else $class='';
                                    
                                    if(self::definePlatformPC())
                                    {
                                        $target_my = '_self';
                                        $fancy_run_my = 'fancy_run';
                                    }
                                    else
                                    {
                                        $target_my = '_blank';
                                        $fancy_run_my = '';
                                    }
                                    
                                    $linkz = CHtml::link('', array('reports/get', 'date'=>$got_day, 'type'=>'by_day'),array('title'=>'Отчёт за '.$got_day,'target'=>$target_my,'class'=>$fancy_run_my));               
                                    $table .= "<div class='$class'>$linkz <span>{$got_day}</span> <br><span>$day_rus</span></div>";
                                
                                
                            }
                                $table .= '</div>';
                            $table .= '</div>';
                        $table .= '</div>';
                        $b=0;
                        $table .= '<div style="height:34px;">';  
                        $table .= '</div>';                          
                        foreach ($array_hotels as $id_hotel)
                        {
                            if(!$first_show and $def_type[$b]==1)
                                    {
                                        $table .= "<div class='line_del'></div>";
                                        $first_show=true;
                                    }
                            $busy_cell = array();
                            $table .= '<div rel="'.$b.'" class="part_row">';    
                            $b++;
                            $z=0;
                            $date_stay_begin_sql = date('Y-m-d',strtotime($array_dates[0]));
                            $date_stay_finish_sql = date('Y-m-d',strtotime($array_dates[count($array_dates)-1]));
                            $ways_reserved = HotelOrder::model()->findAll(array(
                            'condition'=>
                            "id_hotel=$id_hotel and  ( ('$date_stay_begin_sql'<=date(`t`.date_stay_begin) and '$date_stay_finish_sql'>=date(`t`.date_stay_finish)) or ('$date_stay_finish_sql'>=date(`t`.date_stay_begin) and '$date_stay_finish_sql'<=date(`t`.date_stay_finish))  or ('$date_stay_begin_sql'>=date(`t`.date_stay_begin) and '$date_stay_begin_sql'<=date(`t`.date_stay_finish)) )"
                         
                            ,'order'=>'`t`.date_stay_begin ASC','select'=>"*,to_days(`t`.date_stay_finish)-to_days(`t`.date_stay_begin) as tmp_halfmoney"));
                            $checker = 0;
                            
                            
                            
                            foreach ($ways_reserved as $way)
                            {
                                
                                $all_borders = ExtensionOrder::model()->cache(1000)->findAll(array('condition'=>"id_order = {$way->id}"));   
                                unset($bord_list);
                                if(count($all_borders)>0)
                                {
                                    foreach ($all_borders as $bord)
                                    {
                                        $cnvrt = date('Y-m-d',strtotime($bord->date_public));
                                        $bord_list[$cnvrt] = $bord->id;
                                    }
                                }
                                
                               
                                $way_current_date_begin = date('d.m.Y',strtotime($way->date_stay_begin));
                                $way_current_date_finish = date('d.m.Y',strtotime($way->date_stay_finish));
                                 if($way_current_date_finish==$way_current_date_begin) $full_days=0;
                                 else
                                 $full_days = fnc::intervalDays($way_current_date_begin,$way_current_date_finish);
                                 for ($a=0;$a<=$full_days;$a++)
                                 {    
                                   
                                   
                                    $day_in_array = date('d.m.Y',strtotime("+$a day".$way_current_date_begin));
                                    $day_sql = date('Y-m-d',strtotime("+$a day".$way_current_date_begin));
                                  //  $freeslots = ClientHotel::model()->count("id_order=$way->id and date(date_stay_begin)<='$day_sql' and '$day_sql'<=date(date_stay_finish)");
                                    $busy_cell[$day_in_array][$z]['id'] = $way->id;
                                    $busy_cell[$day_in_array][$z]['id_hotel'] = $way->id_hotel;
                                    $busy_cell[$day_in_array][$z]['status'] = $way->status;
                                    $busy_cell[$day_in_array][$z]['date_stay_begin'] = $way->date_stay_begin;
                                    $busy_cell[$day_in_array][$z]['date_stay_finish'] = $way->date_stay_finish;
                                    $busy_cell[$day_in_array][$z]['count_days'] = $way->tmp_halfmoney;
                                    $busy_cell[$day_in_array][$z]['places'] = $way->places;
                                    $busy_cell[$day_in_array][$z]['id_invite'] = $way->id_invite;
                                    $busy_cell[$day_in_array][$z]['create_time'] = $way->create_time;
                                    $busy_cell[$day_in_array][$z]['ring'] = $way->ring;
                                    $busy_cell[$day_in_array][$z]['remember_time'] = $way->remember_time;
                                    $busy_cell[$day_in_array][$z]['broken_begin'] = $way->broken_begin;
                                    $busy_cell[$day_in_array][$z]['TYC'] = $way->TYC;
                                    $busy_cell[$day_in_array][$z]['broken_finish'] = $way->broken_finish;
                                    $busy_cell[$day_in_array][$z]['date_cleaning'] = $way->date_cleaning;
                                    
                                       $busy_cell[$day_in_array][$z]['border_cell'] = $bord_list[$day_sql];
                                    //   echo $day_sql;
         //                              fnc::mpr($bord_list);die();
                                        //if(array_key_exists($day_sql,$bord_list))
                                      //  $busy_cell[$day_in_array][$z]['border_cell'] = ExtensionOrder::model()->find("date(date_public)='$day_sql' and id_order = $way->id")->id;                          
//                                        $busy_cell[$day_in_array][$z]['border_cell'] = '';  
                                         
                                    
                                      
                                   // if($way->places-$freeslots==0) $freeslots='';
//                                    else $freeslots =$way->places-$freeslots;
//                                    $busy_cell[$day_in_array][$z]['freeslots'] = $freeslots;
                                  //  $busy_cell[$day_in_array][$z]['days'] = 
                                 }
                                
                                 $z++;
                            }
                        
                            $n=1;
                            $ring_was=false;
                            $first_child = array();
                                foreach($array_dates as $choose_date)
                                {
                                   
                                    $link = '';
                                        $double_cell='<table><tr>';
                                        $array_rels = '';
                                        $tmp_rel='';
                                        $busy_now='';
                                        $free_slots='';
                                        $tmp_id = $id_hotel;  
                                        $generate_link = CHtml::link('', array('/hotelOrder/reserve', 'id'=>$tmp_id, 'date'=>$choose_date));                                      
                                        $current_date =  date('Y-m-d',strtotime($choose_date));
                                       
                                        $cnt_positions_in_cell = count($busy_cell[$choose_date]);
                                        if($cnt_positions_in_cell>0)
                                        {
                                            $lol=false;
                                            $days_checker = 0;
                                            foreach ($busy_cell[$choose_date] as $get_busy)
                                            {
                                                if($get_busy['count_days']==0) $days_checker++;
                                                
                                            }
                                           
                                            $current_date_first_day =  date('d.m.Y',strtotime($get_busy['date_stay_begin']));
                                                $current_date_last_day =  date('d.m.Y',strtotime($get_busy['date_stay_finish']));
                                                
                                                if(($cnt_positions_in_cell==1 and $current_date_first_day==$choose_date) or ($days_checker==count($busy_cell[$choose_date]))) 
                                                {
                                                   
                                                    $double_cell.="<td><div>$generate_link&nbsp;&nbsp;</div></td>";   
                                                }
                                          $cnts = 0;
                                          
                                            foreach ($busy_cell[$choose_date] as $get_busy)
                                            {
                                                
                                                $cnts++;
                                                $classic='';
                                                    $array_rels = array('alt'=>$get_busy['id']);
                                                    $text_in_link = '';
                                                    if($get_busy['remember_time']!='00-00-00 00:00:00' and date('d.m.Y',strtotime($get_busy['remember_time']))==$choose_date) 
                                                    {
                                                        $text_in_link = date('H:i',strtotime($get_busy['remember_time']));
                                                        $busy_now = self::cellColor($get_busy['status']);
                                                        $gen_left_pos = "<div class='$busy_now'>
<a class='' href='/hotelOrder/monitoring?id={$get_busy[id]}&date=$choose_date' alt='{$get_busy[id]}'></a>
</div>";
                                                        $double_cell.="<td>$gen_left_pos&nbsp;&nbsp;</td>";  
                                                    }
                                                    elseif(isset($get_busy['border_cell'])) $text_in_link ='<div class="positionacion"><span></span></div>';
//                                                    else $text_in_link=$get_busy['freeslots'];
                                                    $turbo_cell = '';
                                                    if($changer==1 and $get_busy['TYC'])
                                                    {
                                                        $cls = ClientHotel::model()->findAll(array('condition'=>"status = 0 and id_order={$get_busy['id']} and date(date_stay_begin)<='$current_date' and '$current_date'<=date(date_stay_finish)",'order'=>'date_stay_finish DESC'));
                                                        
                                                        $n =0;
                                                        foreach ($cls as $cl_one)
                                                        {
                                                            $get_period = Ticks::model()->find(array('select'=>"date(`t`.date_period_begin) as date_period_begin,(select date(`tt`.date_period_finish) from `ticks` `tt` where `tt`.status=1 and `tt`.id_clienthotel = {$cl_one->id} order by `tt`.date_period_finish DESC limit 1) as date_period_finish",'order'=>'`t`.date_period_begin ASC','condition'=>"`t`.status = 1 and`t`.id_clienthotel = {$cl_one->id}"));                                                           
                                                            $class='';
                                                            $n++;
                                                            if( ($current_date==date('Y-m-d',strtotime($cl_one->date_stay_finish)) ) )
                                                                { 
                                                                $class = ($cl_one->finally==0 ? ' leave_today through' : ' leave_today');
                                                                
                                                                }
                                                                if(strtotime($get_period->date_period_begin)<=strtotime($choose_date) and strtotime($choose_date)<=strtotime($get_period->date_period_finish))
                                                                {
                                                                    $class .= " got_money";
                                                                }
                                                            
                                                            $turbo_cell .= "<div class='turbo$class'>$n</div>";
                                                        }
                                                       $n++;
                                                        for($go = $n ; $go <=$get_busy['places'];$go++)
                                                        {
                                                            
                                                            $turbo_cell .= "<div class='turbo hidden'>{$go}</div>";
                                                        }
                                                       
                                                        $text_in_link = $turbo_cell;
                                                    }
                                                    
                                                    if(!in_array($get_busy['id'],$first_child) and $get_busy['status']==1 )                                                     
                                                    {
                                                        $first_child[] = $get_busy['id'];
                                                        $time_show = date('H:i',strtotime($get_busy['date_stay_begin']));
                                                        $text_in_link = "<div class='show_time'>{$time_show}</div>";
                                                    }
                                                    
                                                    
                                                    $check_date_leave = date('d.m.Y',strtotime($get_busy['date_stay_finish']));
                                                    if($choose_date==$check_date_leave) // проверяем последняя ячейка или нет
                                                    {
                                                        $check_hour_leave = date('H:i',strtotime($get_busy['date_stay_finish']));
                                                        if($check_hour_leave!='14:00') // проверяем отличается ли время выселения от 14:00, если да, напоминаем об этом на шахматке
                                                            $text_in_link="<span class='remember_leave_hour'>$check_hour_leave</span>";
                                                    }
                                                    
                                                    
                                                            if(strtotime($choose_date)==strtotime($get_busy['date_cleaning']))
                                                            {
                                                                $text_in_link.='<div class="i_need_go_to_clean"></div>';
                                                            }
                                                    
                                                    
                                                    
                                                    
                                                    $link_on_home = CHtml::link($text_in_link, array('/hotelOrder/monitoring', 'id'=>$get_busy['id'], 'date'=>$choose_date),$array_rels);  
                                                    $broken_begin = date("Y.m.d",strtotime($get_busy['broken_begin']));
                                                    $broken_finish = date("Y.m.d",strtotime($get_busy['broken_finish']));
                                                    $choose_date_time = date("Y.m.d",strtotime($choose_date));
                                                   
                                                    if($broken_begin<=$choose_date_time and $choose_date_time<=$broken_finish)
                                                    {
                                                        if($get_busy['status']>=4)
                                                        $busy_now ="TYC_nomoney";
                                                        else $busy_now ="reserve_small";
                                                    }
                                                    else
                                                    {
                                                            if($get_busy['id_invite']==3 and $get_busy['status']==1)
                                                                $busy_now = 'uncache';
                                                            else
                                                                $busy_now = self::cellColor($get_busy['status']);
                                                       
                                                    }
                                                    $ring = "";
                                                    if(!$ring_was)
                                                    if($get_busy['ring']==1) {$ring = "<span class='ring'></span>";$ring_was = true;}
                                                    
                                                    if(count($busy_cell[$choose_date])!=$cnts)
                                                    if($cnt_positions_in_cell>1) $classic='right_border_yes';
                                                    $double_cell.="<td class='$classic'><div class='$busy_now'>$ring$link_on_home&nbsp;&nbsp;</div></td>";
                                                
                                                
                                                 
                                            }
                                            $empty = false;
                                            if(($cnt_positions_in_cell==1 and $current_date_last_day==$choose_date) or   ($days_checker==count($busy_cell[$choose_date])) or $get_busy['count_days']==0) 
                                                {
                                                    $empty=true;
                                                    $double_cell.="<td><div>$generate_link&nbsp;&nbsp;</div></td>";   
                                                }  
                                                
                                           // $link = $cnt_positions_in_cell;
                                           $double_cell.='</tr></table>';
                                          
                                           $link =  $double_cell;
                                          // $link = ;
                                           
                                        }
                                        else
                                        {
                                            $link =$generate_link;
                                            
                                        }
                                      if(!$empty) $classes=' border_'.$busy_now;
                                      else $classes='';
                                      
                                         if($platform) $addClass = ' pc';
                                         $table .= "<div rel='$n' class='call_fancy{$classes}{$addClass}'>$link</div>";
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
             $first_show = false;
            $days_back = $days_back*-1;
            $today = date('d.m.Y');
            $number=0;
            $table = '<div id="main_chess">';
   
                $table .= '<div class="chess_body">';
                 //Подругаем гостиницы
                    $table .= '<div class="left_part">';
                    $table .= "<div class='first_cell'></div>";
                    
                               foreach($hotels as $hotel)
                                { 
                                    $number++;
                                    if(!$first_show and $hotel->default_type==1)
                                    {
                                        $table .= "<span style='height:34px;display:block;'></span>";
                                        $first_show=true;
                                    }
                                
                                    $ring= '';
                                     $message = '';
                                     $array_hotels[] = $hotel->id;
                                     $def_type[] = $hotel->default_type;
                                     if($hotel->dirty==1) $dirty=' dirty_room';
                                     else $dirty='';
                                     if($hotel->bell!='') $message = 'bell';
                                    
                                     $hotel->name = str_replace(' ','_',$hotel->name);
                                     $table .= "<div class='col_$number cell_hotel first_cell$dirty'>{$hotel->name}</div>";
                                  
                                }
                                
                                $first_show=false;
                     
                    $table .= '</div>';            
                    $table .= '<div class="right_part">';
                        $table .= '<div class="scrolling_part">';
                        $table .= '<div class="relation_part">';
                        $table .= '<div class="part_row dates">';
                            $table .= '<div class="part_row_dates_rel">';
                                $table .= '<div id="scroll_day" class="part_row_dates_abs">';
                                 // Генерируем период
                            for($day=$days_back;$day<=$days_prev;$day++)
                            {                          
                                
                                    $got_day = date('d.m.Y',mktime(0, 0, 0, date("m")  , date("d")+$day, date("Y"))); 
                                    $eng_day =  date('D',strtotime($got_day));
                                    $day_rus = fnc::getRealDay($eng_day);                                
                                    $array_dates[] = $got_day;   
                                    
                                    if($got_day==$today) $class='today';
                                    else $class='';
                                    //$linkz = CHtml::link('', array('hotelOrder/report', 'date'=>$got_day, 'type'=>'by_day'),array('title'=>'Посмотреть отчёт за день'));               
                                    $table .= "<div class='$class'>$linkz$got_day <br><span>$day_rus</span></div>";
                                
                                
                            }
                                $table .= '</div>';
                            $table .= '</div>';
                        $table .= '</div>';
                        $b=0;
                        $table .= '<div style="height:34px;">';  
                        $table .= '</div>';                          
                        foreach ($array_hotels as $id_hotel)
                        {
                            if(!$first_show and $def_type[$b]==1)
                                    {
                                        $table .= "<div class='line_del'></div>";
                                        $first_show=true;
                                    }
                            $busy_cell = array();
                            $table .= '<div rel="'.$b.'" class="part_row">';    
                            $b++;
                            $z=0;
                            $date_stay_begin_sql = date('Y-m-d',strtotime($array_dates[0]));
                            $date_stay_finish_sql = date('Y-m-d',strtotime($array_dates[count($array_dates)-1]));
                            $ways_reserved = HotelOrder::model()->findAll(array(
                            'condition'=>
                            "id_hotel=$id_hotel and  ( ('$date_stay_begin_sql'<=date(date_stay_begin) and '$date_stay_finish_sql'>=date(date_stay_finish)) or ('$date_stay_finish_sql'>=date(date_stay_begin) and '$date_stay_finish_sql'<=date(date_stay_finish))  or ('$date_stay_begin_sql'>=date(date_stay_begin) and '$date_stay_begin_sql'<=date(date_stay_finish)) )"
                            ,'order'=>'date_stay_begin ASC','select'=>"*,to_days(date_stay_finish)-to_days(date_stay_begin) as price_per_day"));
                            $checker = 0;
                            foreach ($ways_reserved as $way)
                            {
                                
                                $way_current_date_begin = date('d.m.Y',strtotime($way->date_stay_begin));
                                $way_current_date_finish = date('d.m.Y',strtotime($way->date_stay_finish));
                                 if($way_current_date_finish==$way_current_date_begin) $full_days=0;
                                 else
                                 $full_days = fnc::intervalDays($way_current_date_begin,$way_current_date_finish);
                                 for ($a=0;$a<=$full_days;$a++)
                                 {    
                                   
                                   
                                    $day_in_array = date('d.m.Y',strtotime("+$a day".$way_current_date_begin));
                                    $day_sql = date('Y-m-d',strtotime("+$a day".$way_current_date_begin));
                                    $freeslots = ClientHotel::model()->count("id_order=$way->id and date(date_stay_begin)<='$day_sql' and '$day_sql'<=date(date_stay_finish)");
                                    $busy_cell[$day_in_array][$z]['id'] = $way->id;
                                    $busy_cell[$day_in_array][$z]['id_hotel'] = $way->id_hotel;
                                    $busy_cell[$day_in_array][$z]['status'] = $way->status;
                                    $busy_cell[$day_in_array][$z]['date_stay_begin'] = $way->date_stay_begin;
                                    $busy_cell[$day_in_array][$z]['date_stay_finish'] = $way->date_stay_finish;
                                    $busy_cell[$day_in_array][$z]['count_days'] = $way->price_per_day;
                                    $busy_cell[$day_in_array][$z]['places'] = $way->places;
                                    $busy_cell[$day_in_array][$z]['id_invite'] = $way->id_invite;
                                    $busy_cell[$day_in_array][$z]['create_time'] = $way->create_time;
                                    $busy_cell[$day_in_array][$z]['ring'] = $way->ring;
                                    $busy_cell[$day_in_array][$z]['remember_time'] = $way->remember_time;
                                    $busy_cell[$day_in_array][$z]['broken_begin'] = $way->broken_begin;
                                    $busy_cell[$day_in_array][$z]['broken_finish'] = $way->broken_finish;
                                    if($way->places-$freeslots==0) $freeslots='';
                                    else $freeslots =$way->places-$freeslots;
                                    $busy_cell[$day_in_array][$z]['freeslots'] = $freeslots;
                                  //  $busy_cell[$day_in_array][$z]['days'] = 
                                 }
                                 $z++;
                            }
                         
                            $n=1;
                            $ring_was=false;
                                foreach($array_dates as $choose_date)
                                {
                                   
                                    $link = '';
                                        $double_cell='<table><tr>';
                                        $array_rels = '';
                                        $tmp_rel='';
                                        $busy_now='';
                                        $free_slots='';
                                        $tmp_id = $id_hotel;  
                                        $generate_link = CHtml::link('', array('/hotelOrder/reserve', 'id'=>$tmp_id, 'date'=>$choose_date));                                      
                                        $current_date =  date('Y-m-d',strtotime($choose_date));
                                       
                                        $cnt_positions_in_cell = count($busy_cell[$choose_date]);
                                        if($cnt_positions_in_cell>0)
                                        {
                                            $lol=false;
                                            $days_checker = 0;
                                            foreach ($busy_cell[$choose_date] as $get_busy)
                                            {
                                                if($get_busy['count_days']==0) $days_checker++;
                                                
                                            }
                                           
                                            $current_date_first_day =  date('d.m.Y',strtotime($get_busy['date_stay_begin']));
                                                $current_date_last_day =  date('d.m.Y',strtotime($get_busy['date_stay_finish']));
                                                
                                                if(($cnt_positions_in_cell==1 and $current_date_first_day==$choose_date) or ($days_checker==count($busy_cell[$choose_date]))) 
                                                {
                                                   
                                                    $double_cell.="<td><div>$generate_link&nbsp;&nbsp;</div></td>";   
                                                }
                                          $cnts = 0;
                                          
                                            foreach ($busy_cell[$choose_date] as $get_busy)
                                            {
                                                $cnts++;
                                                $classic='';
                                                    $array_rels = array('alt'=>$get_busy['id']);
                                                    if($get_busy['remember_time']!='00-00-00 00:00:00' and date('d.m.Y',strtotime($get_busy['remember_time']))==$choose_date) $text_in_link = date('H:i',strtotime($get_busy['remember_time']));
                                                    else $text_in_link=$get_busy['freeslots'];
                                                    
                                                  
                                                    die();
                                                    $link_on_home = CHtml::link($text_in_link, array('/hotelOrder/monitoring', 'id'=>$get_busy['id'], 'date'=>$choose_date),$array_rels);  
                                                    $broken_begin = date("Y.m.d",strtotime($get_busy['broken_begin']));
                                                    $broken_finish = date("Y.m.d",strtotime($get_busy['broken_finish']));
                                                    $choose_date_time = date("Y.m.d",strtotime($choose_date));
                                                   
                                                    if($broken_begin<=$choose_date_time and $choose_date_time<=$broken_finish)
                                                    {
                                                        if($get_busy['status']>=4)
                                                        $busy_now ="TYC_nomoney";
                                                        else $busy_now ="reserve_small";
                                                    }
                                                    
                                                    else
                                                    {
                                                    switch ($get_busy['status'])
                                                    {
                                                        case '6':
                                                            $busy_now ="TYC_halfmoney";
                                                        break;
                                                        case '5':
                                                            $busy_now ="TYC_nomoney";
                                                        break;
                                                        case '4':
                                                            $busy_now ="TYC";
                                                        break;
                                                        
                                                        case '3':
                                                            $busy_now ="halfmoney";
                                                        break;
                                                        
                                                        case '2':
                                                            $busy_now ="was_users";
                                                        break;
                                                        
                                                        case '1':
                                                            $busy_now ="reserve_small";
                                                        break;
                                                        
                                                        case '0':
                                                            $busy_now ="live_small";
                                                        break;
                                                    }
                                                    }
                                                    $ring = "";
                                                    if(!$ring_was)
                                                    if($get_busy['ring']==1) {$ring = "<span class='ring'></span>";$ring_was = true;}
                                                    
                                                    if(count($busy_cell[$choose_date])!=$cnts)
                                                    if($cnt_positions_in_cell>1) $classic='right_border_yes';
                                                    $double_cell.="<td class='$classic'><div class='$busy_now'>$ring$link_on_home&nbsp;&nbsp;</div></td>";
                                                
                                                
                                                 
                                            }
                                            $empty = false;
                                            if(($cnt_positions_in_cell==1 and $current_date_last_day==$choose_date) or   ($days_checker==count($busy_cell[$choose_date])) or $get_busy['count_days']==0) 
                                                {
                                                    $empty=true;
                                                    $double_cell.="<td><div>$generate_link&nbsp;&nbsp;</div></td>";   
                                                }  
                                                
                                           // $link = $cnt_positions_in_cell;
                                           $double_cell.='</tr></table>';
                                          
                                           $link =  $double_cell;
                                          // $link = ;
                                           
                                        }
                                        else
                                        {
                                            $link =$generate_link;
                                        }
                                      if(!$empty) $classes=' border_'.$busy_now;
                                      else $classes='';
                                      
                                         $table .= "<div rel='$n' class='call_fancy$classes'>$link</div>";
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
             $items = array(0=>'Заселение',1=>'Бронирование',2=>'Выселение',3=>'Бронирование с предоплатой',4=>'Используется как ТУЦ');             
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
        
        public static function getMonth($month)
        {
            if(is_numeric($month))
                $list_mounth = array(1=>'Январь',2=>'Февраль',3=>'Март',4=>'Апрель',5=>'Май',6=>'Июнь',7=>'Июль',8=>'Август',9=>'Сентябрь',10=>'Октябрь',11=>'Ноябрь',12=>'Декабрь');
                else
                    $list_mounth = array('Jan'=>'Январь','Feb'=>'Февраль','Mar'=>'Март','Apr'=>'Апрель','May'=>'Май','Jun'=>'Июнь','Jul'=>'Июль','Aug'=>'Август','Sep'=>'Сентябрь','Oct'=>'Октябрь','Nov'=>'Ноябрь','Dec'=>'Декабрь');
            return $list_mounth[$month];
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
               
                $cnt_days = round(($date_finish-$date_begin)/86400);        // Промежуток в днях<br />
               
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
        
        public static function resettingsStatus()
        {
            return array('Проживают','Забронирована','Выселена','Забронирована с предоплатой','ТУЦ оплачена','ТУЦ без оплаты','ТУЦ с предоплатой');
            
        }
        
        
        public static function PaymentsStatus()
        {
            return array('ЗП','Кредит','Под отчёт','Затраты','Другое');
        }
        
        public static function getSwitcher($default_type,$id,$date)
        {
           
            $with="";
            $type = $_GET['type'];
            if($default_type==1)
            {
                $with = "";
                if(isset($_GET['TYC']) and $_GET['TYC']=='') $with='true';
            }
            else
            {
                $with = "true";
                if(isset($_GET['TYC']) and $_GET['TYC']=='true') $with='';
            }
            
            switch ($with)
            {
                case 'true':
                    $txt = 'Используется как ГОСТИНИЦА';
                break;
                case '':
                    $txt = 'Используется как ТУЦ';
                break;
            }
            switch ($type)
            {
                case 'halfmoney':
                    $link = CHtml::link($txt, array('hotelOrder/reserve','method'=>'halfmoney','TYC'=>$with, 'type'=>$type, 'id'=>$id, 'date'=>$date ),array('id'=>'tyc_hotel'));
                break;
                case 'nomoney':
                    $link = CHtml::link($txt, array('hotelOrder/reserve','method'=>'nomoney','TYC'=>$with , 'type'=>$type, 'id'=>$id, 'date'=>$date ),array('id'=>'tyc_hotel'));  
                break;
                case 'with_money':
                    $link = CHtml::link($txt, array('hotelOrder/reserve','method'=>'money','TYC'=>$with, 'type'=>$type, 'id'=>$id, 'date'=>$date ),array('id'=>'tyc_hotel'));      
                break;
                default:
                    $link = '';
                break;
            }
            return $link;
        }
        
        public static function sendSMS($id_order=0,$phone = '79220455189', $street='',$date_time='',$tyc=0,$work = true,$first_time = true)
        {
          
            if(Yii::app()->params['host']!='local')
            {
                 
                if($work)
                {       
                    if(strlen($phone)==11)
                    {    
                         
                        $phone = '7'.substr($phone,1);          
                      
                       
                        $date_time = date('d.m.Y H:i',strtotime($date_time));
                        
                        switch ($tyc)
                        {
                            case 0:
                                $tel = "Администратор 8(3452)53-12-53 - Тюмень";
                                
                            break;
                            case 1:                    
                                $tel = "Администратор 8(922)480-16-63 - Александр";
                           
                            break;
                            default:
                                $tel = "Администратор 8(3452)53-12-53 - Тюмень";
                               
                            break;
                        }
                        $From ='hotel72.ru';
                        
                        if($first_time)
                        {                            
                            $message_sms = "Гостиница Хом-Сити!\nВам забронировали квартиру по адресу $street на $date_time\n$tel";
                        }
                        else
                        {                            
                            $message_sms = "Гостиница Хом-Сити!\nВаша бронь подтверждена, ждём Вас по адресу $street\n$tel";
                        }
                        
                        self::sendSMSLight($phone,$message_sms,"hotel72.ru");
                        
                       
                        
                            if($date_time!="01.01.1970 00:00")
                            {
                                $sms_model = new Sms;
                                $sms_model->id_order = $id_order;
                                $sms_model->phone = $phone;
                                $sms_model->street = $street;
                                $sms_model->city = $tyc;
                                $sms_model->status = 0;
                                $sms_model->date_public = date('Y-m-d H:i',strtotime("-1 hour".$date_time));
                                $sms_model->save();
                            }
                        
                    }
                }
            }
            
        }
        
        
        public static function sendSMSSauna($phone = '79220455189',$date_time='')
        {
          
            
            if(Yii::app()->params['host']!='local')
            {
                 
                       
                    if(strlen($phone)==11)
                    {    
                         
                        $phone = '7'.substr($phone,1);          
                      
                       
                        $date_time = date('d.m.Y H:i',strtotime($date_time));
                        
                        
                                $tel = "Администратор 8(3452)606-808 - Тюмень";
                                
                         
                        $From ='hotel72.ru';
                        
                        $message_sms = "Сауна La Siesta!\nВам забронировали сауну на $date_time\n$tel. Приятного отдыха!";
                        
                        self::sendSMSLight($phone,$message_sms,"hotel72.ru");
                        

                            
                        
                    }
                
            }
            
        }
        
        
        
    public static function generateLINK($GET_string,$id,$return_null=false)
    {
        $GET_array = explode('|',$GET_string);
        $result = array();
        $detected = false;
        
        foreach ($GET_array as $get_param)
        {
            if($get_param==$id) $detected=true;
        }
        if($detected) 
        {
            $result['current'] = 'current';
            $result['link'] = str_replace("$id","",$GET_string);
        }
        else
        {
            $result['current'] = '';
            $result['link'] = $GET_string."|$id";
        }
        
       
        $tmp_link = explode('|',$result['link']);
        $tmp_link = array_diff($tmp_link, array(''));
        $result['link'] = implode('|',$tmp_link); 
        
        return $result;               
    }
    
    
    public static function cellColor($status)
    {
        switch ($status)
        {
            case '6':
                $busy_now ="TYC_halfmoney";
            break;
            case '5':
                $busy_now ="TYC_nomoney";
            break;
            case '4':
                $busy_now ="TYC";
            break;
            
            case '3':
                $busy_now ="halfmoney";
            break;
            
            case '2':
                $busy_now ="was_users";
            break;
            
            case '1':
                $busy_now ="reserve_small";
            break;
            
            case '0':
                $busy_now ="live_small";
            break;
        }
        return $busy_now;
    }
        
      public static function generateBACKuri($recomment_uri = false)
      {
           $gen = "<div class='android_uri'>";
           if($recomment_uri) $uri = $recomment_uri;
           else $uri = '/';
           $gen .= CHtml::link('Вернуться назад',$uri);
           $gen .= "</div>";
           echo $gen;
      }
      
      public static function returnError($message)
      {
          $error = "<div class='error_box'>";
          $error .= "<strong>Ошибка!</strong> ".$message;
          $error .= "</div>";
          return $error;
      }
      
      public static function checkNeedTick($status)
      {
            if($status==0 or $status==3 or $status==4 or $status==6)
            return true;
            else return false;
      }
      
      public static function getAccessRule($n=false)
      {
        $list = array(
                        1=>'Супер администратор',
                        2=>'Диспетчер',
                        3=>'Водитель',
                        4=>'Офис-менеджер',
                        5=>'Стажёр',
                     );
        if(is_numeric($n))
            return $list[$n];
        else return $list;
      }
      
      public static function sendSMSLight($phone, $text, $sender)
        {
            $login = "LeaguePlayer";
            $password = "qwelpo86";
        $host = "api.infosmska.ru";
        $fp = fsockopen($host, 80);
        fwrite($fp, "GET /interfaces/SendMessages.ashx" .
        "?login=" . rawurlencode($login) .
        "&pwd=" . rawurlencode($password) .
        "&phones=" . rawurlencode($phone) .
        "&message=" . rawurlencode($text) .
        "&sender=" . rawurlencode($sender) .
        " HTTP/1.1\r\nHost: $host\r\nConnection: Close\r\n\r\n");
        fwrite($fp, "Host: " . $host . "\r\n");
        fwrite($fp, "\n");
        while(!feof($fp)) {
        $response .= fread($fp, 1);
        }
        fclose($fp);
        list($other, $responseBody) = explode("\r\n\r\n", $response, 2);
        list($other, $ids_str) = explode(":", $responseBody, 2);
        list($sms_id, $other) = explode(";", $ids_str, 2);
        return $sms_id;
        } 
        
        
        public static function convertHour($hour)
        {
            
            if($hour<10 and strlen($hour)==1)
                $hour = "0$hour";
              
            
            return $hour;

        }
        
        public static function loadGraphic()
        {
            for($n=0;$n<6;$n++)
                $array[$n] = 1500;
            for($n=6;$n<18;$n++)
                $array[$n] = 1200;
            for($n=18;$n<24;$n++)
                $array[$n] = 1500;
            
            return $array;
            
        }
        
        public static function VisitorStatus($n=false)
        {
            $array = array('Забронирована','Оплачена','Внесена предоплата','Выселена','delete_row'=>'Удалить данный период');
            if(is_numeric($n))
                return $array[$n];
            else
                return $array;
        }
      public static function ajax()
      {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
            return true;
        else return false;
      }
      
      public static function getExtensionFile($filename, $return_separator = false)
    {
        $split_arr = explode('.', $filename);
        $n = count($split_arr);
        return ($n < 2) ? null : ($return_separator) ? '.'.$split_arr[$n-1] : $split_arr[$n-1];
    }
    
    public static function priceFormat($price)
    {
        $string = "$price";
        $len = strlen($string);
        $result = "";
        for ($i = 1; $i <= $len; $i++) {
            $result.=$string[$len-$i];
            if ($i%3 == 0) $result.=" ";
        }
        return strrev($result);
    }
    
    public static function generateSID($length = 10)
    {
        $symbols = "0123456789";
        $result = '';
        $n = strlen($symbols)-1;
        for ($i=0; $i<$length; $i++)
        {
            $result .= $symbols[rand(0, $n)];
        }        
        return $result;
    }
    
}