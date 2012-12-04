<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> Обязательные для заполнения поля.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'access'); ?>
		<?php echo $form->dropDownList($model,'access',fnc::getAccessRule()); ?>
		<?php echo $form->error($model,'access'); ?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($model,'sauna_access'); ?>
		<?php echo $form->dropDownList($model,'sauna_access',array('Нет доступа к сауне','Полный доступ к сауне','Доступ ТОЛЬКО к сауне')); ?>
		<?php echo $form->error($model,'sauna_access'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'works_to'); ?>
		<?$this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name'=>'Users[works_to]',
        'value'=>($model->isNewRecord ? date('d.m.Y') : date('d.m.Y',strtotime($model->works_to))),
        'options'=>array(
            'showAnim'=>'fold',
            'firstDay'=>1,
            'dateFormat'=>'dd.mm.yy',
        ),
        
        ));?>
		<?php echo $form->error($model,'works_to'); ?>
	</div>
    
    <div class="row">
        <?php
        echo CHtml::label('Время отключения аккаунта','Users_time');
        echo CHtml::textField('Users[time]',date('H:i:s',strtotime("+1 hour")),array('class'=>'time'));
        ?>
    </div>
    
    <div class="hit">Если уровень доступа не стажёр, тогда поля время работы и время отключения не будут учитываться</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->