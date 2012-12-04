<div class="inline">
    <? echo CHtml::label('Проживает до','');?>

<?
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'hotel['.$i.'][user_finish]',
    'value'=>date('d.m.Y',strtotime('+1 day '.$date)),
    
    
    // additional javascript options for the date picker plugin
    'options'=>array(
        'showAnim'=>'fold',
        'firstDay'=>1,
        'dateFormat'=>'dd.mm.yy',
    ),
   
));
?>
</div>
<div class="inline">
    <? echo CHtml::label('За документы','');?>
    <input type="text" class="hotel_user_documents" name="hotel[<?=$i?>][user_document]" value="0" />
</div>
<div class="inline">
    <? echo CHtml::label('От куда приехал','');?>
    <?=CHtml::dropDownList("hotel[{$i}][user_from]",0,UserFrom::getItems());?>
</div>
