function skala_go() 
{
      left = parseInt($('.skala').css('left'));
      
      if(left>=1440)
      {       
        
        $('.skala').animate({left:0},5000);
      }
      else
      {
        $('.skala').animate({left:"+=1"},1000);
      }
	  
}

function updateForm()
{
    var time = $('#user_time').val();
    var date = $('#user_date').val();
    
    $.ajax(
     {
          url: '/sauna/update/',
          type: "GET",
          data: "time="+time+'&date='+date,
          success: function(data) 
          {
            if(data!='')
            {
                $('#sauna_line').html(data);
                afterUpdateSauna();
            }
            
          }
    }); 
}


function afterUpdateSauna()
{
    $(".quadrate").hover(function(){
       var id =  $(this).attr('alt');
       $(this).addClass('main');
       
       if(id!==undefined)
       {
            $('.quadrate[alt="'+id+'"]').addClass('selected_row');
            $('.quadrate[alt="'+id+'"]').parent('.reservation_slot:not(:last)').addClass('border');
       }
            
            
       
       
   },function(){
    $('.quadrate.selected_row').removeClass('selected_row');
    $('.reservation_slot.border').removeClass('border');
    $('.main').removeClass('main');
   });
}



$(document).ready(function(){
   setInterval(skala_go, 60000);
   setInterval(updateForm, 35000);
   afterUpdateSauna();
   
   

   
   $(".quadrate a").fancybox({'type' : 'ajax'}); 
});