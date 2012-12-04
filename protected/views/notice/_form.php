<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'notice-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

	<div class="row">
	
		<?php echo CHtml::hiddenField('Notice[id_client]',$id_user); ?>
	
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Записать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->