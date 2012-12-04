<?php
$this->menu=array(

	array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h1>Создать точку прибытия</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>