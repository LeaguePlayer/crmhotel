

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/reportz.css" media="screen" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/reportm.js"></script>


<?=CHtml::hiddenField('date_for_ajax',date('Y-m-d',strtotime($date)))?>
<div id="guru_report">
    <div class="report_for_copy original">
        <h2>Отчёт за <?=$date?><?=($found_report ? " <span style='color:#f00;'>(Закрытый)</span>": "")?></h2>
        <?switch ($type)
        {
            case 'by_day':

               echo $this->renderPartial('_by_day', array('reports'=>$report,'docs'=>$docs,'service'=>$service,'sauna'=>$sauna,'payments'=>$payments,'goback'=>$goback,'model'=>$model,'found_report'=>$found_report,'agree_form'=>$agree_form,'date'=>$date));
            break;
        }

        ?>
    </div>
    <?if($find_report->dublicate_report) echo '<div class="report_for_copy dubl">'.$find_report->dublicate_report.'</div>';?>
   </div>
    <?if(extension_loaded('gd') and !$found_report and $agree_form):?>
    
   <div class="dublicate_report">
        <div>Не правильный отчет? Закройте его, и работайте с дублем!</div>
    </div>  
    
    <div class="send_my_report">
    <?=CHtml::beginForm();?>

        <div> <?$this->widget('CCaptcha')?></div>
        <div>
        <?=CHtml::activeLabelEx($model, 'verifyCode')?>
        
        <?=CHtml::activeTextField($model, 'verifyCode')?><br />
        </div>
        <?php echo CHtml::submitButton('Отправить отчёт'); ?>

    <?=CHtml::endForm();?>
    </div>
<?  elseif(strtotime($date)<time() and isset($find_report->id) and $find_report->dublicate_report==""):?>
                
       <div class="dublicate_report">
        <div>Не правильный отчет? <span>Создать дубликат отчета</span></div>
        
    </div>     

<?endif?>
<input type="hidden" id="id_report" value="<?=$find_report->id?>" />

