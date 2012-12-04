<?$current_day =  date('Y-m-d',strtotime($date)); ?> 

<?$time_now = date('H:i');?>
<?$users = ClientHotel::model()->count(array('condition'=>"date(date_stay_begin)<='$current_day' and '$current_day'<=date_stay_finish and id_order={$model->id} and status=0"));?>

<?$free_slots = $model->places - $users?>
<fieldset>


<?
if($free_slots>0)
{
     $form=$this->beginWidget('CActiveForm', array('id'=>'form_for_new_user','enableAjaxValidation'=>false,'action'=>'/hotelOrder/monitoring/'));  
      $current_day =  date('Y/m/d',strtotime($date));         
      echo CHtml::hiddenField('date_begin',$current_day);
      echo CHtml::hiddenField('date_finish',$model->date_stay_finish);
      echo CHtml::hiddenField('id_order',$model->id);
      
     for($i=1;$i<=$free_slots;$i++)
     {
       ?>
             <fieldset class='user_fields q<?=$i?>'>
    <legend>Пользователь</legend> 
    <div class="scroll_days">  
    <?
        $scroll_days = HotelOrder::model()->find(array('condition'=>"id={$model->id}",'select'=>"TO_DAYS(`date_stay_finish`) - TO_DAYS('$current_day') as places"))->getAttribute('places');
          
         for($a=0;$a<=$scroll_days;$a++)
         {          
            
               $value_selected_date =  date('Y/m/d',strtotime("+$a day".$date)); 
              $value_selected_date_current =  date('d.m.Y',strtotime("+$a day".$date)); 
            //  echo $value_selected_date.' '.$time_now;
              $reserved = ClientHotel::model()->count(array('condition'=>"id_order={$model->id} and date(date_stay_begin)<='$value_selected_date' and '$value_selected_date'<=date_stay_finish and status=0"));
               
               $free_position_in_rel = intval($model->places)-intval($reserved);
              if(intval($reserved)!=intval($model->places))
              {
            
                $free_position_in_rel_2 = $free_position_in_rel - ($i-1);
                
                 if($free_position_in_rel==$free_slots)
                {
                    $free_position_in_rel=intval($model->places)+1;
                }
                else
                {
                    $free_position_in_rel=0;
                }
                if($free_position_in_rel_2<0) 
                {
                    $free_position_in_rel_2=0;
                }
                echo "<label alt='$free_position_in_rel_2' rel='$free_position_in_rel' title='$value_selected_date_current' class='selected_cell'>$value_selected_date_current<input checked='checked' name='users[$i][select_days][$a]' type='checkbox' value='$value_selected_date'></label>";
              }             
              else
              {
                echo "<label rel='100' class='reserved_slot'>$value_selected_date_current</label>";
              }
              
         }
        
    ?>
    
    </div> 
    
    <? echo CHtml::hiddenField('count_dates',$scroll_days);?>
    <br>
    <div class="user_phone">
         <?php echo CHtml::label('Телефон',''); ?>
              	<?php 
        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
        //'model'=>$model,
        //'attribute'=>'name',
        'id'=>'user_phone'.$i,
        'name'=>'users['.$i.'][phone]',
        'source'=>$this->createUrl('hotelOrder/AutoComplete'),
        'options'=>array(
            'delay'=>300,
            'minLength'=>2,
            'showAnim'=>'fold',
            'select'=>"js:function(event, ui) {      
              
                      $(this).parents('.user_fields').find('.name_here').val(ui.item.username);
                     $(this).parents('.user_fields').find('.id_here').val(ui.item.xyi);
                    
                     if(ui.item.notes>0)
                     {
                           $(this).parents('.user_fields').find('.cancel_user a.note').show();
                            $(this).parents('.user_fields').find('.cancel_user a.note').attr('href','/index.php?r=notice/create&id_user='+ui.item.xyi);
                     }
                    
            }"
        ),
        'htmlOptions'=>array(
            'size'=>'40',
            'class'=>'phonecomplite'
        ),
    ));
        ?>
   </div>
   <div class="user_name">
    <?php echo CHtml::label('Имя',''); ?>
     <?php echo CHtml::textField('users['.$i.'][name]','',array('size'=>40,'maxlength'=>40,'class'=>'name_here')); ?>     
     <?php echo CHtml::hiddenField('users['.$i.'][id]','',array('size'=>40,'maxlength'=>40,'class'=>'id_here')); ?>
     </div>
     
 <div class="user_price">
    <?php echo CHtml::label('Счёт',''); ?>
     <?php echo CHtml::textField('users['.$i.'][price]',0,array('size'=>40,'maxlength'=>40)); ?>
</div>

    </fieldset> 
    
       <?        
     }?>
     
     <?
     
     echo CHtml::ajaxSubmitButton('Обработать', '', array(
    'type' => 'POST',
    // Результат запроса записываем в элемент, найденный
    // по CSS-селектору #output.
    //'update' => '#fancybox-frame',
    'success'=> 'function(data) { parent.jQuery.fancybox.close(); }',
),
array(
    // Меняем тип элемента на submit, чтобы у пользователей
    // с отключенным JavaScript всё было хорошо.
    'type' => 'submit',
    
));

?>

<?

     $this->endWidget();
}
?>
</fieldset>
