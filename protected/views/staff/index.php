<h1>Сотрудники</h1>
<div class="row">
    <?echo CHtml::link('Добавление сотрудника',array('staff/create'));?>
    <br>
      <?echo CHtml::link('Управление персоналом',array('staff/admin'));?>
</div>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
