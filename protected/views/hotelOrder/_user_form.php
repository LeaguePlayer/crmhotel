<?

if(is_numeric($cost)) 
{
    echo CHtml::beginForm();
    echo CHtml::hiddenField('Hotels[cost]',$cost);
}

if(is_numeric($places) and $TYC==1) $round = $places;
else $round = 1;

for($i = 0; $i<$round; $i++)
{
    ?>
    <div class="relation_part user_div">
        <div class="inline">
        <?php echo CHtml::label('Телефоны',''); ?>
        </div>
        <div class="inline">
              	<?php 
        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(

        'id'=>'hotel_user_phone_'.$i,
        'value'=>(isset($this->user_info['phone']) ? $this->user_info['phone'] : ''),
        'name'=>'hotel['.$i.'][user_phone][]',
        'source'=>$this->createUrl('hotelOrder/AutoComplete'),
        'options'=>array(
            'delay'=>300,
            'minLength'=>2,
            'showAnim'=>'fold',
            'select'=>"js:function(event, ui) {      
              
              
                         $(this).parents('.user_div').find('.hotel_user_name').val(ui.item.username);
                         $(this).parents('.user_div').find('.hotel_user_id').val(ui.item.xyi);                     
                         $(this).parents('.user_div').find('.hotel_user_notice').html(ui.item.notes_link);
                         $(this).parents('.user_div').find('.hotel_user_clear').html(ui.item.clear);
                         
            }"
        ),
        'htmlOptions'=>array(
            'size'=>'40',
            'class'=>'phonecomplite'
        ),
    ));
        ?>
        <div class="place_for_phones">
        
        </div>
        </div>
        <div class="inline">
             <a href="javascript:void(0);" class="new_phone" rel="0"></a>
        </div>
    
        
        <div class="inline">
        <div class="inline">
            <? echo CHtml::label('ФИО','');?>
        </div>
            <input type="text" class="hotel_user_name" name="hotel[<?=$i?>][user_name]" value="<?=(isset($this->user_info['name']) ? $this->user_info['name'] : '')?>" />
            <input type="hidden" class="hotel_user_id" name="hotel[<?=$i?>][user_id]" value="<?=(isset($this->user_info['id_user']) ? $this->user_info['id_user'] : '')?>" />
                <?if($hide_score!=1){?>
                    <div class="inline">
                        <? echo CHtml::label('Счёт','');?>
                    </div>
                    <input type="text" class="hotel_user_score" name="hotel[<?=$i?>][user_score]" value="" />
                <?}?>
        </div>
        <?if($TYC==1){?>
        <?php echo $this->renderPartial('_form_tyc',array('i'=>$i,'date'=>$date)); ?>
        <?}?>
        <div class="inline">
            <div class="hotel_user_notice"></div>  
            <div class="hotel_user_clear"></div>  
        </div>
    </div>
<?}?>

<?if(is_numeric($cost)) 
{
    echo CHtml::submitButton('Забронировать',array('name'=>'button_checker'));
    echo CHtml::endForm();
}?>

<?if($hide_score!=1){?>

        <script type="text/javascript">
        $(document).ready(function(){
           
           var cost = $('#Hotels_cost').val();
           var cnt = $('.hotel_user_score').size();
           var result = recalc_cost(cnt,cost);
        
           $('input.hotel_user_score').val(result);
           window.ajax_busy = false;
           reload_notices();
        });
        </script>
<?}?>