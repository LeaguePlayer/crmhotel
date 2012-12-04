<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_clienthotel'); ?>
		<?php echo $form->textField($model,'id_clienthotel'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_period_begin'); ?>
		<?php echo $form->textField($model,'date_period_begin'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_period_finish'); ?>
		<?php echo $form->textField($model,'date_period_finish'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'finish_sum'); ?>
		<?php echo $form->textField($model,'finish_sum'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'note'); ?>
		<?php echo $form->textArea($model,'note',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->