<?php
$this->breadcrumbs=array(
	'Hotels'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Hotels', 'url'=>array('index')),
	array('label'=>'Manage Hotels', 'url'=>array('admin')),
);
?>

<h1>Create Hotels</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>