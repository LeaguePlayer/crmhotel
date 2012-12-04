<?php
$this->menu=array(

	array('label'=>'Управление продуктами', 'url'=>array('admin')),
);
?>

<h1>Реализация продукта "<?=$model_product->title?>"</h1>

<div class="form">
    

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'products-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> Обязательные для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>
        <?if($error) echo "Ошибка! $error"?>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		  <?php echo $form->dropDownList($model,'status',Products::getActualStatus()); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'id_invite'); ?>
		  <?php echo $form->dropDownList($model,'id_invite',fnc::getInviters()); ?>
		<?php echo $form->error($model,'id_invite'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'count_used'); ?>
		<?php echo $form->textField($model,'count_used'); ?>
            <div>
                Сейчас на складе <strong><?=  Products::getActualBalance($model_product->id)?></strong> штук
            </div>
		<?php echo $form->error($model,'count_used'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'price_for_sale'); ?>
		<?php echo $form->textField($model,'price_for_sale'); ?>
                <div>
                    Установленная цена для реализации <strong><?=$model_product->sales_price?></strong> штук
                </div>
		<?php echo $form->error($model,'price_for_sale'); ?>
	</div>

	

	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Реализовать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


<?if(count($list_products_used)>0){?>

<div class="confirm_table">
    <h3>История реализации продукта</h3>
    
    <table class="credittable">
        <thead>
            <tr>
                <td>Когда реализовано?</td>
                <td>Кто реализовал?</td>
                <td>Состояние</td>
                <td>Кол-во штук</td>
                <td>По стоимости за штуку</td>
            </tr>
        </thead>
        <tbody>
            <?foreach ($list_products_used as $list){?>
                <tr>
                    <td><?=date('d.m.Y',strtotime($list->date_used))?></td>
                    <td><?=fnc::getInviters_report($list->id_invite)?></td>
                    <td><?=Products::getActualStatus($list->status)?></td>
                    <td><?=$list->count_used?></td>
                    <td><?=$list->price_for_sale?> руб.</td>
                </tr>
            <?}?>
        </tbody>
    </table>
</div>

<? } ?>
