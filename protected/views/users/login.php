<h1>Авторизация</h1>
  
<div class="form">
<?
    echo CHtml::beginForm();
?>

<?
    if($error)
    {
        echo "<div class='errorSummary'><strong>Ошибка!</strong> <p>Для продолжения, исправьте следующие ошибки:</p>";
        echo "<ul>";
        echo $error;
        echo "</ul>";
        echo "</div>";
    }
?>



<div class="row">
    <?
        echo '<label class="required" for="UserLogin_username">Логин <span class="required">*</span></label><br />';
        echo CHtml::textField('log[name]','',array('size'=>60,'maxlength'=>255));
    ?>
</div>

<div class="row">
    <?
        echo '<label class="required" for="UserLogin_username">Пароль <span class="required">*</span></label><br />';
        echo CHtml::passwordField('log[password]','',array('size'=>60,'maxlength'=>255));
    ?>
</div>





	<div class="row buttons">
		<?php echo CHtml::submitButton("Вход"); ?>
	</div>


<?
    echo CHtml::endForm();
?>
</div>

