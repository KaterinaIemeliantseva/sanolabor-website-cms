$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        { "data": "source" },
        { "data": "destination" },
        { "data": "notificationType" },
        { "data": "date" }
    ];
 
    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);

    /*table - end*/

});
