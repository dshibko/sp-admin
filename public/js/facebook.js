var facebookShareUrl = 'https://www.facebook.com/sharer/sharer.php?s=100&p[url]={url}&p[summary]={summary}';
$(document).ready(function () {

    $('a.like').click(function(event) {
        event.preventDefault();
        var url = facebookShareUrl.replace("{url}", $(this).attr('href')).replace("{summary}", $(this).attr('summary'));
        window.open(url, 'facebook', 'width=500,height=300');
    });

});
