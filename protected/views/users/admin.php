<?php
$this->menu=array(

	array('label'=>'Создать нового пользователя', 'url'=>array('create')),
);
?>

<h1>Управление аккаунтами</h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'users-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		'username',
	//	'password',
		'access',
		'works_to',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

<div class="hit">Уровни доступа:<br />1 - Супер администратор<br />2 - Диспетчер<br />3 - Водитель<br />4 - Офис-менеджер<br />5 - Временный аккаунт (для него указывается время работы)</div>