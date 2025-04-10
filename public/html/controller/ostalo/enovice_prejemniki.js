$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        table_checkbox('status'),
        table_checkbox('gdpr_enovice'),
        table_checkbox('gdpr_ponudba'),
        table_checkbox('gdpr_raziskave'),
        { "data": "email" },
        { "data": "updated_at" },
        { "data": "null", "width" : "130px", "defaultContent":  seznam_button_delete }
    ];
    seznam_default.buttons = ['copy', 'csv', 'excel', 'pdf', 'print'];
    //seznam_default.buttons = [{text: 'Dodaj', action: function ( e, dt, node, config ) { setAddButton(dt); }} ];

     //audit
     seznam_default.ajax.data.audit = 1; 
     seznam_default.ajax.data.audit_message = 'Seznam prejemnikov e-novic'; 

    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);
    setDeleteButton(seznam, table);
    setEditButton(seznam, table);
    setChangeCheckboxButton(seznam, table);

    table.MakeCellsEditable({
        "onUpdate": seznamUpdateCallbackFunction,
        "columns": [4]
    });
    /*table - end*/

});
