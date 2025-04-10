$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
         table_checkbox('status'),
         table_checkbox('brezplacna_dostava'),
        { "data": "bon_sifra" },
        { "data": "popust_odstotek" },
        // { "data": "popust_znesek" },
        { "data": "datum_veljavno_od" },
        { "data": "datum_veljavno_do" },
        { "data": "null", "width" : "130px", "defaultContent": seznam_button_edit + seznam_button_delete }
    ];
    seznam_default.buttons = [{text: 'Dodaj', action: function ( e, dt, node, config ) { setAddButton(dt); }} ];
    seznam_default.searching = false;
    
    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);
    setDeleteButton(seznam, table);
    setEditButton(seznam, table);
    setChangeCheckboxButton(seznam, table);

    table.MakeCellsEditable({
        "onUpdate": seznamUpdateCallbackFunction,
        "columns": []
    });
    /*table - end*/

});
