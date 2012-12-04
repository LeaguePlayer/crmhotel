<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'products-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> Обязательные для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		  <?php 
        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(

        'id'=>'Products_title',
        'value'=>(isset($model->title) ? $model->title : ''),
        'name'=>'Products[title]',
        'source'=>$this->createUrl('products/AutoComplete'),
        'options'=>array(
            'delay'=>300,
            'minLength'=>2,
            'showAnim'=>'fold',
            'select'=>"js:function(event, ui) {      
          
                   $('#Products_purchase_price').val(ui.item.purchase_price);
                   $('#Products_sales_price').val(ui.item.sales_price);
                   $('#Products_id_unit').val(ui.item.id_unit);
                     }",
            
        ),
        'htmlOptions'=>array(
            'size'=>'60',
           
        ),
    ));
        ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'id_unit'); ?>
		<?php echo $form->dropDownList($model,'id_unit',Products::getUnit()); ?>
		<?php echo $form->error($model,'id_unit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'purchase_price'); ?>
		<?php echo $form->textField($model,'purchase_price'); ?>
                <div id="recalc_mod">
                    Сумма закупки составляет <strong>0</strong> руб.
                </div>
		<?php echo $form->error($model,'purchase_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sales_price'); ?>
		<?php echo $form->textField($model,'sales_price'); ?>
		<?php echo $form->error($model,'sales_price'); ?>
	</div>

        <?if($model->isNewRecord){?>
            <div class="row">
                    <?php echo $form->labelEx($model,'brought_cnt'); ?>
                    <?php echo $form->textField($model,'brought_cnt'); ?>
                    <?php echo $form->error($model,'brought_cnt'); ?>
            </div>
        <?}?>
	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->