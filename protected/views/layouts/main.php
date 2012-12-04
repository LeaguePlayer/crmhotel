<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <meta name="viewport" content="width=840, user-scalable=no" /> 
   
    <link rel="apple-touch-icon"   href="/icon.png"/>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <?Yii::app()->clientScript->registerCoreScript('jquery');?>  
    <?Yii::app()->clientScript->registerCoreScript('jquery.ui');?>  
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/lib/fancybox/jquery.fancybox.css?v=2.0.4" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/lib/fancybox/helpers/jquery.fancybox-buttons.css?v=2.0.4" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/lib/fancybox/helpers/jquery.fancybox-thumbs.css?v=2.0.4" media="screen" />
    <?PHP $cs=Yii::app()->getClientScript(); ?>
    <?php $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.upload.js', CClientScript::POS_HEAD); ?>
    <?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/lib/fancybox/jquery.mousewheel-3.0.6.pack.js', CClientScript::POS_HEAD); ?>
    <?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/lib/fancybox/jquery.fancybox.pack.js?v=2.0.4', CClientScript::POS_HEAD); ?>
    <?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/lib/fancybox/helpers/jquery.fancybox-buttons.js?v=2.0.4', CClientScript::POS_HEAD); ?>
    <?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/lib/fancybox/helpers/jquery.fancybox-thumbs.js?v=2.0.4', CClientScript::POS_HEAD); ?>
    <?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/cookie.js', CClientScript::POS_HEAD); ?>
    <?PHP $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/scripts.js', CClientScript::POS_HEAD); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" />
    <title>Администраторская панель Home-City</title>
</head>

<body<?echo (fnc::definePlatformPC() ? '' : ' class="android"')?>>

<?if(count($this->alist)>0){?>
    <div id="actions_list">
        <?foreach ($this->alist as $alist){?>
            <div <?=(isset($_COOKIE['rereserve']) ? 'style="display:none;"' : '')?> class="query" rel="<?=$alist->id?>">
                    <div class="close" rel="<?=$alist->id?>"></div>            
                    <div class="info <?=$alist->post_type?>">
                        <?=$alist->short_desc?>
                    </div>            
                    <div class="panel">
                        <a class="left" href="javascript:void(0);">Переселить</a>
                    </div>            
            </div>
        <?}?>
    </div>
<?}?>



<?php echo $content; ?>


<?if(Yii::app()->controller->createUrl('')=='/site/index'){?>
    <div id="wrap" <?=(isset($_COOKIE['rereserve']) ? 'style="bottom: 0px;"' : '')?>>
    <?
    
        if(isset($_COOKIE['rereserve']))
        {       
            $find_selected = Alist::model()->findByPk($_COOKIE['rereserve'],"status=0");
    echo <<<END
          Выберите ячейку для 
    {$find_selected->short_desc}
    <a rel="{$find_selected->id}" href="javascript:void(0);">(Отменить)</a>
    
END;
            
        }
    ?>
    </div>
<?}?>
    
</body>
</html>