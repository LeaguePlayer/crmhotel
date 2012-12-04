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
		<?php echo $form->label($model,'tmp_halfmoney'); ?>
		<?php echo $form->textField($model,'tmp_halfmoney'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tmp_halfdate'); ?>
		<?php echo $form->textField($model,'tmp_halfdate'); ?>
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

	<div class="row">
		<?php echo $form->label($model,'id_invite'); ?>
		<?php echo $form->textField($model,'id_invite'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_time'); ?>
		<?php echo $form->textField($model,'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'remember_time'); ?>
		<?php echo $form->textField($model,'remember_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'broken_begin'); ?>
		<?php echo $form->textField($model,'broken_begin'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'broken_finish'); ?>
		<?php echo $form->textField($model,'broken_finish'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ring'); ?>
		<?php echo $form->textField($model,'ring'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'TYC'); ?>
		<?php echo $form->textField($model,'TYC'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->