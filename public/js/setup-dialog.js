/*$('#setup').on('submit', function(event) {
    $("#setup-dialog, #global-overlay").fadeOut({
        complete: function() {
            $(this).remove();
            $('body').removeClass('no-scroll');
        }
    });
    event.preventDefault();
});*/

jQuery.extend( jQuery.idealforms.filters, {
    terms: {
        error: 'Term is required',
        regex: function(inputData, value){
           return jQuery(inputData.input).is(":checked");
        }
    }
})
var turnOffCustomStylePoint = 767;

var setupOptions = {

  responsiveAt: turnOffCustomStylePoint,
  onFail: function(){},
  onSuccess: function(){},
  disableCustom: 'button'
};

if ($(document).width() <  turnOffCustomStylePoint)
  setupOptions.disableCustom = 'select';

try {
    if (!$('html').hasClass('lt-ie8')) {
        var $setup = $('#setup').idealforms(setupOptions).data('idealforms');
        $setup.focusFirst();
    }
} catch (e) {}
