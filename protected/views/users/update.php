<?php


$this->menu=array(

	array('label'=>'Создать аккаунт', 'url'=>array('create')),

	array('label'=>'Управление аккаунтами', 'url'=>array('admin')),
);
?>
<?PHP $cs=Yii::app()->getClientScript(); ?>
<?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/timepicker/jquery.ui.timepicker.js?v=0.2.4', CClientScript::POS_HEAD); ?>
<?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/timepicker/include/jquery.ui.core.min.js', CClientScript::POS_HEAD); ?>
<?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/timepicker/include/jquery.ui.widget.min.js', CClientScript::POS_HEAD); ?>
<?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/hotelOrder.js', CClientScript::POS_HEAD); ?>
<link rel="stylesheet" type="text/css" href="/assets/39791ee1/jui/css/base/jquery-ui.css" />

<h1>Редактирование <?php echo $model->username; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>