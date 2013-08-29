var TableManaged = function () {

    return {

        //main function to initiate the module
        init: function (messages, aoColumns, aaSorting, filter) {

            if (!jQuery().dataTable) {
                return;
            }

            if (aoColumns == undefined)
                aoColumns = [
                    null,
                    null,
                    null];

            if (aaSorting == undefined)
                aaSorting = [[ 2, "desc" ]];

            // begin first table
            var oTable = $('#users-table').dataTable({
                "aoColumns": aoColumns,
                "aaSorting": aaSorting,
                "bFilter": filter,
                "bStateSave" : true,
                "aLengthMenu": [[5, 10, 15, 25, 50, 100 , -1], [5, 10, 15, 25, 50, 100, "All"]],
                "iDisplayLength" : 25,
                "iCookieDuration": 60 * 60 * 24 * 365 * 100,
                "fnStateLoadParams" : function(oSettings, oData){
                    oData.oSearch.sSearch = '';
                    oData.aaSorting = [];
                },
                "oLanguage": {
                    "sLengthMenu": messages.perPage,
                    "oPaginate": {
                        "sPrevious": messages.prevLink,
                        "sNext": messages.nextLink
                    },
                    "sInfo": messages.showingLabel
                }
            });
            oTable.fnSort( aaSorting );
        }
    };

}();