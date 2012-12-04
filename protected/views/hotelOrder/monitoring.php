<?
$platform = fnc::definePlatformPC();
if(!$platform) fnc::generateBACKuri();?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/timepicker/jquery.ui.timepicker.js?v=0.2.4"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/timepicker/include/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/timepicker/include/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/monitor.js"></script>
<link rel="stylesheet" type="text/css" href="/assets/39791ee1/jui/css/base/jquery-ui.css" />
<script type="text/javascript">
    $(document).ready(function(){
        $('.time').timepicker();
    });
</script>

<div id="header_blog"> 
    <h1><?=$info['h1']?></h1>
    <h2><?=$info['h2']?></h2>
</div>

<div id="big_message">Были произведены изменения в редактировании заказа
<div class="scores"></div>
<div class="result"><a href="javascript:void(0);" class="ready_to_edit">Применить</a><a href="javascript:void(0);" class="cancel_edit">Отменить</a></div>
</div>

<div class="overflow">
<fieldset class="settings_room left fifty">
    <legend>Редактирование заказа</legend>
        <?php echo CHtml::beginForm(); ?>
        <?if($model->TYC==0 and $model->status == 1){?>
        <div class="field_settings">
                <label>Провижают от</label><br>
                Число
                <?$this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name'=>'resetting[begin][date]',
            'value'=>$info["begin_date"],
            
            
            // additional javascript options for the date picker plugin
            'options'=>array(
                'showAnim'=>'fold',
                'firstDay'=>1,
                'dateFormat'=>'dd.mm.yy',
            ),
            'htmlOptions'=>array(
                'style'=>'height:16px;'
            ),
        ));?>
        <br>
        Время<?php echo CHtml::textField('resetting[begin][time]',$info["begin_time"],array('class'=>'time') )?>
        </div>
        <div class="field_settings">
                <label>Провижают до</label><br>
                Число<?$this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name'=>'resetting[finish][date]',
            'value'=>$info["finish_date"],
            
            
            // additional javascript options for the date picker plugin
            'options'=>array(
                'showAnim'=>'fold',
                'firstDay'=>1,
                'dateFormat'=>'dd.mm.yy',
            ),
            'htmlOptions'=>array(
                'style'=>'height:16px;'
            ),
        ));?><br>Время<?php echo CHtml::textField('resetting[finish][time]',$info["finish_time"],array('class'=>'time') )?>
        
        </div>
        <?}?>
        <div class="field_settings">
                <label>Статус квартиры</label><br>
              <?php echo CHtml::dropDownList('resetting[status]',$model->status,fnc::resettingsStatus() )?>
        
        </div>
        <?=CHtml::hiddenField('type','resetting')?>
        <input type="submit" value="Сохранить изменения">
        <p class="hit">Рекомендуется использовать в крайних случаях, если Вам необходимо продлить или сократить срок брони, воспользуйтесь нижней плашкой <strong>"Изменение брони"</strong>.</p>
        <?php echo CHtml::endForm(); ?>
</fieldset>
    
    
    
    

<fieldset class="left fifty"><legend>Информация</legend>
<?if($model->tmp_halfmoney>0){?>
    <label>Предоплата</label><br>
    <p><?=date('d.m.Y H:i',strtotime($model->tmp_halfdate))?> внесли предоплату в размере <?=fnc::getRealWord($model->tmp_halfmoney,'рубль','рубля','рублей')?></p>
<?}if($info['get_money']>0){?>
<strong>Сумма полученная с зеселения </strong><?=$info['get_money']?> рублей<br />
    <?if($info['remove_money']<0){?>
    <strong>Сумма возврата </strong><?=-1*$info['remove_money']?> рублей<br />
    <?}?>
<?}else{?>
Информация отсутствует
<?}?>
</fieldset>
</div>

