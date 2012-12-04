<?php
$this->breadcrumbs=array(
	'Site Pages'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

?>

<h1>Редактирование сатической страницы <?php echo $model->title; ?></h1>

<ul>
<li><?php echo CHtml::link('Список страниц',array('sitePage/admin')); ?></li>
</ul>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>