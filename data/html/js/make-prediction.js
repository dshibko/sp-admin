var turnOffCustomStylePoint = 767;

var makePredictionOptions = {

  responsiveAt: turnOffCustomStylePoint,
  disableCustom: 'input'
};

if ($(document).width() <  turnOffCustomStylePoint)
  makePredictionOptions.disableCustom = 'select';

var $makePrediction = $('#make-prediction').idealforms(makePredictionOptions).data('idealforms');



