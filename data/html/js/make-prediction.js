var turnOffCustomStylePoint = 767;

var makePredictionOptions = {

  responsiveAt: turnOffCustomStylePoint,
  disableCustom: 'input',

  inputs: {
      // 'home-team-score': {
      //     filters: 'number range',
      //     data: { range: [ 1, 100 ] }
      // }
  }
};

if ($(document).width() <  turnOffCustomStylePoint)
  makePredictionOptions.disableCustom = 'select';

var $makePrediction = $('#make-prediction').idealforms(makePredictionOptions).data('idealforms');
// $makePrediction.focusFirst();
