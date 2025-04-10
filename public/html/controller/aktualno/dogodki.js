$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        seznam_button_status,
        { "data": "naziv" },

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
    /*table - end*/


    $(document).on('click', '#k_add', function() {
     
        var id = $(this).data('id'),
        lokacija_id = $('#k_enota').val(),
        cas_od = $('#k_zacetek').val(),
        cas_do = $('#k_konec').val(),
        datum = $('#k_datum').val();

        var datum_array = datum.split(".");
        
        $.ajax({
            url:$("meta[name=main_url]").attr("content") + "/api/koledar/add",
            data:{
                'auth':$("meta[name=auth]").attr("content"), 
                'id_dogodek' : id, 
                'id_lokacija' : lokacija_id, 
                'datum_od' : datum_array[2]+'-'+datum_array[1]+'-'+datum_array[0], 
                'cas_od' : cas_od, 
                'cas_do' : cas_do
            },
            dataType: "jsonp",
            jsonp: false,
            jsonpCallback: "logResults",
            complete: function(result) {
                //console.log(result);
                if(result.status == 200) {
                    alertify.success('Uspešno', 2);
                    getDataCalendar(id);
                } else {
                    alertify.error('Prišlo je do napake!', 2);
                }
            }
        }).always(function() {

        });

        return false;
    });

    $(document).on('click', '.izbrisi_koledar_zapis', function(){
        var id = $(this).data('id');

        if(confirm('Ali ste prepričani, da želite izbrisati?')){
            $.ajax({
                url:$("meta[name=main_url]").attr("content") + "/api/koledar/delete",
                data:{
                    'auth':$("meta[name=auth]").attr("content"), 
                    'id' : id
                },
                dataType: "jsonp",
                jsonp: false,
                jsonpCallback: "logResults",
                complete: function(result) {
                    //console.log(result);
                    if(result.status == 200) {
                        alertify.success('Uspešno', 2);
                        $(this).closest('tr').fadeOut();
                        getDataCalendar(location.hash.substring(1));
                    } else {
                        alertify.error('Prišlo je do napake!', 2);
                    }
                }
            }).always(function() {
    
            }); 
        }

        return false;
    });

    function getDataCalendar(id) {
        $.ajax({
            type : 'POST',
            url : '/webapp/base/call',
            cache: false,
            data: {
                c: 'Dogodki',
                m: 'getKoledarDogodkov',
                dogodek_id: id
            },
            success : function(data) {
                if(data.success) {
                    showDataCalendar(data.data);
                }
                
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {

            }
        });
    }

    $( window ).on( 'hashchange ready', function(e) {
        var id = location.hash.substring(1);
        if(isNumeric(id)) {
            getDataCalendar(id);
        }
    });

    //

    function showDataCalendar(data) {

        $('#seznam_koledar tbody').html('');
        if(data.length > 0) {
            for (index = 0; index < data.length; ++index) {
                //console.log(data[index].naziv);
                $('#seznam_koledar > tbody:last-child').append('<tr><td>'+data[index].naziv+'</td><td>'+dateToSlo(data[index].datum_od) +'</td>'+
                '<td>'+data[index].cas_od + ' - ' + data[index].cas_do +'</td>'+
                '<td><a href="#" c="Dogodki" m="deleteKoledarDogodkov" class="izbrisi_koledar_zapis" data-id="'+data[index].id + '" title="Izbriši"><i class="icon-trash icon-1x"></i></a></td></tr>');
            }
        }
    }


});
