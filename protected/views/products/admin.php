<?php

$this->menu=array(
	array('label'=>'Добавить продукт на склад', 'url'=>array('create')),
);


?>

<h1>Управление продуктами</h1>



<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'products-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
     'afterAjaxUpdate' => 'js:function(id, data) { checkEmptyFields(); }',
	'columns'=>array(
		
		'title',
		'purchase_price',
		'sales_price',
		
		
            
            
            array(
                    
                    
                    'type'=>'raw',
                    'value'=>'date("d.m.Y",strtotime($data->date_delivery))',
                    'header'=>'Дата последней поставки',
                    
                    ),
            
             array(
                    
                    
                    'type'=>'raw',
                    'value'=>'Products::getActualBalance($data->id)." ".Products::getUnit($data->id_unit)',
                    'header'=>'Остаток на складе',
                    'htmlOptions'=>array('class'=>'checkMyEmpty'),
                    ),
            
          
            
            
		array
                (
                    'class'=>'CButtonColumn',
                    'template'=>'{cash} {update} {delete}',
                    'buttons'=>array
                    (
                        'cash' => array
                        (
                            'label'=>'Монетизация',
                            'imageUrl'=>Yii::app()->request->baseUrl.'/images/dollar.png',
                            'url'=>'Yii::app()->createUrl("products/monetisation", array("id"=>$data->id))',
                        ),
                        
                    ),
                )
	),
)); ?>
