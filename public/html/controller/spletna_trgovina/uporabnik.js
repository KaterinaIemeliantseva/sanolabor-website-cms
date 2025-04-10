$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        table_checkbox('status'),
        { "data": "email" },
        { "data": "ime" },
        { "data": "priimek" },
        { "data": "naslov" },
        { "data": "mesto" },
        { "data": "postna_st" },
        { "data": "tel" },
        { "data": "username" },
        { "data": "null", "width" : "130px", "defaultContent": seznam_button_edit + seznam_button_delete }
    ];
    seznam_default.buttons = [{text: 'Dodaj', action: function ( e, dt, node, config ) { setAddButton(dt); }} ];

    //audit
    seznam_default.ajax.data.audit = 1; 
    seznam_default.ajax.data.audit_message = 'Seznam uporabnikov'; 

    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);
    setDeleteButton(seznam, table);
    setEditButton(seznam, table);
    setChangeCheckboxButton(seznam, table);

    table.MakeCellsEditable({
        "onUpdate": seznamUpdateCallbackFunction,
        "columns": [2,3,4,5,6,7]
    });
    /*table - end*/


    $(document).on('click', '#ponovnoPosljiObvestilo', function() {
        if(confirm("Ali ste prepričani, da želite ponovno poslati potrditveni mail?")) {
            var id = $(this).data('id');

            $.ajax({
                url:$("meta[name=main_url]").attr("content") + "/api/uporabnik/resend-confirmation",
                 data:{'auth':$("meta[name=auth]").attr("content"), 'id' : id},
                 dataType: "jsonp",
                 jsonpCallback: "logResults",
                 success: function(result) {
                     // console.log(result);
                     if(result.success) {
                         alertify.success('Uspešno', 2);
                     } else {
                         alertify.error('Prišlo je do napake!', 2);
                     }
                 }
           });
        }

        return false;
    });
});
