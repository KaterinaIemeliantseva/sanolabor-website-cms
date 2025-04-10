$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        seznam_button_status,
        { "data": "naziv" },
        { "data": "updated_at"},
        { "data": "dokument", 
        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
            $(nTd).html("<a href='/files/commerceConnector/"+oData.naziv+"'>Prenesi</a>");
            // $(nTd).html("<a href='/files/commerceConnector/' >Prenesi</a>");
        } },
        { "data": "null", "width" : "130px", "defaultContent": seznam_button_delete }
    ];
    // seznam_default.buttons = [{text: 'Dodaj', action: function ( e, dt, node, config ) { setAddButton(dt); }} ];

    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);
    setDeleteButton(seznam, table);
    setEditButton(seznam, table);
    setChangeStatusButton(seznam, table);

    table.MakeCellsEditable({
        "onUpdate": seznamUpdateCallbackFunction,
        "columns": []
    });
    /*table - end*/

});     