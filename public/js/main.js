(function ($) {
    $(function () {

        $('#register').validate();
        $('#settings-change-password').validate();
        $('#settings-change-email').validate();
        $('#settings-change-display-name').validate();
        $('#settings-change-language').validate();
        /******************************START COOKIE BAR*******************************/
        var cookie_bar_displayed = $.cookie('cookie_bar_displayed');
        if (!cookie_bar_displayed){
            $('.cookie-bar').show(function(){
                $.cookie('cookie_bar_displayed', 1, { expires: 365 * 10,path : "/"}); //Expires in 10 years
            });
        }
        $('.close-cookie-bar').click(function(){
            $('.cookie-bar').hide();
        });
        /******************************* END COOKIE BAR*****************************/
    });
}(jQuery));