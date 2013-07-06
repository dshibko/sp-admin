function isMobile() {
    return is_iphone || is_touch_device();
}
var turnOffCustomStylePoint = isMobile() ? 767 : 0;

var seasonFilterOptions = {

  responsiveAt: turnOffCustomStylePoint
};

if ($(document).width() <  turnOffCustomStylePoint)
  seasonFilterOptions.disableCustom = 'select';

try {
    $('#season-filter').idealforms(seasonFilterOptions).data('idealforms');
} catch (e) {}

var blockShowMore = false;
var activeRequest = false;

$(document).ready(function () {

    $('#show-more').click(function(event) {
        event.preventDefault();
        if (!blockShowMore)
            loadLeagueRows($("#league-table tr").size() - 1);
    });

    $('#league-filter').change(function() {
        var filterKey = $(this).val();
        switch (filterKey) {
            case 'everyone':
                loadLeagueRows(0, function() {
                    $("#league-table tr:not(:first)").remove();
                });
                break;
            case 'you-and-around':
                loadAroundYouLeagueRows();
                break;
            case 'your-friends':
                loadYourFriendsLeagueRows();
                break;
        }
    });

});

function loadLeagueRows(offset, callback) {
    if (activeRequest !== false) activeRequest.abort();
    activeRequest = $.ajax({
        type: 'get',
        url: window.location.href,
        data: {
            offset: offset,
            onlyRows: true
        },
        success: function(data) {
            if (callback !== undefined && typeof callback == 'function') callback();
            $("#league-table tbody").append(data);
            if ($("#league-table tr").size() - 1 == $("#users-count").val())
                $('#show-more').hide();
            else
                $('#show-more').show();
            blockShowMore = false;
        },
        beforeSend: function() {
            blockShowMore = true;
        }
    });
}

function loadAroundYouLeagueRows() {
    if (activeRequest !== false) activeRequest.abort();
    activeRequest = $.ajax({
        type: 'get',
        url: window.location.href,
        data: {
            aroundYou: true,
            onlyRows: true
        },
        success: function(data) {
            $("#league-table tr:not(:first)").remove();
            $("#league-table tbody").append(data);
            $('#show-more').hide();
        }
    });
}

function loadYourFriendsLeagueRows() {
    if (activeRequest !== false) activeRequest.abort();
    activeRequest = $.ajax({
        type: 'get',
        url: window.location.href,
        data: {
            yourFriends: true,
            onlyRows: true
        },
        success: function(data) {
            $("#league-table tr:not(:first)").remove();
            $("#league-table tbody").append(data);
            $('#show-more').hide();
        }
    });
}