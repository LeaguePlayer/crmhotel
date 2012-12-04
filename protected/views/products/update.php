<?php
$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(

	array('label'=>'Управление продуктами', 'url'=>array('admin')),
);
?>

<h1>Редактирование продукта "<?php echo $model->title; ?>"</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>