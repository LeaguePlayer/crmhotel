<?php
$this->menu=array(
	
	array('label'=>'Создать новую точку', 'url'=>array('create')),
);
?>

<h1>Управление</h1>



<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-from-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'title',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
