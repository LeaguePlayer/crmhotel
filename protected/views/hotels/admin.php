<?php


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('hotels-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление квартирами</h1><br>

<ul>
<li><?php echo CHtml::link('Создать новую квартиру',array('hotels/create')); ?></li>
<li><?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button')); ?></li>
</ul>



<?php
    $form = $this->beginWidget('CActiveForm', array(
        'action'=>array('api/sincHotels'),
    ));
        echo CHtml::submitButton('Синхр', array(
            'id'=>'sinc-button',
        ));
    $this->endWidget();
?>



<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'hotels-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
	
		array(
			'class'=>'CButtonColumn',
            'template'=>'{view}{update}{delete}{gallery}',
            'buttons'=>array
            (
                'gallery' => array
                (
                    'label'=>'Управление галереей',
                    //'imageUrl'=>Yii::app()->request->baseUrl.'/images/email.png',
                    'url'=>'Yii::app()->createUrl("hotels/manageGallery", array("hotel_id"=>$data->id))',
                ),
            ),
		),
	),
)); ?>
