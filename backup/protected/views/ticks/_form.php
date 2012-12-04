<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ticks-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

 <?if(!$model->isNewRecord){?>

	<div class="row">
	
		<?php echo $form->hiddenField($model,'status'); ?>
		
	</div>
    
    

	<div class="row">
		<?php echo $form->labelEx($model,'finish_sum'); ?>
		<?php echo $form->textField($model,'finish_sum'); ?>
		<?php echo $form->error($model,'finish_sum'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'note'); ?>
		<?php echo $form->textArea($model,'note',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'note'); ?>
	</div>
    
    <?}else{ echo $form->hiddenField($model,'status'); ?>
    
	<div class="row">
	
		<?php echo $form->hiddenField($model,'id_clienthotel'); ?>
	    <?php echo CHtml::hiddenField('price_per_day',$order->price_per_day); ?>
        <?php echo $form->hiddenField($model,'date_period_begin'); ?>
	</div>

    <?=fnc::generateDatesWay($model->date_period_begin,$clienthotel->date_stay_finish)?>
<!--
	<div class="row">
		<?php //echo $form->labelEx($model,'date_period_begin'); ?>
		<?php //echo $form->textField($model,'date_period_begin'); ?>
		<?php //echo $form->error($model,'date_period_begin'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'date_period_finish'); ?>
		<?php //echo $form->textField($model,'date_period_finish'); ?>
		<?php //echo $form->error($model,'date_period_finish'); ?>
	</div>
    -->
    <div class="row">
		<?php echo $form->labelEx($model,'id_informer'); ?>
		<?php echo $form->dropDownList($model,'id_informer',fnc::getInviters()); ?>
		<?php echo $form->error($model,'id_informer'); ?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($model,'sum_for_days'); ?>
		<?php echo $form->textField($model,'sum_for_days'); ?>
		<?php echo $form->error($model,'sum_for_days'); ?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($model,'sum_for_doc'); ?>
		<?php echo $form->textField($model,'sum_for_doc'); ?>
		<?php echo $form->error($model,'sum_for_doc'); ?>
	</div>
    
   
<?}?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Выписать' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->