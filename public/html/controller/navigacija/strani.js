$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        seznam_button_status,
        { "data": "naziv" },
        { "data": "tip" },
        { "data": "_tip" },
        { "data": "parent" },
        { "data": "_parent" },
        { "data": "sort" },
        { "data": "null", "width" : "130px", "defaultContent": seznam_button_edit + seznam_button_delete }
    ];
    seznam_default.buttons = [{text: 'Dodaj', action: function ( e, dt, node, config ) { setAddButton(dt); }} ];
    seznam_default.columnDefs = [{ "targets": [ 2, 4 ], "visible": false }];


    seznam_default.ajax.data = addQueryParams(seznam_default.ajax.data, [
        {'name' : 'vsebina.parent', 'selector' : '#filter_kat option:selected'}, 
        {'name' : 'vsebina.tip', 'selector' : '#filter_tip option:selected'}
    ]);
    //seznam_default.ajax.data = addQueryParams(seznam_default.ajax.data, [{'name' : 'vsebina.tip', 'selector' : '#filter_kat option:selected'}] );
    

    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);
    setDeleteButton(seznam, table);
    setEditButton(seznam, table);
    setChangeStatusButton(seznam, table);

    table.MakeCellsEditable({
        "onUpdate": seznamUpdateCallbackFunction,
        "columns": [1,6]
    });
    /*table - end*/


    $('#filter_kat, #filter_tip').on('change', function() {
        table.draw();
    });

});
