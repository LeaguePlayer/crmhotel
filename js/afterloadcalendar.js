
function loadALL()
{
    
    if($('.mails').attr('class')=='mails')
    {
        setInterval(mails,3000);
    }
    
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
    
    
    
    $(".load_fancy").fancybox({
    'type' : 'iframe',
     'width' : '95%',
     'height' : '95%',
     'autoScale' : false,
     'transitionIn' : 'elastic',
     'transitionOut' : 'elastic',
'showNavArrows' : false,


});


    $('#quests').toggle(function(){
       $('.quests').stop(true,true).fadeIn(600);
    },function(){
        $('.quests').stop(true,true).fadeOut(600);
    });
    
    $('.small.right.live_small').parents('.call_fancy').addClass('border_right_live');
    $('.small.right.reserve_small').parents('.call_fancy').addClass('border_right_busy');
    
    $('.small.right.red_zone.red_zone').parents('.call_fancy').addClass('border_right_zone');
    //$('.small.right.busy_cell').parent('.call_fancy').addClass('border_right_busy');
//    $('.small.right.live_now').parent('.call_fancy').addClass('border_right_live');
//    $('.small.right').parent('.call_fancy').css('border-right','none');
//    
            $('.call_fancy a').hover(function(){
               var rel = $(this).attr('alt');
              if(rel!==undefined)
              {
                $('.call_fancy a[alt="'+rel+'"]').addClass('selected_cells');
               
                $('.selected_cells:not(:last)').parents('.call_fancy').addClass('selected_cells_par');

                
              }
        
    },function(){
        $('.call_fancy a').removeClass('selected_cells');
        $('.selected_cells_par').removeClass('selected_cells_par');
       
    });
    
      $('.call_fancy a').hover(function(){
       var position_X = $(this).parents('.call_fancy').attr('rel');
       var position_Y = $(this).parents('.part_row').attr('rel');
       position_Y = parseInt(position_Y)+1;
       
       $('.dates div:nth-child('+position_X+')').addClass('hoveredX');
       $('.left_part div.col_'+position_Y).addClass('hoveredX');
    },function(){
        $('.dates div').removeClass('hoveredX');
        $('.left_part div').removeClass('hoveredX');
        
    });
    
    
    
    
    
   $(".call_fancy.pc a").fancybox({
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

