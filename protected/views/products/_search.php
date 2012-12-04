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
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'purchase_price'); ?>
		<?php echo $form->textField($model,'purchase_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sales_price'); ?>
		<?php echo $form->textField($model,'sales_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'brought_cnt'); ?>
		<?php echo $form->textField($model,'brought_cnt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_delivery'); ?>
		<?php echo $form->textField($model,'date_delivery'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->