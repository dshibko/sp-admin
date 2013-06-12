function isMobile() {
    return is_iphone || is_touch_device();
}
var turnOffCustomStylePoint = isMobile() ? 767 : 0;

var seasonFilterOptions = {

  responsiveAt: turnOffCustomStylePoint
};

if ($(document).width() <  turnOffCustomStylePoint)
  seasonFilterOptions.disableCustom = 'select';

var $seasonFilter = $('#season-filter').idealforms(seasonFilterOptions).data('idealforms');

var blockShowMore = false;

$(document).ready(function () {

    $('#show-more').click(function(event) {
        event.preventDefault();
        if (!blockShowMore)
            $.ajax({
                type: 'get',
                url: window.location.href,
                data: {
                    offset: $("#league-table tr").size() - 1,
                    onlyRows: true
                },
                success: function(data) {
                    $("#league-table tbody").append(data);
                    if ($("#league-table tr").size() - 1 >= $("#users-count").val())
                        $('#show-more').remove();
                    blockShowMore = false;
                },
                beforeSend: function() {
                    blockShowMore = true;
                }
            });
    });

});