$(document).ready(function(){
    $.ajax({
                  url: '/?r=site/ajaxload',
                  success: function(data) {
                      $('.chess_loader').fadeOut(300,function(){
                        $('#ajax_load_place').html(data);
                      });
                      
                  }
                  });
});