<?
if(isset($_GET['id'])) $model->id=$_GET['id'];
?>
<?=fnc::loadCalendar($model->id);?>
