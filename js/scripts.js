function ajax_go_load()
{
    $('.ajax_go').click(function(){
      var href = $(this).attr('href'); 
      var obj = $(this);
      var id_order = $('#id_order_GET').val();
      var date = $('#date_by_GET').val();
      
     var pl = $('.helper_back_action.absl').size();
      if(pl>0)
      {
        $('.helper_back_action.absl').remove();
      }
      $.ajax({
                  url: href,   
                  success: function(data) {
                     $(obj).parent('span').append('<div class="helper_back_action absl">'+data+'</div>');
                     $('.helper_back_action').draggable();  
                     $('.close_ajax').click(function(){
        $(this).parents('.helper_back_action.absl').remove();
    });
    
            $('.save_ajax').click(function(){
                var form_date = $('.helper_back_action.absl').find('#mgt_money_form').serialize();                
                $.ajax({
                  url: href,   
                  type: "POST",
                  data: form_date,
                  success: function(data){
                     $(this).parents('.helper_back_action.absl').remove();
                     
                     
                      $.ajax({
                      url: '/hotelOrder/FastUpdate',   
                      type: "GET",
                      data: "id_order="+id_order+"&date="+date,
                      success: function(data){
                             $('#living_users_list').html(data);
                      }
                      });
                     
                  }
                  });
            });
                  }
                  }); 
                  
      return false;
    });
}
function fancybox_close(){
    
    $('#fancy_outer').hide();
    $('#fancy_overlay').hide();
    $('#fancy_title').hide();
    $('#fancy_loading').hide();
    $('#fancy_ajax').remove();

};
get_scrolls_to_back=0;



function loadisready()
{
       $('.loaderajax').fadeOut(250);
       $('.hidden_div').fadeOut(250);
}

function loadCalendar_control()
{
    
     $.each($('.scroll_days label[alt="0"]'),function()
            {
                $(this).find('input').attr('checked',false);
                $(this).removeClass('selected_cell');
                $(this).addClass('reserved_slot');
            });
    
    
    $('.scroll_days label').click(function(){
    
            if($(this).attr('class')!='reserved_slot')
            {
                var checked = $(this).find('input').attr('checked');
                var clicked_date = $(this).attr('title');
                var clicked_rel = $(this).attr('rel');
                if(checked=='checked')
                {
                    $(this).find('input').attr('checked',false);
                    clicked_rel = parseInt(clicked_rel)+1;            
                    $('.scroll_days label[title="'+clicked_date+'"]').attr('rel',clicked_rel);
                    $(this).removeClass('selected_cell')
                }
                else
                {
                    $(this).find('input').attr('checked',true);
                    clicked_rel = parseInt(clicked_rel)-1;
                    $('.scroll_days label[title="'+clicked_date+'"]').attr('rel',clicked_rel);
                    $(this).addClass('selected_cell')
                }
                
                $('.scroll_days label[rel!="0"][title="'+clicked_date+'"]').removeClass('reserved_slot');
                $('.scroll_days label[rel="0"][title="'+clicked_date+'"][class!="selected_cell"]').addClass('reserved_slot');
                
            }
           
            
        
    });
    $('.scroll_days label').toggle(function(){
        
    },function(){
        
    });
    
  
       
    
    
 
}

function currect_loader()
{

    var margin_top = ($('html').height()-32)/2;
    var margin_left = ($('html').width()-32)/2;
    $('.loaderajax').css('top',margin_top);
    $('.loaderajax').css('left',margin_left);
}

function remark()
{
    var price = $('#price_per_day').val();
    var cnt = $('.price_blok').size();
    var days = $('#howdays').val();
    var result = (price*days)/cnt;
    result = Math.round(result)/days;
    $('.price_blok').val(result);
}


var cost_hotel = 0;

function recalc()
{
    var days = $('#howdays').val();
    var cost = $('#price_per_day').val();
    var result = parseInt(days)*parseInt(cost);
    $('.row.igoro span').html(result);
    remark();    
}

function recancel_user()
{
    $('.cancel_user a.user_canc').click(function(){
        $(this).parents('.user_fields').find('.user_phone input').val('');
        $(this).parents('.user_fields').find('.user_name input').val('');
        $(this).fadeOut(300);
        $(this).parents('.user_fields').find('.cancel_user a.note').fadeOut(300);
    });
    
    $('.user_phone input').keydown(function(){
       if($(this).val().length>0)
       {
            $(this).parents('.user_fields').find('.cancel_user a.note').fadeOut(300);
            $(this).parents('.user_fields').find('.cancel_user a.user_canc').show();
       }
      
    });
}

