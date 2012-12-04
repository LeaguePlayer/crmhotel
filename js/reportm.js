function recalc_all_uncurrects(object)
{
   // alert('i try');
   var cnt = $(object).parents('li').find('.uncurrect_money.show_icon').size();
   if(cnt==0)
   {
      $(object).parents('li').find('.give_warning').hide(350);
   }
   else
   {
      $(object).parents('li').find('.give_warning').show(350);
   }
}

function closeForm()
{
    
        $(this).parents('li').find('.uncurrect_money').fadeIn(400);
       $(this).parents('form.edit_uncurrent_form').remove(); 
    
}

function afterLoadEditor()
{
    $('.close_button').click(closeForm);
    
    $('.send_button').click(function(){
        var obj = $(this).parent();
        var data = $(this).parent().serialize(); 
        $.ajax({
                  url: '/hotelOrder/editUncurrect',   
                  type: "POST",
                  data: data,
                  success: function(data){
                    if(data=="EMPTY")
                    {
                        $(obj).parent('li').find('.uncurrect_money').removeClass('show_icon');
                    }
                    else if(data="FILL")
                    {
                        $(obj).parent('li').find('.uncurrect_money').addClass('show_icon');
                    }
                    
                    
                    $(obj).parent('li').find('.uncurrect_money').fadeIn(400);
                    recalc_all_uncurrects($(obj));
                    $(obj).remove();
                                           
                  }
        });
        return false;
    });
}

function afterLoadList()
{
    $('div.uncurrect_money').click(function()
   {
     if(!$(this).hasClass('second'))
         {
             
         
            var obj = $(this).parent();
            var type = $(this).attr('rel');
           // var date = $('#date_for_ajax').val();
            var id = $(this).attr('alt');
            $(this).hide();
            $.ajax({
                      url: '/hotelOrder/uncurrect',   
                      type: "POST",
                      data: "id="+id+"&type="+type,
                      success: function(data){
                             $(obj).append(data);
                             afterLoadEditor();

                      }
            });
        
        }
   });
}

function recalc_itogo_sum_id(objective)
{
    
        var my_parent = $(objective).parents('.report_for_copy');
        var new_value = 0;
        $.each($(my_parent).find('li[class!="miss_kassa"] .input_ready'),function()
        {
            var tmp_val_to_add = parseInt($(this).text());
            if(isNaN(tmp_val_to_add))
                {
                    tmp_val_to_add = parseInt($(this).val());
                }
                
            new_value = new_value + tmp_val_to_add;
            
        });
        $(my_parent).find('#itogo_sum span').text(new_value);
}


function recalc_itogo_category(objective)
{
        var invite_id = $(objective).parent('li').attr('rel');
       
        var my_parent = $(objective).parents('.report_for_copy');
        var new_value = 0;
        $.each($(my_parent).find('li[rel="'+invite_id+'"] .input_ready'),function()
        {
            var tmp_val_to_add = parseInt($(this).text());
            if(isNaN(tmp_val_to_add))
                {
                    tmp_val_to_add = parseInt($(this).val());
                }
                
            new_value = new_value + tmp_val_to_add;
            //new_value = new_value + parseInt($(this).val());
        });
        $(my_parent).find('#by_office ul li[rel="'+invite_id+'"] span').text(new_value);
}

function recalc_itogo_sum_by_day(objective)
{
    
        var my_parent = $(objective).parents('.report_for_copy');
        var new_value = 0;
        $.each($(my_parent).find('.input_ready'),function()
        {
            var tmp_val_to_add = parseInt($(this).text());
            if(isNaN(tmp_val_to_add))
                {
                    tmp_val_to_add = parseInt($(this).val());
                }
                
            new_value = new_value + tmp_val_to_add;
            //new_value = new_value + parseInt($(this).val());
        });
        $(my_parent).find('#owner_sum span').text(new_value);
}

function reload_close_category()
{
    $('.finished_edit').click(function(){
       var obj = $(this).parent('li');
       var id_invite = $(obj).attr('rel');
       
       $.each($('.dubl .parent_report').find('ul li[rel="'+id_invite+'"] input.input_ready'),function()
        {
            $(this).parent('li').find('.finished_edit').hide();
            var tmp_val = $(this).val();
           $(this).wrap('<span class="input_ready" />');
            $(this).replaceWith(tmp_val);
          //  alert('t');
            
            
        });
        
        
        
    });
}

function show_save_report()
{
    reload_close_category();
    
    $('.dubl').find('.icons_list span.save_report:hidden').show(200,function(){
        $(this).click(function(){
           var icons_list = $('.dubl').find('.icons_list');
            $(icons_list).addClass('load');
            $(this).hide();
            var id = $('#id_report').val();
            var type = "update";
            var my_duble = $('.report_for_copy.dubl');
            
            $.each($(my_duble).find('input'),function()
            {
                var new_value = parseInt($(this).val());
                $(this).attr('value',new_value);
            });
            $.each($(my_duble).find('.edit_uncurrent_form textarea'),function()
            {
                var new_value = $(this).val();
                
                $(this).text(new_value);
            });
            
            $.each($(my_duble).find('.edit_uncurrent_form input'),function()
            {
                var new_value = $(this).val();
                $(this).attr('value',new_value);
            });
            
            $(my_duble).find('.edit_uncurrent_form').hide();
            
            $(my_duble).find('.load').removeClass('load');
            var html = encodeURIComponent($(my_duble).html());
          

           
            
            $.ajax({
                  url: '/reports/dublicate',   
                  type: "POST",
                  data: "id_report="+id+"&type="+type+"&html="+html,
                  success: function(data){
                      $(icons_list).removeClass('load');
                      if(data=="UPDATED")
                          {
                                
                                
                                
                                
                          }
                          else
                              {
                                  alert("По каким-то причинам произошла ошибка!");
                              }
                          
                  }
            });
           
        });
    });
    
}

