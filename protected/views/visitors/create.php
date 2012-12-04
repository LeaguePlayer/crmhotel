<?php
$this->breadcrumbs=array(
	'Visitors'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Visitors', 'url'=>array('index')),
	array('label'=>'Manage Visitors', 'url'=>array('admin')),
);
?>

<h1>Create Visitors</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>