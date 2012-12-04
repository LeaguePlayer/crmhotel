<?php
$this->breadcrumbs=array(
	'Hotel Orders'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List HotelOrder', 'url'=>array('index')),
	array('label'=>'Create HotelOrder', 'url'=>array('create')),
	array('label'=>'View HotelOrder', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage HotelOrder', 'url'=>array('admin')),
);
?>

<h1>Update HotelOrder <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>