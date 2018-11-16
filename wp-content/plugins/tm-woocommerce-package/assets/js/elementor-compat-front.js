( function( $ ) {

	"use strict";

	var tmWooElementorFront = {

		init: function() {

			var widgets = {
				'wp-widget-__tm_products_smart_box_widget.default' : tmWooElementorFront.initSmartBox
			};

			$.each( widgets, function( widget, callback ) {
				elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});
		},

		initSmartBox: function( $scope ) {

			var $tabs = $scope.find( '.rd-material-tabs' );

			if ( ! $tabs.length ) {
				return;
			}

			$tabs.RDMaterialTabs({
				marginContent: $tabs.data( 'margin' ),
				margin: $tabs.data( 'margin' )
			});

		}

	};

	$( window ).on( 'elementor/frontend/init', tmWooElementorFront.init );

}( jQuery ) );
