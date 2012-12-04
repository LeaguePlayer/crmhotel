var can_pos = 8;
var ajax_busy = false;
var lim_l = 4;
var lim_r = 10;
var position = 4;

function reload_calendar()
{
    
 

    
$('#next_cal').click(function(){
    if ($('.scrolling_part').is(':animated')==false)
    {
         var scrolling_on = 88;
        if(window.position<window.can_pos)
        {
            
             
             $('.scrolling_part').animate({left:'-='+scrolling_on},300);
             $('#scroll_day').animate({left:'-='+scrolling_on},300);
             window.position++;
        }
        else
        {
          
            window.position=0;
          updateTable_right();
          
        }
                          
    }                 
    return false;
});
    
        $('#back_cal').click(function(){
            if(window.position>0)
        {
            
                if ($('.scrolling_part').is(':animated')==false)
                {
                                       var scrolling_on = 88;
                                       var last_left = 0;
                                        $('.scrolling_part').animate({left:'+='+scrolling_on},300);
                                        $('#scroll_day').animate({left:'+='+scrolling_on},300);
                                        window.position--;
                }
                
                                 
                
        }
        else
        {
                    
                    window.position=8;
                  updateTable_left();
                  
                }
        return false;
    });
}

function updateTable_right()
{
   
      window.lim_r = window.lim_r+8;

    window.lim_l = window.lim_l-8;
         var cats='&cat=';      
           
       
            $.each($('.filt.ch a.current'),function()
        {        
            cats=cats+$(this).attr('rel')+'|';
        }); 
        
        cats = cats.slice(0, -1);
   
     var str ="/hotelOrder/GetLastChange?since="+window.lim_l+"&to="+window.lim_r+"&time=0&left=0"+cats;
   

             $.ajax({
                  url: str,
                
                  
                  success: function(data) {
                      if(data!='')
                      {
                        arr = data.split('DELENIE:');         
                        arr[0]=arr[0].substring(0, arr[0].length - 1);         
                        arr[1]=arr[1].substring(0, arr[1].length - 1);    
                        $('#calendar_on_main_br').html(arr[0]);
                        $('.right_part').css('height',$('.chess_body').height());
                          $('#user_time').val(arr[1]);
                           loadALL();                          
                              $('.scrolling_part').animate({left:'-=88'},300);
                                        $('#scroll_day').animate({left:'-=88'},300);
                                        window.position++;
                      }
                  }
                  });
}

function updateTable_left()
{
  
    window.lim_r = window.lim_r-8;

    window.lim_l = window.lim_l+8;
   var cats='&cat=';      
           
       
            $.each($('.filt.ch a.current'),function()
        {        
            cats=cats+$(this).attr('rel')+'|';
        }); 
        
        cats = cats.slice(0, -1);
   
     var str ="/hotelOrder/GetLastChange?since="+window.lim_l+"&to="+window.lim_r+"&time=0&left=-704"+cats;
             $.ajax({
                  url: str,
                  
                  success: function(data) {
                      if(data!='')
                      {
                        arr = data.split('DELENIE:');         
                        arr[0]=arr[0].substring(0, arr[0].length - 1);         
                        arr[1]=arr[1].substring(0, arr[1].length - 1);    
                        $('#calendar_on_main_br').html(arr[0]);
                        $('.right_part').css('height',$('.chess_body').height());
                          $('#user_time').val(arr[1]);
                           loadALL();
                          
                              $('.scrolling_part').animate({left:'+=88'},300);
                                        $('#scroll_day').animate({left:'+=88'},300);
                                        window.position--;
                      }
                  }
                  });
}




