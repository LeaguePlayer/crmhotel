<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'hotels-form_dirty',
	'enableAjaxValidation'=>false,
)); ?>

<div>
        <? echo CHtml::hiddenField('dirty','0');?>
</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Сохранить' : 'Да'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->