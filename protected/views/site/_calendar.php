<?php
$type = $_GET['type'];
switch ($type)
{
   
    default:
    if($_SESSION['access']>4) echo fnc::getTableLitte($hotels,$days_back,$days_prev);
    else 
    {
        
        echo fnc::getTable($days_back,$days_prev,$left);
       
    }
    break;
}


?>
