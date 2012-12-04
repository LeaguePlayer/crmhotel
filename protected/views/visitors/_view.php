<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_place')); ?>:</b>
	<?php echo CHtml::encode($data->id_place); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_invite')); ?>:</b>
	<?php echo CHtml::encode($data->id_invite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_stay_begin')); ?>:</b>
	<?php echo CHtml::encode($data->date_stay_begin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_stay_finish')); ?>:</b>
	<?php echo CHtml::encode($data->date_stay_finish); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />


</div>