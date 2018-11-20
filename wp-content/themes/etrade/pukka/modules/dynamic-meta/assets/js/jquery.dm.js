"use strict";

var collectDataList = new Array();
var aniTime = 500;

jQuery(document).ready(function($){

	 var colorOptions = {
        defaultColor: false,
        change: function(event, ui){},
        clear: function(){},
        hide: true,
        palettes: true
    };
	// init color pickers
	$('#dynamic-meta-wrapper .dm-select-color').wpColorPicker(colorOptions);

	// init numeric up-down
	$('.numeric-updown').numericUpDown();

	// save data
	$('#post').submit(function(e){
		$('#dynamic-meta-wrapper textarea.tinymce').each(function(index, elem){
			var $elem = $(elem);
				tinymce.get($elem.attr('id')).save();
		});

		for(var i = 0; i < collectDataList.length; i++){
			collectDataList[i]();
		}
	});

	// text area tabulation
	$('#dynamic-meta-wrapper').on('keydown', 'textarea', function(e) {
		if(e.keyCode === 9) { // tab was pressed
			// get caret position/selection
			var start = this.selectionStart;
			var end = this.selectionEnd;

			var $this = $(this);
			var value = $this.val();

			// set textarea value to: text before caret + tab + text after caret
			$this.val(value.substring(0, start)
						+ "\t"
						+ value.substring(end));

			// put caret at right position again (add one for the tab)
			this.selectionStart = this.selectionEnd = start + 1;

			// prevent the focus lose
			e.preventDefault();
		}
	});

	//jQuery UI sortable
	$( "#dynamic-meta-content" ).sortable({
		revert: true,
		start: function(event, ui){
			// Because Firefox is cousing problems with TinyMCE when moving
			// editors, we need to destroy editor befor draging starts,
			// and create it again when sorting stops if needed.
			// It is a bit ugly, but there is no better solution at the moment
			stopMCE(ui.item);
		},
		update: function(event, ui){
		},
		beforeStop: function(event, ui){
		},
		stop: function( event, ui ) {
			resumeMCE(ui.item);
		}
	});

	$('#dynamic-meta-wrapper').on('mouseenter', '.dynamic-meta-box-title', function(e){
		if($('#dynamic-meta-wrapper .dm-edit-options:visible').length > 0){
			$("#dynamic-meta-content" ).sortable('disable');
		}else{
			$("#dynamic-meta-content" ).sortable('enable');
		}
	});


	$('#dynamic-meta-wrapper').on('mouseleave', '.dynamic-meta-box-title', function(e){
		$( "#dynamic-meta-content" ).sortable('disable');
	});


	$('#dynamic-meta-wrapper').on('click', '.dm-size-up', function(e){
		var box = $(this).closest('.dynamic-meta-box').get(0);
		var size = $(box).find('.dm-size');
		var sval = parseInt($(size).val());
        var max = parseInt($(size).data('max'));
        var min = parseInt($(size).data('min'));
        var step = parseInt($(size).data('step'));
		if(sval < max){
			sval += step;
            if(sval > max){
                sval = max;
            }
			$(box).css('width', sval + '%');
			$(size).val(sval);
		}else{
			sval = max;
			$(box).css('width', sval + '%');
			$(size).val(sval);
        }
		$('#dynamic-meta-content').trigger('boxresize', [box]);
	});

	$('#dynamic-meta-wrapper').on('click', '.dm-size-down', function(e){
		var box = $(this).closest('.dynamic-meta-box').get(0);
		var size = $(box).find('.dm-size');
		var sval = parseInt($(size).val());
        var max = parseInt($(size).data('max'));
        var min = parseInt($(size).data('min'));
        var step = parseInt($(size).data('step'));

		if(sval > min){
			sval -= step;
            if(sval < min){
                sval = min;
            }
			$(box).css('width', sval + '%');
			$(size).val(sval);
		}else{
			sval = min;
			$(box).css('width', sval + '%');
			$(size).val(sval);
		}
		$('#dynamic-meta-content').trigger('boxresize', [box]);
	});


	$('#dynamic-meta-add').click(function(e){
		var data = {
			action: 'pukka_get_dm_box',
			type: $('#dynamic-meta-select').val()
		}
		$('#dynamic-meta-loading').css('display', 'inline');
		$.post(ajaxurl, data, function(res){
			$('#dynamic-meta-loading').css('display', '');
			$('#dynamic-meta-content').append(res);
            $('#dynamic-meta-content').trigger('dmadded');
		});
	});


	var menuClicked = 'top';
    $('.dm-toolbar li').click(function(e){
		var data = {
			action: 'pukka_get_dm_box',
			type: $(this).data('type')
		}
		if($(this).parent().hasClass('top')){
			menuClicked = 'top';
		}else{
			menuClicked = 'bottom';
		}
		$(this).find('.dm-tool-loading').css('display', 'block');
		$.post(ajaxurl, data, function(res){
			$('#dynamic-meta-loading').css('display', '');
			if('top' == menuClicked){
				$('#dynamic-meta-content').prepend(res);
			}else{
				$('#dynamic-meta-content').append(res);
			}
            $('#dynamic-meta-content').trigger('dmadded');
            $('.dm-tool-loading').css('display', '');
		});
	});


    $('#dynamic-meta-content').on('focus', 'textarea', function(e){
        $(this).closest('.dm-content-box').addClass('focus');
    });

    $('#dynamic-meta-content').on('blur', 'textarea', function(e){
        $(this).closest('.dm-content-box').removeClass('focus');
    });


	setTimeout(function(){$('#dynamic-meta-content').trigger('dmadded')}, 500);

	/*
	* TinyMCE and stuff
	*/
	var UID = 1;
	$('#dynamic-meta-wrapper textarea').each(function(index, elem){
		if($(elem).hasClass('mce-off')) return;
		elem.id = 'dm_mce_' + UID;
		elem.className += ' new-tinymce';
		UID++;
	});

	//$('#dynamic-meta-wrapper textarea').addClass('new-tinymce');
    // to disable tinymce
    if( typeof(PukkaDM) != "undefined" && typeof(PukkaDM.tinyMCE) != "undefined" && PukkaDM.tinyMCE == true ) {
        setTimeout(prepareTextAreas, 500);
    }

	function prepareTextAreas(){
		startTinyMCE("textarea.new-tinymce");
		$('#dynamic-meta-wrapper textarea.new-tinymce').addClass('tinymce').removeClass('new-tinymce');
	}


	// when new dm added, init tinymce on that element
	$('#dynamic-meta-content').on('dmadded dmmcerefresh', function(e){
		// add color pickers if needed
		$('#dynamic-meta-wrapper .dm-select-color').wpColorPicker(colorOptions);
		// add numerci up-down
		$('.numeric-updown').numericUpDown();

		$('#dynamic-meta-wrapper textarea').each(function(index, elem){
			var $elem = $(elem);
			if($elem.hasClass('mce-off')) return;
			if(typeof(elem.id) == 'undefined' || elem.id == ''){
				elem.id = 'dm_mce_' + UID;
				UID++;
			}
			if(!$elem.hasClass('tinymce') && !$elem.hasClass('mce-off')){
				$elem.addClass('new-tinymce');
			}
		});

        // to disable tinymce
        if( typeof(PukkaDM) == "undefined" && typeof(PukkaDM.tinyMCE) == "undefined" && PukkaDM.tinyMCE == true ) {
            prepareTextAreas();
        }
	});


    $('form#post').submit(function(e){
        $('textarea.tinymce').each(function(index, elem){
            var $elem = $(elem);
            if(null != tinymce.get($elem.attr('id'))){
                tinymce.get($elem.attr('id')).save();
            }
            $elem.trigger('blur');
        });
    });


	$('#dynamic-meta-content').on('click', '.dm-enable-mce', function(e){
		var $txtBox = $(this).closest('.dm-content-box').find('textarea.tinymce, textarea.mce-off');
		var $check = $(this);

		$txtBox.each(function(index, elem){
			var $elem = $(elem);
			var id = $elem.attr('id');
			if($check.is(':checked')){
				startTinyMCE('#' + id);
				$elem.removeClass('mce-off').addClass('tinymce');
			}else{
				if(tinymce.majorVersion < 4){
					try{
						tinymce.execCommand('mceRemoveControl', false, id);
					}catch(error){}
				}else{
					try{
						tinymce.get(id).destroy();
					}catch(error){}
				}
				$elem.removeClass('tinymce').addClass('mce-off');
			}
		});
	});

	/*
	$('.dynamic-meta-box').blur(function(e){
		$(this).find('textarea.tinymce').each(function(index, elem){
			var $elem = $(elem);
			tinymce.get($elem.attr('id')).save();
			$elem.trigger('blur');
		});
	});
	*/

});

