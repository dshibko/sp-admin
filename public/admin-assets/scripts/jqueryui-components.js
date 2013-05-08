var JQueryUIComponents = function () {

    
    var handleDatePickers = function () {
        $(".ui-date-picker").datepicker();
        $(".ui-date-picker-with-button-bar").datepicker({
          showButtonPanel: true
        });
    }

    return {
        //main function to initiate the module
        init: function () {
            handleDatePickers();
        }

    };

}();