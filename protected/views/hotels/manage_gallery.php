
<?PHP $cs=Yii::app()->getClientScript(); ?>
<?php $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/sortable/jquery.ui.sortable.js', CClientScript::POS_HEAD); ?>
<?php $cs->registerCssFile(Yii::app()->theme->baseUrl . '/css/manage_gallery.css'); ?>

<h1>Управление галереей</h1>
    
    <div id="upload_images">Загрузить фото</div>
    <div id="status_upload"><span>Ожидание файлов...</span></div>
    <div id="result_photos" style="margin-top: 20px;"></div>
    
    <?php CHtml::endForm(); ?>

 
<script type="text/javascript">
    function sort_db(){
        var sort_ph = new Array;
        var counter = 0;
        $('.photo_one img').each(function(){
            sort_ph[counter] = $(this).attr('alt');
            counter++;
            //alert($(this).attr('alt'));
            //alert(sort_ph[$(this).attr('alt')]);
        });
        $.ajax({
            url: '<?php echo $this->createUrl('api/updateDataPhotos') ?>',
            type: "POST",
            data: {sort_photos:sort_ph},
        });
    }

    $(function() {
        $( "#result_photos" ).sortable({
            update: function() {
                sort_db();
            }
        });
        $( "#result_photos" ).disableSelection();
    });
    
    

    function loadPhotos(hot_id, arr) {
        
        var photo_types = [
            "Комната",
            "Гостиная",
            "Спальня",
            "Кухня",
            "Ванная и туалет",
            "Прихожая"
        ];
        
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->createUrl('api/getPhotos') ?>',
            data: {hotel_id: hot_id, photos: arr},
            dataType: 'json',
            success: function(data){
                //alert(data);
                //$("#result_photos").html('');
                if (data !== null) {
                    $.each(data, function (i, item){
                        //alert(item.id);
                        //if (!$.inArray(item.id, arr)) {
                            
                            $("#result_photos").append(
                                "<div class='photo_one'>" +
                                    "<div class='photo_wrap'>" +
                                        "<div class='close' title='Удалить!' rel='" + item.id + "'></div>" +
                                        "<a class='fancy_img' href='<?=Yii::app()->params['siteDomain']?>/uploads/gallery/hotels/" + item.name + "' rel='one_game'>" +
                                            "<img class='hidden_img' alt='" + item.id + "' src='<?=Yii::app()->params['siteDomain']?>/uploads/gallery/hotels/thumbs/" + item.name + "'>" +
                                        "</a>" +
                                    "</div>" +
                                "</div>"
                            );
                            
                            $("#result_photos .photo_one:last").append("<div class='radiobuttons'></div>");
                            for (var i=1; i<=photo_types.length; i++) {
                                $("#result_photos .photo_one:last .radiobuttons").append("<label><input rel='" + item.id + "' type='radio' name='typephoto-" + item.id + "' value='" +i+ "' " + ( (item.type==i) ? "checked='checked'" : "" ) + " />" + photo_types[i-1] + "</label>");
                            }
                            
                            $("#status_upload span").text("");
                        //}
        	        });
                    $("#result_photos").fadeIn(1000);
                }
            }
        });
    }

    $(document).ready(function(){
        loadPhotos(<?=$hotel->id?>);
        
        new AjaxUpload('#upload_images', {
            //crossDomain: true,
            // какому скрипту передавать файлы на загрузку? только на свой домен
            action: '<?php echo $this->createUrl('api/uploadPhoto') ?>',
            // имя файла
            name: 'Photos[]',
            // дополнительные данные для передачи
            data: {
                hotel_id : '<?php echo $hotel->id?>',
            },
            // авто submit
            autoSubmit: true,
            // формат в котором данные будет ответ от сервера .
            // HTML (text) и XML определяются автоматически .
            // Удобно при использовании  JSON , в таком случае устанавливаем параметр как "json" .
            // Также установите тип ответа (Content-Type) в text/html, иначе это не будет работать в IE6
            responseType: false,
            // отправка файла сразу после выбора
            // удобно использовать если  autoSubmit отключен
            onChange: function(file, extension){},
            // что произойдет при  начале отправки  файла
            onSubmit: function(file, extension) {
                $('#status_photos span').text("Загрузка на сервер...");
                $('#status_photos span').addClass('work');
            },
            // что выполнить при завершении отправки  файла
            onComplete: function(file, response) {
                console.log(response);
                $('#status_upload span').text("Ожидание файлов");
                $('#status_upload span').removeClass('work');
                loadPhotos(<?php echo $hotel->id ?>, response);
                $(".fancy_img").fancybox();
                $('a.hidden_img').fadeIn(600, function(){
                    $(this).removeClass('hidden_img');
                });
            }
        });
        
        $(".fancy_img").fancybox();
    });
    
    $('#result_photos').delegate('.photo_one div.close', 'click', function() {
        var id = $(this).attr('rel');
        var obj = $(this);
        $.ajax({
            url: '<?php echo $this->createUrl('api/removePhoto') ?>',
            type: "POST",
            data: {id_photo:id},
            success: function(data) {
                if(data=='OK') {
                    $(obj).parents('.photo_one').fadeOut(400,function(){
                        $(this).remove();
                    });
                    sort_db();
                }
                else {
                    alert(data);
                }
            }
        });
    })
    
    
    $(".photo_one input:radio").live("click", function(){
        $.ajax({
            url: '<?php echo $this->createUrl('api/updateDataPhotos') ?>',
            type: "POST",
            data: {
                update_type_photo: {
                    id_photo: $(this).attr("rel"),
                    type: $(this).val()
                }
            },
        });
    })
    
    
</script>