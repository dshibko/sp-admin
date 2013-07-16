var turnOffCustomStylePoint = 767;

var loginOptions = {

    responsiveAt: turnOffCustomStylePoint,
    onFail:function(){},
    onSuccess: function(){},
    inputs: {
        'email': {
            filters: 'required email max',
            data: { max: 50 }
        }
    }
};
if ($(document).width() <  turnOffCustomStylePoint)
    loginOptions.disableCustom = 'select';

try {
    if (!$('html').hasClass('lt-ie8')) {
    $('#forgot').idealforms(loginOptions).data('idealforms');
    }
} catch (e) {}

