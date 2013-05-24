var turnOffCustomStylePoint = 767;

var makePredictionOptions = {

  responsiveAt: turnOffCustomStylePoint,

  inputs: {
      // 'title': {

      // }
  }
};

if ($(document).width() <  turnOffCustomStylePoint)
  registerOptions.disableCustom = 'select';

var $makePrediction = $('#make-prediction').idealforms(makePredictionOptions).data('idealforms');
$makePrediction.focusFirst();