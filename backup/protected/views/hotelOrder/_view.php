<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_hotel')); ?>:</b>
	<?php echo CHtml::encode($data->id_hotel); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_stay_begin')); ?>:</b>
	<?php echo CHtml::encode($data->date_stay_begin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_stay_finish')); ?>:</b>
	<?php echo CHtml::encode($data->date_stay_finish); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price_per_day')); ?>:</b>
	<?php echo CHtml::encode($data->price_per_day); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('places')); ?>:</b>
	<?php echo CHtml::encode($data->places); ?>
	<br />


</div>