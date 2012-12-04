<?if(Yii::app()->controller->createUrl('')!='/sauna/index'){?>
    <li class="filt ses"><a class="<?=(Yii::app()->session['all_homes']==1 ? 'current' : '')?>" href="/site/changefilt<?$link_cats?>">Убрать заселённые</a></li>
    <li class="filt ses"><a class="<?=(Yii::app()->session['tyc_only']==1 ? 'current' : '')?>" href="/site/rewrite<?$link_cats?>">Только ТУЦ</a></li>
<?}?>