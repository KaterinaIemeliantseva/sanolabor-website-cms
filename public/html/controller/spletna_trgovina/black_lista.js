
$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        seznam_button_status,
        { "data": "ime" },
        { "data": "priimek" },
        { "data": "naslov_blacklista" },
        { "data": "email" },
        { "data": "drzava" },
        { "data": "telefon" },
        { "data": "postna_st" },
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
        "columns": []
    });
    /*table - end*/

    $(document).on('click', '.save_button', function() {
        const inputIme = document.querySelector('input[name="ime"]');
        const inputPriimek = document.querySelector('input[name="priimek"]');
        const inputNaslov = document.querySelector('input[name="naslov_blacklista"]');
        const inputPostna_st = document.querySelector('input[name="postna_st"]');

        if((inputIme.value.length > 0 && inputPriimek.value.length <= 0) || (inputIme.value.length <= 0 && inputPriimek.value.length > 0)){
            alertify.error('Izpolnite Ime, Priimek, Naslov in Poštno številko!', 5);
            return false;
        }
        if ((inputIme.value.length > 0 && inputPriimek.value.length > 0)) {
            if((inputNaslov.value.length <= 0 && inputPostna_st.value.length > 0) || (inputNaslov.value.length > 0 && inputPostna_st.value.length <= 0) || (inputNaslov.value.length <= 0 && inputPostna_st.value.length <= 0)){
                alertify.error('Izpolnite Ime, Priimek, Naslov in Poštno številko!', 5);
                return false;
            }
        }
    });
});


        