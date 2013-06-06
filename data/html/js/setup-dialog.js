$('#setup').on('submit', function(event) { 
    $("#setup-dialog, #global-overlay").fadeOut({
        complete: function() {
            $(this).remove();
            $('body').removeClass('no-scroll');
        }
    }); 
    event.preventDefault();
});


var turnOffCustomStylePoint = 767;

var setupOptions = {

  responsiveAt: turnOffCustomStylePoint,
  disableCustom: 'button',
};

if ($(document).width() <  turnOffCustomStylePoint)
  setupOptions.disableCustom = 'select';

var $setup = $('#setup').idealforms(setupOptions).data('idealforms');
$setup.focusFirst();