var TableManaged = function () {

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
                    null,
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