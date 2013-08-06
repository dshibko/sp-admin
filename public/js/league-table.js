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
    if (!$('html').hasClass('lt-ie8')) {
    $('#season-filter').idealforms(seasonFilterOptions).data('idealforms');
    }
} catch (e) {}

var blockShowMore = false;
var activeRequest = false;

$(document).ready(function () {

    $('#show-more').click(function(event) {
        event.preventDefault();
        if (!blockShowMore)
            loadLeagueRows(showMoreLoaderType, $("#league-table tr").size() - 1);
    });

    $('#league-filter').change(function() {
        var filterKey = $(this).val();
        switch (filterKey) {
            case 'everyone':
                loadLeagueRows(selectLoaderType, function() {
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

    $("#league-code-button").click(function(e) {
        e.preventDefault();
        alertDialog($("#private-league-code").html());
    });

    $("#league-edit-button").click(function(e) {
        e.preventDefault();
        $("#league-edit-button").hide();
        $("#league-delete-button").show();
        $("#league-table").find("td.player a.remove-player").show();
    });

    $("#league-delete-button").click(function() {
        return confirm($(this).attr('title'));
    });

    $("#league-leave-button").click(function() {
        return confirm($(this).attr('title'));
    });

    $("#league-table").on('click', "td.player a.remove-player", function() {
        return confirm($(this).attr('title'));
    });

});

function loadLeagueRows(type, offset, callback) {
    if (activeRequest !== false) activeRequest.abort();
    showLoaderImg(type);
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
        complete: function() {
            hideLoaderImg(type);
        },
        beforeSend: function() {
            blockShowMore = true;
        }
    });
}

function loadAroundYouLeagueRows() {
    if (activeRequest !== false) activeRequest.abort();
    showLoaderImg(selectLoaderType);
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
        },
        complete: function() {
            hideLoaderImg(selectLoaderType);
        }
    });
}

function loadYourFriendsLeagueRows() {
    if (activeRequest !== false) activeRequest.abort();
    showLoaderImg(selectLoaderType);
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
        },
        complete: function() {
            hideLoaderImg(selectLoaderType);
        }
    });
        }

var loaderImgScr = '/img/ajax-loader.gif';
var showMoreLoaderType = 'show-more';
var selectLoaderType = 'select';

function showLoaderImg(type) {
    switch (type) {
        case showMoreLoaderType:
            var loaderImg = $("<img/>");
            loaderImg.attr('src', loaderImgScr).load(function() {
                var showMoreEl = $('p > a#show-more');
                var loaderImgWrapper = showMoreEl.parents('p:first');
                loaderImgWrapper.css('position', 'relative');
                loaderImg.css('position', 'absolute');
                loaderImg.css('top', 10);
                loaderImgWrapper.append(loaderImg);
                loaderImg.css('left', showMoreEl.position().left + showMoreEl.outerWidth() / 2 - this.width / 2);
            });
            break;
        case selectLoaderType:
            var loaderImg = $("<img/>");
            loaderImg.attr('src', loaderImgScr).load(function() {
                var selectForm = $('form#season-filter');
                var loaderImgWrapper = selectForm.parents('header:first');
                loaderImgWrapper.css('position', 'relative');
                loaderImg.css('position', 'absolute');
                loaderImgWrapper.append(loaderImg);
                loaderImg.css('left', selectForm.position().left - this.width - 10);
                loaderImg.css('top', 3);
    });
            break;
    }
}

function hideLoaderImg(type) {
    switch (type) {
        case showMoreLoaderType:
            $('p > a#show-more').parents('p:first').find('img').remove();
            break;
        case selectLoaderType:
            $('form#season-filter').parents('header:first').children('img').remove();
            break;
    }
}

function alertDialog(text) {
    alert(text);
}