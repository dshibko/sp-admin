$(document).ready(function () {
    var count = 0;
    var gridFluid = function (size) {
        if (count != 0){
            $('#container').masonry( 'destroy' );
        }
        $('#container').masonry({
            itemSelector: '.item',
            isResizable: false,
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
if (window.communityPredictedData !== undefined){
    plots.push($.plot('#community-predicted-results',
        window.communityPredictedData,
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
}
$(window).on('resize', function() {
    for (var i = plots.length - 1; i >= 0; i--) {
        plots[i].resize();
        plots[i].setupGrid();
        plots[i].draw();
    };
});