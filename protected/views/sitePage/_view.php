<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('url')); ?>:</b>
	<?php echo CHtml::encode($data->url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('meta_title')); ?>:</b>
	<?php echo CHtml::encode($data->meta_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('meta_description')); ?>:</b>
	<?php echo CHtml::encode($data->meta_description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('meta_keywords')); ?>:</b>
	<?php echo CHtml::encode($data->meta_keywords); ?>
	<br />


</div>