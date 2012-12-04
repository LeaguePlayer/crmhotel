<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('purchase_price')); ?>:</b>
	<?php echo CHtml::encode($data->purchase_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sales_price')); ?>:</b>
	<?php echo CHtml::encode($data->sales_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('brought_cnt')); ?>:</b>
	<?php echo CHtml::encode($data->brought_cnt); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_delivery')); ?>:</b>
	<?php echo CHtml::encode($data->date_delivery); ?>
	<br />


</div>