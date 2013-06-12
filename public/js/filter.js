var turnOffCustomStylePoint = 767;

var seasonFilterOptions = {

  responsiveAt: turnOffCustomStylePoint
};

if ($(document).width() <  turnOffCustomStylePoint)
  seasonFilterOptions.disableCustom = 'select';

var $seasonFilter = $('#season-filter').idealforms(seasonFilterOptions).data('idealforms');
$seasonFilter.focusFirst();