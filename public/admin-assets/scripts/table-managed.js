var TableManaged = function () {

    jQuery.fn.dataTableExt.oSort['gender-asc']  = function(x,y) {
        return ((x < y) ? -1 : ((x > y) ?  1 : 0));
    };

    jQuery.fn.dataTableExt.oSort['gender-desc'] = function(x,y) {
        return ((x < y) ?  1 : ((x > y) ? -1 : 0));
    };

    return {

        //main function to initiate the module
        init: function (messages, aoColumns, aaSorting) {

            if (!jQuery().dataTable) {
                return;
            }

            if (aoColumns == undefined)
                aoColumns = [
                    null,
                    null,
                    { "sType": 'gender' },
                    null];

            if (aaSorting == undefined)
                aaSorting = [[ 3, "desc" ]];

            // begin first table
            $('#users-table').dataTable({
                "aoColumns": aoColumns,
                "aaSorting": aaSorting,
                "bFilter": false,
                "oLanguage": {
                    "sLengthMenu": messages.perPage,
                    "oPaginate": {
                        "sPrevious": messages.prevLink,
                        "sNext": messages.nextLink
                    },
                    "sInfo": messages.showingLabel
                }
            });

        }
    };

}();