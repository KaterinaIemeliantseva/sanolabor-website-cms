$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        table_checkbox('status'),
        table_checkbox('menu'),
        table_checkbox('filter'),
        { "data": "id" },
        { "data": "naziv" },
        { "data": "_parent" },
        { "data": "parent" },
        { "data": "sort" },
        // { "data": "id_old" },
        { "data": "null", "width" : "130px", "defaultContent": seznam_button_edit + seznam_button_delete }
    ];
    seznam_default.buttons = [{text: 'Dodaj', action: function ( e, dt, node, config ) { setAddButton(dt); }} ];
    seznam_default.columnDefs = [{ "targets": [ 5 ], "visible": false }];
    
    seznam_default.ajax.data = addQueryParams(seznam_default.ajax.data, [
        {'name' : 'artikel_kategorija.parent', 'selector' : '#filter_kat option:selected'},
        {'name' : 'artikel_kategorija.menu', 'selector' : '#menu_status:checked'},
        {'name' : 'artikel_kategorija.filter', 'selector' : '#filter_status:checked'}
    ]);

    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);
    setDeleteButton(seznam, table);
    setEditButton(seznam, table);
    setChangeCheckboxButton(seznam, table);

    table.MakeCellsEditable({
        "onUpdate": seznamUpdateCallbackFunction,
        "columns": [4,7]
    });

    /*table - end*/

    $('#filter_kat, #filter_status, #kategorija_status, #menu_status').on('change', function() { table.draw();  });

});
