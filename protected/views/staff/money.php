<h1>Добро пожаловать в систему бухгалтерского учета Home-City</h1>

<div>
    Вы выбрали сотрудника <strong><?=$selected_user->name?></strong><?=($selected_user->account->username ? ', к нему привязан логин<strong> '.$selected_user->account->username.'</strong>' : '')?>
</div>
<div>
    <div class="switcher_conteiner">Выберите Операцию: <span class="minus"></span> <span class=""></span></div>
    <div class="conteiner_for_tab">
        <div>
            <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'money-form',
                    'enableAjaxValidation'=>false,
            )); ?>


                    <?php echo $form->errorSummary($model); ?>
            
                    <div class="row">
                            <?php echo $form->labelEx($model,'id_type'); ?>
                            <?php echo $form->dropDownList($model,'id_type',  PaymentsOrder::getType()); ?>
                            <?php echo $form->error($model,'id_type'); ?>
                    </div>
               
                    <div class="row credit_option" <?=($model->id_type!=1 ? 'style="display:none;"' : '')?>>
                            <?php echo $form->labelEx($model,'credit_option'); ?>
                            <?php echo $form->checkBox($model,'credit_option'); ?>
                            <?php echo $form->error($model,'credit_option'); ?>
                    </div>
                    
                    <div class="row">
                            <?php echo $form->labelEx($model,'id_invite'); ?>
                            <?php echo $form->dropDownList($model,'id_invite',fnc::getInviters()); ?>
                            <?php echo $form->error($model,'id_invite'); ?>
                    </div>      
                   
            
                    <div class="row">
                            <?php echo $form->labelEx($model,'price'); ?>
                            <?php echo $form->textField($model,'price'); ?>
                            <?php echo $form->error($model,'price'); ?>
                    </div>
            
                   

                    <div class="row buttons">
                            <?php echo CHtml::submitButton($model->isNewRecord ? 'Выписать' : 'Изменить'); ?>
                    </div>

            <?php $this->endWidget(); ?>
        </div>
        
    </div>
    
    
    <?if(count($userinfo['credit'])>0){?>
        <div class="confirm_table">
            <h3>Кредитная история</h3>
            <table class="credittable">
                <thead>
                    <tr>
                        <td>Дата транзакции</td>
                        <td>Сумма</td>
                    </tr>
                </thead>
                <tbody>
                    <?$full_sum=0;?>
                    <?foreach($userinfo['credit'] as $credit_row){?>
                            <tr class="<?=($credit_row->credit_option==1 ? 'plusik' : 'minus')?>">
                               <td><?=date('d.m.Y H:i',strtotime($credit_row->date_public))?></td>
                               <td><?=$credit_row->price?>  руб.</td>
                               <?
                                $full_sum += ($credit_row->credit_option==1 ? ($credit_row->price*1) : ($credit_row->price*-1));
                               ?>
                            </tr>
                    <?}?>
                            <tr><td class="super_itog" colspan="2">Итого по кредиту: <strong><?=$full_sum?></strong> руб.</td></tr>
                </tbody>
            </table>
            
        </div>
    <?}?>

    <?if(count($userinfo['report'])>0){?>
        <div class="confirm_table">
            <h3>Деньги под отчет</h3>
            <table class="credittable">
                <thead>
                    <tr>
                        <td>Дата транзакции</td>
                        <td>Сумма</td>
                        <td>Операции</td>
                    </tr>
                </thead>
                <tbody>
                    <?$full_sum=0;?>
                    <?foreach($userinfo['report'] as $credit_row){?>
                            <tr class="<?=($credit_row->status==1 ? 'plusik' : 'minus')?>">
                               <td><?=date('d.m.Y H:i',strtotime($credit_row->date_public))?></td>
                               <td><span class="report_sum"><?=$credit_row->price?></span>  руб.</td>
                               <td><a class='got_tick' href="javascript:void(0);" rel="<?=$credit_row->id?>"><?=($credit_row->status==0 ? 'Отсчетался' : 'Не отсчетался')?></a></td>
                               <?
                                $full_sum += ($credit_row->status==0 ? ($credit_row->price) : 0);
                               ?>
                            </tr>
                    <?}?>
                            <tr><td class="super_itog" colspan="2">Еще не отсчетались на сумму: <strong><?=$full_sum?></strong> руб.</td></tr>
                </tbody>
            </table>
            
        </div>
    <?}?>
    
        <?if(count($userinfo['cache'])>0){?>
        <div class="confirm_table">
            <h3>Зарплатная история</h3>
            <table class="credittable">
                <thead>
                    <tr>
                        <td>Дата транзакции</td>
                        <td>Сумма</td>
                    </tr>
                </thead>
                <tbody>
                    <?$full_sum=0;?>
                    <?foreach($userinfo['cache'] as $credit_row){?>
                            <tr>
                               <td><?=date('d.m.Y H:i',strtotime($credit_row->date_public))?></td>
                               <td><?=$credit_row->price?>  руб.</td>
                               <?
                                $full_sum += $credit_row->price;
                               ?>
                            </tr>
                    <?}?>
                            <tr><td class="super_itog" colspan="2">Итого по зарплате за все время: <strong><?=$full_sum?></strong> руб.</td></tr>
                </tbody>
            </table>
            
        </div>
    <?}?>
    
</div>
