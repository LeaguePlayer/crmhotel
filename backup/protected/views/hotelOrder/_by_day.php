<style>

.reporttable
{
    width: 450px;
}
.reporttable tr td
{
    text-align: center;
    border-bottom: 1px solid #000;
    padding: 0;margin: 0;
}
.reporttable thead tr td
{   
     font-weight: bold;
}
</style>
<table class="reporttable">
<thead>
    <tr>
        <td>
        Кто заселял
        </td>
        <td>
        Уже оплачено / Общая сумма
        </td>
    </tr>
</thead>
    <tbody>
        
            <?
     
            foreach ($reports as $report)
            {
        //      fnc::mpr($report);
        if($report['sum(finish_sum)']=='') $report['sum(finish_sum)']=0;
        if($report['sum_for_days']=='') $report['sum_for_days']=0;
            
                 echo "<tr>";
                 echo "<td>".fnc::getInviters_report($report['id_invite'])."</td>";
                
                echo "<td>";
                echo $report['sum(finish_sum)'].' / ';
                echo "{$report['sum_for_days']}</td>";
                   
                 
                 echo "</tr>"; 
               
            }
              
            ?>
        
    </tbody>
</table>