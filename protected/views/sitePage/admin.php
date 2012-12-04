<?php
$this->breadcrumbs=array(
	'Site Pages'=>array('index'),
	'Manage',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('site-page-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление текстовыми страницами сайта</h1>

<ul>
<li><?php echo CHtml::link('Создать страницу',array('sitePage/create')); ?></li>
<li><?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button')); ?></li>
</ul>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'site-page-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
        'id',
		'url',
		'title',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
