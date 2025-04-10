$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        table_checkbox('status'),
        { "data": "naziv" },
        { "data": "null", "width" : "130px", "defaultContent": seznam_button_edit + seznam_button_delete }
    ];
    seznam_default.buttons = [{text: 'Dodaj', action: function ( e, dt, node, config ) { setAddButton(dt); }} ];

    //audit
    seznam_default.ajax.data.audit = 1; 
    seznam_default.ajax.data.audit_message = 'Seznam avtorjev'; 

    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);
    setDeleteButton(seznam, table);
    setEditButton(seznam, table);
    setChangeCheckboxButton(seznam, table);

    table.MakeCellsEditable({
        "onUpdate": seznamUpdateCallbackFunction,
        "columns": [1]
    });
    /*table - end*/

});
