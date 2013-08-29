$(window).load(function () {
    var count = 0;
    var gridFluid = function (size) {
        if (count != 0){
            $('#container').masonry( 'destroy' );
        }
        $('#container').masonry({
            itemSelector: '.item',
           // isResizable: false,
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

function initCharts(doResize){

    if (window.clubVictoryPredictionsData !== undefined){
        var clubVictoryPredictionsChart = $.plot($('#chart-victory-predictions'),
            window.clubVictoryPredictionsData,
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
                                return '<b style="color: #a01749;">'+label+'</b>';
                            }
                        }
                    }
                },
                legend: {
                    show: false
                }
            }
        );

        if (doResize === true){
            clubVictoryPredictionsChart.resize();
            clubVictoryPredictionsChart.setupGrid();
            clubVictoryPredictionsChart.draw();
        }
    }

    if (window.headToHeadData !== undefined){
        var headToHeadChart = $.plot($('#chart-head-to-head-results'), 
            window.headToHeadData, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        innerRadius: 0.4,
                        stroke: { width: 0 },
                        label:{
                            radius: 3/4,
                            formatter: function (label, series) {
                                return label;
                            },
                            background: {

                            }
                        }
                    }
                },
                legend: {
                    show: false
                }
            });

        if (doResize === true){
            headToHeadChart.resize();
            headToHeadChart.setupGrid();
            headToHeadChart.draw();
        }

    }

    if (window.topScoresData !== undefined){
        var top5ScoresChart = $.plot($('#chart_top5'),
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
         });

        if (doResize === true){
            top5ScoresChart.resize();
            top5ScoresChart.setupGrid();
            top5ScoresChart.draw();
        }

    }




//    if ($('#chart-head-to-head-results').size() > 0){
//        var headToHeadChart = $.plot($('#chart-head-to-head-results'),
//            [
//                {color: '#363636', data: 7, label:''},
//                {color: '#144a9b', data: 25, label:''},
//                {color: '#7f7f80', data: 10,label:''}
//            ],
//            {
//                series: {
//                    pie: {
//                        innerRadius: 0.4,
//                        stroke: {color: '#e6edf8', width: 0},
//                        show: true,
//                        radius: 1,
//                        label: {
//                            show: true,
//                            radius: 2/3,
//                            formatter: function(label, series) {
//                                return series.data[0][1];
//                            }
//                        }
//                    }
//                },
//                legend: {
//                    show: false
//                }
//            }
//        );
//
//        if (doResize === true){
//            headToHeadChart.resize();
//            headToHeadChart.setupGrid();
//            headToHeadChart.draw();
//        }
//    }

    if ($('#chart-away-form').size() > 0){
        var awayFormChart = $.plot($('#chart-away-form'),
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
        );

        if (doResize === true){
            awayFormChart.resize();
            awayFormChart.setupGrid();
            awayFormChart.draw();
        }
    }

    if ($('#chart-home-form').size() > 0){
        var homeFormChart = $.plot($('#chart-home-form'),
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
                                return series.data[0][1]+ '%';
                            }
                        }
                    }
                },
                legend: {
                    show: false
                }
            }
        );

        if (doResize === true){
            homeFormChart.resize();
            homeFormChart.setupGrid();
            homeFormChart.draw();
        }
    }
}

initCharts(false);
$(window).on('resize', function() {
    initCharts(true);
});
