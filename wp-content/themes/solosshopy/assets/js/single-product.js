/* global jssor_options */

jQuery( function( $ ) {

	$.fn.productImgPreload = function() {
		this.each( function() {
			$( '<img/>' )[0].src = this;
		} );
	};

	var $productWrapper = $( '.single_product_wrapper' ),
		$easyzoom = $( '.images .easyzoom', $productWrapper ).easyZoom(),
		easyZoomApi = $easyzoom.data( 'easyZoom' ),
		items = [],
		index,
		thumb = $( '.images .thumbnails .thumbnail', $productWrapper ),
		enlarge = $( '.images .enlarge', $productWrapper ),
		zoom_enabled = true;

	if ( thumb[0] ) {
		var preloadHref = [],
			thumb_0 = thumb.eq( 0 );

		thumb_0.addClass( 'selected' );
		preloadHref[0] = thumb_0.data( 'href' );

		if ( thumb.eq( 1 ) ) {
			var thumb_1 = thumb.eq( 1 );

			preloadHref[1] = thumb_1.data( 'thumb' );
			preloadHref[2] = thumb_1.data( 'href' );
		}

		$( preloadHref ).productImgPreload();

		thumb.each( function() {
			items.push( {
				src: $( this ).data( 'href' )
			} );
		} );

		thumb.on( 'click', thumbClickHandler );
	}

	function thumbClickHandler() {
		var $this = $( this ),
			$mainImageWrap = $( '.woocommerce-main-image' ),
			$mainImage = $( 'img', $mainImageWrap ),
			$thumbImage = $( 'img', $this );

		thumb.removeClass( 'selected' );
		$this.addClass( 'selected' );

		if ( easyZoomApi ) {
			easyZoomApi.teardown();
		}

		zoom_enabled = false;

		$mainImageWrap.attr( {
			href: $this.data( 'href' )
		} );

		$mainImage.attr( {
			src: $this.data( 'thumb' ),
			srcset: $thumbImage.attr( 'srcset' ),
			title: $thumbImage.attr( 'title' ),
			alt: $thumbImage.attr( 'alt' )
		} );

		zoom();
		index = $this.index();
		open_popup( index );
		preloadHref = [];
		preloadHref[0] = $this.data( 'href' );

		if ( $this.next().length ) {
			preloadHref[1] = $this.next().data( 'thumb' );
			preloadHref[2] = $this.next().data( 'href' );
		}
		else if ( $this.prev().length ) {
			preloadHref[1] = $this.prev().data( 'thumb' );
			preloadHref[2] = $this.prev().data( 'href' );
		}

		$( preloadHref ).productImgPreload();
	}

	function open_popup( index ) {
		enlarge.on( 'click', function() {

			$.magnificPopup.open( {
				items: items,
				gallery: {
					enabled: true,
					preload: [1, 1]
				},
				type: 'image'
			}, index );

		} );
	}

	open_popup( 0 );

	function zoom() {

		if ( 768 > Math.min( $( window ).width(), screen.width ) ) {
			if ( true === zoom_enabled && easyZoomApi ) {
				easyZoomApi.teardown();
				zoom_enabled = false;
			}
		} else {
			if ( false === zoom_enabled && easyZoomApi ) {
				easyZoomApi._init();
				zoom_enabled = true;
			}
		}
	}

	zoom();
	$( window ).on( 'resize orientationchange', zoom );

} );