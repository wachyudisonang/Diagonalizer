jQuery(document).ready(function() {
    function set_uploader(button, field, el, type) {
        // make sure both button and field are in the DOM
        if(jQuery(button) && jQuery(field)) {
            // when button is clicked show thick box
            jQuery(button).click(function() {
                tb_show('', 'media-upload.php?type='+type+'&TB_iframe=true&post_id=0');
                jQuery('#media-items .savesend .button').addClass('button-primary');
                // when the thick box is opened set send to editor button
                var placeHolder = jQuery(el).find('.image-placeholder') || null;
                var img = jQuery(el).find('.image-placeholder img') || null;
                //console.log(img);
                set_send(field, placeHolder[0], img[0]);
                return false;
            });
        }
    }

    function set_send(field,placeHolder,img) {
        // store send_to_event so at end of function normal editor works
        window.original_send_to_editor = window.send_to_editor;

        // override function so you can have multiple uploaders pre page
        window.send_to_editor = function(html) {
            imgurl = jQuery('img',html).attr('src');
            jQuery(field).val(imgurl);

            if(placeHolder) {
                jQuery(placeHolder).append('<img class="upload-img" id="#acpt_img_slider_url" src="' + imgurl + '" />');
                jQuery(img).remove();
            }

            tb_remove();
            // Set normal uploader for editor
            window.send_to_editor = window.original_send_to_editor;
        };
    }

    // place set_uploader functions below, button then field
    jQuery('.control-group').each(function(index, el) {
        var button = jQuery(el).find('.upload-button'), uploadUrl = jQuery(el).find('.upload-url'), type = null;
        if(jQuery(uploadUrl).hasClass('file')) {
            type = 'file';
        } else if(jQuery(uploadUrl).hasClass('image')) {
            type = 'image';
        }
        console.log(type);
        if(button){ set_uploader(button[0], uploadUrl[0], el, type);}

        jQuery(el).on('click', '.image-placeholder .remove-image', function(){
            jQuery(this).parent().parent().find('.upload-url').attr('value', '');
            jQuery(this).parent().remove();
            jQuery(el).append('<div class="image-placeholder"><div class="remove-image"></div></div>');
        });
    });

});