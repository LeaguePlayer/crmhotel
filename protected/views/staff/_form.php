<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'staff-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
    <? 
       $USERS = Users::model()->getAllUsers();
    ?>
		<?php echo $form->labelEx($model,'id_account'); ?>
		<?php echo $form->dropDownList($model,'id_account',$USERS); ?>
		<?php echo $form->error($model,'id_account'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->