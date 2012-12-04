//make table editable, refresh charts on blur$(function(){
$(function(){	
	$('.canedit')
		.click(function(){
			if( !$(this).is('.input') ){
				$(this).addClass('input')
					.html('<input style="width:70px !important;" type="text" value="'+ $(this).text() +'" />')
					.find('input').focus()
					.blur(function(){
						//remove td class, remove input
						$(this).parent().removeClass('input').html($(this).val() || 0);
						//update charts	
						$('.visualize').trigger('visualizeRefresh');
                                                $(this).select();
					});					
			}
		})
		.hover(function(){ $(this).addClass('hover'); },function(){ $(this).removeClass('hover'); });
                
                $(document).keypress(function(e) {
                    if(e.which == 13) {
                       var my_val = $('.canedit.input').children('input').val();
                       $('.canedit.input').html(my_val);
                       $('.canedit.input').removeClass('input');
                    }
                });

});