function updateTable()
{
    
    var my_time = $('#user_time').val();
     var left = -88*window.position;
     var cats='&cat=';      
           
       
            $.each($('.filt.ch a.current'),function()
        {        
            cats=cats+$(this).attr('rel')+'|';
        }); 
        
        cats = cats.slice(0, -1);
   
     var str ="/hotelOrder/GetLastChange?since="+window.lim_l+"&to="+window.lim_r+"&user_time="+my_time+"&left="+left+cats;
             $.ajax({
                  url: str,
                 
                  
                  success: function(data) {
                      if(data!='')
                      {
                        
                        arr = data.split('DELENIE:');         
                        arr[0]=arr[0].substring(0, arr[0].length - 1);         
                        arr[1]=arr[1].substring(0, arr[1].length - 1);    
                        arr[2]=arr[2].substring(0, arr[2].length - 1);  
                        arr[3]=arr[3].substring(0, arr[3].length - 1);  
                       
                        if(arr[3]=='RESERVE')
                        {
                            
                            $('#wrap').animate({bottom:-32},300,function(){
                               $(this).html(''); 
                            });
                        }
                        
                        $.each($(arr[2]).find('.query'),function()
                        {
                            var id_alist = $(this).attr('rel');
                            if(!$("#actions_list div.query[rel='"+id_alist+"']").is('div'))
                            {
                              
                               if($('#actions_list').size()==0)
                               {
                                    
                                 $('body').prepend('<div id="actions_list"></div>');
                               }
                            $(this).wrap('<div class="query" rel="'+id_alist+'"></div>');
                            $(this).hide();
                               $('#actions_list').prepend($(this));
                              
                               reloadCloseWrap();
                                $(this).fadeIn(500);
                            }
                            
                        });
                        
                        $('#calendar_on_main_br').html(arr[0]);
                        $('.right_part').css('height',$('.chess_body').height());
                          $('#user_time').val(arr[1]);
                           loadALL();
                          
                      }
                  }
                  });
}

function updateMails()
{
    var my_time = $('#user_time').val();
    var type = $('#typetable').val();
     var str ="/hotelOrder/updateMails&user_time="+my_time+type;
             $.ajax({
                  url: str,
                  
                  
                  success: function(data) {
                      if(data!='')
                      {
                        arr = data.split('DELENIE:');         
                        arr[0]=arr[0].substring(0, arr[0].length - 1);         
                        arr[1]=arr[1].substring(0, arr[1].length - 1);    
                        $('.body_menu').html(arr[0]);
                        $('.right_part').css('height',$('.chess_body').height());
                          $('#user_time').val(arr[1]);
                           loadALL();
                      }
                  }
                  });
}

function mails()
{
    $('.mails').fadeOut(500,function(){
        $('.mails').fadeIn(500);
    })
}

function select_back_action(alt,text)
{
     $('div.call_fancy a[alt='+alt+']:last').parent('div').append('<span class="helper_back_action">'+text+'</span>');
    $('div.call_fancy a[alt='+alt+']').parent('div').addClass('selected_back_action');
    $('div.call_fancy a[alt='+alt+']:not(:last)').parents('div.call_fancy').addClass('selected_back_action_border');
//    $('div.call_fancy a[alt='+alt+']:last').parent('div').addClass('selected_back_action_right');
     $( ".helper_back_action" ).draggable();
}

function reloadTopMenu()
{
    $.ajax({
                  url: '/site/reLoadMenu',
                  success: function(data)
                  {                    
                     $('li.doc').html(data);
                  }
           });
           
    $.ajax({
                  url: '/site/reLoadMessageBox',
                  success: function(data)
                  {                    
                     $('li.m_box').html(data);
                  }
           });
}

function wrapclose()
{
    $('#wrap a').click(function(){
       var id = $(this).attr('rel'); 
       $.cookie("rereserve", null);
       $(this).parent('#wrap').animate({'bottom':-32},300);
       $('.query[rel="'+id+'"]').show(400);
       
    });
}

