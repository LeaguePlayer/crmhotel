<?php
$this->breadcrumbs=array(
	'Hotel Orders',
);

$this->menu=array(
	array('label'=>'Create HotelOrder', 'url'=>array('create')),
	array('label'=>'Manage HotelOrder', 'url'=>array('admin')),
);
?>

<h1>Hotel Orders</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
