"use strict";

jQuery(document).ready(function($){
	var formAction = false;
	// Save options
	$("#pukka-settings").on("submit", function(e){
		//if action in progress, return
		if(formAction) return false;
		e.preventDefault();
		//check who invoke form submit
		//if it is save button, do the save
		formAction = true;
		var form = $(this);
		$(".pukka-ajax-load").css("display", "inline");
		$.post(ajaxurl, form.serialize(), function(response){

			if( typeof(response.error) != 'undefined' && false == response.error){
				showNotification('Saved, your changes have been!', 'success');
				if( typeof(response.fields) != 'undefined' && response.fields.length > 0){
					setThemeOptions(response.fields);
				}
			}
			else{
				showNotification('Error saving changes!', 'error');
			}

			$(".pukka-ajax-load").css("display", "none");
			formAction = false;
		}, "json");


		return false;
	});

	$(".pukka-save-settings").on("click", function (e) {
		e.preventDefault();

		$("#pukka-settings").trigger("submit");
	});


	$('#pukka-reset-settings').click(function(e){
		if(!confirm("Are you sure you want to reset all settings? \n(Strings and images will be preserved)")){
			return false;
		}
		formAction = true;
		$(".pukka-ajax-reset").css("display", "inline");
		$.post(ajaxurl, {action: 'pukka_framework_reset'}, function(response){
			if( typeof(response.error) != 'undefined' && false == response.error){
				setThemeOptions(response.fields);
				showNotification(response.message, 'success');
			}
			else{
				showNotification(response.message, 'error');
			}

			$(".pukka-ajax-reset").css("display", "none");
			formAction = false;
		}, "json");
	});


	// Init tabs
	// Init tabs
	if( $(".pukka-tabs").length > 0 ){
		$(".pukka-tabs").tabs();
	}

	if( $(".pukka-sortable").length > 0 ){
		$(".pukka-sortable").sortable();
		$(".pukka-sortable").disableSelection();
	}

	/* Pukka Font Pickers */
	$(".pukka-font-select").on("change", function(){

		var $fontWeights = $(this).find("option:selected").data("weight");
		var $fontSelect = $(this),
			$fontWeight = $fontSelect.closest(".pukka-section").find(".pukka-font-weight");

		$fontWeight.find("option").attr("disabled", "disabled");

		if( typeof($fontWeights) != "undefined" ) {
			$.each($fontWeights, function(i, v){

				if( v == "regular" ){
					v = 400;
				}

				$fontWeight.find("option[value='"+ v +"']").removeAttr("disabled");

			});
		}

		if( !$fontWeight.find("option[value='400']").is(":disabled") ){
			// select 400 (regular) weight if it's not disabled
			$fontWeight.find("option[value='400']").prop("selected", true);
		}
		else{
			// else select first not disabled option
			$fontWeight.find("option:not([disabled])").first().prop("selected", true);
		}
	});

	/*
	 // Initi custom selectboxes
	 $(".pukka-single-select").selectbox({
	 speed: 400
	 });

	 // Init custom checkboxes
	 $('#dynamic-meta-wrapper input, .pukka-input-wrap input').iCheck({
	 checkboxClass: 'icheckbox_polaris',
	 radioClass: 'iradio_polaris',
	 increaseArea: '-10%' // optional
	 });

	 $('#dynamic-meta-wrapper').on({
	 dmadded: function(e){
	 $(this).find('select').selectbox({
	 speed: 400
	 });
	 }
	 },
	 '#dynamic-meta-content'
	 );


	 $("#featured-form").on({
	 added : function(e){
	 $(this).find('select:visible').selectbox({speed: 400});
	 }
	 },
	 '#featured');
	 */

	// Product gallery file uploads
	var product_gallery_frame;
	var $image_gallery_ids = $('#pukka_image_gallery');
	var $product_images = $('#pukka_images_container ul.product_images');

	jQuery('.add_product_images').on( 'click', 'a', function( event ) {
		var $el = $(this);
		var attachment_ids = $image_gallery_ids.val();

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( product_gallery_frame ) {
			product_gallery_frame.open();
			return;
		}

		// Create the media frame.
		product_gallery_frame = wp.media.frames.product_gallery = wp.media({
			// Set the title of the modal.
			title: $el.data('choose'),
			button: {
				text: $el.data('update'),
			},
			states : [
				new wp.media.controller.Library({
					title: $el.data('choose'),
					filterable :	'all',
					multiple: true,
				})
			]
		});

		// When an image is selected, run a callback.
		product_gallery_frame.on( 'select', function() {

			var selection = product_gallery_frame.state().get('selection');

			selection.map( function( attachment ) {

				attachment = attachment.toJSON();

				if ( attachment.id ) {
					attachment_ids   = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;
					var attachment_image = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

					$product_images.append('\
						<li class="image" data-attachment_id="' + attachment.id + '">\
							<img src="' + attachment_image + '" />\
							<ul class="actions">\
								<li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li>\
							</ul>\
						</li>');
				}

			});

			$image_gallery_ids.val( attachment_ids );
		});

		// Finally, open the modal.
		product_gallery_frame.open();
	});

	// Image ordering
	$product_images.sortable({
		items: 'li.image',
		cursor: 'move',
		scrollSensitivity:40,
		forcePlaceholderSize: true,
		forceHelperSize: false,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'wc-metabox-sortable-placeholder',
		start:function(event,ui){
			ui.item.css('background-color','#f6f6f6');
		},
		stop:function(event,ui){
			ui.item.removeAttr('style');
		},
		update: function(event, ui) {
			var attachment_ids = '';

			$('#pukka_images_container ul li.image').css('cursor','default').each(function() {
				var attachment_id = jQuery(this).attr( 'data-attachment_id' );
				attachment_ids = attachment_ids + attachment_id + ',';
			});

			$image_gallery_ids.val( attachment_ids );
		}
	});

	// Remove images
	$('#pukka_images_container').on( 'click', 'a.delete', function() {
		$(this).closest('li.image').remove();

		var attachment_ids = '';

		$('#pukka_images_container ul li.image').css('cursor','default').each(function() {
			var attachment_id = jQuery(this).attr( 'data-attachment_id' );
			attachment_ids = attachment_ids + attachment_id + ',';
		});

		$image_gallery_ids.val( attachment_ids );

		// remove any lingering tooltips
		$( '#tiptip_holder' ).removeAttr( 'style' );
		$( '#tiptip_arrow' ).removeAttr( 'style' );

		return false;
	});




	/* BEGIN: Pukka Grid (hides "Dynamic meta box" if appropriate page template is selected */
	/*
	 if( $("#page_template").val() == "page-templates/template-pukka-grid.php" ){
	 $("#pukka_dynamic_metabox_").hide();
	 }

	 $("#page_template").on("change", function(){

	 if( $(this).val() == "page-templates/template-pukka-grid.php" ){
	 $("#pukka_dynamic_metabox_").hide();
	 }
	 else{
	 $("#pukka_dynamic_metabox_").show();
	 }

	 });
	 */
	/* END: Pukka Grid */


});