function startTinyMCE(selector){

	if( typeof(tinymce) == "undefined" ){
		return;
	}

	if(tinymce.majorVersion < 4){
		jQuery(selector).each(function(index, elem){
			tinymce.execCommand('mceAddControl',false, elem.id);
		});
	}else{
		tinymce.init({
			selector: selector,
			plugins: [
					"image charmap hr",
					"fullscreen media",
					"directionality textcolor paste textcolor"
			],

			toolbar1: "fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontsizeselect",
			toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | inserttime preview | forecolor backcolor",
			toolbar3: "hr removeformat | subscript superscript | charmap emoticons | fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

			image_advtab: true,
			menubar: false,
			toolbar_items_size: 'small',

			style_formats: [
					{title: 'Bold text', inline: 'b'},
					{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
					{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
					{title: 'Example 1', inline: 'span', classes: 'example1'},
					{title: 'Example 2', inline: 'span', classes: 'example2'},
					{title: 'Table styles'},
					{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
			],

			templates: [
					{title: 'Test template 1', content: 'Test 1'},
					{title: 'Test template 2', content: 'Test 2'}
			]
		});
	}
}

function updateMCE(item){
	var $item = jQuery(item);
	if(!$item.hasClass('tinymce')){
		$item = $item.find('textarea.tinymce');
	}
	$item.each(function(index, elem){
		var $elem = jQuery(elem);
		var id = $elem.attr('id');
		if(tinymce.majorVersion < 4){
			try{
				tinymce.execCommand('mceRemoveControl', false, id);
			}catch(error){}
		}else{
			try{
				tinymce.get(id).destroy();
			}catch(error){}
		}

		startTinyMCE('#' + id);
	});
}

function resumeMCE(container){
	jQuery(container).find('textarea.tinymce').each(function(index, elem){
		var $elem = jQuery(elem);
		if($elem.hasClass('tinymce')){
			var id = $elem.attr('id');
			startTinyMCE('#' + id);
		}
	});
}

function stopMCE(container){
	jQuery(container).find('textarea.tinymce').each(function(index, elem){
		var $elem = jQuery(elem);
		if($elem.hasClass('tinymce')){
			var id = $elem.attr('id');
			if(tinymce.majorVersion < 4){
				try{
					tinymce.execCommand('mceRemoveControl', false, id);
				}catch(error){}
			}else{
				try{
					tinymce.get(id).destroy();
				}catch(error){}
			}
		}
	});
}