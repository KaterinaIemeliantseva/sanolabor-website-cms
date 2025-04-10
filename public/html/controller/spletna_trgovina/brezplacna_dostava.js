$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
         table_checkbox('status'),
        { "data": "datum_od" },
        { "data": "datum_do" },
        { "data": "null", "width" : "130px", "defaultContent": seznam_button_edit + seznam_button_delete }
    ];
    seznam_default.buttons = [{text: 'Dodaj', action: function ( e, dt, node, config ) { setAddButton(dt); }} ];
    seznam_default.searching = false;
    
    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);
    setDeleteButton(seznam, table);
    setEditButton(seznam, table);
    setChangeCheckboxButton(seznam, table);

    // table.MakeCellsEditable({
    //     "onUpdate": seznamUpdateCallbackFunction,
    //     "columns": [3,4]
    // });
    /*table - end*/

});
