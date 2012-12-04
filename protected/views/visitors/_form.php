<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'visitors-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_place'); ?>
		<?php echo $form->textField($model,'id_place'); ?>
		<?php echo $form->error($model,'id_place'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_invite'); ?>
		<?php echo $form->textField($model,'id_invite'); ?>
		<?php echo $form->error($model,'id_invite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_stay_begin'); ?>
		<?php echo $form->textField($model,'date_stay_begin'); ?>
		<?php echo $form->error($model,'date_stay_begin'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_stay_finish'); ?>
		<?php echo $form->textField($model,'date_stay_finish'); ?>
		<?php echo $form->error($model,'date_stay_finish'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->