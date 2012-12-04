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
		<?php echo $form->labelEx($model,'square'); ?>
		<?php echo $form->textField($model,'square',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'square'); ?>
	</div>
    
    
    <?$options = Option::model()->findAll(array('order'=>'value'));?>
    <?foreach($options as $option):?>
        <?
            $checked = false;
            foreach($model->options as $hotelOption)
            {
                if( $hotelOption->id === $option->id )
                {
                    $checked = true;
                    break;
                }
            }
        ?>
        
        <div class="row">
            <label>
                <?php echo CHtml::checkBox("Hotels[options][".$option->id."]", $checked); ?>
                <?php echo $option->value; ?>
            </label>
        </div>
    <?endforeach?>
    
    <div class="row">
		<?php //echo $form->labelEx($model,'wifi'); ?>
		<?php //echo $form->checkBox($model,'wifi'); ?>
		<?php //echo $form->error($model,'wifi'); ?>
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
    
            	<div class="row">
		<?php echo $form->labelEx($model,'default_host'); ?>
		<?php echo $form->dropDownList($model,'default_host',fnc::getInviters()); ?>
		<?php echo $form->error($model,'default_host'); ?>
	</div>
    
    
    <div class="row">
		<?php echo $form->labelEx($model,'sinc'); ?>
		<?php echo $form->checkBox($model,'sinc'); ?>
		<?php echo $form->error($model,'wifi'); ?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($model,'full_desc'); ?>
		<?php echo $form->textarea($model,'full_desc', array('cols'=>60, 'rows'=>'5')); ?>
		<?php echo $form->error($model,'full_desc'); ?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($model,'short_desc'); ?>
		<?php echo $form->textarea($model,'short_desc', array('cols'=>60, 'rows'=>'5')); ?>
		<?php echo $form->error($model,'short_desc'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->