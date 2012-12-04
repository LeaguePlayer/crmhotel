<h2>Отчёт за <?=$date?></h2>
<?switch ($type)
{
    case 'by_day':
    
       echo $this->renderPartial('_by_day', array('reports'=>$report));
    break;
}
?>