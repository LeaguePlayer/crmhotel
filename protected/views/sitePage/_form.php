<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'site-page-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php $this->widget('application.extensions.tinymce_elfinder.tinymce.ETinyMce', array(
            'name'=>'SitePage[content]',
            'value'=>$model->content,
            'editorTemplate'=>'simple',
            'options' => array(
                'theme' => 'advanced',
                'width'=>'660px',
                'height'=>'450px',
                'plugins' =>'autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template',
                'language'=>"ru",
                //'theme_advanced_buttons1' => "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
            	//'theme_advanced_buttons2' =>"cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            	//'theme_advanced_buttons3' => "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
            	//'theme_advanced_buttons4' =>"insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
                'theme_advanced_toolbar_location' => "top",
            	'theme_advanced_toolbar_align'=>"left",
            	'theme_advanced_statusbar_location' =>"bottom",
            	'theme_advanced_resizing' =>true,
            ),
        
        )); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'meta_title'); ?>
		<?php echo $form->textField($model,'meta_title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'meta_title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'meta_description'); ?>
		<?php echo $form->textarea($model,'meta_description'); ?>
		<?php echo $form->error($model,'meta_description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'meta_keywords'); ?>
		<?php echo $form->textarea($model,'meta_keywords'); ?>
		<?php echo $form->error($model,'meta_keywords'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'visible'); ?>
		<?php echo $form->checkbox($model,'visible'); ?>
		<?php echo $form->error($model,'visible'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->