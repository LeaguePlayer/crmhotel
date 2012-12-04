<?
$platform = fnc::definePlatformPC();
if(!$platform) fnc::generateBACKuri($_SERVER['HTTP_REFERER']);
?>
<div id="header_blog">
<? $day_eng = date('D',strtotime($date_stay))?>

<?if(!isset($_GET['rereserve'])){?>
<h1><?=fnc::getStatus($status)?> <?=Hotels::getItem($id)->getAttribute('name')?> <?=fnc::getSwitcher(Hotels::getItem($id)->getAttribute('default_type'),$id,$date_stay)?></h1>
<?}else{
 $id_last_ho = $_GET['rereserve']['id_order_last'];  
 $last_ho = HotelOrder::model()->findByPk($id_last_ho);
 
?>

<h1>Переселение из <?=Hotels::getItem($last_ho->id_hotel)->name?> в <?=Hotels::getItem($id)->name?> </h1>
<?}?>

<h2><?=fnc::getRealDay($day_eng).' '.$date_stay?></h2>
</div>
<?php echo $this->renderPartial('_form2', array('model'=>$model,'status'=>$status,'id_hotel'=>$id,'date_stay'=>$date_stay,'type'=>$type,'halfmoney'=>$halfmoney)); ?>