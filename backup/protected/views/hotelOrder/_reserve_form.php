<?$current_day =  date('d.m.Y',strtotime($date_stay)); ?> 
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
          <?
            $today = date('Y-m-d',strtotime($date_stay));
            $get_time = HotelOrder::model()->find(array('condition'=>"id_hotel = $id_hotel and date(date_stay_finish) = '$today'"));
            
            if(count($get_time)>0) $time = date('H:i',strtotime($get_time->date_stay_finish));
            else $time = date('H:i');
            
        ?>
     <?php echo Chtml::textField('timepicker',$time,array('size'=>30,'maxlength'=>100, 'class'=>'time')); ?>
    </td></tr>
    </tbody>
    </table>

	<div class="row">
		

		<?php echo $form->error($model,'date_stay_begin'); ?>
	</div>
    <div class="row">

       
    </div>

<div class="row hidden">
<label class="housdays">На сколько дней?</label>
<?php echo CHtml::textField('howdays','1',array('size'=>10,'maxlength'=>10)); ?>
<label class="inputchecker">
Перевести в часы
</label>
<?php echo CHtml::checkBox('howhous','1',array('class'=>'hidden')); ?>
</div>

	<div class="row hidden">
		<?php echo $form->labelEx($model,'price_per_day'); ?>
        <?echo CHtml::textField('HotelOrder[price_per_day]',$hotel_category->cost,array('size'=>10,'maxlength'=>10))?>
		<?php //echo $form->textField($model,'price_per_day',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'price_per_day'); ?>
	</div>
    <div class="row igoro hidden">
        Стоимость составляет <span>0</span> руб
    </div>
    
   	<div class="row hidden">
		<?php echo $form->labelEx($model,'id_invite'); ?>
        <?php echo CHtml::dropDownList('HotelOrder[id_invite]','0',fnc::getInviters())?>
		<?php echo $form->error($model,'id_invite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'places'); ?>

        <?php echo CHtml::dropDownList('HotelOrder[places]','0',fnc::getHotelCategory($hotel_category->id_cat))?>
		<?php echo $form->error($model,'places'); ?>
	</div>
    
     <?php echo $this->render('_form_users'); ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Забронировать' : 'Save'); ?>
    

	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->