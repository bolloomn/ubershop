"use strict";

jQuery( document ).ready(function( $ ) {
	var $searchContainer = $( '#search-outer' );
	var aniTime = 500;
	var responsiveCheck = false; // this var tells us if we are in responsive mode
	var $responsiveCheck = $( '#responsive-check' );
	var $mainMenu = $( '#menu-top' );
	var $menuToggle = $('#menu-toggle' );
	// page wrapper
	var $wrapper = $('#wrapper');
	var $main = $('#main');
	var first = true;
	var $window = $(window);
	var $body = $('body');
	var $shopBar = $('#shop-bar');
		
	if($mainMenu.length == 0){
		$mainMenu = $('#sidebar-left');
	}
	
	if(typeof(Pukka.language) == 'undefined'){
		Pukka.language = '';
	}


	// Needed so product block doesn't collapse when child is absolute.
	/*$( '.product-block' ).hover(
	  function() {
	  	console.log( $( this ) );
	  	$( this ).css( 'height', $( this ).outerHeight() );
	    $( this ).addClass( 'product-block-hover' );
	  }, function() {
	  	var self = this;
	  	$( self ).removeClass( 'product-block-hover' );
	  	setTimeout( function() {
	  		$( self ).css( 'height', 'auto' );
	  	}, 350 );
	  }
	);*/
	
	/*
	*	Drop down menu
	************************************/
	function dropDownMenu(){
		if(responsiveCheck) return;
		
		$('.menu-dropdown').each(function(index, elem){
			var width = 0;
			var $elem = $(elem);
			$elem.css('display', 'block');
			$elem.children('ul').children('li').each(function(index, li){
				width += parseInt($(li).outerWidth(true)) + 20;
			});
			$elem.css('display', '').css('max-width', Math.floor(width * 1.05));
		});
	}

	$('.menu ul li.menu-item-has-children > a').click(function(e){
		if( $(this).attr("href") == "#" || $body.hasClass('mobile') ){
			e.preventDefault();
		}

		if($body.hasClass('desktop')) return true;
		$($(this).closest('li').find('ul').get(0)).slideToggle();
	});
	
	/*
	*	Search stuff
	************************************/
	$("#menu-search").click(function(e){
		e.preventDefault();

		$searchContainer.stop().css('display', 'block').animate({opacity: 1}, {duration: aniTime, queue: false});
		$searchContainer.find('#s-main').focus();
	});
	
	$('#searchsubmit-main').click(function(e){
		$(this).closest('form').submit();
	});

	$(document).mouseup(function(e){
		if($searchContainer.is(":visible") && !$searchContainer.is(e.target)
		&& $searchContainer.has(e.target).length === 0)
			{
				$searchContainer.stop().animate({opacity: 0}, {duration: aniTime, queue: false, complete: function(e){
						$searchContainer.css('display', 'none');
				}});
			}
	});

	$searchContainer.find('#s-main').keyup(function(e){
		if(27 == e.keyCode){
			$searchContainer.stop().animate({opacity: 0}, {duration: aniTime, queue: false, complete: function(e){
					$searchContainer.css('display', 'none');
			}});
		}
	});

	// bind autocomplete if needed
	$searchContainer.on({
		focus: function(e) {
		    if ( !$(this).data("autocomplete") ) { // If the autocomplete wasn't called yet:

			$(this).autocomplete({
						appendTo: "#search-outer",
						source: Pukka.ajaxurl + "?action=pukka_search_autocomplete&lang=" + Pukka.language,
						minLength: 2,
						response: function(event, ui) {
				            // ui.content is the array that's about to be sent to the response callback.
				            if (ui.content.length === 0) {
								// if no posts found, show this message in notification
								// area (no notification area for this at the moment)
				                //$("#empty-message").text("No results found");
				            } else {
				                //$("#empty-message").empty();
				            }
							$('#searchsubmit-main span').addClass('fa-search').removeClass('fa-spinner fa-spin');
				        },
						select: function( event, ui ) {
							if(false != ui.item.url){
								window.location = ui.item.url;
							}else{
								$(this).closest('form').submit();
							}

							return false;
						},
						search: function( event, ui){
							$('#searchsubmit-main span').addClass('fa-spinner fa-spin').removeClass('fa-search');
						}
					})._renderItem = function( ul, item ) {
					return $( "<li>" )
						.data( "data-ui-id", item.ID )
						.append( $("<a>" ).text( item.label ) )
						.appendTo( ul );
					};

			} // if
		},
		click: function(e){
			e.stopPropagation();
		}
	}, "#s-main");

	/*
	*	Portfolio and Team boxes content centering
	***********************************************/
	function fixPortfolioTeamContent(){
		$('.portfolio-item.no-margin, .team-item.no-margin').each(function(index, elem){
			var $elem = $(elem);
			var $content = $elem.find('.text-content');
			var padding = Math.floor(($elem.height() - $content.height()) / 2);
			$content.css('padding-top', padding);
		});
	}

	
	$(window).resize(resize);
	
	function resize(){
		responsive();
		
		dropDownMenu();
		menuCheck();
		shopBasketFix();
		
		$('.window-width').each(function(index, elem){
			var $elem = $(elem);
			var windowWidth = $window.width();
			$elem.css('width', windowWidth);
			$elem.css('margin-left', '');
			$elem.css('margin-left', 0 - $elem.offset().left);
		});

		fixPortfolioTeamContent();
	};
	
	resize();
	
	setTimeout(function(e){ resize(); }, 3000);
	
	function runMasonry(){
		// Post masonry
		if( $("body").hasClass("has-grid") && $(".brick").length > 0 ){

			// init masonry
			var $container = $(".brick").parent().masonry({
				columnWidth: 5,
				isAnimated: !Modernizr.csstransitions,
				itemSelector: '.brick',
				/*gutter: '.masonry-gutter-sizer',*/
				animationOptions: {
					duration: 750,
					easing: 'linear',
					queue: false
				}
			});
			
			// thanks @nacin for bundling imagesLoaded
			$container.imagesLoaded( function() {
				$container.masonry();
			});
		}

		// Portfolio masonry
		if( $(".portfolio-grid").length > 0 ){
			var $container = $('.column-wrap');
			$container.masonry({
				itemSelector: '.portfolio-item',
				columnWidth: 1,
			});

			// This does the filter by binding an event on the change of a select box
			$("#grid-filter span").on("click", function(e) {
				e.preventDefault();

				$("#grid-filter .current").removeClass("current");
				$(this).addClass("current");

				var group = $(this).data("cat");
				var group_class = "." + group;
				
				if( group != "all" ){
					$(".portfolio-item").hide();
					$(group_class).show();

				}
				else{
					$(".portfolio-item").show();
				}

				$container.masonry('layout');
			});
		}
	}
	// delay masonry creation
	setTimeout(function(){ runMasonry(); }, 1000);

	/*
	*   Slider
	******************************************/
	if( $(".slider").length > 0 ){
		var $slider = $('.slider').flexslider({
							animation: "slide",
							controlNav: true,
							directionNav: false,
							smoothHeight: true
						  });
	}
	
	/*
	*   Lightbox
	******************************************/
	if( typeof($(".swipebox").swipebox) === "function") {
		var swipeboxInstance = $(".swipebox").swipebox();
	}


	/*
	*   Testimonials
	******************************************/
	$('.testimonials-slider').flexslider({
		animation: "slide",
		controlNav: false,
		directionNav: false,
		useCSS: false // flickering bug
	});
	
	// responsive menu open/close
	$menuToggle.click(function(e){
		$mainMenu.toggleClass('open');

		// iOS safari video overlay fix
		if (navigator.userAgent.match(/(iPod|iPhone|iPad)/i)) {
			if( $mainMenu.hasClass('open') ){
				// $("video").prop("controls", false);
	  			// $("video").css("opacity", 0);
	  			$("video").hide();
			}
			else{
				// $("video").prop("controls", true);
	  			// $("video").css("opacity", 1);
	  			$("video").show();
			}
		}
	});
	
	// check if we are in responsive mode, and set flag that indicates it
	function responsive(){
		responsiveCheck = $responsiveCheck.css('display') != 'none';
		if(responsiveCheck){
			$body.addClass('mobile');
			$body.removeClass('desktop');
		}else{
			$body.removeClass('mobile');
			$body.addClass('desktop');
		}
	}
	
	// check if menu needs to be changed to responsive menu
	function menuCheck(){
		if(responsiveCheck){
			$mainMenu.removeClass('full').addClass('responsive');
		}else{
			$mainMenu.removeClass('responsive').addClass('full');
			$('.menu ul li.menu-item-has-children ul').css('display', '');
		}
	}
	
	function shopBasketFix(){
		// basket overflow fix
		if($shopBar.length > 0){
			$shopBar.find('.basket > ul').css('max-height', $window.height() - 300).css('overflow-y', 'auto');
		}
	}
	
	if($shopBar.length > 0){
		shopBasketFix();
		
		$('#shop-toggle').mouseenter(function(e){
			if($shopBar.position().top < 0){
				$shopBar.stop().animate({top: $body.offset().top}, {duration: aniTime});
			}else{
				//$shopBar.stop().animate({top: $body.offset().top - $shopBar.height()}, {duration: aniTime});
			}
		});
		
		$('#shop-bar #shop-menu').mouseleave(function(e){
			$shopBar.stop().animate({top: $body.offset().top - $shopBar.height()}, {duration: aniTime});
		});
	}
	
	// basket peek view
	var basketHide = null;
	$(document).on('basket-refresh', function(e){
		shopBasketFix();
		
		if(first){
			first = false;
			return;
		}
		if(null != basketHide) return; 
		if($shopBar.length > 0){
			$shopBar.animate({top: '+=20px'},{duration: aniTime, complete: function(e){
				$shopBar.animate({top: '-=20px'},{duration: aniTime, easing: "easeOutBounce"});
			}});
		}else{
			$('.basket').addClass('open');
			basketHide = setTimeout(function(){
				$('.basket').removeClass('open');
				basketHide = null;
			}, 2000);
		}
	});
	
	$('.basket').mouseover(function(e){
		if(null != basketHide){
			$('.basket').removeClass('open');
			clearTimeout(basketHide);
			basketHide = null;
		}
	});
	
	// Font effects
	
	if(typeof(fontEffects) != 'undefined' && fontEffects.length > 0){
		for(var i = 0; i < fontEffects.length; i++){
			$(fontEffects[i].target).addClass('font-effect-' + fontEffects[i].effect);
		}
	}


	/*
	*  Scroll to top link
	******************************************/
	$('#top-link').click(function(e){
		e.preventDefault();
		$("html,body").animate({ scrollTop: 0 }, "slow");
	});

	$(window).scroll(function() {
	    if( $(this).scrollTop() > $(window).height() ){
	        $('#top-link:hidden').stop(true, true).fadeIn().css("display","block");
	    }else{
	        $('#top-link').stop(true, true).fadeOut();
	    }
	});
	
	/*
	*  Share
	******************************************/
	$("#content").on({
		click: function(e){
			e.preventDefault();

			var url = $(this).parent().data("url"),
				title = $(this).parent().data("title"),
				description = $(this).parent().data("desc"),
				image = $(this).parent().data("image"),
				network = $(this).data("network");

			var fn_name = "pukka"+ network.toUpperCase() +"Share";
			// create function
			var fn = window[fn_name];

			if( typeof fn === 'function' ){
				fn(url, title, description, image);
			}
		}
	}, ".pukka-share");

	// Update position on window resize
	$(window).resize(function(){
		share_winTop = (screen.height / 2) - (share_winHeight / 2);
		share_winLeft = (screen.width / 2) - (share_winWidth / 2);
	});

	$("li.product").on({
		click: function(e){
			//console.log('share');
			e.preventDefault();

			$(this).closest(".product").find(".social-share-buttons").toggle();
		}

	}, ".product-share");


	/*
	* Message box
	******************************************/
	$(".pukka-message-box a").on("click", function(e){
		e.preventDefault();
		// hide message box (don't remove it)
		$(this).closest(".pukka-message-box").fadeOut("fast");
	});


    /*
     *   Top Menu sticky
     ************************************/
    var stickyNavTop = $mainMenu.outerHeight() + 50; //$mainMenu.offset().top;

    var stickyTimer;

    var stickyNav = function(){

		// if we're in responsive mode, do nothing
		if( responsiveCheck )
			return;

        var scrollTop = $(window).scrollTop();

        if (scrollTop > stickyNavTop) {
            $mainMenu.addClass('sticky scrolled');

            if( !$mainMenu.hasClass('sticky-animate') ) {

                stickyTimer = setTimeout(function () {
                    $mainMenu.addClass('sticky-animate');
                    clearStickyTimer();
                }, 100);
            }


        } else {
            $mainMenu.removeClass('sticky scrolled sticky-animate');
        }
    };

    function clearStickyTimer(){
        clearTimeout(stickyTimer);
    }

    if( $mainMenu.hasClass("menu-sticky") ){
        stickyNav();
        $(window).scroll(function () {
            stickyNav();
        });
    }

	// Apply cart coupon
	jQuery( '.pukka-apply-coupon' ).on( 'click', function( e ) {
		e.preventDefault();
		jQuery( '.cart .actions .coupon #coupon_code' ).val( jQuery( '#pukka-coupon-code' ).val() );
		jQuery( '.cart .actions .coupon .button' ).trigger( 'click' );
	});

});


