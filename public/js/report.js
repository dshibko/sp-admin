var $container = $('#container');
$container.imagesLoaded(function(){
  $container.masonry({
    itemSelector : '.item',
    columnWidth : function( containerWidth ) {
                    return containerWidth / 3;
                  },
    // isAnimated: true,
    isResizable: true,
    isFitWidth: true
  });
});


// var $container = $('#container');
// $container.imagesLoaded(function () {
//     $(window).resize(function () {
//         if ($(window).width() > 767) {
//             $container.masonry({
//                 itemSelector: '.item',
//                 columnWidth: function (containerWidth) {
//                     return containerWidth / 3;
//                 },
//                 // isAnimated: true,
//                 isResizable: true,
//                 isFitWidth: true
//             });
//         }
//         else {
//             $container.masonry({
//                 itemSelector: '.item',
//                 columnWidth: function (containerWidth) {
//                     return containerWidth / 2;
//                 },
//                 // isAnimated: true,
//                 isResizable: true,
//                 isFitWidth: true
//             });
//         }
//     });
// });

$.plot('#chart-head-to-head-results', 
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
);


$.plot('#chart-away-form', 
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


$.plot('#chart-home-form', 
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



