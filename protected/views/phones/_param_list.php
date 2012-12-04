<form method="post">
<?php echo CHtml::textField('user[name]',$user->name)?><br>
<?php echo CHtml::hiddenField('user[id]',$user->id);?>
<input name="ring_empty" type="submit" value="Переименовать">
</form>