function reloadCloseWrap()
{
     $('.query .close').click(function(){
       var id = $(this).attr('rel');
       var obj = $(this);
       $.ajax({
                  url: '/hotelOrder/DeleteList/id/'+id,
                  
                  success: function(data)
                  {                    
                     if(data==='OK')
                     {
                        $(obj).parent('.query').animate({opacity:0},400,function(){
                            $(this).slideUp(400,function(){
                                $(this).remove();
                            });
                       }); 
                     }
                     else
                     {
                        alert(data);
                     }
                  }
           });
       
            
       
    });
    
    
      $('.query .panel a.left').click(function(){
       if($('#wrap').css('bottom')=='-32px')
       {
            var thisobject = $(this).parents('.query');
            var id = $(thisobject).attr('rel');
            $.cookie("rereserve", id);

            $('#wrap').html('Выберите ячейку для '+$(thisobject).find('.info').html()+'<a href="javascript:void(0);" rel='+$(thisobject).attr('rel')+'>(Отменить)</a>');
            wrapclose();
            $('#wrap').animate({'bottom':0},300);        
            $(thisobject).hide(500);   
        }
       else
       {
         alert("Нельзя выполнять сразу несколько действий");
       } 
    });
}

$(document).ready(function(){
    wrapclose();
   reloadCloseWrap();
    
   
    
    
  
    
  
  setInterval(reloadTopMenu,30000);
  
  

   $('#refresh_page').click(function(){
    window.location.reload();
   });
    
    $('.filt.ses a').click(function(){
        if(window.ajax_busy==false)
        {
            var ob = $(this);
            window.ajax_busy=true;
        href = $(this).attr('href');
        $.ajax({
                  url: href,
                 
                  success: function(data) {
                    $(ob).toggleClass('current');
           var my_time = $('#user_time').val();
     var left = -88*window.position;
     
     
     var href = '/';
   
     var left = -88*window.position;
      
           
       
          
        
      
   
     var str ="since="+window.lim_l+"&to="+window.lim_r+"&left="+left;
     
     
   

             $.ajax({
                  url: href,
                  type: "GET",
                  data: str,
                  
                  success: function(data) {
                      if(data!='')
                      {
                        $('#calendar_on_main_br').html(data);
                          $('.right_part').css('height',$('.chess_body').height());
                
                           loadALL();   
                           window.ajax_busy=false;                       
                      }
                  }
                  });
                    
                    }
                  
                  });
        
        
                  
                  
                  
          }
          return false;        
    });
  
   
    $('.ajax_go_back').click(function(){
         var linky = $(this).attr('href');
        $.ajax({
                  url: linky,
                  type: "GET",
                
                  
                  success: function(data) {
                    $('#place_for_scripts').html(data);
                   
                  }
                  });
                  return false;
    });
    
        
    
    
           $('#moneytable').toggle(function(){
            $(this).addClass('show_money');
    $('#typetable').val('&type=moneytable');
     var str ="r=hotelOrder/newcal&since="+window.since_up+"&to="+window.to_up+"&type=moneytable";
             $.ajax({
                  url: '/',
                  type: "GET",
                  data: str,
                  
                  success: function(data) {
                      $('#calendar_on_main_br').html(data);
                      $('.right_part').css('height',$('.chess_body').height());
                      loadALL();
                  }
                  });
    },function(){
        $(this).removeClass('show_money');
        $('#typetable').val('');
             var str ="r=hotelOrder/newcal&since="+window.since_up+"&to="+window.to_up+"";
             $.ajax({
                  url: '/',
                  type: "GET",
                  data: str,
                  
                  success: function(data) {
                      $('#calendar_on_main_br').html(data);
                      $('.right_part').css('height',$('.chess_body').height());
                      loadALL();
                  }
                  });
    })
    
    
    
    if($('.mails').attr('class')=='mails' || $('.nomail').attr('class')=='nomail')
    {
    setInterval(updateMails,5000);    
    }
    
        
    setInterval(updateTable,45000);
    
    loadALL();
    if($(".call_fancy").is('Div'))
    {
        
$(".cell_hotel.pc a").fancybox({
    'type' : 'iframe',
     'width' : '95%',
     'height' : '95%',
     'autoScale' : false,
     'transitionIn' : 'elastic',
     'transitionOut' : 'elastic',
'showNavArrows' : false,
'afterClose' : function(){

      updateTable();

        
     },


});









    } 
       reload_calendar();
      
       
});

