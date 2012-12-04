<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <?Yii::app()->clientScript->registerCoreScript('jquery');?>  
    <?Yii::app()->clientScript->registerCoreScript('jquery.ui');?>  
        <?PHP $cs=Yii::app()->getClientScript(); ?>
        
        <?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/fancybox/jquery.mousewheel-3.0.4.pack.js', CClientScript::POS_HEAD); ?>
        <?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/fancybox/jquery.fancybox-1.3.4.pack.js', CClientScript::POS_HEAD); ?>
        <?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/scripts.js', CClientScript::POS_HEAD); ?>
    
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css" />
 	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
    
    
    <? //echo CHtml::scriptFile(Yii::app()->request->baseUrl.'/js/jquery.js');?>

    


	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

</head>

<body>

<div class="container" id="page">



	<?php echo $content; ?>



</div><!-- page -->

</body>
</html>