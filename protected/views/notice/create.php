<h4><strong>Список замечаний</strong></h4>
<form method="post">
<?
    if(count($notices)>0)
    {
     
        echo "<ul>";
        foreach ($notices as $notice)
        {
            $link = "<input value='$notice->id' type='checkbox' name='rowdelete[$notice->id]'>";
            echo "<li>$notice->text - $link</li>";
        }
        echo "</ul>";
    }
    else
    {
        echo "<h3>Замечаний нет!</h3>";
    }
?>
<input name="ring_empty" type="submit" value="Удалить выбранные">
</form>
<br>
<h4><strong>Добавление замечания</strong></h4>
<?php echo $this->renderPartial('_form', array('model'=>$model,'id_user'=>$id_user)); ?>