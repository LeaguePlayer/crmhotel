<?
$platform = fnc::definePlatformPC();
if(!$platform) fnc::generateBACKuri();?>
<?if($model->dirty==1){?>

<h1>Убрали квартиру - <?=$model->name?>?</h1>

<?php echo $this->renderPartial('_form_dirty', array('model'=>$model)); ?><br>

<?php echo $this->renderPartial('_form_bell', array('model'=>$model)); ?><br />


<?}else{?>
<h1>Квартира <?=$model->name?> - убрана</h1><br>

<?php echo $this->renderPartial('_form_bell', array('model'=>$model)); ?>
<?}?>