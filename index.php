<?php
function getPoddomen ($url, $num=0) {
$dom_array  = explode('.',$url);
if(count($dom_array)>2)
return $dom_array[$num];
else return 'undefined';
}
$lang = getPoddomen($_SERVER['HTTP_HOST']);

switch ($lang)
{
    case '66':
       $db_lang = array(
			'connectionString' => 'mysql:host=localhost;dbname=lplayer_crm66',
			'emulatePrepare' => true,
			'username' => 'lplayer_crm66',
			'password' => 'qwelpo86',
			'charset' => 'utf8',
		);
    break;
    
    default:
       $db_lang =  array(
    			'connectionString' => 'mysql:host=localhost;dbname=lplayer_crm',
    			'emulatePrepare' => true,
    			'username' => 'lplayer_crm',
    			'password' => 'qwelpo86',
    			'charset' => 'utf8',
    		);
    break;
}

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
