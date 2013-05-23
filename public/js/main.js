var is_iphone = navigator.userAgent.match(/iPhone/i);

// function loaded() {
//   document.addEventListener('touchmove', function(e){ e.preventDefault(); });
// }
// document.addEventListener('DOMContentLoaded', loaded);

function is_touch_device() {
    try {
        document.createEvent("TouchEvent");
        return true;
    } catch ( e ) {
        return false;
    }
}

$(document).ready(function () {

    /******************************START COOKIE BAR*******************************/
    var cookie_bar_displayed = $.cookie('cookie_bar_displayed');
    if (!cookie_bar_displayed){
        $('.cookie-bar').show(function(){
            $.cookie('cookie_bar_displayed', 1, { expires: 365 * 10,path : "/"}); //Expires in 10 years
        });
    }
    $('.close-cookie-bar').click(function(){
        $('.cookie-bar').hide();
    });
    /******************************* END COOKIE BAR*****************************/
    
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

    if ( navigator.userAgent.indexOf(" Safari/") != -1 && navigator.userAgent.indexOf(" Version/5") != -1) {
        $('body').addClass("safari5");
    }


    ;(function(window, document, undefined) {
        'use strict';

        var htmlElement     = document.getElementsByTagName('html')[0],
            documentElement = document.documentElement,
            layouts = {
                mobile: {
                    width: 750,
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
                    breakpoint: 751
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

            current = layouts[state.layout];

            size = Math.max(current.min, Math.min(current.max, Math.floor(current.base * (width / current.width))));

            if(size !== state.size) {
                state.size = size;

                htmlElement.style.fontSize = size + 'px';
            }
        }

        $(window).resize(updateFontsize);
        $(window).bind("orientationchange", updateFontsize);

        updateFontsize();
    }(window, document));

});

