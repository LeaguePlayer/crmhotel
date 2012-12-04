var since_up = 0;
var to_up = 7;

function reload_calendar()
{
    
 

    
    $('#next_cal').click(function(){
       
        window.get_scrolls_to_back--;
        var scroll_now = 7-window.get_scrolls_to_back*1;
         var scroll_now_back = window.get_scrolls_to_back*1+1;
       
      var type = $('#typetable').val();
        var str = "r=hotelOrder/newcal&since="+scroll_now_back+"&to="+scroll_now+type;
        window.since_up = scroll_now_back-1;
        window.to_up = scroll_now;
                           $.ajax({
                  url: '/',
                  type: "GET",
                  data: str,
                  
                  success: function(data) {
                       $('#calendar_on_main_br').html(data);
                       $('.right_part').css('height',$('.chess_body').height());
                       var scrolling_on = 88;
                      
                       var last_left = 0;
                    
                        $('.scrolling_part').css('left',last_left);
                       $('.scrolling_part').animate({left:'-='+scrolling_on},1000);
                        
                        loadALL();
                  }
                  });
                        return false;
    });
    
        $('#back_cal').click(function(){
            window.get_scrolls_to_back++;
       var scroll_now = 8-window.get_scrolls_to_back*1;
        var scroll_now_back = window.get_scrolls_to_back*1;
 var type = $('#typetable').val();
        var str = "r=hotelOrder/newcal&since="+scroll_now_back+"&to="+scroll_now+type;
      window.since_up = scroll_now_back;
        window.to_up = scroll_now-1;
                           $.ajax({
                  url: '/',
                  type: "GET",
                  data: str,
                  
                  success: function(data) {
                       $('#calendar_on_main_br').html(data);
                       $('.right_part').css('height',$('.chess_body').height());
                       var scrolling_on = 88;
                       var last_left = -scrolling_on;
                
                        $('.scrolling_part').css('left',last_left);
                       $('.scrolling_part').animate({left:'+='+scrolling_on},1000);
                       loadALL();
                          
                          
                      
                  }
                  });
                        return false;
    });
}

function updateTable()
{
    var my_time = $('#user_time').val();
    var type = $('#typetable').val();
     var str ="r=hotelOrder/GetLastChange&since="+window.since_up+"&to="+window.to_up+"&user_time="+my_time+type;
             $.ajax({
                  url: '/',
                  type: "GET",
                  data: str,
                  
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
                      }
                  }
                  });
}

function updateMails()
{
    var my_time = $('#user_time').val();
    var type = $('#typetable').val();
     var str ="r=hotelOrder/updateMails&user_time="+my_time+type;
             $.ajax({
                  url: '/',
                  type: "GET",
                  data: str,
                  
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

$(document).ready(function(){
    
    
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
      //  $(".call_fancy a").fancybox({'type' : 'iframe'}); 
     //   $(".call_fancy a").fancybox({ 'type' : 'iframe', 'frameWidth': 1500, 'frameHeight': 1700 });
     //   $("#fancy_outer").css({'width': '500px', 'height': '500px'});
//$(".call_fancy a").fancybox.scrollBox();
$(".call_fancy a").fancybox({
    'type' : 'iframe',
     'width' : '75%',
     'height' : '95%',
     'autoScale' : false,
     'transitionIn' : 'elastic',
     'transitionOut' : 'elastic',
'showNavArrows' : false,
'onClosed' : function(){

      updateTable();

        
     },


});

$(".cell_hotel a").fancybox({
    'type' : 'iframe',
     'width' : '75%',
     'height' : '95%',
     'autoScale' : false,
     'transitionIn' : 'elastic',
     'transitionOut' : 'elastic',
'showNavArrows' : false,
'onClosed' : function(){

      updateTable();

        
     },


});



$(".fancy_run").fancybox({
    'type' : 'iframe',
     'width' : '75%',
     'height' : '95%',
     'autoScale' : false,
     'transitionIn' : 'elastic',
     'transitionOut' : 'elastic',
'showNavArrows' : false,
});





    } 
       reload_calendar();
});