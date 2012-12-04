<?php
$this->breadcrumbs=array(
	'Ticks'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Ticks', 'url'=>array('index')),
	array('label'=>'Create Ticks', 'url'=>array('create')),
	array('label'=>'Update Ticks', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Ticks', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ticks', 'url'=>array('admin')),
);
?>

<h1>View Ticks #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_clienthotel',
		'date_period_begin',
		'date_period_finish',
		'status',
		'finish_sum',
		'note',
	),
)); ?>
