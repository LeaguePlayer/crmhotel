<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    
    <? echo CHtml::scriptFile(Yii::app()->request->baseUrl.'/js/jquery.js');?>
	<!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/first.css" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

</head>

<body>





      
       
    


<?echo CHtml::scriptFile(Yii::app()->request->baseUrl.'/js/relink.js');?>
<div class="chess_loader"></div>
<div id="ajax_load_place">

</div>
</body>
</html>