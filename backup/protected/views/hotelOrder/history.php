<?
    if(count($logs)>0)
    {
        ?>
        <table class="history_table">
        <thead>
        <tr>
        <td>Кол-во дней</td>
        <td>Место проживания</td>
        <td>Стоимость за сутки</td>
        <td>Сколько проплатил</td>
        <td>За документы</td>
        <td>С какого</td>
        <td>По какое</td>
        <td>Кто заселял</td>
        </tr>
        </thead>
        <tbody>
        <?
        foreach($logs as $log)
        {
            $days = fnc::intervalDays($log->date_stay_begin,$log->date_stay_finish);
            $id_hotel = HotelOrder::model()->findByPk($log->id_order);
            $hotel_name = Hotels::model()->findByPk($id_hotel->id)->getAttribute('name');
            $current_day_begin =  date('d.m.Y H:i',strtotime($log->date_stay_begin));
            $current_day_finish =  date('d.m.Y H:i',strtotime($log->date_stay_finish));
            $inviter = fnc::getInviters_report($id_hotel->id_invite);
            print("<tr>
        <td>$days</td>
        <td>$hotel_name</td>
        <td>$log->price_per_day руб.</td>
        <td>$log->got_money руб.</td>
        <td>$log->got_money_docs руб.</td>
        <td>$current_day_begin</td>
        <td>$current_day_finish</td>
        <td>$inviter</td>
        </tr>");
        }
        ?>
        </tbody>
        </table>
        <?
    }
    else
    echo "<h1>Нет истории о пользователе</h1>"
    

?>