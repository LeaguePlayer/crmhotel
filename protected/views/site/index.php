<style>
body
{
        padding-top: 47px;
        
}
.chess_body .right_part
{
    width: 616px !important;
}
</style>
<?echo CHtml::scriptFile(Yii::app()->request->baseUrl.'/js/site.js');?>
<?echo CHtml::scriptFile(Yii::app()->request->baseUrl.'/js/afterloadcalendar.js');?>
<?php
// $hotels - Объект, где храняться все гостиницы
    
?>
<div id="navigation_scrolls">
<a id="back_cal" href="javascript:void(0);"></a>
<a id="next_cal" href="javascript:void(0);"></a>
</div>

<div id="calendar_on_main_br">
<?php echo $this->renderPartial('_calendar', array('hotels_tyc'=>$hotels_tyc,'days_back'=>4,'days_prev'=>10)); ?>
</div>
<div class="body_menu">
    <div class="router">
        <ul class="left">
        <?php  echo $this->renderPartial('_menu_2',array('users'=>$user)); ?>
        </ul>
        <ul class="right">
        <?php  echo $this->renderPartial('_menu',array('users'=>$user,'settings'=>$settings)); ?>
        </ul>
    </div>
</div>
<div id="top_bg"></div>
<?if($show) {?>
<script>
$(document).ready(function(){
    $.fancybox('<strong>Внимание!</strong><br>Отчёт за вчерашний день не был закрыт, возможно Вы забыли это сделать, в случае избежания потери целостности данных, предлагаем Вам закрыть отчёт.');
 
});
</script>
<?}?>
<div><input type="hidden" id="user_time" value="<?=time();?>"><input type="hidden" id="typetable" value="<?=$_GET['cat']?>"></div>

<div id="place_for_scripts">
    <?php echo $this->renderPartial('_scripts_load', array('script'=>'')); ?>
</div>