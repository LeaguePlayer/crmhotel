<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/month.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/month_print.css" media="print" />

        <h2>Отчёт за <?=$result['month']?> <?=$result['year']?> года</h2>
        <?
            $full_itogo = array();
            $super_itogo = 0;
        ?>
        
        <table class="reportotable">
            <thead>
                <tr>
                    <td>Адрес</td>
                    <?
                        for($i=1;$i<=$result['all_days'];$i++)
                        {                          
                            echo "<td>$i.{$result[month_num]}</td>";
                        }
                    ?>
                    <td>Итого за кв.</td>
                </tr>  
            </thead>
            <tbody>
                <?foreach($result['hotels'] as $id_hotel => $hotel){?>
                
                <?
                    $itogo = 0;
                ?>
                
                <tr>
                    <td><?=$hotel?></td>
                        <?for($i=1;$i<=$result['all_days'];$i++){?>
                    
                    <?
                        $cur_sum = (is_numeric($result['ticks'][$id_hotel][$i]) ? $result['ticks'][$id_hotel][$i] : 0);
                        $itogo +=$cur_sum;
                        $full_itogo[$i] += $cur_sum;
                        $my_class = ($cur_sum==0 ? 'poor_day' : 'good_day')
                    ?>
                    
                    <td nowrap class="cal <?=$my_class?>"><?=fnc::priceFormat($cur_sum)?></td>
                        <?}?>
                    
                    <td nowrap class="cal itogo"><?=fnc::priceFormat($itogo)?></td>
                    <?$super_itogo +=$itogo?>
                </tr> 
                <?}?>
                
                <tr>
                    <td>
                        --
                    </td>
                    <?
                        for($i=1;$i<=$result['all_days'];$i++)
                        {               
                            $tmp_itogo = fnc::priceFormat($full_itogo[$i]);
                            echo "<td nowrap>{$tmp_itogo}</td>";
                        }
                    ?>
                    <td nowrap><?=fnc::priceFormat($super_itogo)?></td>
                </tr>
                
                
            </tbody>
        </table>