function recalc_cost(n_objects,cost)
{
    var result = cost/n_objects;
    return Math.round(result);
}


function ajax_changer(result,sub_name,uri,noupdate)
{
   
   
    if(noupdate!=true || result!=0)
    {
        if(uri==false)
        {
            uri = $('#tyc_hotel').attr('href');
        }
    
        var places = $('#HotelOrder_places').val();
        $.ajax({
                  url: uri,
                  type: "POST",
                  data: "home_type="+result+"&places="+places,
                  
                  success: function(data) {
                  
                    $('#form_user_create').html(data);   
                    
                        $('#HotelOrder_TYC').val(result);
                    
                    if(sub_name!=false)
                    {
                        $('#tyc_hotel').text(sub_name);
                    }
                    
                  }
                });
    }
    else
    {
        window.ajax_busy=false;
    }
    
            
}

function load_background_animate()
{
    $(".phonecomplite").keyup(function(){
       var dlina_stroki = $(this).val().length;
       if(dlina_stroki==11)
       {
         $(this).stop(true,true).animate({backgroundColor:'#23bb00'},300);
       }
       else
       {
        $(this).stop(true,true).animate({backgroundColor:'#ff7474'},300);
        
       }
   });
   
   $(".phonecomplite").change(function(){
  
      
         $(this).stop(true,true).animate({backgroundColor:'#23bb00'},300);
       
   });
}

function reload_notices()
{
    
    
    $('.hotel_user_clear').click(function(){
        $(this).parents('.user_div').find('input[class!="hotel_user_score"]').val('').css('background','#fff');
        $(this).parent('div.user_div').find('.hotel_user_notice').html('');
        $(this).html('');
    });
    
    $('.new_phone').click(function(){
        var obj = $(this).parents('div.user_div').find('input.phonecomplite:first').clone().css('background','#fff');
        obj.val('');
        $(this).parents('div.user_div').find('div.inline div.place_for_phones').append(obj);
       load_background_animate();
           
    });
    load_background_animate();
   
   
}



$(document).ready(function(){

    $('#HotelOrder_places').change(function(){
        if(window.ajax_busy==false)
        {
            var result = $('#HotelOrder_TYC').val();
            ajax_changer(result,false,false,true);
        }
        
    });
    
    
    
    $('.time').timepicker();
    
    
    
  $('#switch_day_hour input').click(function(){
        var checked = $(this).attr('checked');
        $('#switch_day_hour *').removeClass('current');
        if(checked==='checked')
        {            
            $('.hours').addClass('current');
        }
        else
        {
            $('.days').addClass('current');
        }
   });
   
   $('#tyc_hotel').click(function(){
        if(window.ajax_busy==false)
        {
            window.ajax_busy = true;
            var name = $(this).text();
            
            if(name=='Используется как ГОСТИНИЦА')
            {
                var sub_name = 'Используется как ТУЦ';
                var result = 1;
            }
            else
            {
                var sub_name = 'Используется как ГОСТИНИЦА';            
                var result = 0;
            }
            
            ajax_changer(result,sub_name,false,false);
        }
           
        
      
        return false;
        
   });
});