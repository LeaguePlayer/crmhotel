<?php
$div = "<table id='driver_table'>";
    $div .="<thead>";
        $div.="<tr>";
        $div .= "<td>Выписанная сумма</td>";
        $div .= "<td>Когда выписали</td>";
        $div .= "<td>Комментарий</td>";
        $div .= "<td colspan='2'>Действия</td>";
        $div.="</tr>";
    $div .="</thead>";
    
    
    
    $div .="<tbody>";
    foreach ($docs_for_drivers as $doc)
    {
     
        if(is_numeric($array_with_all_found_fly_ticks[$doc->id_clienthotel]))
        {
            $second_sum = $array_with_all_found_fly_ticks[$doc->id_clienthotel];
            unset($array_with_all_found_fly_ticks[$doc->id_clienthotel]);
        }
        else $second_sum = 0;
        
        $itogo =  round($second_sum+$doc->price->price,-2);
        
        $correct_date = date('d.m.Y H:i',strtotime($doc->date_public));
        $div .="<tr>";
        
        $div .= "<td>{$doc->price->price} + {$second_sum} руб (Итого <strong>{$itogo}</strong> руб)</td>";
        $div .= "<td>$correct_date</td>";
        $div .= "<td>{$doc->price->node}</td>";
        $div .= "<td><a href='/documents/update/$doc->id'>Редактировать</a></td>";
        $div .= "<td><a class='get_pay' alt='$doc->id_clienthotel' rel='$doc->id' href='javascript:void(0);'>Оплатить</a></td>";
        
        $div .="</tr>";
    }
    if(count($array_with_all_found_fly_ticks)>0)
    {
        foreach($array_with_all_found_fly_ticks as $id_clienthotel => $ticket)
        {
            $div .="<tr style='background:#f2f3ff;'>";
        
            $div .= "<td>$ticket руб (Итого <strong>{$ticket}</strong> руб)</td>";
            $div .= "<td></td>";
            $div .= "<td>Проживание ТУЦ</td>";
            
            $div .= "<td style='text-align:center;' colspan='2'><a class='get_pay_tick' rel='$id_clienthotel' href='javascript:void(0);'>Оплатить</a></td>";

            $div .="</tr>";
        }
    }
    
    $div .="</tbody>";
$div .="</table>";
echo $div;