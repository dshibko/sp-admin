function isMobile() {
    return is_iphone || is_touch_device();
}
var turnOffCustomStylePoint = isMobile() ? 767 : 0;

var makePredictionOptions = {

  responsiveAt: turnOffCustomStylePoint,

  inputs: {
      // 'title': {

      // }
  },

  onSuccess: onSuccess

};

function onSuccess() {
    var homeScoreElement = $("#home-team-score");
    var awayScoreElement = $("#away-team-score");
    if (homeScoreElement.val() != '' && awayScoreElement.val() == '')
        awayScoreElement.val('0');
    if (awayScoreElement.val() != '' && homeScoreElement.val() == '')
        homeScoreElement.val('0');
    unbindUnload();
}

if ($(document).width() <  turnOffCustomStylePoint)
    makePredictionOptions.disableCustom = 'select';

var formElement = $('#make-prediction');


try {
    var $makePrediction = formElement.idealforms(makePredictionOptions).data('idealforms');
} catch (e) {
    var $makePrediction = undefined;
    formElement.submit(onSuccess);
}

var prevValues = [];
if (savedHomeScore != -1 && savedAwayScore != -1) {
    prevValues['home-team-score'] = savedHomeScore;
    prevValues['away-team-score'] = savedAwayScore;
}

var numbersKeys = [48, 49, 50, 51, 52, 53, 54 , 55, 56, 57,
                    96, 97, 98, 99, 100, 101, 102, 103, 104, 105];

//var countdownEl;
var startTime;

$(document).ready(function () {

    if (isEditMode)
        $("button[type='submit']").on('click', enableEditMode);

    $("div.away-team section, div.home-team section").each(function(i, obj) {
        if ($(obj).find('select').size() > 0) $(obj).find('span.ideal-field, select').hide();
    });

    $(window).resize(resizeFix);
    resizeFix();

    var countdown = $('aside.competition-countdown');
    var untilTime = countdown.find('strong').text();
    startTime = new Date(parseInt(untilTime) * 1000);
    countdown.find('strong').remove();
    initCountdown();
    countdown.show();

    $("#home-team-score, #away-team-score").blur(function() {
        if ($(this).val() == '' && !$(this).attr('disabled'))
            $(this).val('0');
        $(this).keyup();
    });

    $("#home-team-score, #away-team-score").keydown(function(event) {
        if ($.inArray(event.which, $.merge([8, 9, 46], numbersKeys)) == -1) {
            event.stopPropagation();
            return false;
        }
        if ($.inArray(event.which, numbersKeys) != -1)
            $(this).val('');
        if (event.which == 9)
            $(this).keyup();
    });

    $("#home-team-score, #away-team-score").keyup(function() {
        var goals = parseInt($(this).val());
        if (jQuery.isNumeric(goals)) {
            goals = parseInt(goals);
            if (goals != getPrevGoalsValue(this.id)) {
                initUnload();
                var wrapperClass = this.id.substring(0, this.id.length - 6);
                var originalSelectId = this.id + 'r';
                var section = $(this).parents("." + wrapperClass).find('section');
                var originalSelect = $("#" + originalSelectId);

                var squad = [];
                for (var i = 0; i < originalSelect.get(0).options.length; i++) {
                    var player = originalSelect.get(0).options[i];
                    squad[squad.length] = player.text + '::' + player.value;
                }

                if (getPrevGoalsValue(this.id) < goals) {
                    for (var i = getPrevGoalsValue(this.id); i < goals; i++) {
                        var id = originalSelect.attr('id') + '-' + (i + 1);
                        addSelect(section, squad, id);
                    }
                } else {
                    for (var i = getPrevGoalsValue(this.id); i > goals; i--) {
                        var id = originalSelect.attr('id') + '-' + i;
                        removeSelect(id);
                    }
                }

                setPrevGoalsValue(this.id, goals);
            }
        } else
            $(this).val('');
        renderScorersLabel();
    });

    $(".home-team, .away-team").on("change", "select", function() {
        initUnload();
    });

});

