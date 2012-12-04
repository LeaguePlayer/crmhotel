<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'documents-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($exmodel,'price'); ?>
		<?php echo $form->textField($exmodel,'price'); ?>
		<?php echo $form->error($exmodel,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_invite'); ?>
		<?php echo $form->dropDownList($model,'id_invite',fnc::getInviters()); ?>
		<?php echo $form->error($model,'id_invite'); ?>
	</div>
    
    <?if(!$model->isNewRecord or $model->post_type!=''){?>
    
        <div class="row">
    		<?php echo $form->labelEx($exmodel,'node'); ?>
    		<?php echo $form->textArea($exmodel,'node',array('rows'=>6, 'cols'=>50)); ?>
    		<?php echo $form->error($exmodel,'node'); ?>
    	</div>
        
        
         <div class="row">
    		<?php echo $form->labelEx($model,'status'); ?>
    		<?php echo $form->checkBox($model,'status'); ?>
    		<?php echo $form->error($model,'status'); ?>
    	</div>
        
    <?}?>
    
    <?
        if(is_object($my_tick))
        {
            ?>
    <fieldset>
        <legend>Информация о счете</legend>
        <div class="row">
            
            <label>Кто будет выплачивать деньги</label>
            <?echo CHtml::dropDownList('Ticks[id_invite]',$my_tick->id_invite,fnc::getInviters());?>
    	</div>
    
        <div class="row">
    		<label>Сумма к оплате</label>
                <?echo CHtml::textField('Ticks[finish_sum]',$my_tick->finish_sum);?>
    	</div>
     </fieldset>
            <?
        }
    ?>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Выписать' : 'Изменить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->