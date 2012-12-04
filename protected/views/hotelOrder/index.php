<?if(!$platform) fnc::generateBACKuri();?>
<div class="changer">
    <?=CHtml::link('Заселение с оплатой',array('/hotelOrder/reserve','type'=>'with_money','date'=>$date,'id'=>$id_hotel));?>
</div>
<div class="changer">
    <?=CHtml::link('Бронирование без оплаты',array('/hotelOrder/reserve','type'=>'nomoney','date'=>$date,'id'=>$id_hotel));?>
</div>
<div class="changer">
    <?=CHtml::link('Бронирование с предоплатой',array('/hotelOrder/reserve','type'=>'halfmoney','date'=>$date,'id'=>$id_hotel));?>
</div>