function enableEditMode(event) {
    event.preventDefault();
    $(this).html($(this).find('div').html());
    $("#home-team-score, #away-team-score").removeAttr('disabled');
    $("div.home-team-scorers, div.away-team-scorers").html('');
    $("div.home-team section, div.away-team section").find('span.ideal-field, select').show();
    $("button[type='submit']").off('click', enableEditMode);
    renderScorersLabel();
}

var prevPeriods;

function initCountdown() {
    prevPeriods = null;
    $.ajax({
        type: "POST",
        url: utcTimeUrl,
        async: false,
        success : function(data) {
            var now = new Date();
            now.setTime(data * 1000);
            showCountdown(now);
        },
        error : function(data) {
            showCountdown(new Date());
        }
    });
}

function showCountdown(now) {
    var cdEl = $("<strong></strong>");
    cdEl.countdown({
        until: startTime,
        format: "DHMS",
        layout: "{dnn} {hnn} {mnn} {snn}",
        serverSync: function() {
            return now;
        },
        onTick: function (periods) {
            if (prevPeriods !== null) {
                var prevDate = getDateFromPeriods(prevPeriods);
                var newDate = getDateFromPeriods(periods);
                var timeDif = prevDate.getTime() - newDate.getTime();
                if (timeDif < 0 || timeDif > 1000) {
                    $('aside.competition-countdown p strong').countdown('destroy').remove();
                    initCountdown();
                }
            }
            prevPeriods = periods;
        },
        expiryUrl: liveMatchRedirect
    });
    $('aside.competition-countdown p').prepend(cdEl);
}

function getDateFromPeriods(periods) {
    var date = new Date();
    var time = periods[6] + periods[5] * 60 + periods[4] * 60 * 60 + periods[3] * 60 * 60 * 24;
    date.setTime(time * 1000);
    return date;
}

function addSelect(section, data, id) {
    if ($makePrediction !== undefined) {
        $makePrediction.addFields(
            {
                id: id,
                name: id,
                type: 'select',
                list: data
            });
        var divWrapper = $("#" + id).parents("div.ideal-wrap:first");
        $("#" + id).parents("span.ideal-field:first").appendTo(section);
        divWrapper.remove();
    } else {
        var originalId = id.substring(0, id.lastIndexOf("-"));
        var select = $("#" + originalId);
        var newSelect = select.clone(true, true);
        newSelect.attr('id', id).attr('name', id).show();
        section.append(newSelect);
    }
}

function removeSelect(id) {
    var select = $("#" + id);
    if (select.parents('span.ideal-field').size() > 0)
        select.parents('span.ideal-field').remove();
    else
        select.remove();
}

function getPrevGoalsValue(id) {
    return prevValues[id] ? prevValues[id] : 0;
}

function setPrevGoalsValue(id, value) {
    prevValues[id] = value;
}

var emHeight = -1;

function resizeFix() {
    $('div.away-team label').height('auto');
    $('div.home-team label').height('auto');
    var maxHeight = Math.max($('div.away-team label').height(), $('div.home-team label').height());
    var minHeight = Math.min($('div.away-team label').height(), $('div.home-team label').height());
    if (emHeight == -1) emHeight = $('div.league-logo em').height();
    $('div.league-logo em').height(emHeight + maxHeight - minHeight);
    $('div.home-team label').height(maxHeight);
    $('div.away-team label').height(maxHeight);
}

var unloadInitialized = false;

function initUnload() {
    if (!unloadInitialized) {
        $(window).bind('beforeunload', pageUnload);
        unloadInitialized = true;
    }
}

function unbindUnload() {
    $(window).unbind('beforeunload', pageUnload);
}

function pageUnload() {
    return pageUnloadCopy;
}

function renderScorersLabel() {
    if ($("select[id*='home-team-scorer-']").size() > 0 || $("select[id*='away-team-scorer-']").size() > 0)
        $("div.league-logo span").show();
    else
        $("div.league-logo span").hide();
}