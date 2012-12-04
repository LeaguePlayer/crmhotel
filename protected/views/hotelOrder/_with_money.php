<h1>Выселение с перерасчетом</h1>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'clients-form',
	'enableAjaxValidation'=>false,
)); ?>

	
	<div class="row">
	    Клиент заплатил <?=fnc::getRealWord($sum_all_days,'рубль','рубля','рублей')?>. <br>Веселение происходит за <?=fnc::getRealWord($return_days)?>. <br>По предварительным подчётам, Вы должны Клиенту сумму в размере <?=fnc::getRealWord($return_sum,'рубль','рубля','рублей')?>
	</div>




	<div class="row">
            <?echo CHtml::label('Кто выселил?','return_id_invite');?><br>
		<?php echo CHtml::dropDownList('return[id_invite]',$id_invite_last,fnc::getInviters()); ?><br>
	    <?echo CHtml::label('Сумма возврата','return_sum_for_days');?><br>
		<?php echo CHtml::textField('return[sum_for_days]',$return_sum,array('size'=>60,'maxlength'=>255)); ?><br>
        <?echo CHtml::label('Дополнительная сумма за документы','return_sum_for_doc');?><br>
		<?php echo CHtml::textField('return[sum_for_doc]','0',array('size'=>60,'maxlength'=>255)); ?>
        	<?php echo CHtml::hiddenField('return[date_period_begin]',$begin); ?>
            <?php echo CHtml::hiddenField('return[date_period_finish]',$finish); ?>
            <?php echo CHtml::hiddenField('return[id_clienthotel]',$id_clienthotel); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton("Выплатить"); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->