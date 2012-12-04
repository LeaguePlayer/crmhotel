<?php
$this->breadcrumbs=array(
	'Site Pages'=>array('index'),
	'Create',
);

?>

<h1>Создание статической страницы</h1>

<ul>
<li><?php echo CHtml::link('Список страниц',array('sitePage/admin')); ?></li>
</ul>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>