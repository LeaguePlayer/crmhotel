<?
    $itogo = 0; // Инициализируем переменные
    $itogo_by_office = array();
    
    
    if(isset($reports) and !empty($reports)) // Работаем с отчетом по прибыли
    {?>
        
<div class="parent_report">
    <span class="cap_h5">Отчёт по прибыли</span>
    <ul>
        <?
            $sum = 0;
            foreach ($reports as $report)
            {
                if($report['id_invite']==4 OR $report['id_invite']==3) $class_warning="miss_kassa";
                else $class_warning=false;
                
                if($report['finish_sum']=='') $report['finish_sum']=0;

            $warning = (HotelOrder::checkUncurrectMoney($report['id_invite'],$date) ? ' style="display:inline-block"' : ' style="display:none;"');
        ?>

        <li rel="<?=$report['id_invite']?>" class="<?=$class_warning?>">
            <div<?=$warning?> title='Этот отчет сформирован с ошибкой!' class='give_warning'></div>
            <?=fnc::getInviters_report($report['id_invite'])?>  -  <span class="ready_for_edit"><?=$report['finish_sum']?></span> 
            <span class="more_info" alt="<?=$report['id_invite']?>" rel="ticks"></span>
        </li>             

        <?  
                if(!$class_warning) $sum +=$report['finish_sum'];    
                
                $itogo_by_office[$report['id_invite']] += $report['finish_sum'];
                             
                echo "</tr>"; 
            }                  
        ?>
    </ul>      
     
    <br>
    <div class="itogo_sum">
      <strong>Итого</strong>: <?=$sum?> руб.
    </div>
    <?$itogo += $sum;?>
    <br /><br />
</div>
<?}?>


<?
    if(isset($sauna) and !empty($sauna)) // Работаем с отчетом по сауне
    {
        ?>
        
<div class="parent_report">
    <span class="cap_h5">Отчёт по сауне</span>
    <ul>
        <?
            $sum = 0;
            foreach ($sauna as $saun)
            {
                
                if($saun['id_invite']==4 OR $saun['id_invite']==3) $class_warning="miss_kassa";
                    else $class_warning=false;
                if($saun['finish_sum']=='') $saun['finish_sum']=0;      
        ?>    

        <li rel="<?=$saun['id_invite']?>" class="<?=$class_warning?>">
                <?=fnc::getInviters_report($saun['id_invite'])?>  -  <span class="ready_for_edit"><?=$saun['finish_sum']?></span> 
                <span class="more_info" alt="<?=$saun['id_invite']?>" rel="sauna"></span>
        </li>             

        <?  
                if(!$class_warning) $sum +=$saun['finish_sum'];      
                
                $itogo_by_office[$saun['id_invite']] += $saun['finish_sum'];
                
                echo "</tr>"; 
            }                  
        ?>
    </ul>      
      
    <br>
    <div class="itogo_sum">
       <strong>Итого</strong>: <?=$sum?> руб.
    </div>
    <?$itogo += $sum;?>
    <br /><br />
</div>
<?}?>


<?
    if(isset($goback) and !empty($goback)) // Работаем с отчетом возвратов
    {
        ?>
    <div class="parent_report">
            <span class="cap_h5">Отчёт по возвратам</span>
            <ul>
                <?
                    $sum = 0;
                    foreach ($goback as $report)
                    {
                       
                        
                        if($report['id_invite']==4 OR $report['id_invite']==3) $class_warning="miss_kassa";
                            else $class_warning=false;
                        if($report['sum(finish_sum)']=='') $report['sum(finish_sum)']=0;
                ?>    

                <li rel="<?=$report['id_invite']?>" class="<?=$class_warning?>">
                        <?=fnc::getInviters_report($report['id_invite'])?>  -  <span class="ready_for_edit"><?=$report['sum(finish_sum)']?></span>
                </li>             

                <?  
                        if(!$class_warning) $sum +=$report['sum(finish_sum)'];   
                        
                        $itogo_by_office[$report['id_invite']] += $report['sum(finish_sum)'];
                        
                        echo "</tr>"; 
                    }                  
                ?>
            </ul>

        <br>

        <div class="itogo_sum">
            <strong>Итого</strong>: <?=(-1*$sum)?> руб.
        </div>
        <?$itogo += $sum;?>
        <br /><br />
    </div>
<?}?>

