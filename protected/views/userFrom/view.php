<?php
$this->breadcrumbs=array(
	'User Froms'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List UserFrom', 'url'=>array('index')),
	array('label'=>'Create UserFrom', 'url'=>array('create')),
	array('label'=>'Update UserFrom', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UserFrom', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserFrom', 'url'=>array('admin')),
);
?>

<h1>View UserFrom #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
	),
)); ?>
