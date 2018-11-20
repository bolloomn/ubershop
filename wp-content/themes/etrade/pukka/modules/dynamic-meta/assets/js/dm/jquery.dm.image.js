"use strict";

jQuery(document).ready(function($){

    // Uploading files
    var file_frame;
    var id = 1;
    var wp_media_post_id = (typeof(wp.media) != "undefined" && typeof(wp.media.model) != "undefined" ) ? wp.media.model.settings.post.id : 0;
    var $currentBox = null;

    $('#dynamic-meta-wrapper').on('click', '.dm-add-image, .dm-image-preview', function(event){
        event.preventDefault();
        var $box = $(this).closest('.dynamic-meta-box');
        $currentBox = $box;
        if (file_frame){
          file_frame.open();
          return;
        }

        file_frame = wp.media.frames.file_frame = wp.media({
          title: 'Upload image',
          button: {
            text: 'Select image'
          },
          multiple: false  // Set to true to allow multiple files to be selected
        });

        file_frame.on( 'select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            $currentBox.find('.dm-image-id').val(attachment.id);
            $currentBox.find('.dm-image-url').val(attachment.url);
            $currentBox.find('.dm-image-preview').attr('src', attachment.url);
            wp.media.model.settings.post.id = wp_media_post_id;
        });

        // Finally, open the modal
        file_frame.open();
    });

    $('#dynamic-meta-wrapper').on('click', '.dm-remove-image', function(event){
        var $box = $(this).closest('.dynamic-meta-box');
        $box.find('.dm-content').val('');
        $box.find('.dm-image-id').val('');
        $box.find('.dm-image-url').val('');
        $box.find('.dm-image-preview').attr('src', $box.find('.dm-image-preview').data('default'));
    });

    $('#dynamic-meta-wrapper').on('change', '.image-size-select', function(e){
        var $box = $(this).closest('.dynamic-meta-box');
        var imgId = $box.find('.dm-image-id').val();
        if('' != imgId){
            var imgSize = $(this).val();
            $box.find('.dm-tool-loading').css('display', 'block');
            $.post(ajaxurl, {action: 'pukka_get_image_url', img_id: imgId, img_size: imgSize}, function(res){
                res = JSON.parse(res);
                if(res.error == false){
                    $box.find('.dm-image-preview').attr('src', res.url);
                    $box.find('.dm-tool-loading').css('display', 'none');
                }
            });
        }
    });

    // remove image box
    $('#dynamic-meta-wrapper').on('click', '.dm-type-image .dm-remove', function(e){
        if(!confirm("Ara you sure you want to remove this item?")) return;
        $(this).closest('.dynamic-meta-box').remove();
    });

    // open options panel
    $('#dynamic-meta-wrapper').on('click', '.dm-type-image .dm-options', function(e){
        var $box = $(this).closest('.dynamic-meta-box');
        $box.find('.dm-edit-options').fadeIn(aniTime);
    });

    // close options panel
    $('#dynamic-meta-wrapper').on('click', '.dm-type-image .dm-options-close', function(e){
        var $box = $(this).closest('.dynamic-meta-box');
        $box.find('.dm-edit-options').fadeOut(0);
    });

    function getContentJSON(dataBox){
        var contentInput = $(dataBox).find('.dm-content');
        var json = $(contentInput).val();
        if($.trim(json) == ''){
            json = '{"data":[], "num": 0}';
        }
        var content = JSON.parse(json);

        return content;
    }

    function setContentJSON(dataBox, content){
        var contentInput = $(dataBox).find('.dm-content');
        $(contentInput).val(JSON.stringify(content));
    }

    function collectData(){
        $('#dynamic-meta-wrapper .dm-type-image').each(function(index, elem){
            var $box = $(elem);
            var content = getContentJSON($box);
            var data = {};
            var inputs = $box.find('.dm-data-input').get();
            for(var i = 0; i < inputs.length; i++){
                data[$(inputs[i]).data('var')] = $(inputs[i]).val();
            }

            content.data[0] = data;
            setContentJSON($box, content);
        });
    }

    // adds collectData function to global array, so that we can collect all
    // data on post save
    collectDataList.push(collectData);

});
