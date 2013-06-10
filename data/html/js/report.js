$(document).ready(function () {
        var gridFluid = function (size) {
            console.log(size);
            $('#container').masonry( 'destroy' );
            $('#container').masonry({
                itemSelector: '.item',
                // isFitWidth: true,
                columnWidth: function (containerWidth) {
                    return containerWidth / size;
                }
            });
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

plots.push($.plot('#chart_top5', 
    [
      {color: '#a01749', data: 10},
      {color: '#b2466e', data: 20},
      {color: '#c47692', data: 30},
      {color: '#d6a4b7', data: 10},
      {color: '#e7c6d3', data: 30}
    ], {
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



plots.push($.plot('#chart-head-to-head-results', 
    [
      {color: '#363636', data: 530},
      {color: '#144a9b', data: 193},
      {color: '#7f7f80', data: 20}
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
                        return series.data[0][1];
                    }
                }
            }
        },
        legend: {
            show: false
        }
    }
));


plots.push($.plot('#chart-away-form', 
    [
      {color: '#363636', data: 53},
      {color: '#144a9b', data: 41},
      {color: '#7f7f80', data: 6}
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


plots.push($.plot('#chart-home-form', 
    [
      {color: '#363636', data: 53},
      {color: '#144a9b', data: 41},
      {color: '#7f7f80', data: 6}
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

$(window).on('resize', function() {
    for (var i = plots.length - 1; i >= 0; i--) {
        plots[i].resize();
        plots[i].setupGrid();
        plots[i].draw();
    };
});
// $(function() {
//     for (var i = plots.length - 1; i >= 0; i--) {
//         plots[i].resize();
//         plots[i].setupGrid();
//         plots[i].draw();
//     }
// });
