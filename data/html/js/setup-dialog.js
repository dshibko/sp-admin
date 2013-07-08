$('#setup').on('submit', function(event) { 
    $("#setup-dialog, #global-overlay").fadeOut({
        complete: function() {
            $(this).remove();
            $('body').removeClass('no-scroll');
        }
    }); 
    event.preventDefault();
    $(window).off('resize.dialog');
});


var turnOffCustomStylePoint = 767;

var setupOptions = {

  responsiveAt: turnOffCustomStylePoint,
  disableCustom: 'button'

  // inputs: {
  //     'term1': {
  //         filters: 'min',
  //         data: { min: 1 },
  //         errors: { min: 'Check only <strong>1</strong> option.' }
  //     }
  //   }
};

if ($(document).width() <  turnOffCustomStylePoint)
  setupOptions.disableCustom = 'select';

var $setup = $('#setup').idealforms(setupOptions).data('idealforms');
$setup.focusFirst();