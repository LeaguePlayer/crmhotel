<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo $data->name; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_account')); ?>:</b>
	<?php 
        $acc =  Users::getUser($data->id_account)->username; 
        if($acc!='') echo $acc; else echo 'Нет привязанного аккаунта';
    ?>
    
	<br />


</div>