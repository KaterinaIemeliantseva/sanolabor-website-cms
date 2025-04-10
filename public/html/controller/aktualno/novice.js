$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        seznam_button_status,
        { "data": "datum_objave", "width" : "90px" },
        { "data": "naziv" },
        { "data": "_kategorija_mm" },
        { "data": "null", "width" : "130px", "defaultContent": seznam_button_edit + seznam_button_delete }
    ];
    seznam_default.buttons = [{text: 'Dodaj', action: function ( e, dt, node, config ) { setAddButton(dt); }} ];
    //seznam_default.columnDefs = [{ "targets": [ 4 ], "visible": false }];

    seznam_default.ajax.data = addQueryParams(seznam_default.ajax.data, [{'name' : 'custom_kategorija', 'selector' : '#filter_kat option:selected'}] );

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

    $('#filter_kat').on('change', function() { table.draw(); });


    // var filters = [];
    // filters.push({'naziv' : 'filter_kat', 'index' : 3, 'check_text' : true});
    // createFilter(table, filters);

});
