function recalc_blogs()
{
    var purchase_price = $('#Products_purchase_price').val();
    var brought_cnt = parseInt($('#Products_brought_cnt').val());
    
    var result = purchase_price*brought_cnt;
    
    $('#recalc_mod strong').html(result);
}

function checkEmptyFields()
{
        $.each($('table.items tbody tr').find('td.checkMyEmpty'),function()
        {
           var val_on_td = parseInt($(this).text());
           if(val_on_td==0)
               {
                   $(this).parent().addClass('red_line');
               }
        });
}

$(document).ready(function(){
    
   checkEmptyFields();
   recalc_blogs();
   $('#Products_purchase_price').keyup(function(){
       recalc_blogs();
   });
   
   $('#Products_brought_cnt').keyup(function(){
       recalc_blogs();
   });
   
});