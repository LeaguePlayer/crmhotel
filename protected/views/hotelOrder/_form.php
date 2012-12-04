<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'hotel-order-form',
	'enableAjaxValidation'=>false,
)); ?>

 

  

	
		<?php echo $form->hiddenField($model,'id_hotel'); ?>
	    <?php echo $form->hiddenField($model,'create_time'); ?>
        <?php echo $form->hiddenField($hotel,'cost'); ?>
        <?php echo $form->hiddenField($model,'TYC'); ?>
        <?php echo $form->hiddenField($model,'status'); ?>
    
    

	<div class="row">
		<?php echo $form->labelEx($model,'date_stay_begin'); ?>
        
        <?$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'stay[date]',
    'value'=>$date,
    
    
    // additional javascript options for the date picker plugin
    'options'=>array(
        'showAnim'=>'fold',
        'firstDay'=>1,
        'dateFormat'=>'dd.mm.yy',
    ),
    
));?>	
		<?php echo $form->error($model,'date_stay_begin'); ?>
	</div>
    
    
    <div class="row">
        <?php
        echo CHtml::label('Время заселения','stay_time');
        echo CHtml::textField('stay[time]',$date_for_picker,array('class'=>'time'));
        ?>
    </div>
    
    <div class="row">
        <label>Продлить на</label>
        <?php echo CHtml::textField('param[next_days]',(isset($this->user_info['next_days']) ? $this->user_info['next_days'] : 1));?>
        <label id="switch_day_hour"><input name="param[hour]" type="checkbox" value="1" /><span class="days current">Дней</span><span class="hours">Часов</span></label>
    </div>



	<div class="row">
		<?php echo $form->labelEx($model,'places'); ?>
		<?php echo $form->dropDownList($model,'places',fnc::getHotelCategory($hotel->id_cat)); ?>
		<?php echo $form->error($model,'places'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_invite'); ?>
		<?php echo $form->dropDownList($model,'id_invite',fnc::getInviters()); ?>
		<?php echo $form->error($model,'id_invite'); ?>
	</div>


    <?if($hidden_blog_post_pay){?>
   
    
	<div class="row">
		<?php echo $form->labelEx($model,'tmp_halfmoney'); ?>
		<?php echo $form->textField($model,'tmp_halfmoney'); ?>
		<?php echo $form->error($model,'tmp_halfmoney'); ?>
        <?php echo $form->textField($model,'tmp_halfdate'); ?>
        
	</div>	
    <?}?>

	

		<div id="form_user_create">
            <?php echo $this->renderPartial('_user_form', array('TYC'=>$model->TYC,'date'=>$date)); ?>
        </div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Забронировать',array('name'=>'button_checker')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->