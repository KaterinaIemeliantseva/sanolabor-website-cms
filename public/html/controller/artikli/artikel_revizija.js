$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        { "data": "naziv" },
        { "data": "dobavitelj_naziv" },
        { "data": "dobavitelj_username" },
        { "data": "cas_spremembe" },
        { "data": "null", "width" : "130px", "defaultContent": seznam_button_edit  }
    ];
    seznam_default.buttons = [];

    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);
    setDeleteButton(seznam, table);
    setEditButton(seznam, table);
    setChangeStatusButton(seznam, table);

});
