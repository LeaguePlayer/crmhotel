 <fieldset>
    <legend>Информация о проживающих</legend>
   
   <?for($b=1;$b<=6;$b++){?>
      <fieldset <?=$style?> class='user_fields q<?=$b?>'>
    <legend>Пользователь</legend>    
    <div class="user_phone">
     <?php echo CHtml::label('Телефон',''); ?>
          	<?php 
    $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
    //'model'=>$model,
    //'attribute'=>'name',
    'id'=>'user_phone',
    'name'=>'users['.$b.'][phone][0]',
    'source'=>$this->createUrl('hotelOrder/AutoComplete'),
    'options'=>array(
        'delay'=>300,
        'minLength'=>2,
        'showAnim'=>'fold',
        'select'=>"js:function(event, ui) {      
          
                  $(this).parents('.user_fields').find('.name_blok').val(ui.item.username);
                  $(this).parents('.user_fields').find('.phone_blok').val(ui.item.xyi);
                
        }"
    ),
    'htmlOptions'=>array(
        'size'=>'40',
        'class'=>'phonecomplite'
    ),
));
    ?>
    <div class="list_phones"></div>
    </div>
   <a rel="0" alt="<?=$b?>" class="new_phone" href="javascript:void(0);"></a>
     <div class="user_name">
    <?php echo CHtml::label('Имя',''); ?>
     <?php echo CHtml::textField('users['.$b.'][name]','',array('size'=>40,'maxlength'=>40,'class'=>'name_blok')); ?>
     <?php echo CHtml::hiddenField('users['.$b.'][id]','',array('size'=>40,'maxlength'=>40,'class'=>'phone_blok')); ?>
     <?php //echo CHtml::textField('users[1][phone]','',array('size'=>40,'maxlength'=>40)); ?>
</div>
    </fieldset>
    <?$style='style="display:none;"'?>
<?}?>
    
       
    

    
    </fieldset>