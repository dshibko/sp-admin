var turnOffCustomStylePoint = 767;

var makePredictionOptions = {

  responsiveAt: turnOffCustomStylePoint,

  inputs: {
      // 'title': {

      // }
  },

  onSuccess: function() {
      var homeScoreElement = $("#home-team-score");
      var awayScoreElement = $("#away-team-score");
      if (homeScoreElement.val() != '' && awayScoreElement.val() == '')
          awayScoreElement.val('0');
      if (awayScoreElement.val() != '' && homeScoreElement.val() == '')
          homeScoreElement.val('0');
  }

};

if ($(document).width() <  turnOffCustomStylePoint)
    makePredictionOptions.disableCustom = 'select';

var formElement = $('#make-prediction');
var $makePrediction = formElement.idealforms(makePredictionOptions).data('idealforms');
$makePrediction.focusFirst();

var prevValues = [];
if (savedHomeScore != -1 && savedAwayScore != -1) {
    prevValues['home-team-score'] = savedHomeScore;
    prevValues['away-team-score'] = savedAwayScore;
}

var numbersKeys = [48, 49, 50, 51, 52, 53, 54 , 55, 56, 57,
                    96, 97, 98, 99, 100, 101, 102, 103, 104, 105];

$(document).ready(function () {

    if ($('div.away-team label').height() != $('div.home-team label').height()) {
        var maxHeight = Math.max($('div.away-team label').height(), $('div.home-team label').height());
        var minHeight = Math.min($('div.away-team label').height(), $('div.home-team label').height());
        $('div.league-logo em').height($('div.league-logo em').height() + maxHeight - minHeight);
        $('div.home-team label').height(maxHeight);
        $('div.away-team label').height(maxHeight);
    }

    var countdown = $('aside.competition-countdown');
    var untilTime = countdown.find('strong').text();
    var startTime = new Date(parseInt(untilTime) * 1000);
    countdown.find('strong').countdown({
        until: $.countdown.UTCDate(-1 * startTime.getTimezoneOffset(), startTime),
        format: "DHMS",
        layout: "{dnn} {hnn} {mnn} {snn}",
        onExpiry: function() {
            window.location.href = liveMatchRedirect;
        }
    });
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
    });

});

function addSelect(section, data, id) {
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