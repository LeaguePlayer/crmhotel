<?php
$this->breadcrumbs=array(
	'Site Pages',
);

$this->menu=array(
	array('label'=>'Create SitePage', 'url'=>array('create')),
	array('label'=>'Manage SitePage', 'url'=>array('admin')),
);
?>

<h1>Site Pages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
