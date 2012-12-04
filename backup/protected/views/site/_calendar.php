<?php
$type = $_GET['type'];
switch ($type)
{
    case 'moneytable':
        echo fnc::getMoneyTable($hotels,$days_back,$days_prev);
    break;
    default:
    if($_SESSION['access']>4) echo fnc::getTableLitte($hotels,$days_back,$days_prev);
    else echo fnc::getTable($hotels,$days_back,$days_prev);
    break;
}


?>
