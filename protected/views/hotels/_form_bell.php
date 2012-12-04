<div class="form hoteledit">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'hotels-form',
    'action'=>array('hotels/addBell','id'=>$model->id),
	'enableAjaxValidation'=>false,
)); ?>

<?if(Users::getDostup(1)){?>
    <div class="row">
		<?php echo $form->labelEx($model,'admin_message'); ?> <span class="admin_message"></span>    <br>    
		<?php echo $form->textArea($model,'admin_message',array('cols'=>60,'rows'=>15)); ?>
		<?php echo $form->error($model,'admin_message'); ?>
    </div>
<?}else{?>
    <div class="row">
        <?php echo $form->labelEx($model,'admin_message'); ?> <span class="admin_message"></span>    <br>    
        <div class="edit_txt"><?=$model->admin_message?></div>
    </div>
<?}?>

   	<div class="row">
		<?php echo $form->labelEx($model,'bell'); ?>    <span class="bell"></span><br>    
		<?php echo $form->textArea($model,'bell',array('cols'=>60,'rows'=>15)); ?>
		<?php echo $form->error($model,'bell'); ?>
	</div>
    
    
    <div class="row">
		<?php echo $form->labelEx($model,'quest'); ?>    <span class="remont"></span><br>    
		<?php echo $form->textArea($model,'quest',array('cols'=>60,'rows'=>15)); ?>
		<?php echo $form->error($model,'quest'); ?>
    </div>
    
    

	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->