<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sum_docs')); ?>:</b>
	<?php echo CHtml::encode($data->sum_docs); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_invite')); ?>:</b>
	<?php echo CHtml::encode($data->id_invite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_public')); ?>:</b>
	<?php echo CHtml::encode($data->date_public); ?>
	<br />


</div>