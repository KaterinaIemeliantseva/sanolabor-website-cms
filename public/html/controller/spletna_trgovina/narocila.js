$(function(){
    var self = this;

    var seznam_button_cart = " <a class=\"send_cart\" href=\"#\" title=\"Košarica\"><i class=\"icon-shopping-cart icon-1_5x\"></i></a>";
    var seznam_button_obvestilo = "<a class=\"send_obvestilo hide\" href=\"#\" title=\"Pošlji obvestilo\"><i class=\"icon-envelope-alt icon-1_5x\"></i></a>";
    var seznam_button_pravna_oseba = "<img title=\"Pravna oseba\" src=\"/public/resources/images/user_suit.png\" class=\"pravnaOseba hide\" />";
    var seznam_button_op = "<img title=\"Osebni prevzem\" src=\"/public/resources/images/user_green.png\" class=\"osebniPrevzem hide\" />";


    /*table - start*/
    seznam_default.columns = [
        { "data": "id" },
        { "data": "st_narocila" },
        { "data": "created_at" },
        { "data": "e_posta" },
        { "data": "ime_priimek" },
        { "data": "nacin_placila_opis" },
        { "data": "znesek_placila" },
        { "data": "znesek_dostave" },
        { "data": "partner_opis" },
        { "data": "opombe" },
        { "data": "nacin_placila" },
        { "data": "_artikli" },
        { "data": "narocilo_zakljuceno" },
        { "data": "enota_naziv" },
        { "data": "_cas_poslano_v_enoto" },
        { "data": "_kupon_koda" },
        { "data": "_kupon_brezplacna_dostava" },
        { "data": "payment_reference_id" },
        { "data": "kartica_potrdilo_cas" },
        { "data": "kartica_type" },
        { "data": "updated_at" },
        { "data": "interna_st_racuna" },
        { "data": "furs_stevilka" },

        { "data": "null", "width" : "130px", "defaultContent": "<img src=\"/public/resources/images/ajax-loader.gif\" class=\"ajaxLoaderCart hide\" /> " + seznam_button_pravna_oseba + seznam_button_op + seznam_button_obvestilo + seznam_button_cart + seznam_button_edit + seznam_button_delete }
    ];

    seznam_default.buttons = ['copy', 'csv', 'excel', 'pdf', 'print'];

    // if($.cookie('test')) {
    //     var toStringFormat = {
    //         exportOptions: {
    //             format: {
    //                 body: function ( data, row, column, node ) {
    //                     if(column === 20) {
    //                         // return String(data) + "";
    //                         return "" + '100025585210112211' + "";
    //                     }

    //                     //gumbi
    //                     if(column === 21) {
    //                         return '';
    //                     }

    //                     return data;

    //                     // return column === 20 ?
    //                     //     data.replace( /[$,]/g, '' ) :
    //                     //     data;
    //                 }
    //             }
    //         }
    //     };

    //     //https://datatables.net/extensions/buttons/examples/html5/excelTextBold.html
    //     seznam_default.buttons = [
    //         'copy', 
    //         'csv', 
    //         $.extend( true, {}, toStringFormat, {
    //             extend: 'excelHtml5',
    //             // customize: function( xlsx ) {
    //             //     var sheet = xlsx.xl.worksheets['sheet1.xml'];
     
    //             //     $('row c[r^="U"]', sheet).attr( 's', '50' );
    //             // }
    //         } ),
    //         //'excel',
    //         'pdf', 
    //         'print'];
    // }

    // seznam_default.columnDefs = [{ "targets": [ 9,10,11,13,14,15,16,17,18,19,20,21 ], "visible": false }];
    seznam_default.columnDefs = [{ "targets": [ 10,11,12,14,15,16,17,18,19,20,21,22 ], "visible": false }];

  
    //izvoz kartic za ASW
    seznam_default.buttons.push({
        extend: 'csvHtml5',
        text: 'ASW kartice', 
        action: function ( e, dt, node, config ) { 
            if($('#to').val() == '' || $('#from').val() == '') {
                alertify.error('Izberite datum od do!', 2);
            } else {
                exportToFile({'c' : 'Narocila', 'm' : 'exportAsw', 'date_from' : $('#from').val(),  'date_to' : $('#to').val(), 'filename' : 'izvoz.csv'});
            }
        }});


    //izvoz po postavkah
   // if($.cookie('test')) {
    seznam_default.buttons.push({
        extend: 'csvHtml5',
        text: 'Postavke', 
        action: function ( e, dt, node, config ) { 
            if($('#to').val() == '' || $('#from').val() == '') {
                alertify.error('Izberite datum od do!', 2);
            } else {
                exportToFile({'c' : 'Narocila', 'm' : 'exportNarociloPostavke', 'date_from' : $('#from').val(),  'date_to' : $('#to').val(), 'filename' : 'izvoz.xlsx'});
            }
    }});
   // }
    


    seznam_default.createdRow = function( row, data, dataIndex) {

        // if(data.pregledano != 1) {
        //     $(row).addClass('nepregledano');
        // }

        //console.log(data);
        if(!data.stevilka_narocila) {
            $(row).addClass('redRow');
        } else {
            $(row).find('.send_cart').addClass('hide');
        }

        //gumb za obvestilo
        // if(data.nacin_dostave == 999991 && data.obvestilo_op_poslano != 1) {
        //     $(row).find('.send_obvestilo').removeClass('hide');
        // }

        if(data.racun_podjetje == 1) {
            $(row).find('.pravnaOseba').removeClass('hide');
        }

        if(data.nacin_dostave == '148969') {
            $(row).find('.osebniPrevzem').removeClass('hide');
        }

    }

    //audit
    seznam_default.ajax.data.audit = 0; 
    seznam_default.ajax.data.audit_message = 'Seznam naročil'; 

  
        seznam_default.ajax.data = addQueryParams(seznam_default.ajax.data, [
            {'name' : 'narocilo.nacin_placila', 'selector' : '#filter_np option:selected'}
            ,{'name' : 'narocilo.nacin_dostave', 'selector' : '#filter_nd option:selected'}
            ,{'name' : 'narocilo.created_at', 'selector' : '#from', 'operator' : '>='}
            ,{'name' : 'narocilo.created_at', 'selector' : '#to', 'operator' : '<='}
            ,{'name' : 'narocilo.racun_podjetje', 'selector' : '#racun_podjetje:checked', 'operator' : '='}
            ,{'name' : 'narocilo.dostava_drzava_id', 'selector' : '#tujina:checked', 'operator' : '!='}
            ,{'name' : 'narocilo.status', 'selector' : '#nezakljucena_narocila:checked', 'operator' : '!='}
            ,{"name" : "(narocilo.stevilka_narocila is null or LENGTH(narocilo.stevilka_narocila) < 1) and narocilo.nacin_placila", 'selector' : '#neplacani_predracuni:checked', 'operator' : '='}
            ,{'name' : 'narocilo.vsebuje_zdravilo', 'selector' : '#vsebuje_zdravilo:checked', 'operator' : '='}
        ]);


    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);
    setEditButton(seznam, table);
    setDeleteButton(seznam, table);

    /*table - end*/

    //date
    var from = $( "#from" ).datepicker({
            dateFormat: dateFormat,
            changeMonth: true,
        }).on( "change", function() {
            to.datepicker("option", "minDate", getDate(this));
            table.draw();
        });

    var to = $( "#to" ).datepicker({
            dateFormat: dateFormat,
            changeMonth: true,
        }).on( "change", function() {
            from.datepicker("option", "maxDate", getDate(this));
            table.draw();
        });

    

    $('#filter_nd, #filter_np,  #nezakljucena_narocila, #neplacani_predracuni, #racun_podjetje, #tujina, #vsebuje_zdravilo').on('change', function() {
        table.draw();
    });


    //posreduj kosarico
    seznam.find('tbody').on( 'click', '.send_cart', function () {
        if(confirm("Ali ste prepričani, da želite poslati naročilov v enoto?")) {
            var self = this;
            var data = table.row( $(this).parents('tr') ).data();
            $('tr#' + data.id + ' img.ajaxLoaderCart').removeClass('hide');
            //console.log(data);


            $.ajax({
                url:$("meta[name=main_url]").attr("content") + "/api/confirmOrder",
                // url: "http://127.0.0.1:8000/api/confirmOrder",
                data:{'auth':$("meta[name=auth]").attr("content"), 'id' : data.id},
                dataType: "jsonp",
                jsonpCallback: "logResults",
                success: function(result) {
                    // console.log(result);
                    if(result.success) {
                        alertify.success('Uspešno', 2);
                        $(self).parents('tr').removeClass('redRow');
                        $('tr#' + data.id + ' .send_cart').addClass('hide');
                    } else {
                        alertify.error('Prišlo je do napake!', 2);
                    }
                }
            }).always(function() {
                $('tr#' + data.id + ' img.ajaxLoaderCart').addClass('hide');
            });
        }

        return false;
    });

    //pošlji obvestilo o osebnem prevzemu
    seznam.find('tbody').on( 'click', '.send_obvestilo', function () {
        var self = this;
        var data = table.row( $(this).parents('tr') ).data();
        $('tr#' + data.id + ' img.ajaxLoaderCart').removeClass('hide');
        //console.log(data);

        $.ajax({
            url:$("meta[name=main_url]").attr("content") + "/api/confirmOrder",
              //url: "http://127.0.0.1:8000/api/sendPersonalTakeoverNotification",
              data:{'auth':$("meta[name=auth]").attr("content"), 'id' : data.id},
              dataType: "jsonp",
              jsonpCallback: "logResults",
              success: function(result) {
                  // console.log(result);
                  if(result.success) {
                      alertify.success('Uspešno', 2);
                      $(self).parents('tr').removeClass('redRow');
                      $('tr#' + data.id + ' .send_obvestilo').addClass('hide');
                  } else {
                      alertify.error('Prišlo je do napake!', 2);
                  }
            }
        }).always(function() {
            $('tr#' + data.id + ' img.ajaxLoaderCart').addClass('hide');
        });

        return false;
    });

    //ponovi obvestilo 
    $(document).on('click', '#ponovnoPosljiObvestilo', function() {
        if(confirm("Ali ste prepričani, da želite ponovno poslati obvestilo o naročilu?")) {
            var id = $(this).data('id');

            $.ajax({
                url:$("meta[name=main_url]").attr("content") + "/api/sendConfirmationMail",
                 data:{'auth':$("meta[name=auth]").attr("content"), 'id' : id},
                 dataType: "jsonp",
                 jsonpCallback: "logResults",
                 success: function(result) {
                    //  console.log(result);
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

    function resendOrder(id, ws = 0) {
        
        $('.ajaxLoader1').removeClass('hide');
        $.ajax({
            url:$("meta[name=main_url]").attr("content") + "/api/resend-order",
             data:{'auth':$("meta[name=auth]").attr("content"), 'id' : id, 'ws' : ws},
             dataType: "jsonp",
             jsonpCallback: "logResults",
             success: function(result) {
                 // console.log(result);
                 if(result.success) {
                     alertify.success('Uspešno. Nova številka naročila je: ' + result.data, 2);

                     window.location.hash = "#"+result.data;
                     location.reload();
                 } else {
                     alertify.error('Prišlo je do napake!', 2);
                 }
             }
       });
    }

    //ponovi poslji narocilo 
    $(document).on('click', '#ponovnoPosljiNarocilo', function() {
        if(confirm("Ali ste prepričani, da želite ponovno poslati naročilo v enoto?")) {
            var id = $(this).data('id');
            resendOrder(id, 1);
        }

        return false;
    });

    //dupliciraj naročilo
    $(document).on('click', '#podvojiNarocilo', function() {
        if(confirm("Ali ste prepričani, da želite narediti kopijo naročila?")) {
            var id = $(this).data('id');
            resendOrder(id, 0);
        }

        return false;
    });

    $(document).on('click', '#pokaziPredracun', function() {
    
        var id = $(this).data('id');
        location.href= $("meta[name=main_url]").attr("content") + "/api/invoice?id=" + id + "&auth=" + $("meta[name=auth]").attr("content");

        return false;
    });

});
