var turnOffCustomStylePoint = 767;

var registerOptions = {

  responsiveAt: turnOffCustomStylePoint,
  onFail: function(){},
  onSuccess: function(){},
  inputs: {
      'title': {
          filters: 'exclude',
          data: { exclude: [""] },
          errors : {
            exclude: 'Select a Title.'
          }
      },
      'first_name': {
          filters: 'required max',
          data: {
              max: 30
          }
      },
      'last_name': {
          filters: 'required max',
          data: {
              max: 30
          }
      },
      'country': {
          filters: 'exclude',
          data: { exclude: [""] },
          errors : {
            exclude: 'Select a Country.'
          }
      },
      'email': {
          filters: 'required email max',
          data: { max: 50 }
      },
      'confirm_email': {
          filters: 'required email max equalto',
          data: {
              max: 50,
              equalto : '#registration-email'
          }
      },
      'password': {
          filters: 'required pass max',
          data: { max: 15 }
      },
      'confirm_password': {
          filters: 'required pass max equalto',
          data: {
              max: 15,
              equalto : '#registration-password'
          }
      },
      'display_name': {
          filters: 'required max',
          data: { max: 20 }
      },
      'avatar': {
          filters: 'extension',
          data: { extension: [ 'jpg', 'png', 'jpeg','gif', 'bmp' ] }
      }
  }
};

if ($(document).width() <  turnOffCustomStylePoint)
  registerOptions.disableCustom = 'select';

try {
    if (!$('html').hasClass('lt-ie8')) {
    var $register = $('#register').idealforms(registerOptions).data('idealforms');
    $register.focusFirst();
    }
} catch (e) {}
