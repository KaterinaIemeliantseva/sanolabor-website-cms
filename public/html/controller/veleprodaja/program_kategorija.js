
$(function() {
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        seznam_button_status,
        { "data": "naziv" },
        { "data": "_parent" },
        { "data": "parent" },
        { "data": "sort" },
        { "data": "null", "width" : "130px", "defaultContent": seznam_button_edit + seznam_button_delete }
    ];
    seznam_default.buttons = [{text: 'Dodaj', action: function ( e, dt, node, config ) { setAddButton(dt); }} ];
    seznam_default.columnDefs = [{ "targets": [ 2 ], "visible": false }];
    seznam_default.ajax.data = addQueryParams(seznam_default.ajax.data, [
        {'name' : 'program_kategorija.parent', 'selector' : '#filter_kat option:selected'}
    ]);

    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);
    setDeleteButton(seznam, table);
    setEditButton(seznam, table);
    setChangeStatusButton(seznam, table);

    table.MakeCellsEditable({
        "onUpdate": seznamUpdateCallbackFunction,
        "columns": [1,4]
    });
    /*table - end*/

    $('#filter_kat').on('change', function() { table.draw();  });
});


        