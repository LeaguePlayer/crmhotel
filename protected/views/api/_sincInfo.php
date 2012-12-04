<span id="info-label">Удалено строк: </span>
<span id="count"><?=$deleted?></span><br />

<span id="info-label">Обновлено строк: </span>
<span id="count"><?=$updated?></span><br />

<span id="info-label">Добавлено строк: </span>
<span id="count"><?=$inserted?></span><br />

<?php
    echo CHtml::link('Гут', $this->createUrl('hotels/admin'));
?>