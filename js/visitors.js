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
      
           
    });
    
   
   
}

function recalculator()
{
    $('#itogo span').html('');
    $('#itogo').addClass('load');
    
    var date_begin = $('#field_date').val();
    var time_begin = $('#field_time').val();
    var n_hour = $('#field_how').val();
     
     $.ajax(
     {
          url: '/visitors/recalc/',
          type: "GET",
          data: "date_begin="+date_begin+"&time_begin="+time_begin+"&n_hour="+n_hour,
          success: function(data) 
          {
            $('#itogo').removeClass('load');
            $('#itogo input').val(data);
          }
    }); 
}


$(document).ready(function(){
   $('.time').timepicker(); 
   
   
   $('.go_to_prepay').click(function(){
       if($('#go_to_prepay').is(':hidden'))
           {
               $('#field_pay').attr('checked',false);
               $('#go_to_prepay').stop(true,true).show(300);
           }
           else
               {
                   $('#go_to_prepay').stop(true,true).hide(300);
                   $('#go_to_prepay').find('input').val(0);
               }
   });
   
  $('#field_pay').change(function(){
     if($(this).is(':checked'))
         {
             $('#go_to_prepay').stop(true,true).hide(300);
                   $('#go_to_prepay').find('input').val(0);
         }
         
  });
    
   
   reload_notices();
   recalculator();
   
   $('#field_how').keyup(recalculator);
   $('#field_time').change(recalculator);
   
});