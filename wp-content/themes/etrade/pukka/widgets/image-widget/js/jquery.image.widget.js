
jQuery(document).ready(function($){
	"use strict";
	
	function set_widget_params(el){
		field_id = $(el).parent().data('field_id');
		widget_id = $(el).parent().data('widget_id');
	}

	$(".widgets-holder-wrap").on("click", ".pukka-remove-image", function(e){
		e.preventDefault();
		set_widget_params(this);

		$("#"+ widget_id +"-thumb").empty();
		$("#"+ field_id).val("");
		//$("#"+ widget_id +"-thumb").addClass('pukka-file-placeholder');
		$(this).hide();
	});


	// Uploading files
	var file_frame;
	var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id

	var post_id = 0;

	var field_id = 0;
	var widget_id = 0;

	jQuery(".widgets-holder-wrap").on("click", ".pukka-upload-image", function( event ){

		event.preventDefault();

		set_widget_params(this);

		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			// Set the post ID to what we want
			file_frame.uploader.uploader.param( "post_id", post_id );
			// Open frame
			file_frame.open();
			return;
		} else {
			// Set the wp.media post id so the uploader grabs the ID we want when initialised
			wp.media.model.settings.post.id = post_id;
		}

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: $( this ).data( 'uploader_title' ),
			button: {
				text: $( this ).data( 'uploader_button_text' )
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			var attachment = file_frame.state().get('selection').first().toJSON();

			// Do something with attachment.id and/or attachment.url here

			$("#"+ field_id ).val(attachment.id);
			$("#"+ widget_id +"-thumb").html("<img src="+ attachment.url +" style='max-width:200px;' />");
			$("#remove-upload-"+ widget_id).show();
			$("#"+ widget_id + "-thumb").removeClass('pukka-file-placeholder');

			// Restore the main post ID
			wp.media.model.settings.post.id = wp_media_post_id;
		});

		// Finally, open the modal
		file_frame.open();
	});

	// Restore the main ID when the add media button is pressed
	jQuery('a.add_media').on('click', function() {
		wp.media.model.settings.post.id = wp_media_post_id;
	});

}); //document ready
