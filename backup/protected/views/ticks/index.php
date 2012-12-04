<?php
$this->breadcrumbs=array(
	'Ticks',
);

$this->menu=array(
	array('label'=>'Create Ticks', 'url'=>array('create')),
	array('label'=>'Manage Ticks', 'url'=>array('admin')),
);
?>

<h1>Ticks</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
