<?if($gameover>0){?>
<h1>Выписка счёта</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'order'=>$order,'clienthotel'=>$clienthotel)); ?>
<?}else{?>
<?if($status==1){?>
<h1>Уже оплачено!</h1>
<?}else{?>
<h1>Счёт выписан, ожидается пока заберут деньги!</h1>
<?}?>
<?}?>