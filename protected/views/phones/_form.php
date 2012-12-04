<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'phones-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	
		<?php echo $form->hiddenField($model,'id_client'); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Записать' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->