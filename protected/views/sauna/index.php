<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/sauna.css" />
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
<?PHP $cs=Yii::app()->getClientScript(); ?>

<?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/sauna.js', CClientScript::POS_HEAD); ?>

<div id="sauna_line">
    <?=$this->renderPartial('_heart',array('user'=>$user,'date'=>$date,'visitors'=>$visitors));?>
</div>
<?
    $last_day = date('Y-m-d',strtotime("-1 day".$date));
    $next_day = date('Y-m-d',strtotime("+1 day".$date));
    $eng_day =  date('D',strtotime($date));
    $day_rus = fnc::getRealDay($eng_day); 
?>
<div style="text-align: center;margin-top: 130px;">Выбрано: <?=date('d.m.Y',strtotime($date))?> (<?=$day_rus?>)</div><br /><br />
<div style="margin: 0px auto 0 auto;width: 500px; overflow: hidden;">
<div style="float: left;"><a href="<?="/sauna/index/date/$last_day"?>">ПРЕДЫДУЩИЙ ДЕНЬ</a></div>

<div style="float:right;"><a href="<?="/sauna/index/date/$next_day"?>">СЛЕДУЮЩИЙ ДЕНЬ</a></div>
</div>
