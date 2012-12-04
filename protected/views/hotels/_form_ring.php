<h1>Напоминание о звонке</h1>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'hotels-form',
    'action'=>array('hotels/addRing','id'=>$model->id),
	'enableAjaxValidation'=>false,
)); ?>

   	<div class="row">
  
		<?php echo $form->textArea($model,'ring',array('cols'=>60,'rows'=>3)); ?>
		<?php echo $form->error($model,'ring'); ?>
	</div>


	<div class="row buttons">
    <?if($model->ring=='')$but_text = "Напомнить позвонить"; else $but_text = "Позвонил"; ?>
		<?php echo CHtml::submitButton($but_text); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->