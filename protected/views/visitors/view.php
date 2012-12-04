<?php
$this->breadcrumbs=array(
	'Visitors'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Visitors', 'url'=>array('index')),
	array('label'=>'Create Visitors', 'url'=>array('create')),
	array('label'=>'Update Visitors', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Visitors', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Visitors', 'url'=>array('admin')),
);
?>

<h1>View Visitors #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_place',
		'id_invite',
		'date_stay_begin',
		'date_stay_finish',
		'status',
	),
)); ?>
