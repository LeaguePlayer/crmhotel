<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_clienthotel')); ?>:</b>
	<?php echo CHtml::encode($data->id_clienthotel); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_period_begin')); ?>:</b>
	<?php echo CHtml::encode($data->date_period_begin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_period_finish')); ?>:</b>
	<?php echo CHtml::encode($data->date_period_finish); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('finish_sum')); ?>:</b>
	<?php echo CHtml::encode($data->finish_sum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('note')); ?>:</b>
	<?php echo CHtml::encode($data->note); ?>
	<br />


</div>