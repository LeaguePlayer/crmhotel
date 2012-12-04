<?if($model->dirty==1){?>

<h1>Убрана квартира - <?=$model->name?>?</h1>

<?php echo $this->renderPartial('_form_dirty', array('model'=>$model)); ?>
<?}else{?>
<h1>Квартира <?=$model->name?> - убрана</h1>
<?}?>