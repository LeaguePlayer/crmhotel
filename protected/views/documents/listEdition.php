<br><br>


<div class="driver_docs">
            <table id='driver_table'>
                <thead>
                    <tr>
                        <td>Выписанные суммы</td>
                        <td>Дата изменения</td>
                        <td>Комментарий</td>
                        
                    </tr>
                </thead>
                <tbody>
                    <? foreach($model->prices as $obj){?>
                    <tr>
                        <td><?=$obj->price?> руб.</td>
                        <td><?=date('d.m.Y H:i',strtotime($obj->date_edit))?></td>
                        <td>
                            <?=$obj->node?>
                        </td>
                      <?}?>
                    </tr>
                </tbody>
            </table>        
</div>
    

