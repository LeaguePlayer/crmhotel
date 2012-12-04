     <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/timepicker/jquery.ui.timepicker.js?v=0.2.4"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/timepicker/include/jquery.ui.core.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/timepicker/include/jquery.ui.widget.min.js"></script>
    <link rel="stylesheet" type="text/css" href="http://admin.hotel72.ru/assets/39791ee1/jui/css/base/jquery-ui.css" />
<? $model_date_converted = date('Y-m-d',strtotime($model->date_stay_finish));?>

<?$cnt_on_this_date = HotelOrder::model()->count("id_hotel={$model->id_hotel} and (date(date_stay_begin)='$model_date_converted' or date(date_stay_finish)='$model_date_converted')")?>

  <script type="text/javascript">
            $(document).ready(function() {
                $('.time').timepicker({ onClose: function(){                    
     
           var id_order = $('#id_order_GET').val();
           var time = $(this).val();
          
           var str = "r=hotelOrder/fastupdatetimer&id_order="+id_order+"&time="+time;
      
                           $.ajax({
                  url: '/',
                  type: "GET",
                  data: str                  
                 
                  });
                    
                }});
                
            });
        </script>  
<?$converted_date =  date('Y-m-d',strtotime($date)); ?> 
<?$users = ClientHotel::model()->count(array('condition'=>"date(date_stay_begin)<='$converted_date' and '$converted_date'<=date(date_stay_finish) and id_order={$model->id} and status=0"));?>

<?$free_slots = $model->places - $users?>
    
    <div class="hidden_div"></div>
    <div id="loadpage">
    <div class="loaderajax"></div>
<div id="header_blog">
<? $day_eng = date('D',strtotime($date))?>
<?php echo $this->renderPartial('_header', array('hotel'=>$hotel,'int_dates'=>$int_dates)); ?>
<h2><?=fnc::getRealDay($day_eng).' '.$date?></h2>
</div>




<?php echo $this->renderPartial('_monitor', array('model'=>$model,'date'=>$date)); ?>

	<fieldset>
<legend>Настройки квартиры</legend>
<div class="field_settings">
        <label>Колличество мест</label>
        <?php echo CHtml::dropDownList('rechange_places',$current_places,fnc::getHotelCategory($hotel_category))?>
</div>
<div class="field_settings">
        <label>Стоимость</label>
	    <?php echo CHtml::textField('price_order',$model->price_per_day,array('id'=>'HotelOrder_price_per_day') )?>
</div>
<div class="field_settings">
        <label>Кто заселил?</label>
        
	    <?php echo CHtml::dropDownList('invite_who',$model->id_invite,fnc::getInviters() )?>

</div>
<div class="field_settings">
        <label>Во сколько выселение?</label>
        <?$current_time =  date('H:i',strtotime($model->date_stay_finish)); ?> 
	    <?php echo CHtml::textField('time_changer',$current_time,array('class'=>'time') )?>

</div>
<?if(HotelOrder::model()->findByPk($id_order)->getAttribute('status')==0){?>
<?if($converted_date==$model_date_converted){?>
<?if($cnt_on_this_date<2){?>
<div class="field_settings">
        <label>Продлить проживание? (количество дней)</label>   <br>     
        
	    <?php echo CHtml::textField('newLive[days]','1',array('id'=>'howdays') )?><br>
        <div class="row igoro">
Стоимость составляет
<span>0</span>
руб
</div>
        <?
            echo CHtml::ajaxLink ('Продлить проживание',
                     CController::createUrl('hotelOrder/Extend'),
                     array('update' => '#basket-update','data'=>"js:$('.newLive_form').serialize()+'&days='+$('#howdays').val()",'type'=>'POST','success'=>'function(){parent.jQuery.fancybox.close();}'),
                     array('class' => 'add')); 
         ?>

</div>
<?}?>
<?}?>
<?}?>

<?if($model->status==1){?>
<div class="field_settings">
        <?
         echo CHtml::linkButton('Снять бронь', array(
    'submit'=>array(
        'hotelOrder/delete',
        'id' => $id_order,
    ),
    'params'=>array(
        //'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken
    ),
    'confirm'=>"Точно снять бронь?"
))?>

</div>
<?}?>
        
        
	</fieldset>

<div id="living_users_list">
<form class="newLive_form">
<?php echo $this->renderPartial('_live_users', array('model'=>$model,'date'=>$date)); ?>
</form>
</div>

<?if($free_slots>0){?>
<div class="free_places"><a href="/?r=hotelOrder/users&id_order=<?=$model->id?>&date=<?=$date?>">Свободных мест <span><?=$free_slots?></span></a></div>
<?}?>
<script>
$(document).ready(function(){

     
            $('.free_places a').fancybox({
    'type' : 'iframe',
         'width' : '75%',
     'height' : '95%',
     'autoScale' : false,
     'transitionIn' : 'elastic',
     'transitionOut' : 'elastic',
     'onClosed' : function(){
        
           var date = $('#date_by_GET').val();
           var id_order = $('#id_order_GET').val();
           var str = "r=hotelOrder/fastupdate&date="+date+"&id_order="+id_order;
      
                           $.ajax({
                  url: '/',
                  type: "GET",
                  data: str,
                  
                  success: function(data) {
                      $('#living_users_list').html(data);
                      var cnt_lives = $('.add').size();
                     
                      var cnt_slots = $('#rechange_places').val();
                      cnt_slots  = parseInt(cnt_slots);
                      var result = cnt_slots-cnt_lives;
                      
                      if(result>0)
                      {
                        $('.free_places a span').html(result);
                      }
                      else
                      {
                        $('.free_places').remove();
                      }
                  }
                  });
        
     },

}); 
});
</script>

<?if($converted_date==$model_date_converted){?>
<?if($cnt_on_this_date<2){?>
<div id="reserved_after">
    <?=CHtml::link('Забронировать после этого заказа', array('hotelOrder/reserve', 'id'=>$model->id_hotel, 'date'=>$date));               ?>
</div>
<?}?>
<?}?>

</div>