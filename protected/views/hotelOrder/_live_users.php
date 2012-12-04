<form class="newLive_form">
<?
echo CHtml::hiddenField('id_order_GET',$model->id);
echo CHtml::hiddenField('date_by_GET',$date);
?>

<fieldset>
    <legend>Тут живут</legend>
        <ul id="users_list">
        
        <?php        
        if(count($users)>0)
        {
            foreach ($users as $user)
            {
                $addHidden = '';
                
                //форма редактирования данных
                $get_begin_date = date('d.m.Y',strtotime($user->date_stay_begin));
                    $get_begin_time = date('H:i',strtotime($user->date_stay_begin));
                    $get_finish_date = date('d.m.Y',strtotime($user->date_stay_finish));
                    $get_finish_time = date('H:i',strtotime($user->date_stay_finish));

                if($user->status!=1)
                {

                    $generate_input_date_begin = "<input class='datepicker_object' name='fastedit[{$user->id}][begin_date]' type='text' value='$get_begin_date'>";
                    $generate_input_time_begin = "<input class='time' name='fastedit[{$user->id}][begin_time]' type='text' value='$get_begin_time'>";

                    $generate_input_date_finish = "<input class='datepicker_object' name='fastedit[{$user->id}][finish_date]' type='text' value='$get_finish_date'>";
                    $generate_input_time_finish = "<input class='time' name='fastedit[{$user->id}][finish_time]' type='text' value='$get_finish_time'>";

                    $form_top = "<dt class='top_block'>{$generate_input_date_begin}{$generate_input_time_begin} - {$generate_input_date_finish}{$generate_input_time_finish}</dt>";
                }
                else   
                {
                    $form_top = "<dt class='top_block'>{$get_begin_date} {$get_begin_time} - {$get_finish_date} {$get_finish_time}</dt>";
                }
                
                
                //конец формы редактирования данных
                
                
                if($user->price_for>0) $and_with_docs =" за документы <strong>$user->price_for руб</strong>";
                else $and_with_docs = '';
                
                
                $date_sql  = date("Y-m-d",strtotime($date));
                if(date('Y-m-d',strtotime($user->date_stay_finish))!=$date_sql) $addHidden=' hidden';
                $cost_on_this_day = MgtMoney::model()->find(array("condition"=>"date(date_public)<='$date_sql' and id_clienthotel=$user->id",'order'=>'date_public DESC'));
                $dop_sum = 0;
                $get_last_tick_payed = Ticks::model()->find(array('order'=>'date_period_finish DESC','condition'=>"id_clienthotel=$user->id and status=1"));
                $get_last_tick_unpayed = Ticks::model()->find(array('order'=>'date_period_finish DESC','condition'=>"id_clienthotel=$user->id and status=0"));
                
              
                $client = Clients::model()->findByPk($user->id_client);
                 $notes = Notice::model()->count("id_client = $client->id");
                if($notes>0) $note = '<div class="score">'.$notes.'</div>';
                else $note='';
                $getPhone = '';
                $getPhones = Phones::model()->findAll(array("condition"=>"id_client={$client->id}",'order'=>'id DESC','limit'=>3));
                   if(count($getPhones)>0) 
                   {
                    $getPhone ="<ul class='phone_list'>";
                        foreach ($getPhones as $getblog)
                        {
                            $getPhone .="<li>{$getblog->phone}</li>";
                        }
                    $getPhone .="</ul>";
                   }
                if($user->status==0)
                {
                    
                   $sum = Ticks::model()->find(array('condition'=>"id_clienthotel={$user->id} and status=0",'select'=>'sum(sum_for_days)+sum(sum_for_doc) as sum_for_days'));
                 
                    if(!is_numeric($sum->sum_for_days)) 
                    {
                        $sum = Ticks::model()->find(array('condition'=>"id_clienthotel={$user->id} and status=3",'select'=>'sum(sum_for_days) as sum_for_days, sum(finish_sum) as finish_sum'));
                        $sum->sum_for_days = $sum->sum_for_days - $sum->finish_sum;
                    }
                         
                    $raznica = strtotime($user->date_stay_finish)-strtotime($user->date_stay_begin);                    
                    $tick_ot = Ticks::model()->find(array('condition'=>"id_clienthotel={$user->id} and status=1","select"=>"date_period_begin,(select date_period_finish FROM `ticks` where id_clienthotel={$user->id} and status=1 order by date_period_finish DESC LIMIT 1) as date_period_finish",'order'=>'date_period_begin ASC'));                    
                    $raznica_cash = strtotime($tick_ot->date_period_finish)-strtotime($tick_ot->date_period_begin); 
                    
                    $find_fly_ticks = Ticks::model()->find(array('condition'=>"id_clienthotel={$user->id} and status=6","select"=>"sum(finish_sum) as finish_sum"));
                    
                    if($find_fly_ticks->finish_sum>0) $wait_money = 'Ожидает подтверждения на сумму '.$find_fly_ticks->finish_sum;
                    else $wait_money = '';
                  
                    
                    $sum_ot = $raznica-$raznica_cash; 
                    
                    $sto_procentov = $raznica+$raznica_cash;
                           
                    if($sto_procentov>0) 
                        $proc2 = round(($sum_ot*100)/$sto_procentov);      // процент не выплаты (красный цвет)
                                
                    
                                   
                    
                    $proc1 = 100-$proc2;
                                      
                    $form='';
                    if($proc1>0)
                    {
                        $form .= "<div style='width:".$proc1."px' class='pays'>$proc1%</div>";
                    }
                    if($proc2>0)
                    {                        
                        $form .= "<div style='width:".$proc2."px' class='unpays'>$proc2%</div>";
                    }
                    
                    if($user->from) 
                    {
                        if(is_numeric($user->from))
                            $from_got = UserFrom::getItems($user->from);
                        else $from_got = $user->from;
                        $addFrom = " ($from_got)";
                    }
                    else $addFrom='';
                    
                    $get_sum = MgtMoney::getScore($user->id);
                    
                    
                    if($sum->sum_for_days>0)
                        $sum->sum_for_days = round($sum->sum_for_days,-2);  
                    
                    elseif($user->price_for>0)
                    {
                        
                         
                                      $sum_for_docs = MgtMoney::getScoreForDoc($user->id);  
                                      
                        $sum_with_doc = round(($sum_for_docs*0.1+$get_sum),-2);
                        
                    }
                    else $sum_with_doc = $get_sum;
                    
                    
                    if($model->TYC==1)
                    {
                        if($user->finally==1) $pulsee = 'checked="checked"'; else $pulsee = '';
                         if($user->arrived==1) $pulsee2 = 'checked="checked"'; else $pulsee2 = '';
                            $finnaly = "<div class='right'><label>Приехал?<input $pulsee2 class='edit_arrived' type='checkbox' value='1' name='$user->id'></label> - <label>Окончательно?<input $pulsee class='edit_finally' type='checkbox' value='1' name='$user->id'></label></div>";
                    }
                    
                    
                 
                   if($sum->sum_for_days>0) $sum_form = 'По расчётам программмы примерно '.$sum->sum_for_days.' руб?';
                   else $sum_form = 'По расчётам программмы примерно '.$sum_with_doc.' руб?';
                   echo '<li>'.$form_top.'<span>'.$client->name.$addFrom.'</span> '.$getPhone.'<div class="wait_money">'.$wait_money.'</div><div class="nav_icons"><div>'.CHtml::link("<img src='/images/phone.png' title='Посмотреть телефонные номера'>", array('phones/create','id_user'=>$client->id),array('class'=>'fancy_run')).'</div><div>'.$note.CHtml::link("<img src='/images/note.png' title='Замечания'>", array('notice/create','id_user'=>$client->id),array('class'=>'fancy_run')).'</div><div>'.
                   CHtml::link(
            "<img src='/images/gotmoney.png' title='Оплатил?'>",
             array('hotelOrder/eviction','id_order'=>$model->id,'id_user'=>$client->id,'type'=>'got_money','date'=>$date,'id_clienthotel'=>$user->id),
             array('confirm' => 'Пользователь оплатил указанную сумму? '.$sum_form)
        )
                     .'</div><div>'.CHtml::link("<img src='/images/3g.png' title='Форма 3г'>",array("hotelOrder/forma3g",'id_clienthotel'=>$user->id)).'</div><div>'.CHtml::link("<img src='/images/home-go.png' title='Переселить'>", array('hotelOrder/goalist','post_id'=>$user->id, 'id_order'=>$model->id,'post_type'=>'rereserve','post_data'=>$date),array('class'=>'pereselit')).'</div><div>'.CHtml::link("<img src='/images/calc.png' title='Выселить с перерасчетом!'>", array('hotelOrder/eviction','id_user'=>$client->id, 'id_order'=>$model->id,'type'=>'exit_with_money','date'=>$date),array('class'=>'recalc')).'</div>
                             <div>'.CHtml::ajaxLink ("<img src='/images/exit.png' title='Выселить!'>",
                             CController::createUrl('HotelOrder/eviction', array('id_user'=>$client->id,'id_clienthotel'=>$user->id, 'id_order'=>$model->id,'type'=>'exit','date'=>$date)),
                             array('update' => '#living_users_list'),
                             array('class' => 'add')).'</div>
                             </div>
                             <div class="row_box">
                             <table class="close_table'.$addHidden.'"><tr><td>с опл</td><td>без опл</td><tr><td><input name="g_user[newLive][]" type="checkbox" value="'.$user->id.'"></td><td><input class="show_time" name="g_user[no_newLive][]" type="checkbox" value="'.$user->id.'"></td></tr></table>
                             </div>
                             <div class="info_div_user">
                             <div class="left">Платит <strong>'.$cost_on_this_day->cost.' руб</strong>'.$and_with_docs.' <span>(от '.date('d.m.Y',strtotime($cost_on_this_day->date_public)).') <a href="/hotelOrder/addMgtMoney?date='.$date.'&id_clienthotel='.$user->id.'"><img src="/images/edit.png"></a></span></div>
                            <div class="procents">'.$form.'</div> '.$finnaly.'
                             
                             </div></li>';
                }
                else
                {
                     echo '<li>'.$form_top.'<span>'.$client->name.$addFrom.'</span> '.$getPhone.'<div class="nav_icons"><div>'.CHtml::link("<img src='/images/phone.png' title='Посмотреть телефонные номера'>", array('phones/create','id_user'=>$client->id),array('class'=>'fancy_run')).'</div><div>'.CHtml::link("<img src='/images/3g.png' title='Форма 3г'>",array("hotelOrder/forma3g",'id_clienthotel'=>$user->id)).'</div><div>'.$note.CHtml::link("<img src='/images/note.png' title='Замечания'>", array('notice/create','id_user'=>$client->id),array('class'=>'fancy_run')).'</div></div></li>';
                }
                    
                
               
               
            }
        }
        else
        {
            echo "Информация отсутствует";
        }
        
        ?>
        </ul>
</fieldset>

</form>
<?
if($reloadscript==1)
{
    ?>
    <script type="text/javascript">
    $(".fancy_run").fancybox({
    'type' : 'iframe',
     'width' : '95%',
     'height' : '95%',
     'autoScale' : false,
     'transitionIn' : 'elastic',
     'transitionOut' : 'elastic',
'showNavArrows' : false,
});

ajax_go_load();
    </script>
    <?
}
?>