function load_close_button()
{
    $('.close_report').click(function(){
        var retVal = confirm("Дублированный отчет восстановление не подлежит. Действительно удалить ?");
        if( retVal == true )
        {
            var icons_list = $('.dubl').find('.icons_list');
            $(icons_list).addClass('load');
            
            var id = $('#id_report').val();
            var type = "delete";
           // var html = encodeURIComponent($('.dubl').html());
             var obj = $(this);

           
            
            $.ajax({
                  url: '/reports/dublicate',   
                  type: "POST",
                  data: "id_report="+id+"&type="+type,
                  success: function(data){
                      $(icons_list).removeClass('load');
                      if(data=="DELETED")
                          {
                                
                                $(obj).hide();
                                $(obj).parents('.dubl').hide(400,function(){
                                    $(this).remove();
                                }); 
                                $('.dublicate_report').show(400); 
                                
                          }
                          else
                              {
                                  alert("По каким-то причинам произошла ошибка!");
                              }
                          
                  }
            });
           
        }
       
    });
}


function recalc_itogo_sum_class()
{
    
    $('input.input_ready').keyup(function(){
        
        var my_parent = $(this).parents('.parent_report');
        var new_value = 0;
        $.each($(my_parent).find('li[class!="miss_kassa"] .input_ready'),function()
        {
            var tmp_val_to_add = parseInt($(this).text());
            if(isNaN(tmp_val_to_add))
                {
                    tmp_val_to_add = parseInt($(this).val());
                }
                
            new_value = new_value + tmp_val_to_add;
            //new_value = new_value + parseInt($(this).val());
        });
        $(my_parent).find('.itogo_sum').html('<strong>Итого</strong>: '+new_value+' руб. ');
        recalc_itogo_sum_id(this);
        recalc_itogo_sum_by_day(this);
        recalc_itogo_category(this);
        show_save_report();
        
    });
}

function solveEditNow()
{
    $('.uncurrect_money.second').click(function(){
        var form  = $(this).children('form');
        $(form).show(200);
        
        
//        if($(this).hasClass('show_icon'))
//            {
//                alert('have');
//            }
//            else
//                {
//                    alert('no');
//                }
    });
    $('.send_button_second').click(function(){
        var my_form = $(this).parent('form');
        var my_text = $(my_form).find('textarea').val();
            if(my_text=="")
            {
                $(my_form).parent('.uncurrect_money.second').removeClass('show_icon');
            }
            else
            {
                $(my_form).parent('.uncurrect_money.second').addClass('show_icon');
            }
        $(my_form).hide(150);
        show_save_report();
        return false;
    });
}

$(document).ready(function(){
    load_close_button();
    recalc_itogo_sum_class();
    solveEditNow();
    reload_close_category();
    
    $('div.dublicate_report div span').click(function(){
        $(this).parents('div.dublicate_report').hide();
      
       var obj = $('.report_for_copy').clone();
       $(obj).removeClass('original');
       $(obj).children('h2').text('Дубликат');
       $(obj).find('.more_info').remove();
       $(obj).addClass('dubl');
       $(obj).find('.give_warning').remove();
       $(obj).append("<div class='icons_list'><span class='close_report'></span><span class='save_report'></span></div>");
       $(obj).find('ul li ul').remove();
        $.each($(obj).find('.ready_for_edit'),function()
        {
            var now_value = parseInt($(this).text());
            $(this).replaceWith("<input class='input_ready' type='text' value='"+now_value+"' /><div class='uncurrect_money second'><form style='right:0px; display:none;z-index:15;' class='edit_uncurrent_form'><textarea name='param[uncurrect]'></textarea><br><input class='send_button_second' style='width:200px;' value='сохранить' type='submit'></form></div><div title='Закрыть отчет по категории' class='finished_edit'></div>");

        });
       
       $(obj).hide();
       $("#guru_report").append(obj);
       recalc_itogo_sum_class();
       $(obj).show(400);
       load_close_button();
       solveEditNow();
       reload_close_category();
    });
    
    
   $('.more_info').click(function(){
        var obj = $(this).parent();
        var type = $(this).attr('rel');
        var date = $('#date_for_ajax').val();
        var id = $(this).attr('alt');
        $(this).hide();
        $.ajax({
                  url: '/reports/listing',   
                  type: "POST",
                  data: "id="+id+"&type="+type+"&date="+date,
                  success: function(data){
                         $(obj).append(data);
                         afterLoadList();
                  }
        });
        
   });
   
   
   
   
   
});