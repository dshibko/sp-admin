var turnOffCustomStylePoint = 767;

var loginOptions = {

  responsiveAt: turnOffCustomStylePoint,

  inputs: {
      'email': {
          filters: 'required email max',
          data: { max: 50 }
      },
      'password': {
          filters: 'required pass max',
          data: { max: 15 }
      }
  }
};

if ($(document).width() <  turnOffCustomStylePoint)
  loginOptions.disableCustom = 'select';

var $login = $('#login').idealforms(loginOptions).data('idealforms');
