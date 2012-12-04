<?
    $find_count_docs = Documents::model()->count("status=0");
    $find_fly_ticks = Ticks::model()->count("status=6");
    $tmp_sum = $find_fly_ticks + $find_count_docs;
    if($tmp_sum>0)
        $formered_form = "<div class='finded_docs'>$find_count_docs / $find_fly_ticks</div>";
?>
<?=$formered_form?><a class="fancy_run" href="/documents/create" title="Выписка документов"></a>