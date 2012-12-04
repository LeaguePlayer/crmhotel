<h1>Управление персоналом</h1>

<div class="row">
    <?echo CHtml::link('Добавление сотрудника',array('staff/create'));?> 
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'staff-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		
		'name',
		
                array(
                    
                    
                    'type'=>'raw',
                    'value'=>'Users::getUser($data->id_account)->username',
                    'name'=>'id_account'
                    ),
		array
                (
                    'class'=>'CButtonColumn',
                    'template'=>'{cash} {update} {delete}',
                    'buttons'=>array
                    (
                        'cash' => array
                        (
                            'label'=>'Финансовые манипуляции',
                            'imageUrl'=>Yii::app()->request->baseUrl.'/images/dollar.png',
                            'url'=>'Yii::app()->createUrl("staff/money", array("id"=>$data->id))',
                        ),
                        
                    ),
                )
	),
)); ?>
