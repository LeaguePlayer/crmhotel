
<li class="e"><a href="/users/logout" title="Выход"></a></li>

<?if($users->getAccess()<=2){?>

<li class="doc"><?php echo $this->renderPartial('/site/_menu_doc'); ?></li>
<li class="staff"><a class="fancy_run" href="/staff/admin" title="Сотрудники"></a></li>
<?}?>
<?if($users->getAccess()==1){?>
<li class="home"><a class="fancy_run" href="/hotels/admin" title="Управление квартирами"></a></li>
<li class="pageadmin"><a class="fancy_run" href="/sitePage/admin" title="Управление сайтом"></a></li>
<li class="point_start"><a class="fancy_run" href="/userFrom/admin" title="Точки прибытия ТУЦ"></a></li>
<li class="accounts"><a class="fancy_run" href="/users/admin" title="Управление аккаунтами"></a></li>
<li class="reports"><a href="/reports/list" title="Отчеты за месяц"></a></li>
<?}?>


<?if($users->getAccess()!=4){?>
<li class="redo"><a onclick="return false;" class="ajax_go_back" href="/sqlLogs/goback" title="Отменить последнее действие"></a></li>
<?}?>

<?if($users->sauna_access_check()){?>

    <?if(Yii::app()->controller->createUrl('')=='/sauna/index'){?>
    <li><a title="Квартиры" href="/"><img src="/images/home.png" /></a></li>
    <?}else{?>
    <li><a title="Сауна" href="/sauna/"><img src="/images/bath.png" /></a></li>
    <?}?>
    
    

<?}?>
<li class="service"><a target="_blank" href="/products/admin/" title="Выписка услуг"></a></li>

<li><a title="Обновить страницу" id="refresh_page" href="javascript:void(0);"><img src="/images/refresh.png" /></a></li>


<li class="m_box"><?php echo $this->renderPartial('/site/_menu_box',array('settings'=>$settings)); ?></li>