$(document).ready(function(){
    
    $('.edit_finally').click(function(){
       var id_clienthotel = $(this).attr('name');
       
       if($(this).attr('checked')=="checked")
       {
        finallyy = 1;
       }
       else
       {
        finallyy=0;
       }
       $.ajax({
                  url: "/hotelOrder/editFinally",   
                  type: "POST",
                  data: "id_clienthotel="+id_clienthotel+"&finally="+finallyy                 
            }); 
    });
    
    
    $('.edit_arrived').click(function(){
       var id_clienthotel = $(this).attr('name');
       
       if($(this).attr('checked')=="checked")
       {
        finallyy = 1;
       }
       else
       {
        finallyy=0;
       }
       $.ajax({
                  url: "/hotelOrder/editArrived",   
                  type: "POST",
                  data: "id_clienthotel="+id_clienthotel+"&arrived="+finallyy                 
            }); 
    });
    
    
    $( ".datepicker_object" ).datepicker({ firstDay: 1,dateFormat: 'dd.mm.yy' });
    
   $('.get_pay').click(function(){
     var obj = $(this).parents('tr');
     var id = $(this).attr('rel');
     var id_clienthotel = $(this).attr('alt');
     
        $.ajax({
                      url: '/documents/ChangeStatus',   
                      type: "POST",
                      data: "id="+id+"&id_clienthotel="+id_clienthotel,
                      success: function(data)
                      {
                         if(data==='OK')
                         {
                            $(obj).fadeOut(450,function(){
                                $(obj).remove();
                            });
                         }
                         else
                         {
                            alert("Неизвестная ошибка");
                         }
                        
                      }
        });
   });
   
   $('.get_pay_tick').click(function(){
     var obj = $(this).parents('tr');
     var id = $(this).attr('rel');
     
        $.ajax({
                      url: '/Documents/ChangeStatusTick',   
                      type: "POST",
                      data: "id="+id,
                      success: function(data)
                      {
                         if(data==='OK')
                         {
                            $(obj).fadeOut(450,function(){
                                $(obj).remove();
                            });
                         }
                         else
                         {
                            alert("Неизвестная ошибка");
                         }
                        
                      }
        });
   });
		
    
    recancel_user();
    
    ajax_go_load();
    
    $('a.cancel_edit').click(function(){
        $('#big_message').fadeOut(450);
    });
    
    $('a.ready_to_edit').click(function(){
         var date = $('.newLive_form').serialize();
       $.ajax({
                      url: '/hotelOrder/edit_order/step/2',   
                      type: "POST",
                      data: date,
                      success: function(data)
                      {
                         if(data==='OK')
                         {
                            $('#big_message').fadeOut(450);
                         }
                         else
                         {
                            alert("Для начала Вы должны исправить все ошибки!");
                         }
                        
                      }
            });
    });
    
    $('.top_block input').change(function(){
       var date = $('.newLive_form').serialize();
       $.ajax({
                      url: '/hotelOrder/edit_order/step/1',   
                      type: "POST",
                      data: date,
                      success: function(data)
                      {
                         if($('#big_message').is(':hidden'))
                         {
                            $('#big_message').fadeIn(450);
                         }
                         
                         $('#big_message div.scores').html(data);
                        
                      }
            });
    });
    
    
    $(".fancy_run").fancybox({
    'type' : 'iframe',
     'width' : '95%',
     'height' : '95%',
     'autoScale' : false,
     'transitionIn' : 'elastic',
     'transitionOut' : 'elastic',
'showNavArrows' : false,
});

    
    $('.close_table tr td input').click(function(){
        var work = true;
        
         if($(this).attr('checked')!='checked')
         {
           
            work=false
         }
        $(this).parents('.close_table').find('input').attr('checked',false);
            if(work==false)
            {
               
                $(this).attr('checked',false);
            }
            else
            {
                $(this).attr('checked',true);
            }
        if($('input.show_time:checked').size()>0)
        {
            $('.hidden_time').show(400);
        }
        else
        {
            $('.hidden_time').hide(400);
        }
    })
    
    
    $(window).scroll(function(){
        var top_margin = $('html').position().top;
        top_margin = top_margin*-1;
        if(top_margin>1)
        {
            $('#top_bg').stop(true,true).fadeIn(300);
        }
        else if(top_margin==0)
        {
            $('#top_bg').stop(true,true).fadeOut(300);
        }
    })
    


    
    $('.way_boxs .switch').click(function(){
        
        $(this).nextAll('.switch').removeClass('on').addClass('off').find('input').attr('checked',false);
        
        $(this).prevAll('.switch').removeClass('off').addClass('on').find('input').attr('checked',true);
        $(this).removeClass('off').addClass('on').find('input').attr('checked',true);
        var cnt_checked = $('.way_boxs .switch label input:checked').size();
        var price = $('#price_per_day').val();
        var result = cnt_checked*price;
        $('#Ticks_sum_for_days').val(result);
    })
    
  
    
    
    recalc();
    $('#howdays').keyup(recalc);
    $('#price_per_day').keyup(recalc);
    
    
    $('.inputchecker').toggle(function(){
        $('#howhous').attr('checked',false);
        $(this).html('Перевести в дни');
        $('.housdays').html('На сколько часов?');
        window.cost_hotel = $('#price_per_day').val();
        $('#price_per_day').val('500');
        recalc();
    },function(){
        $('#howhous').attr('checked',true);
        $(this).html('Перевести в часы');
        $('.housdays').html('На сколько дней?');
        $('#price_per_day').val(window.cost_hotel);
        recalc();
    });
    

    

    
    $('#price_order').keyup(function(){
              var price = $(this).val();
       var id_order = $('#id_order_GET').val();
           var str = "r=hotelOrder/CashChange&price="+price+"&id="+id_order;
      price = parseInt(price);
      if(price>=0)
      {
                      $.ajax({
                  url: '/',
                  type: "GET",
                  data: str,
                  
                  success: function(data) {
                      
                  }
                  }); 
      }
             
                  
    });
    
 
    currect_loader();
    loadCalendar_control();
    
    $(document).resize(currect_loader());
    
    
  
    
    
    
    
    var cnt_height = ($('.cell_hotel').size()+2)*35;
    $('.chess_body .right_part').css('height',cnt_height);
    

});