$(document).ready(function(){
    $('#PaymentsOrder_id_type').change(function(){
        var selected_value = $(this).find('option:selected').val();
        switch(selected_value)
        {
            case '0':
                $('.credit_option').hide(200);
            break;
            
            case '1':
                $('.credit_option').show(200);
            break;
            
            case '2':
                $('.credit_option').hide(200);
            break;
        }
    });
    
    
    $('.got_tick').click(function(){
        var obj = $(this);
        var id = $(this).attr('rel');
        var my_parent = $(this).parents('tr');
        var real_sum = $(my_parent).find('.report_sum').text();
        var id_type = 0;
        var alert_text = "Действительно отменить действие на сумму "+real_sum+" руб ?";
        if($(my_parent).attr('class')=='minus')
            {
                id_type=1;
                alert_text = "Действительно сотрудник отсчитался на сумму "+real_sum+" руб ?";
            }
            
        var retVal = confirm(alert_text);
        if( retVal == true )
            {
                var type = 'setStatus';
                $.ajax({
                  url: '/staff/manipulations',   
                  type: "POST",
                  data: "id_paymend="+id+"&type="+type+"&status="+id_type,
                  success: function(data){
                      
                            if(data=="COMPLETE")
                                {
                                    var super_parent = $(obj).parents('.credittable');
                                    if(id_type==1)
                                        {
                                            $(my_parent).removeClass('minus');
                                            $(my_parent).addClass('plusik');
                                            $(my_parent).animate({backgroundColor:'#D5FFDB'},400);
                                            $(obj).text('Не отсчетался');
                                        }
                                        else
                                            {
                                                $(my_parent).removeClass('plusik');
                                                $(my_parent).addClass('minus');
                                                $(my_parent).animate({backgroundColor:'#FFD5D5'},400);
                                                $(obj).text('Отсчетался');
                                            }

                                            var unreported_sum = 0;

                                        $.each($(super_parent).find('.minus span.report_sum'),function()
                                        {
                                            unreported_sum = unreported_sum + parseInt($(this).text());
                                        });

                                        $(super_parent).find('.super_itog strong').text(unreported_sum);   
                                }
                                else
                                    {
                                        alert("Произошла неизвестная ошибка");
                                    }
                  }
            });
                
                
            }
        
    });
    
});