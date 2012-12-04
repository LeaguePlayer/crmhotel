<?php
$this->menu=array(

	array('label'=>'Управление продуктами', 'url'=>array('admin')),
);
?>

<h1>Добавление нового продукта на склад</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>