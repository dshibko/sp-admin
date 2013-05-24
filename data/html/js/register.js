var turnOffCustomStylePoint = 767;

var registerOptions = {

  responsiveAt: turnOffCustomStylePoint,

  onFail: function() {
    alert( $register.getInvalid().length +' invalid fields.' )
  },
  onFail: function(){
    // Form does NOT validate
  },

  inputs: {
      'title': {
          filters: 'exclude',
          data: { exclude: ['default'] },
          errors : {
            exclude: 'Select a Title.'
          }
      },
      'first_name': {
          filters: 'required name ajax',
          data: {
            //   ajax: {
            //     url: 'validate.php',
            //     _success: function( resp, text ) {
            //           // The request was succesful
            //     },
            //     _error: function( text, error ) {
            //           // The request failed
            //     },
            //     // Other $.ajax methods
            //   }
            // },
            // errors: {
            //   ajax: {
            //     success: 'Name not available.',
            //     error: 'Sorry, there was an error on the server. Try again later.'
            //   }
            }
      },
      'last_name': {
          filters: 'required name',
          data: {
            //ajax: { url:'validate.php' }
          }
      },
      'country': {
          filters: 'exclude',
          data: { exclude: ['default'] },
          errors : {
            exclude: 'Select a Country.'
          }
      },
      'email': {
          filters: 'required email max',
          data: { max: 50 }
      },
      'confirm_email': {
          filters: 'required email max',
          data: { max: 50 }
      },
      'password': {
          filters: 'required pass max',
          data: { max: 15 }
      },
      'confirm_password': {
          filters: 'required pass max',
          data: { max: 15 }
      },
      'display_name': {
          filters: 'required username max',
          data: { max: 20 }
      },
      'avatar': {
          filters: 'extension',
          data: { extension: [ 'jpg', 'png' ] }
      }
  }
};

if ($(document).width() <  turnOffCustomStylePoint)
  registerOptions.disableCustom = 'select';

var $register = $('#register').idealforms(registerOptions).data('idealforms');
$('#reset').click(function(){ $register.reset().fresh().focusFirst() });
$register.focusFirst();