<?
$platform = fnc::definePlatformPC();
if(!$platform) fnc::generateBACKuri($_SERVER["HTTP_REFERER"]);?>
<?$date_SQL =  date('Y-m-d',strtotime($date)); ?> 
<?
    $mgt = MgtMoney::model()->find(array("condition"=>"date(date_public)<='$date_SQL' and id_clienthotel=$id_clienthotel",'order'=>'id DESC'));

    $cl = ClientHotel::model()->findByPk($id_clienthotel);
    $cl_date = date('Y-m-d',strtotime($cl->date_stay_begin));
    $date_for_input = (strtotime($cl_date)==strtotime($date_SQL) ? $cl->date_stay_begin : "$date_SQL 14:00:00");
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'mgt_money_form',
	'enableAjaxValidation'=>false,
)); ?>
    
    <div class="row">
        <label>Платит за проживание от <?=$date?></label><br />
        <?=CHtml::textField('mgt[cost]',$mgt->cost)?> руб.<br />
        <label>Платит за документы</label><br />
        <?=CHtml::textField('cl[price_for]',$cl->price_for)?><br />
        <label>От куда приехал</label><br />
        <?=CHtml::dropDownList('cl[from]',$cl->from,UserFrom::getItems())?><br />
        <?=CHtml::hiddenField('mgt[id_clienthotel]',$id_clienthotel)?>
        <?=CHtml::hiddenField('mgt[date_public]',$date_for_input)?>
    </div>
    

	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохранить'); ?>   
       
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->