<fieldset class="settings_room">
    <legend>Настройки квартиры</legend>
            <?if(is_object($info['sms_send'])) {?>
                <?if($info['sms_send']->status==0){?>
                <?php echo CHtml::beginForm(); ?>
                <label>СМС Напоминание?</label><br>
                <input name="sms_send" type="submit" value="Отправить">
                <?=CHtml::hiddenField('type','sms')?>
                <?php echo CHtml::endForm(); ?>
                <?}else{?>
                    <div class="row field_settings">СМС Напоминание отправлено!</div>
                <?}?>
            <?}?>
            <?if($model->ring==1){?>
                <?php echo CHtml::beginForm(); ?>
                <label>Напоминание! Необходимо позвонить клиенту</label><br>
                <input name="ring_empty" type="submit" value="Позвонили">
                <?=CHtml::hiddenField('type','ring')?>
                <?php echo CHtml::endForm(); ?>
            <?}?>
            <?php echo CHtml::beginForm(); ?>
                <div class="field_settings">
                    <label>Колличество мест</label>
                    <?php echo CHtml::dropDownList('edit[places]',$model->places,fnc::getHotelCategory($info['hotel_category']))?>
                </div>
                
                <div class="field_settings">
                    <label>Кто заселил?</label>        
                    <?php echo CHtml::dropDownList('edit[id_invite]',$model->id_invite,fnc::getInviters())?>        
                </div>
                
                <?if($info['show_remember']){?>    
                    <div><label>Уточните время и дату напоминания</label><br><?$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name'=>'remember[date]',
                    'value'=>$info['remember_date'],
                    // additional javascript options for the date picker plugin
                    'options'=>array(
                    'showAnim'=>'fold',
                    'firstDay'=>1,
                    'dateFormat'=>'dd.mm.yy',
                    ),
                    'htmlOptions'=>array(
                    'style'=>'height:16px;'
                    ),
                    ));?><br><?php echo CHtml::textField('remember[time]',$info['remember_time'],array('class'=>'time') )?></div>
                <?}?>
                
                <?if($model->status==1 or $model->status==5){?>
                    <div class="field_settings">
                        <? echo CHtml::linkButton('Снять бронь', array(
                        'submit'=>array(
                        'hotelOrder/delete',
                        'id' => $model->id,
                        ),
                        'params'=>array(
                        
                        //'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken
                        ),
                        'confirm'=>"Точно снять бронь?",'id'=>'cancel_reserved_cell',
                        ))?>            
                    </div>
                <?}?>        
            <?=CHtml::hiddenField('type','edit')?>
            <input type="submit" value="Сохранить настройки">    
        <?php echo CHtml::endForm(); ?>
</fieldset>


<fieldset class="settings_room">
        <legend>Настройки дополнительной уборки</legend>
    
        <?php echo CHtml::beginForm(); ?>
                <label>Следующая уборка запланирована на</label><br>
                <?$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name'=>'clean[date_cleaning]',
                            'value'=>$info['date_cleaning'],
                            
                            
                            // additional javascript options for the date picker plugin
                            'options'=>array(
                            'showAnim'=>'fold',
                            'firstDay'=>1,
                            'dateFormat'=>'dd.mm.yy',
                            ),
                            'htmlOptions'=>array(
                            'style'=>'height:16px;'
                            ),
                            ));?>
           
           <?=CHtml::hiddenField('type','edit_clean')?>
            <input type="submit" name="go_later" value="Перенести уборку">    
            <?
            if(strtotime($info['date'])==strtotime(date('Y-m-d')))
                {
                    if(strtotime(date('Y-m-d'))==strtotime($info['date_cleaning']))
                    {
                    ?>
                        <input type="submit" name="clean_complete" value="Уборка выполнена!">   
                    <?
                    }
                }
                ?>
        <?php echo CHtml::endForm(); ?>
            
</fieldset>

