var turnOffCustomStylePoint = 767;

var loginOptions = {

  responsiveAt: turnOffCustomStylePoint,
  onFail:function(){},
  onSuccess: function(){},
  inputs: {
      'email': {
          filters: 'required email max',
          data: { max: 50 }
      },
      'password': {
          filters: 'required max',
          data: { max: 15 }
      }
  }
};
if ($(document).width() <  turnOffCustomStylePoint)
  loginOptions.disableCustom = 'select';

try {
    $('#login').idealforms(loginOptions).data('idealforms');
} catch (e) {}
