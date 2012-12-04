<?php echo $this->renderPartial('_book', array('book'=>$book)); ?>

<h3>Добавление нового телефона</h3>
<div class="row">
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>