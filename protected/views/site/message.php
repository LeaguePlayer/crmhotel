<h1>Сообщения дня</h1>


<div class="form">

<?php echo CHtml::beginForm(); ?>



	<div class="row">
		<?php echo CHtml::label('Введите сообщение','message'); ?>
        <br />
		<?php echo CHtml::textArea('message',$settings->message,array('rows'=>10,'cols'=>80)); ?>
		
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php echo CHtml::endForm(); ?>

</div><!-- form -->