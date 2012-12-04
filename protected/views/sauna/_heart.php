    <div class="body_menu">
        <div class="router">
            <ul class="left">
            <?php  echo $this->renderPartial('/site/_menu_2',array('users'=>$user)); ?>
            </ul>
            <ul class="right">
            <?php  echo $this->renderPartial('/site/_menu',array('users'=>$user)); ?>
            </ul>
        </div>
    </div>
    
    
<div id="sauna_list">
    <?php  echo $this->renderPartial('_sauna',array('date'=>$date,'visitors'=>$visitors)); ?>
</div>



<div><input type="hidden" id="user_time" value="<?=time()?>" /></div>
<div><input type="hidden" id="user_date" value="<?=date('Y-m-d',strtotime($date))?>" /></div>