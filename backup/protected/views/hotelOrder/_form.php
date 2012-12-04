<?$current_day =  date('d.m.Y',strtotime($date_stay)); ?> 
<?$result_dates=1;?>
<?$id_client=$_GET['rereserve']['id_clientHotel']?>

     <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/timepicker/jquery.ui.timepicker.js?v=0.2.4"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/timepicker/include/jquery.ui.core.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/timepicker/include/jquery.ui.widget.min.js"></script>

  <script type="text/javascript">
            $(document).ready(function() {
                $('.time').timepicker();
                
            });
        </script>  

<?$date_stay_begin = $current_day.' '.date('H:i')?>
<?$next_day =  date('d.m.Y',strtotime('+1 day'.$date_stay)); ?>                                
<?$date_stay_finish = $next_day.' 14:00'?>

<?$hotel_category = Hotels::model()->findByPk($id_hotel)?>
 <?
            $today = date('Y-m-d',strtotime($date_stay));
            $get_time = HotelOrder::model()->find(array('condition'=>"id_hotel = $id_hotel and date(date_stay_finish) = '$today'"));
            
            if(count($get_time)>0) $time = date('H:i',strtotime($get_time->date_stay_finish));
            else $time = date('H:i');
            
        ?>
<?
if(isset($type))
{
    switch ($type)
    {
        case 'small_left':
            $converted_date =  date('Y-m-d',strtotime('-1 day'.$date_stay));
            $converted_date_selected =  date('Y-m-d',strtotime($date_stay));
            $begin_date = HotelOrder::model()->find(array('condition'=>"id_hotel=$id_hotel and date(date_stay_finish)='$converted_date'",'order'=>"date_stay_finish DESC"));
            $how_many_hours = HotelOrder::model()->find(array('condition'=>"id_hotel=$id_hotel and date(date_stay_begin)='$converted_date_selected'",'order'=>"date_stay_finish DESC"));
            
            if(count($begin_date)>0)
            {
                $current_day =  date('d.m.Y',strtotime($begin_date->date_stay_finish));
                $time = date('H:i',strtotime($begin_date->date_stay_finish));
            }
           
            if(count($how_many_hours)>0)
            {
                  $date1 = strtotime($begin_date->date_stay_finish);
                  $date2 = strtotime($how_many_hours->date_stay_begin);
                  
                  if(floor(($date2-$date1)/3600)<24)
                  {
                    $result_dates = floor(($date2-$date1)/3600);
                  ?>
                  <script>
                  $(document).ready(function(){
                    $('.inputchecker').click();
                  });
                  </script>
                  <?
                  }
            }
        break;
        case 'small_right':

        break;
    }
    
    
}

?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'hotel-order-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> Обязательные поля для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>



	<div class="row">	
        <?php echo CHtml::hiddenField('HotelOrder[status]',$status);?>
		 <?php echo CHtml::hiddenField('HotelOrder[id_hotel]',$id_hotel);?>
	</div>
    
    <?
        if(isset($id_client) and $id_client!='')
        {
            $ClientHotel_finish = ClientHotel::model()->findByPk($id_client)->getAttribute('date_stay_finish');
            $date2 = strtotime($ClientHotel_finish);
            $date1 = strtotime($date_stay);
            $result_dates = floor(($date2-$date1)/86400);
        }
    ?>
    
    <table><thead><tr><td colspan="2">
    <?php echo $form->labelEx($model,'date_stay_begin'); ?>
    </td></tr></thead>
    <tbody>
    <tr><td>
    Дата
    </td>
        <td>
    Часы минуты
    </td></tr>
    <tr><td>
     <?$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'HotelOrder[date_stay_begin]',
    'value'=>$current_day,
    
    
    // additional javascript options for the date picker plugin
    'options'=>array(
        'showAnim'=>'fold',
        'firstDay'=>1,
        'dateFormat'=>'dd.mm.yy',
    ),
    'htmlOptions'=>array(
        'style'=>'height:16px;'
    ),
));?>
    </td>
        <td>
       
     <?php echo Chtml::textField('timepicker',$time,array('size'=>30,'maxlength'=>100, 'class'=>'time')); ?>
    </td></tr>
    </tbody>
    </table>

	<div class="row">
		

		<?php echo $form->error($model,'date_stay_begin'); ?>
	</div>
    <div class="row">

       
    </div>

<div class="row">
<label class="housdays">На сколько дней?</label>
<?php echo CHtml::textField('howdays',$result_dates,array('size'=>10,'maxlength'=>10)); ?>
<label class="inputchecker">
Перевести в часы
</label>
<?php echo CHtml::checkBox('howhous','1',array('class'=>'hiden')); ?>
</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price_per_day'); ?>
        <?echo CHtml::textField('HotelOrder[price_per_day]',$hotel_category->cost,array('size'=>10,'maxlength'=>10))?>
		<?php //echo $form->textField($model,'price_per_day',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'price_per_day'); ?>
	</div>
    <div class="row igoro">
        Стоимость составляет <span>0</span> руб
    </div>
    
   	<div class="row">
		<?php echo $form->labelEx($model,'id_invite'); ?>
        <?php echo CHtml::dropDownList('HotelOrder[id_invite]','0',fnc::getInviters())?>
		<?php echo $form->error($model,'id_invite'); ?>
	</div>
<?if($hotel_category->default_type==1){?>
	<div class="row">
		<?php echo $form->labelEx($model,'places'); ?>
        <?php echo CHtml::dropDownList('HotelOrder[places]','0',fnc::getHotelCategory($hotel_category->id_cat))?>
		<?php echo $form->error($model,'places'); ?>
	</div>
<?}else{?>
     <?php echo CHtml::hiddenField('HotelOrder[places]','1')?>
<?}?>
    
    <?if(!isset($id_client) or $id_client==''){?>
   <?php echo $this->render('_form_users'); ?>
<?}?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Заселить' : 'Save'); ?>
    

	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->