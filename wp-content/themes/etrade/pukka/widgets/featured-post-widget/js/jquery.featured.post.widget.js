
jQuery(document).ready(function($){
	"use strict";
	
	var pukka_field_id = 0;
	var pukka_widget_id = 0;

	function set_widget_params(el){
		pukka_field_id = $(el).parent().data('field_id');
		pukka_widget_id = $(el).parent().data('widget_id');
	}

    // bind autocomplete if needed
    $(".widgets-holder-wrap").on({
        focus: function(e) {

        		set_widget_params(this);

	            var type = $(this).data("type"),
	                source = $(this).data("source"),
	                language = $(this).data("lang"),
	                nonce = $("#nonce-"+ pukka_widget_id).val();

	            if ( !$(this).data("autocomplete") ) { // If the autocomplete wasn't called yet:

	            	$(this).autocomplete({
	                        source: ajaxurl + "?action=pukka_ajax_autocomplete&type=" + type + "&source="+ source +"&lang=" + language +"&pukka_ajax_nonce="+ nonce,
	                        minLength: 2,
	                        response: function(event, ui) {
	                            // ui.content is the array that's about to be sent to the response callback.
	                            if (ui.content.length === 0) {
	                                //$("#empty-message").text("No results found");
	                            } else {
	                                //$("#empty-message").empty();
	                            }
	                        },
	                        select: function( event, ui ) {
	                            $("#"+ pukka_field_id).val(ui.item.value);
	                            $("#"+ pukka_widget_id +"-autocomplete").val(ui.item.label);
	                            return false;
	                    }
	                    })._renderItem = function( ul, item ) {
	                    return $( "<li>" )
	                        .append( $("<a>" ).text( item.label ) )
	                        .appendTo( ul );
	                    };
	                    
	            } // if
	        }
	    }, ".pukka-featured-post-autocomplete");

}); //document ready
