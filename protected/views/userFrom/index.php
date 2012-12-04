<?php
$this->breadcrumbs=array(
	'User Froms',
);

$this->menu=array(
	array('label'=>'Create UserFrom', 'url'=>array('create')),
	array('label'=>'Manage UserFrom', 'url'=>array('admin')),
);
?>

<h1>User Froms</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
