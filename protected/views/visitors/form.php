<?PHP $cs=Yii::app()->getClientScript(); ?>
<?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/timepicker/jquery.ui.timepicker.js?v=0.2.4', CClientScript::POS_HEAD); ?>
<?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/timepicker/include/jquery.ui.core.min.js', CClientScript::POS_HEAD); ?>
<?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/timepicker/include/jquery.ui.widget.min.js', CClientScript::POS_HEAD); ?>
<?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/visitors.js', CClientScript::POS_HEAD); ?>
<link rel="stylesheet" type="text/css" href="http://admin.hotel72.ru/assets/39791ee1/jui/css/base/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->baseUrl?>/css/visitors.css" />

<h1>Резервируем сауну</h1>

<?
if(!empty($error))
{
    echo $error;
}
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'visitors-form',
	'enableAjaxValidation'=>false,
)); ?>	

	<?php echo $form->errorSummary($model); ?>



	<div class="row">
		<?php echo $form->labelEx($model,'id_invite'); ?>
		<?php echo $form->dropDownList($model,'id_invite',fnc::getInviters()); ?>
		<?php echo $form->error($model,'id_invite'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::label('День бронирования','field_date')?>
		        
        <?$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'field[date]',
    'value'=>$info['date'],
    
    
    // additional javascript options for the date picker plugin
    'options'=>array(
        'showAnim'=>'fold',
        'firstDay'=>1,
        'dateFormat'=>'dd.mm.yy',
    ),    
));?>	
	</div>    
    
    <div class="row">
		<?php echo CHtml::label('Время бронирования','field_time')?>
		<?php echo CHtml::textField('field[time]',$info['time'],array('class'=>'time'));?>
	</div>
    
    <div class="row">
        <?php echo CHtml::label('На сколько часов?','field_how')?>
        <?php echo CHtml::textField('field[how]',1)?>
        <p class="hit">Минуты необходимо указывать через запятую, например, если заселение идет 2 часа 45 минут,<br />нужно указать 2,45 чтобы указать заселение на пол часа используйте 0,30</p>
    </div>
    
    
    <div id="itogo" class="load">Сумма к оплате <input name="field[sum]" type="text" value="0" /> руб.</div>
     
    <div class="row">
        <?php echo CHtml::label('Оплачена полностью?','field_pay')?>
        <?php echo CHtml::checkBox('field[pay]',0)?> или <span class="go_to_prepay">использовать предоплату</span>
    </div>
    
    <div class="row" id="go_to_prepay">
        <?php echo CHtml::label('Внесли предоплату в размере?','field_prepay')?>
        <?php echo CHtml::textField('field[prepay]',0)?>
    </div>
    
    
    <div class="row">
		<?php echo $form->labelEx($model,'learnedby'); ?>
		<?php 
        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(

        'id'=>'learnedby',
        'value'=>(isset($model->learnedby) ? $model->learnedby : ''),
        'name'=>'Visitors[learnedby]',
        'source'=>$this->createUrl('visitors/AutoComplete'),
        'options'=>array(
            'delay'=>300,
            'minLength'=>2,
            'showAnim'=>'fold',
            'select'=>"js:function(event, ui) {      
              
             // $(this).val(ui.item.learnedby);
//                         $(this).parents('.user_div').find('.hotel_user_name').val(ui.item.username);
//                         $(this).parents('.user_div').find('.hotel_user_id').val(ui.item.xyi);                     
//                         $(this).parents('.user_div').find('.hotel_user_notice').html(ui.item.notes_link);
//                         $(this).parents('.user_div').find('.hotel_user_clear').html(ui.item.clear);
                         
            }"
        ),
        'htmlOptions'=>array(
            'size'=>'40',
            'class'=>'phonecomplite'
        ),
    ));
        ?>
		<?php echo $form->error($model,'learnedby'); ?>
	</div>
    
    <div id="form_user_create">
            <?php echo $this->renderPartial('/hotelOrder/_user_form', array('TYC'=>0,'date'=>$info['date'],'hide_score'=>1)); ?>
    </div>
        	
	    <?php echo $form->hiddenField($model,'id_place'); ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Резевр'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->