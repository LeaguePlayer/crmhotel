<?php
$this->breadcrumbs=array(
	'Visitors'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Visitors', 'url'=>array('index')),
	array('label'=>'Create Visitors', 'url'=>array('create')),
	array('label'=>'View Visitors', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Visitors', 'url'=>array('admin')),
);
?>

<h1>Update Visitors <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>