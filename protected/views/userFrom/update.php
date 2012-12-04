<?php
$this->menu=array(
	
	array('label'=>'Создать новую точку', 'url'=>array('create')),

	array('label'=>'Управление точками', 'url'=>array('admin')),
);
?>

<h1>Редактирование <?php echo $model->title; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>