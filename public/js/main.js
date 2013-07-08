var is_iphone = navigator.userAgent.match(/iPhone/i);

function is_touch_device() {  
  try {  
    document.createEvent("TouchEvent");  
    return true;  
  } catch ( e ) {  
    return false;  
  }  
}

$(document).ready(function () {

// Only do anything if the browser does not support placeholders
    if (!Modernizr.input.placeholder) {

        // Format all elements with the placeholder attribute and insert it as a value
        $('[placeholder]').each(function() {
            if ($(this).val() == '') {
                $(this).val($(this).attr('placeholder'));
                $(this).addClass('placeholder');
            }
            $(this).focus(function() {
                if ($(this).val() == $(this).attr('placeholder') && $(this).hasClass('placeholder')) {
                    $(this).val('');
                    $(this).removeClass('placeholder');
                }
            }).blur(function() {
                if($(this).val() == '') {
                    $(this).val($(this).attr('placeholder'));
                    $(this).addClass('placeholder');
                }
            });
        });

        // Clean up any placeholders if the form gets submitted
        $('[placeholder]').parents('form').submit(function() {
            $(this).find('[placeholder]').each(function() {
                if ($(this).val() == $(this).attr('placeholder') && $(this).hasClass('placeholder')) {
                    $(this).val('');
                }
            });
        });

        // Clean up any placeholders if the page is refreshed
        window.onbeforeunload = function() {
            $('[placeholder]').each(function() {
                if ($(this).val() == $(this).attr('placeholder') && $(this).hasClass('placeholder')) {
                    $(this).val('');
                }
            });
        };
    }


    if($(".popup").size()!=0) { $(".popup").Popup();}
    $(".login-popup-button").click(function(event) {
        return false;
    });
    if($('.dialog:visible').length) {
        $('body').addClass('no-scroll');
    }
    function updateDialogPosition() {
        var dialog = $('.dialog:visible');
        if($(window).height() > dialog.outerHeight()) {
            dialog.css('margin-top', ($(window).height() - dialog.outerHeight()) / 2);
        } else {
            dialog.css('margin-top', 0)
        }
    }

    $(window).on('resize.dialog', updateDialogPosition);
    updateDialogPosition();


    if ( navigator.userAgent.indexOf(" Safari/") != -1 && navigator.userAgent.indexOf(" Version/5") != -1) {
        $('body').addClass("safari5");
    };
    $('#make-prediction').addClass("stack");

    if ( navigator.userAgent.indexOf(" Safari/") != -1 && navigator.userAgent.indexOf(" Version/5") != -1) {
        $('body').addClass("safari5");
    };
    $('#make-prediction').addClass("stack");

//
//nav
//

    var border = 4;

    $('a.mobilenav').click(function(e){
        var prevHeight = $('nav.main-navigation > ul').height();
        $('nav.main-navigation > ul').height('auto');
        var navHeight = $('nav.main-navigation > ul').height();
        $('nav.main-navigation > ul').height(prevHeight);
        var offset = navHeight + border;
        if( $('nav.main-navigation > ul').height() > 0 ){
            $('nav.main-navigation > ul').removeClass('expanded');
            $('nav.main-navigation > ul').animate({height: 0},200, undefined, function() {
                $('html,body').animate({ scrollTop: 0 }, 'slow');
            });
            $('a.mobilenav').css('background-position', '12px 12px');
        } else {
            $('nav.main-navigation > ul').addClass('expanded');
            $('nav.main-navigation > ul').animate({height: offset},200);
            $('a.mobilenav').css('background-position', '12px -24px');
        }
        return false;
    });

    var responsiveBreakpoint = 767;

    $(window).resize(function() {
        if ($('nav.main-navigation > ul').hasClass('expanded'))
            if ($(window).width() > responsiveBreakpoint)
                $('nav.main-navigation > ul').height($('nav.main-navigation > ul li:first').height());
            else {
                $('nav.main-navigation > ul').height('auto');
                $('nav.main-navigation > ul').height($('nav.main-navigation > ul').height());
            }
    });
//
//
//

});

;(function(window, document, undefined) {
    'use strict';

    var htmlElement     = document.getElementsByTagName('html')[0],
        documentElement = document.documentElement,
        layouts = {
            mobile: {
                // width: 750,
                width: 767,
                base: 16,
                min: 16,
                max: 16,
                breakpoint: 320
            },
            tabletportrait: {
                width:      768,
                base:       12,
                min:        12,
                max:        12,
                breakpoint: 767
                // breakpoint: 751
            },
            tabletlandscape: {
                width:      1024,
                base:       15,
                min:        12,
                max:        15,
                breakpoint: 769
            },
            desktop: {
                width:      1280,
                base:       16,
                min:        13,
                max:        16,
                breakpoint: 1025
            }
        },
        state = {
            size:   null,
            layout: null
        };

    function updateFontsize() {
        var width, id, layout, current, size;

        width = documentElement.offsetWidth;

        for(id in layouts) {
            if(layouts[id].breakpoint && width >= layouts[id].breakpoint) {
                layout = id;
            }
        }

        if(layout !== state.layout) {
            state.layout = layout;
            htmlElement.setAttribute('data-layout', layout);
        }

        if (state.layout && layouts[state.layout]){
            current = layouts[state.layout];

            size = Math.max(current.min, Math.min(current.max, Math.floor(current.base * (width / current.width))));

            if(size !== state.size) {
                state.size = size;

                htmlElement.style.fontSize = size + 'px';
            }
        }

    }

    $(window).resize(updateFontsize);
    $(window).bind("orientationchange", updateFontsize);

    updateFontsize();
}(window, document));