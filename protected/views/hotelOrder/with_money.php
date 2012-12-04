<?if(!$platform) fnc::generateBACKuri($_SERVER['HTTP_REFERER']);?>
<?PHP $cs=Yii::app()->getClientScript(); ?>
<?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/timepicker/jquery.ui.timepicker.js?v=0.2.4', CClientScript::POS_HEAD); ?>
<?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/timepicker/include/jquery.ui.core.min.js', CClientScript::POS_HEAD); ?>
<?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/timepicker/include/jquery.ui.widget.min.js', CClientScript::POS_HEAD); ?>
<?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/hotelOrder.js', CClientScript::POS_HEAD); ?>
<link rel="stylesheet" type="text/css" href="http://admin.hotel72.ru/assets/39791ee1/jui/css/base/jquery-ui.css" />


<h1><?=$caption?></h1>
<h3><?=$infodate?></h3>
<h3><?=fnc::getSwitcher($hotel->default_type,$hotel->id,$date)?></h3>

<?=$error;?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'hotel'=>$hotel,'date'=>$date,'date_for_picker'=>$date_for_picker,'hidden_blog_post_pay'=>$hidden_blog_post_pay,'hidden_blog'=>$hidden_blog)); ?>