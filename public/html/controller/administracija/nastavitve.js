$(function(){
    var self = this;

    function getSeznamNastavitve() {
        seznam_default.columns = [
            seznam_button_status,
            { "data": "ime_priimek", "width" : "230px" },
            { "data": "username" },
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
            "columns": [1]
        });
    }

    getSeznamNastavitve();

});
