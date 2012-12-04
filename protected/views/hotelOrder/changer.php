<?
$platform = fnc::definePlatformPC();
if(!$platform) fnc::generateBACKuri($_SERVER['HTTP_REFERER']);
?>
<?=$form?>