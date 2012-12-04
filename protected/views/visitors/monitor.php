<h1>Сауна занята с <?=date('H:i',strtotime($visitor->date_stay_begin))?> до <?=date('H:i',strtotime($visitor->date_stay_finish))?></h1>
<h3>Выбранная Вами дата: <?echo date('d.m',strtotime($date)); echo ", время "; echo $time." часов";?></h3>

<?
if(!empty($error))
{
    echo $error;
}
?>

<fieldset>
    <legend>Форма редактирования</legend>
    <form action="/visitors/monitor/date/<?=$date?>/time/<?=$time?>/id/<?=$visitor->id?>" method="POST">
    <div class="row">
		<?php echo CHtml::label('Кто заселил?',''); ?>
		<?php echo CHtml::dropDownList('edit[id_invite]', $visitor->id_invite,fnc::getInviters()); ?>	        	
	</div>
    <div class="row">
		<?php echo CHtml::label('Состояние',''); ?>
		<?php echo CHtml::dropDownList('edit[status]', $visitor->status,fnc::VisitorStatus()); ?>		
	</div>
    <div class="row">		
		<input type="submit" value="Редактировать" />
	</div>
    </form>
</fieldset>

<fieldset>
    <legend>Форма Продления</legend>
    <form action="/visitors/monitor/date/<?=$date?>/time/<?=$time?>/id/<?=$visitor->id?>" method="POST">
    <div class="row">
		<?php echo CHtml::label('На сколько часов продлить?',''); ?>
		<?php echo CHtml::textField('extension[hours]', 1); ?>	   
<p class="hit">Минуты необходимо указывать через запятую, например, если заселение идет 2 часа 45 минут,<br />нужно указать 2,45 чтобы указать заселение на пол часа используйте 0,30</p>        
	</div>   
    <div class="row">
		<?php echo CHtml::label('Продление с оплатой?',''); ?>
		<?php echo CHtml::checkBox('extension[pay]', 0); ?>	        	
	</div> 
    <div class="row">
		<?php echo CHtml::label('Стоимость за час?',''); ?>
		<?php echo CHtml::textField('extension[sum]', 1200); ?>	        	
	</div> 
    <div class="row">		
		<input type="submit" value="Продлить" />
	</div>
    </form>
</fieldset>

<?if(count($info['prepay'])>0){?>
    <fieldset>
        <legend>История предоплат</legend>

        <?foreach ($info['prepay'] as $prepay){
            
            $eng_day =  date('D',strtotime($prepay->date_public));
            $day_rus = fnc::getRealDay($eng_day); 
        ?>
            <div class="row" style="font-size:12px;"><?=date('d.m.Y',strtotime($prepay->date_public))?> (<?=$day_rus?>) - <?=fnc::priceFormat($prepay->prepay)?> рублей</div>


        <?}?>
    </fieldset>
<?}?>

<fieldset>
    <legend>Панель оплаты</legend>
    <div class="info_cost">Клиент оплатил <strong><?=$info['pay']?></strong> руб</div>
   
    <?if($info['unpay']>0){?>
        <div class="info_cost unpay">Ожидается к оплате <strong><?=$info['unpay']?></strong> руб</div>
        <form action="/visitors/monitor/date/<?=$date?>/time/<?=$time?>/id/<?=$visitor->id?>" method="POST">
            <input type="submit" value="Оплатить" name="got_money" />
        </form>
    <?}?>
    
</fieldset>
<fieldset>
    <legend>Информация о посетителе</legend>
    
    <?if(!empty($user->name)){?>
        <div><strong><?=$user->name?></strong></div>        
    <?}?>
    <?if(count($user->phones)>0){?>
        <div><strong>Контакты:</strong></div>
        <?foreach ($user->phones as $phone){?>
            <div><?=$phone->phone?></div>    
        <?}?>    
    <?}?>
    
    <?if($visitor->status!=3){?>
        <form action="/visitors/monitor/date/<?=$date?>/time/<?=$time?>/id/<?=$visitor->id?>" method="POST">
            <input type="submit" name="exit_live" value="Выселить" />
        </form>
    <?}?>
    
</fieldset>