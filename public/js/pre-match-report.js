$(document).ready(function () {
    var count = 0;
    var gridFluid = function (size) {
        if (count != 0){
            $('#container').masonry( 'destroy' );
        }
        $('#container').masonry({
            itemSelector: '.item',
            // isFitWidth: true,
            columnWidth: function (containerWidth) {
                return containerWidth / size;
            }
        });
        count++;
    }
    if ($(window).width() > 767){
        gridFluid(3);
    } else {
        gridFluid(2);
    }
    $(window).on('resize', function () {
        if ($(this).width() > 767) {
            gridFluid(3);
        } else {
            gridFluid(2);
        }
    });
});


var plots = [];
if (window.topScoresData !== undefined){
    plots.push($.plot('#chart_top5',
        window.topScoresData, {
            series: {
                pie: {
                    innerRadius: 0.4,
                    stroke: {color: '#f2e1e7', width: 0},
                    show: true,
                    label: {
                        show: false
                    }
                }
            }
    }));

}

plots.push($.plot('#community-predicted-results',
    [
        {color: '#90b1d4', data: 95},
        {color: '#144a9b', data: 5}
    ],
    {
        series: {
            pie: {
                innerRadius: 0.8,
                stroke: {color: '#e6edf8', width: 0},
                show: true,
                radius: 1,
                label: {
                    show: true,
                    radius: 0,
                    formatter: function(label, series) {
                        return series.data[0][1] + '%';
                    }
                }
            }
        },
        legend: {
            show: false
        }
    }
));

if ($('#chart-head-to-head-results').size() > 0){
    plots.push($.plot('#chart-head-to-head-results',
        [
            {color: '#363636', data: 530, label:''},
            {color: '#144a9b', data: 193, label:''},
            {color: '#7f7f80', data: 20,label:''}
        ],
        {
            series: {
                pie: {
                    innerRadius: 0.4,
                    stroke: {color: '#e6edf8', width: 0},
                    show: true,
                    radius: 1,
                    label: {
                        show: true,
                        radius: 2/3,
                        formatter: function(label, series) {
                            return series.data[0][1] + label;
                        }
                    }
                }
            },
            legend: {
                show: false
            }
        }
    ));
}



if ($('#chart-away-form').size() > 0){
    plots.push($.plot('#chart-away-form',
        [
            {color: '#363636', data: 53, label : '%'},
            {color: '#144a9b', data: 41, label : '%'},
            {color: '#7f7f80', data: 6, label : '%'}
        ],
        {
            series: {
                pie: {
                    innerRadius: 0.4,
                    stroke: {color: '#e6edf8', width: 0},
                    show: true,
                    radius: 1,
                    label: {
                        show: true,
                        radius: 2/3,
                        formatter: function(label, series) {
                            return series.data[0][1] + label;
                        }
                    }
                }
            },
            legend: {
                show: false
            }
        }
    ));
}



if ($('#chart-home-form').size() > 0){
    plots.push($.plot('#chart-home-form',
        [
            {color: '#363636', data: 53, label : '%'},
            {color: '#144a9b', data: 41, label : '%'},
            {color: '#7f7f80', data: 6, label : '%'}
        ],
        {
            series: {
                pie: {
                    innerRadius: 0.4,
                    stroke: {color: '#e6edf8', width: 0},
                    show: true,
                    radius: 1,
                    label: {
                        show: true,
                        radius: 2/3,
                        formatter: function(label, series) {
                            return series.data[0][1]+ label;
                        }
                    }
                }
            },
            legend: {
                show: false
            }
        }
    ));
}


$(window).on('resize', function() {
    for (var i = plots.length - 1; i >= 0; i--) {
        plots[i].resize();
        plots[i].setupGrid();
        plots[i].draw();
    };
});

