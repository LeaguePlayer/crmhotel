<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_hotel'); ?>
		<?php echo $form->textField($model,'id_hotel',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_stay_begin'); ?>
		<?php echo $form->textField($model,'date_stay_begin'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_stay_finish'); ?>
		<?php echo $form->textField($model,'date_stay_finish'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'price_per_day'); ?>
		<?php echo $form->textField($model,'price_per_day',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'places'); ?>
		<?php echo $form->textField($model,'places',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->