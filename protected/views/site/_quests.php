<?$quests = Ticks::model()->findAll(array('select'=>"(sum(sum_for_days)+sum(sum_for_doc)) as sum_for_days,id_clienthotel,id",'condition'=>"date(date_public)<=date(now()) and status = 0",'order'=>'date_public DESC','group'=>'id_clienthotel'));?>
<div class="quests">
<?$last_name='';?>
<?$full_sum=0;?>

<?foreach ($quests as $quest){
    
    $order = ClientHotel::model()->findByPk($quest->id_clienthotel);

    $client_name = Clients::model()->findByPk($order->id_client)->getAttribute('name');
    $id_hotel = HotelOrder::model()->findByPk($order->id_order)->getAttribute('id_hotel');
    $hotel_name = Hotels::model()->findByPk($id_hotel)->getAttribute('name');
        if($hotel_name!=$last_name) {
            echo "<div class='cap_group'>$hotel_name</div>";
            $last_name=$hotel_name;
        }
    
    echo "<div class='litle_a'><a class='load_fancy' href='/?r=ticks/update&id={$quest->id}'>Забрать {$quest->sum_for_days} р. у $client_name</a></div>";
}?>


</div>