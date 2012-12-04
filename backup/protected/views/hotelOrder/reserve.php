<div id="header_blog">
<? $day_eng = date('D',strtotime($date_stay))?>
<h1><?=fnc::getStatus($status)?> <?=Hotels::getItem($id)->getAttribute('name')?></h1>
<h2><?=fnc::getRealDay($day_eng).' '.$date_stay?></h2>
</div>

<?php echo $this->renderPartial('_reserve_form', array('model'=>$model,'status'=>$status,'id_hotel'=>$id,'date_stay'=>$date_stay,'type'=>$type)); ?>