// Social share
var share_winWidth =  '520',
	share_winHeight = '350';

var share_winTop = (screen.height / 2) - (share_winHeight / 2);
var share_winLeft = (screen.width / 2) - (share_winWidth / 2);

function pukkaFBShare(url, title, descr, image) {
  window.open('http://www.facebook.com/sharer.php?m2w&s=100&p[title]=' + title + '&p[summary]=' + descr + '&p[url]=' + encodeURIComponent(url) + '&p[images][0]=' + image, 'sharer', 'top=' + share_winTop + ',left=' + share_winLeft + ',toolbar=0,status=0,width=' + share_winWidth + ',height=' + share_winHeight);
}


function pukkaTWShare(url, title, descr, image) {
  window.open('https://twitter.com/share?url=' + url +'&text='+ title, 'sharer', 'top=' + share_winTop + ',left=' + share_winLeft + ',toolbar=0,status=0,width=' + share_winWidth + ',height=' + share_winHeight);
}

function pukkaGPShare(url, title, descr, image) {
  window.open('https://plus.google.com/share?url='+ encodeURIComponent(url), 'sharer', 'top=' + share_winTop + ',left=' + share_winLeft + ',toolbar=0,status=0,width=' + share_winWidth + ',height=' + share_winHeight);
}

function pukkaINShare(url, title, descr, image) {
  window.open('http://www.linkedin.com/shareArticle?mini=true&url='+ encodeURIComponent(url) +"&title="+ title +"&sumary="+ descr, 'sharer', 'top=' + share_winTop + ',left=' + share_winLeft + ',toolbar=0,status=0,width=' + share_winWidth + ',height=' + share_winHeight);
}

function pukkaPTShare(url, title, descr, image) {
  window.open('http://pinterest.com/pin/create/button/?url=' + url + '&description=' + descr + '&media=' + image, 'sharer', 'top=' + share_winTop + ',left=' + share_winLeft + ',toolbar=0,status=0,width=' + share_winWidth + ',height=' + share_winHeight);
}