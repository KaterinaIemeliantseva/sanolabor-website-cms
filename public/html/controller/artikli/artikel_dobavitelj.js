
$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        { "data": "naziv" },
        { "data": "dobavitelj_username" },
        { "data": "dobavitelj_naziv" },
        { "data": "cas_spremembe" },
        { "data": "null", "width" : "130px", "defaultContent": seznam_button_edit  }
    ];
    //seznam_default.buttons = [{text: 'Dodaj', action: function ( e, dt, node, config ) { setAddButton(dt); }} ];

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

    // var el_count = $('.save_button').length;
    // console.log(el_count);

    $(document).on('click', '#save_button_all_changes', function() {
        if(confirm("Ali ste prepričani, da želite potrditi vse spremembe?")) {
            $('.save_button').each(function(i, obj) {
                obj.click();
            });

            /*setTimeout(function(){ 
                var el_count = $('.save_button').length;
                //console.log(el_count);
                
                location.reload();
                
            }, 1500);*/
        }
    });

    
    //console.log(el_count);
    

    
    
});


        