<h1><?=($type=='' ? 'Просмотр выписатых документов' : 'Просмотр выписаных счетов за услуги/товар')?></h1>
<?
    if($type=='')
    {
        $caption_button = 'Выписать документы';
    }
    else
    {
        $caption_button = 'Выписать счет';
    }
?>

<ul class="operation_control">
    <li><?echo CHtml::link($caption_button,array('create','type'=>$type))?></li>
</ul>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'documents-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		 array(
			'name'=>'price',
			'value'=>'$data->price->price'
		),
             array(
                    
                    
                    'type'=>'raw',
                    'value'=>'fnc::getInviters_report($data->id_invite)',
                    'name'=>'id_invite'
                    ),
            
           array(
                        'type'=>'raw',
			'header'=>'ТУЦ?',
			'value'=>'($data->id_clienthotel==0 ? "" : "ДА")'
		),
            
		
		'date_public',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

    <?if(count($docs_for_drivers)>0){?>
        <div class="driver_docs">
            <?
                $this->renderPartial('_docs_drivers',array(
            			
                        'docs_for_drivers'=>$docs_for_drivers,
                    'array_with_all_found_fly_ticks'=>$array_with_all_found_fly_ticks,
            		));
            ?>
        </div>
    <?}?>
