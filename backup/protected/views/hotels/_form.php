<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'hotels-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_cat'); ?>
		<?php echo $form->dropDownList($model,'id_cat',fnc::getCategory()); ?>
		<?php echo $form->error($model,'id_cat'); ?>
	</div>
    
    	<div class="row">
		<?php echo $form->labelEx($model,'cost'); ?>
		<?php echo $form->textField($model,'cost',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'cost'); ?>
	</div>
    
        	<div class="row">
		<?php echo $form->labelEx($model,'default_type'); ?>
		<?php echo $form->dropDownList($model,'default_type',fnc::getHotelType()); ?>
		<?php echo $form->error($model,'default_type'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->