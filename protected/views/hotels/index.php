<?php
$this->breadcrumbs=array(
	'Hotels',
);

$this->menu=array(
	array('label'=>'Create Hotels', 'url'=>array('create')),
	array('label'=>'Manage Hotels', 'url'=>array('admin')),
);
?>

<h1>Hotels</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
