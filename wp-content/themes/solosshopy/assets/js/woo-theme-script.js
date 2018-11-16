(function ($) {
	"use strict";
	var document = CherryJsCore.variable.$document,
		body = $("body"),
		wooCountDown = $('.tm-products-sale-end-date[data-countdown]');
	CherryJsCore.utilites.namespace('theme_script');
	CherryJsCore.theme_script = {
		init: function () {
			// Document ready event check
			if (CherryJsCore.status.is_ready) {
				this.document_ready_render();
			} else {
				CherryJsCore.variable.$document.on('ready', this.document_ready_render.bind(this));
			}
		},
		document_ready_render: function () {
			this.custom_quantity(this, body);
			this.init_countdown(this, wooCountDown);
			this.woo_toggles(this, document);
		},
		custom_quantity: function (self, $body) {
			var body = $body;
			body.on("click", ".tm-qty-plus", function () {
				changeQuantity($(this).parent(), 'add');
			});
			body.on("click", ".tm-qty-minus", function () {
				changeQuantity($(this).parent(), 'remove');
			});
			function changeQuantity(quantity, state) {
				var input = quantity.find('input[type="number"]'),
					max = input.attr('max'),
					min = input.attr('min'),
					inputVal = parseFloat(input.val());
				switch (state) {
					case 'add':
						if (inputVal < max || '' === max) {
							inputVal = inputVal + 1;
						}
						break;
					case 'remove':
						if (inputVal > min) {
							inputVal = inputVal - 1;
						}
						break;
					default:
						break
				}
				input.val(inputVal);
				input.trigger("change");
			}
		},
		init_countdown: function (self, wooCountDown) {
			function startCountdown() {
				var $this = $(this),
					initalized = $this.data('initalized'),
					finalDate = $this.data('countdown'),
					format = '<span>%D <i>days</i></span> <span>%H <i>Hrs</i></span><span>%M <i>Min</i></span>';
				if (undefined !== initalized) {
					return;
				}
				$this.countdown(finalDate, function (event) {
					$this.html(event.strftime(format));
				});
				$this.data('initalized', true);
			};
			wooCountDown.each( startCountdown );
			// fix for ajax product filter
			CherryJsCore.variable.$document.on('tm_wc_products_changed', function ( event ) {
				wooCountDown = $('.tm-products-sale-end-date[data-countdown]');
				wooCountDown.each( startCountdown );
			})
		},
		woo_toggles: function (self, $document) {
			$document
				.on('click', '.cart-contents', cartClickHandler)
				.on('click', '.site-header-cart__wrapper', cartWrapClickHandler)
				.on('click', cartCloseHandler);
			function cartClickHandler() {
				$('.site-header-cart__wrapper').toggleClass('open');
			}
			function cartWrapClickHandler(event) {
				event.stopPropagation();
			}
			function cartCloseHandler() {
				$('.site-header-cart__wrapper').removeClass('open');
			}
		}
	};
	CherryJsCore.theme_script.init();
}(jQuery));