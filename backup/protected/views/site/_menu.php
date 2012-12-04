
<?if($_SESSION['access']==1){?>
<li><a id="moneytable" href="javascript:void(0);">Показать оплаты</a></li>
<li><a class="fancy_run" href="/index.php?r=hotels/admin">Управление квартирами</a></li>
<?}?>
<?if($_SESSION['access']==3){?>
<?$msgs = Ticks::model()->count(array('condition'=>"date(date_public)<=date(now()) and status = 0",'group'=>"id_clienthotel"))?>
<?if($msgs>0) $class='mails'; else $class='nomail';?>
<li><img class="<?=$class?>" src="/images/mail.png"><a id="quests" href="javascript:void(0);">Сообщения (<?=$msgs?>)</a>
<?if($msgs>0){?>
<?php  echo $this->renderPartial('/site/_quests'); ?>
<?}?>

</li>
<?}?>
<li><a href="index.php?r=user/logout">Выход</a></li>
