function recalcfast()
{
  var sum = 0;
  var days = $('#howdays').val();
  var switcher = $('#switch_day_hour input').attr('checked');
  if(switcher!='checked')
  {
     
     sum = $('div.left strong').text();
     sum = parseInt(sum);
  }
  else
  {
    sum = 500;
  }
  return sum*days;
 
}

function event_on_button(){
        var sum = recalcfast();
        $('.field_settings .scores strong').text(sum);
    }

$(document).ready(function(){
    event_on_button();
    $('#howdays').keyup(event_on_button);
    
     $('#switch_day_hour input').click(function(){
        event_on_button();
        
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
   
   $('a.pereselit').click(function(){
         	
        var href = $(this).attr('href');

         $.ajax
         ({
                  url: href,
                  success: function(data) 
                  {
                      if(data=='OK')
                      {
                        parent.jQuery.fancybox.close(); 
                      }
                      else
                      {
                        alert(data);
                      }
                  }
                  
         });
       
         return false;
   });
   
});