<?if(($model->status==0 or $model->status==4) and $model->TYC!=1){?>
<?
    $cnt_users_tyc = ClientHotel::model()->count("id_order = {$model->id} and date(date_stay_finish)='{$info['date']}'");
?>
<?if(count($cnt_users_tyc)>0){?>
<?if($info['cnt_on_this_date']<2){?>
    <fieldset class="settings_room">
        <legend>Продлить проживание</legend>
    
            
            <div class="field_settings">
              Продлить ( кол-во <label id="switch_day_hour"><input name="param[hour]" type="checkbox" value="1" /><span class="days current">Дней</span><span class="hours">Часов</span></label> ) <br />
              
                  <div class="scores">Сумма за продление составит <strong>0</strong> рублей.</div>
                  <br /> 
              
                <?php echo CHtml::textField('newLive[days]','1',array('id'=>'howdays') )?><br />
                <?if($model->TYC!=1){?>
                    <div class="hidden_time" style="display: none;">
                        <label>Укажите время и дату напоминания</label><br />
                            <?$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name'=>'newLive[date_remember]',
                            'value'=>$info['date'],
                            
                            
                            // additional javascript options for the date picker plugin
                            'options'=>array(
                            'showAnim'=>'fold',
                            'firstDay'=>1,
                            'dateFormat'=>'dd.mm.yy',
                            ),
                            'htmlOptions'=>array(
                            'style'=>'height:16px;'
                            ),
                            ));?>
                            <br>
                            <?php echo CHtml::textField('newLive[time]',date('H:i'),array('class'=>'time') )?>
                    </div>
                <?}?>
                
                <?
                echo CHtml::ajaxLink ('Продлить проживание',
                         CController::createUrl('hotelOrder/Extend'),
                         array('data'=>"js:$('.newLive_form').serialize()+'&days='+$('#howdays').val()+'&remember='+$('#newLive_time').val()+'&remember_date='+$('#newLive_date_remember').val()+'&switcher='+$('#switch_day_hour input').attr('checked')",'type'=>'POST','success'=>'function(data){if(data==="OK") parent.jQuery.fancybox.close(); else if (data==="redirect") {window.location="/";} else alert(data);}'),
                         array('class' => 'add','id'=>'cont_live')); 
                ?>
                
            </div>
           
            
    </fieldset>
 <?}?>
 <?}?>
 <?}?>
 
<div id="living_users_list">

<?php echo $this->renderPartial('_live_users', array('users'=>$users,'model'=>$model,'date'=>$info['date'])); ?>

</div>

<?if($info['freeusers']>0){?>
<div class="free_places"><a href="/hotelOrder/users?&id_order=<?=$model->id?>&date=<?=$info['date']?>">Свободных мест <span><?=$info['freeusers']?></span></a></div>

<script>
$(document).ready(function(){
            $('.free_places a').fancybox({
    'type' : 'iframe',
         'width' : '95%',
     'height' : '95%',
     'autoScale' : false,
     'transitionIn' : 'elastic',
     'transitionOut' : 'elastic',
     'onClosed' : function(){
        
                   
                   var date = '<?php echo $info['date']; ?>';                  
                   var id_order = '<?php echo $model->id; ?>';
                   var str = "r=hotelOrder/fastupdate&date="+date+"&id_order="+id_order;
      
                           $.ajax({
                  url: '/',
                  type: "GET",
                  data: str,
                  
                  success: function(data) {
                      $('#living_users_list').html(data);
                      var cnt_lives = $('.add').size();
                     
                      var cnt_slots = $('#rechange_places').val();
                      cnt_slots  = parseInt(cnt_slots);
                      var result = cnt_slots-cnt_lives;
                      
                      if(result>0)
                      {
                        $('.free_places a span').html(result);
                      }
                      else
                      {
                        $('.free_places').remove();
                      }
                  }
                  });
        
     },

}); 
});
</script>
<?}?>

<?if($info['finish_date']==$info['date']){?>
<?if($info['cnt_on_this_date']<2){?>
<div id="reserved_after">
    <?=CHtml::link('Забронировать после этого заказа', array('hotelOrder/reserve', 'id'=>$model->id_hotel, 'date'=>$info['date']));               ?>
</div>
<?}?>
<?}?>

