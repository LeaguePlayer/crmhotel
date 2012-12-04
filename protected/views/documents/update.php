<h1>Изменение выписанного документа №<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'exmodel'=>$exmodel,'my_tick'=>$my_tick)); ?>

<?php echo $this->renderPartial('listEdition', array('model'=>$listing)); ?>