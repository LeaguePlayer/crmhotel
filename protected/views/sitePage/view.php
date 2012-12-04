<?php
$this->breadcrumbs=array(
	'Site Pages'=>array('index'),
	$model->title,
);
?>

<h1>Просмотр страницы <?php echo $model->title; ?></h1>

<ul>
<li><?php echo CHtml::link('Создать новую страницу',array('sitePage/create')); ?></li>
<li><?php echo CHtml::link('Управление страницами',array('sitePage/admin')); ?></li>
</ul>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'url',
		'title',
		'content',
		'meta_title',
		'meta_description',
		'meta_keywords',
	),
)); ?>
