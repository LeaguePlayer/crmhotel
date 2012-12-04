<div class="row">
<?
    if(count($book)>0)
    {
        echo "<h3>Записная книжка пользователя</h3>";
        echo "<ul>";
        foreach ($book as $number)
        {
            echo "<li>$number->phone</li>";
        }
        echo "</ul>";
    }
    else
    {
        echo "<h3>Записная книжка пользователя пуста!</h3>";
    }
?>
</div>