<?
    if(count($docs)>0) // Работаем с отчетом документов
    {
?>
    <div class="parent_report">
        <br><br>
        <span class="cap_h5">Отчёт за выписанные документы</span>
        <ul>
            <?
                $sum = 0;
                foreach ($docs as $doc)
                {
                    
                    $field_ob = '';
                    $field_ob =  (is_object($doc) ? fnc::getInviters_report($doc->id_invite) : fnc::getInviters_report($doc['id_invite']));
                    $field_id_invite =  (is_object($doc) ? $doc->id_invite : $doc['id_invite']);
                    
                    $sum_to_field = (is_object($doc) ? $doc->status : $doc['sum(finish_sum)']);
                    if($field_id_invite==4 OR $field_id_invite==3) $class_warning="miss_kassa";
                            else $class_warning=false;


            ?>    

            <li rel="<?=$field_id_invite?>" class="<?=$class_warning?>">
                    <?=$field_ob?>  -  <span class="ready_for_edit"><?=$sum_to_field?></span>
            </li>             

            <?  
                    if(!$class_warning) $sum +=$sum_to_field; 
                    
                    $itogo_by_office[$field_id_invite] += $sum_to_field;
                    
                    echo "</tr>"; 
                }                  
            ?>
        </ul>    

        <br>

        <div class="itogo_sum">
            <strong>Итого</strong>: <?=$sum?> руб.
        </div>
        <?$itogo += $sum;?>
    </div>
<?}?>



<?
    if(count($service)>0) // Работаем с отчетом выписанных счетов, товаров
    {
?>
    <div class="parent_report">
        <br><br>
        <span class="cap_h5">Отчёт за выписанные счета на товары/услуги</span>
        <ul>
            <?
                $sum = 0;
                foreach ($service as $doc)
                {
                    
                    $field_ob = '';
                    $field_ob =  (is_object($doc) ? fnc::getInviters_report($doc->id_invite) : fnc::getInviters_report($doc['id_invite']));
                    $field_id_invite =  (is_object($doc) ? $doc->id_invite : $doc['id_invite']);
                    
                    $sum_to_field = (is_object($doc) ? $doc->status : $doc['sum(finish_sum)']);
                    if($field_id_invite==4 OR $field_id_invite==3) $class_warning="miss_kassa";
                            else $class_warning=false;


            ?>    

            <li rel="<?=$field_id_invite?>" class="<?=$class_warning?>">
                    <?=$field_ob?>  -  <span class="ready_for_edit"><?=$sum_to_field?></span>
            </li>             

            <?  
                    if(!$class_warning) $sum +=$sum_to_field; 
                    
                    $itogo_by_office[$field_id_invite] += $sum_to_field;
                    
                    echo "</tr>"; 
                }                  
            ?>
        </ul>    

        <br>

        <div class="itogo_sum">
            <strong>Итого</strong>: <?=$sum?> руб.
        </div>
        <?$itogo += $sum;?>
    </div>
<?}?>



<?
    if(count($payments)>0) // Работаем с отчетом затрат
    {
?>
    <div class="parent_report">
        <br><br>
        <span class="cap_h5">Затраты</span>
        <ul>
            <?
                $sum = 0;
                foreach ($payments as $doc)
                {
                    
                    $field_ob = '';
                    $field_ob =  (is_object($doc) ? fnc::getInviters_report($doc->id_invite) : fnc::getInviters_report($doc['id_invite']));
                    $field_id_invite =  (is_object($doc) ? $doc->id_invite : $doc['id_invite']);
                    
                    $sum_to_field = (is_object($doc) ? $doc->price : $doc['sum(finish_sum)']);
                    if($field_id_invite==4 OR $field_id_invite==3) $class_warning="miss_kassa";
                            else $class_warning=false;

            ?>    

            <li rel="<?=$field_id_invite?>" class="<?=$class_warning?>">
                    <?=$field_ob?>  -  <span class="ready_for_edit">-<?=$sum_to_field?></span>
                    <span class="more_info" alt="<?=$field_id_invite?>" rel="zatrati"></span>
            </li>             

            <?  
                    if(!$class_warning) $sum -=$sum_to_field;  
                   
                    $itogo_by_office[$field_id_invite] -= $sum_to_field;
                    
                    echo "</tr>"; 
                }                  
            ?>
        </ul>        

        <br>

        <div class="itogo_sum">
            <strong>Итого</strong>: <?=$sum*-1?> руб.
        </div>

        <?$itogo += $sum;?>
    </div>
<?}?>

<?
    if(is_numeric($itogo)) // Выводим общий итог за день по всем отчетам
    {
?>
<hr>

<div id="itogo_sum">
    <strong>Итого за сутки наличные</strong>: <span><?=$itogo?></span> руб.
</div>

<?
    if(count($itogo_by_office)>0)
    {
        $owner_sum = 0;
        echo "<div id='by_office'>";
        echo "<strong>Итого по категориям</strong>";
            echo "<ul>";
            foreach ($itogo_by_office as $id_invite => $by_office)
            {
                if($id_invite==4 OR $id_invite==3) $class_warning="miss_kassa";
                else $class_warning=false;
                            
                echo "<li rel='{$id_invite}' class='{$class_warning}'>";
                    echo fnc::getInviters_report($id_invite);
                        echo " - ";
                    echo "<span>{$by_office}</span> руб.";
                        $owner_sum += $by_office;
                echo "</li>";
            }
            echo "</ul>";
        echo "</div>";
        
        echo "<div id='owner_sum'>";
            echo "<strong>Итого за сутки</strong>: <span>{$owner_sum}</span> руб.";
        echo "</div>";
    }
?>

<?}?>


