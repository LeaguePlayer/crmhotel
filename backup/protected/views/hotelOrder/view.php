<?php
$this->breadcrumbs=array(
	'Hotel Orders'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List HotelOrder', 'url'=>array('index')),
	array('label'=>'Create HotelOrder', 'url'=>array('create')),
	array('label'=>'Update HotelOrder', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete HotelOrder', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage HotelOrder', 'url'=>array('admin')),
);
?>

<h1>View HotelOrder #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_hotel',
		'status',
		'date_stay_begin',
		'date_stay_finish',
		'price_per_day',
		'places',
	),
)); ?>
