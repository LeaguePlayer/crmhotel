
<form method="post">
<?
    if(count($book)>0)
    {
        echo "<h3>Записная книжка пользователя</h3>";
        echo "<ul>";
        foreach ($book as $number)
        {
            $link = "<input value='$number->id' type='checkbox' name='rowdelete[$number->id]'>";
            echo "<li>$number->phone - $link</li>";
        }
        echo "</ul>";
    }
    else
    {
        echo "<h3>Записная книжка пользователя пуста!</h3>";
    }
?>
<input name="ring_empty" type="submit" value="Удалить выбранные">
</form>