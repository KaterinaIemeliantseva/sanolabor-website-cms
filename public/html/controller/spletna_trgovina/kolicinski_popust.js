
$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        table_checkbox('status'),
        { "data": "sifra" },
        { "data": "naziv" },
        { "data": "kolicina" },
        { "data": "popust" },
        { "data": "datum_do" },
        { "data": "null", "width" : "130px", "defaultContent": seznam_button_edit + seznam_button_delete }
    ];
    seznam_default.buttons = [{text: 'Dodaj', action: function ( e, dt, node, config ) { setAddButton(dt); }} ];

    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);
    setDeleteButton(seznam, table);
    setEditButton(seznam, table);
    setChangeStatusButton(seznam, table);

    table.MakeCellsEditable({
        "onUpdate": seznamUpdateCallbackFunction,
        "columns": [2]
    });
    /*table - end*/
});


        