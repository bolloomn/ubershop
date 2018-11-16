( function( $ ) {

	"use strict";

	var tmWooElementor = {

		init: function() {

			var widgets = {
				'wp-widget-__tm_banners_grid_widget' : tmWooElementor.initBannersGrid,
				'wp-widget-__tm_custom_menu_widget'  : tmWooElementor.initCustomMenu,
				'wp-widget-tm_about_store_widget'    : tmWooElementor.initAboutStore
			};

			_.each( widgets, tmWooElementor.widgetsWalker );
		},

		widgetsWalker: function( callback, widget ) {
			elementor.hooks.addAction( 'panel/widgets/' + widget + '/controls/wp_widget/loaded', callback );
		},

		initBannersGrid: function( panel ) {
			if ( panel.ui.form.length ) {
				window.tmBannerGridWidgetAdmin.init( 'init', panel.ui.form );
			}
		},

		initCustomMenu: function( panel ) {
			if ( panel.ui.form.length ) {
				window.tmCustomMenuWidgetAdmin.init( 'init', panel.ui.form );
			}
		},

		initAboutStore: function( panel ) {
			if ( panel.ui.form.length ) {
				window.tmAboutStoreWidgetAdmin.init( 'init', panel.ui.form );
			}
		}

	};

	tmWooElementor.init();

}( jQuery ) );
