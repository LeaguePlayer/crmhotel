<h3>Информация о пользователе</h3>
<?php echo $this->renderPartial('_param_list', array('user'=>$user)); ?>
<?php echo $this->renderPartial('_book', array('book'=>$book)); ?>

<h3>Добавление нового телефона</h3>
<div class="row">
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>        
</div>