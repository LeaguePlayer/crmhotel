
<?$users = Clients::getLiveUsers($model->id,$date)?>
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
       foreach ($user->clienthotel as $cc)  {$ccs = $cc->status;}
$getPhone = Phones::model()->find(array("condition"=>"id_client={$user->id}",'order'=>'id DESC'))->getAttribute('phone');
            
        if($ccs==0)
        {
            $raznica = fnc::intervalDays($cc->date_stay_begin,$cc->date_stay_finish);
            $tick_ot = Ticks::model()->find(array('condition'=>"id_clienthotel={$cc->id} and status=1","select"=>"date_period_begin,(select date_period_finish FROM `ticks` where id_clienthotel={$cc->id} and status=1 order by date_period_finish DESC LIMIT 1) as date_period_finish",'order'=>'date_period_begin ASC'));
            $raznica_cash = fnc::intervalDays($tick_ot->date_period_begin,$tick_ot->date_period_finish);
            $sum_ot = $raznica-$raznica_cash;
            $proc1 = round(($raznica_cash*100)/$raznica);
            $proc2 = round(($sum_ot*100)/$raznica);
            $proc = $proc1+$proc2;
           
            if($proc>100) {$proc = 100 - $proc1 - $proc2;$proc1 = $proc1 + $proc;}
           
            $form='';
            if($raznica_cash>0)
            {
                
                $form .= "<div style='width:".$proc1."px' class='pays'>$proc1%</div>";
            }
            if($raznica>0)
            {
                
                $form .= "<div style='width:".$proc2."px' class='unpays'>$proc2%</div>";
            }
           // echo $getPhone;die();
             echo '<li><span>'.$user->name.'</span> '.$getPhone.'<div class="nav_icons"><div>'.CHtml::link("<img src='/images/phone.png' title='Посмотреть телефонные номера'>", array('phones/create','id_user'=>$user->id)).'</div><div>'.CHtml::link("<img src='/images/info.png' title='Посмотреть историю'>", array('hotelOrder/history','id_user'=>$user->id, 'id_order'=>$model->id,'date'=>$date),array('class'=>'recalc')).'</div><div>'.CHtml::link("<img src='/images/money.png' title='Выписать счёт'>", array('ticks/create','id_user'=>$user->id, 'id_order'=>$model->id,'date'=>$date),array('class'=>'recalc')).'</div><div>'.CHtml::link("<img src='/images/home-go.png' title='Переселить'>", array('hotelOrder/eviction','id_user'=>$user->id, 'id_order'=>$model->id,'type'=>'rereserve','date'=>$date),array('class'=>'recalc')).'</div><div>'.CHtml::link("<img src='/images/calc.png' title='Выселить с перерасчетом!'>", array('hotelOrder/eviction','id_user'=>$user->id, 'id_order'=>$model->id,'type'=>'exit_with_money','date'=>$date),array('class'=>'recalc')).'</div>
                     <div>'.CHtml::ajaxLink ("<img src='/images/exit.png' title='Выселить!'>",
                     CController::createUrl('HotelOrder/eviction', array('id_user'=>$user->id, 'id_order'=>$model->id,'type'=>'exit','date'=>$date)),
                     array('update' => '#living_users_list'),
                     array('class' => 'add')).'</div>
                     </div><div class="procents">'.$form.'</div><div class="row_box"><input name="newLive['.$user->id.']" type="checkbox" value="'.$user->id.'"></div></li>';
        }
        else
        {
             echo '<li><span>'.$user->name.'</span> '.$getPhone.'<div class="nav_icons"><div>'.CHtml::link("<img src='/images/phone.png' title='Посмотреть телефонные номера'>", array('phones/create','id_user'=>$user->id)).'</div><div>'.CHtml::link("<img src='/images/info.png' title='Посмотреть историю'>", array('hotelOrder/history','id_user'=>$user->id, 'id_order'=>$model->id,'date'=>$date),array('class'=>'recalc')).'</div></div></li>';
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