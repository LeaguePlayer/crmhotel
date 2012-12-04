<style>
body
{
        padding-top: 50px;
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
<?php echo $this->renderPartial('_calendar', array('hotels'=>$hotels,'days_back'=>0,'days_prev'=>7)); ?>
</div>
<ul class="body_menu">
<?php  echo $this->renderPartial('_menu'); ?>
</ul>
<div id="top_bg"></div>

<div><input type="hidden" id="user_time" value="<?=time();?>"><input type="hidden" id="typetable" value=""></div>
