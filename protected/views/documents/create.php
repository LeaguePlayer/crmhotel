<h1><?=($model->post_type=='' ? 'Выписать документы' : 'Выписать счет за услугу/товар')?></h1>
<?
    if($model->post_type=='')
    {
        $caption_button = 'Управление выписанными документами';
    }
    else
    {
        $caption_button = 'Управление выписанными счетами';
    }
?>

<ul class="operation_control">
    <li><?echo CHtml::link($caption_button,array('admin','type'=>$model->post_type))?></li>
</ul>

<fieldset>
<legend>Выписка документа</legend>
<?php echo $this->renderPartial('_form', array('model'=>$model,'exmodel'=>$exmodel)); ?>
</fieldset>

<br />

    <?if(count($docs_for_drivers)>0 or count($array_with_all_found_fly_ticks)>0){?>
        <div class="driver_docs">
            <?
                $this->renderPartial('_docs_drivers',array(
            			'array_with_all_found_fly_ticks'=>$array_with_all_found_fly_ticks,
                        'docs_for_drivers'=>$docs_for_drivers
            		));
            ?>
        </div>
    <?}?>