function showNotification(msg, type){
	//type = 'success' || 'error';
	var iconClass ='';
	if('success' == type){
		iconClass = 'success';
	}else{
		iconClass = 'error';
	}
	var $ = jQuery;
	var html = document.createElement('div');
	html.className = 'pukka-notification ' + iconClass;
	$(html).css('opacity', '0');
	html.innerHTML = '<div class="icon '+ iconClass + '"></div><div class="message">' + msg + '</div>';

	$('body').append(html);
	$(html).animate({opacity: 1}, 1000, function(){
		$(this).delay(1000).animate({opacity: 0}, 1000, function(){
			$(this).remove();
		});
	});
}

function setThemeOptions(options){
	var $ = jQuery;

	for(var i = 0; i < options.length; i++){
		var elem = options[i];
		var $elem = $('#' + elem.id);

		if('checkbox' == elem.type){
			if('on' == elem.value){
				$('input[type=checkbox]#' + elem.id).attr('checked', 'checked');
			}else{
				$('input[type=checkbox]#' + elem.id).removeAttr('checked');
			}
		}else{
			$elem.val(elem.value).change();
		}

		if('file' == elem.type && '' != elem.url){
			var $imgWrap = $elem.closest('.pukka-input').find('.pukka-img-wrap');
			$imgWrap.html('<img src="' + elem.url + '" alt="Preview" style="max-width: 200px;" />');
			$imgWrap.removeClass('pukka-file-placeholder');
		}
		if('select' == elem.type && $elem.hasClass('pukka-single-select')){
			$elem.addClass('was-sb');
		}
	}
	/*
	 $('.was-sb').selectbox('detach');
	 $('.was-sb').selectbox({speed: 400});

	 $('input').iCheck('update');
	 */
}