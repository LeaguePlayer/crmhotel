
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
    
  
    
   // $('.close_fields').click(function(){
//       var no_checked = false;
//           $.each($('.user_fields'),function()
//            {
//               var checked_input = $(this).find('.scroll_days label input:checked').size();
//               
//               if(checked_input>0)
//               {
//                    var name = $(this).find('.name_here').val();
//                   var phone = $(this).find('.phonecomplite').val();
//                   if(name!='')
//                   {
//                      
//                      $(this).hide(500,function(){
//                        $(this).remove();
//                            var cnt_user_fields = $('.user_fields').size();
//                            if(cnt_user_fields==0)
//                            {
//                                    $('#form_for_new_user').hide(500);
//                            }
//                      });
//                      $('#users_list').append('<li>'+name+' '+phone+'</li>');
//                   }
//               }
//               else
//               {
//                 no_checked = true;
//               }
//               
//              
//            });
//           
//            if(no_checked)
//            {
//                alert('Вы не выбрали период проживания у пользователей');
//            }
//    });

    
    $('#HotelOrder_places').change(function(){
        var cnt_users =  parseInt($('#HotelOrder_places option:selected').val());      
        var visibled_fields = parseInt($('.user_fields:visible').size());
        var result = cnt_users - visibled_fields;
        
        if(result>0)
        {
            for(i=1;i<=result;i++)
            {
                var id = visibled_fields+i;
                $('.q'+id).show(400);
            }
        }
        else if(result<0)
        {
            result = Math.abs(result);
           
                for(i=0;i<result;i++)
            {
                var id = visibled_fields-i;
                $('.q'+id).hide(400,function(){
                    $(this).find('input').val('');
                });
            }
        }
    });    

    
  

    

    
      
    
    
    
    
 
}

function currect_loader()
{

    var margin_top = ($('html').height()-32)/2;
    var margin_left = ($('html').width()-32)/2;
    $('.loaderajax').css('top',margin_top);
    $('.loaderajax').css('left',margin_left);
}

var cost_hotel = 0;

function recalc()
{
    var days = $('#howdays').val();
    var cost = $('#HotelOrder_price_per_day').val();
    var result = parseInt(days)*parseInt(cost);
    $('.row.igoro span').html(result);
    
}
$(document).ready(function(){
    
    
    $(window).scroll(function(){
        var top_margin = $('html').position().top;
        top_margin = top_margin*-1;
        if(top_margin>1)
        {
            $('#top_bg').stop(true,true).fadeIn(800);
        }
        else if(top_margin==0)
        {
            $('#top_bg').stop(true,true).fadeOut(800);
        }
    })
    
    $('.new_phone').click(function(){
        var rel = $(this).attr('rel');
        rel = parseInt(rel)+1;
        var blog = $(this).attr('alt');
        $(this).parent('.user_fields').find('.list_phones').append('<label for="">Телефон</label> <input id="user_phone" class="phonecomplite ui-autocomplete-input" type="text" name="users['+blog+'][phone]['+rel+']" size="40" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">');
        $(this).attr('rel',rel);
    });

    
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
    $('#HotelOrder_price_per_day').keyup(recalc);
    
    $('.inputchecker').toggle(function(){
        $('#howhous').attr('checked',false);
        $(this).html('Перевести в дни');
        $('.housdays').html('На сколько часов?');
        window.cost_hotel = $('#HotelOrder_price_per_day').val();
        $('#HotelOrder_price_per_day').val('500');
        recalc();
    },function(){
        $('#howhous').attr('checked',true);
        $(this).html('Перевести в часы');
        $('.housdays').html('На сколько дней?');
        $('#HotelOrder_price_per_day').val(window.cost_hotel);
        recalc();
    });
    
    $('#rechange_places').change(function(){
        var places = $('#rechange_places option:selected').val();
        var date = $('#date_by_GET').val();
        var id_order = $('#id_order_GET').val();
        var str = "r=hotelOrder/rechange&places="+places+"&id="+id_order+"&date="+date;
      
                           $.ajax({
                  url: '/',
                  type: "GET",
                  data: str,
                  
                  success: function(data) {
                       $('body').html(data);
                  }
                  });
    })
    
    $('#invite_who').change(function(){
       var invite = $('#invite_who option:selected').val();
       var id_order = $('#id_order_GET').val();
           var str = "r=hotelOrder/invites&id_invite="+invite+"&id="+id_order;
      
                           $.ajax({
                  url: '/',
                  type: "GET",
                  data: str,
                  
                  success: function(data) {
                      
                  }
                  });
    })
    
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
    
    
  
    
    
    
    
    var cnt_height = ($('.cell_hotel').size()+1)*35;
    $('.chess_body .right_part').css('height',cnt_height);
    

});