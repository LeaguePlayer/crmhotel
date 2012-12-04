<script>
if($('.helper_back_action').size()==0){
<?php echo $script;?>

$('dt.back_action_yes').click(function(){
         var linky = $(this).attr('href');
        $.ajax({
                  url: linky,
                  type: "GET",
                
                  
                  success: function(data) {
                    $('#place_for_scripts').html(data);
                    $('.selected_back_action').removeClass('selected_back_action');
        $('.selected_back_action_border').removeClass('selected_back_action_border');
        
        $(this).parent('.helper_back_action').fadeOut(500,function(){
            $(this).remove();
        })
                        updateTable();
                  }
                  });
                  return false;
    });
    
    
    $('dt.back_action_no').click(function(){
        $('.selected_back_action').removeClass('selected_back_action');
        $('.selected_back_action_border').removeClass('selected_back_action_border');
       
        $(this).parent('.helper_back_action').fadeOut(500,function(){
            $(this).remove();
        })
    });
}
</script>

