<?
$last_hotel = '';

if(count($report)>0)
{
    $n=0;
    foreach ($report as $cl)
    {
        $docs = Documents::model()->find(array('condition'=>"id_clienthotel={$cl->id}",'select'=>'sum(sum_docs) as sum_docs'));
        $n++;
       $hotel  = Hotels::model()->with(array('with_order'=>array('condition'=>"`with_order`.id = {$cl->id_order}",'select'=>false)))->find(array('select'=>'`t`.name'));
       $client = Clients::model()->findByPk($cl->id_client);
       
       if($last_hotel!=$hotel->name)
       {
            $last_hotel = $hotel->name;
            if($n==1)
                echo "<div class='report_head'>";
            else
            {
                echo "</div><div class='report_head'>";
            }
            
            echo "<div class='head_t'>".$hotel->name."</div>";
       }
       else echo "<div class='clear'></div>";
       
       
       
       echo "<div class='left'>".$client->name."</div>";
       echo "<ul class='left'>";
       echo "<li> Деньги за проживание <strong>".$cl->tickets_one->sum_for_days."</strong> руб.</li>";
       if($docs->sum_docs>0)
        echo "<li> Деньги за документы <strong>".$docs->sum_docs."</strong> руб.</li>";
       echo "</ul>";
       
        if($n==count($report))
            echo "</div>";
    }
    
}

fnc::generateBACKuri($_SERVER["HTTP_REFERER"]);
?>