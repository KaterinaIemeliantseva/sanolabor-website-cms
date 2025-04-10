$(function() {
    var self = this;

    var seznam_button_duplicate = " <a class=\"duplicate\" href=\"#\" title=\"Kopiraj\"><i class=\"icon-copy icon-1_5x\"></i></a>";

    /*table - start*/
    seznam_default.columns = [
        seznam_button_status,
        table_checkbox('arhiv'),
        { "data": "naziv" },
        { "data": "_sifre" },
        { "data": "updated_at" },
        { "data": "id" },
        { "data": "_kategorija" },
        { "data": "null", "width": "130px", "defaultContent": "<img src=\"/public/resources/images/ajax-loader.gif\" class=\"ajaxLoaderCart hide\" /> " + seznam_button_edit + seznam_button_delete }
    ];
    seznam_default.buttons = ['excel', { text: 'Dodaj', action: function(e, dt, node, config) { setAddButton(dt); } }];
    seznam_default.columnDefs = [{ "targets": [ 6 ], "visible": false }];
    seznam_default.ajax.data = addQueryParams(seznam_default.ajax.data, [
        { 'name': 'artikel.arhiv', 'selector': '#status_arhiv:checked' },
        { 'name': 'artikel_cena.ni_popusta', 'selector': '#ni_popusta:checked' }
    ]);


    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);
    setDeleteButton(seznam, table);
    setEditButton(seznam, table);
    setChangeStatusButton(seznam, table);
    setChangeCheckboxButton(seznam, table);

    // table.MakeCellsEditable({
    //     "onUpdate": seznamUpdateCallbackFunction,
    //     "columns": [2]
    // });
    /*table - end*/

    $('#status_arhiv').on('change', function() {
        table.draw();
    });
       $('#ni_popusta').on('change', function() {
        table.draw();
    });

    $(document).on('click', '.change_checkbox', function(e) {
        $check = document.querySelector('.change_status').checked;
        if ($check) {
            //$('.change_checkbox').prop('checked', false);
        }
        //console.log(e.currentTarget.checked);
        //console.log(e.target.parentNode);
        //alert($(this).closest('tr').prop('id'));
    });


    $(document).on('click', '#update_cene_zaloga', function() {
        $('.ajaxLoaderUpdateZaloga').removeClass('hide');
        var sifra = $(this).data('sifra');
        $.ajax({
            url: $("meta[name=main_url]").attr("content") + "/api/articleUpdate",
            data: { 'auth': $("meta[name=auth]").attr("content"), 'sifra': sifra },
            dataType: "jsonp",
            jsonp: false,
            jsonpCallback: "logResults",
            complete: function(result) {
                // console.log(result);
                if (result.status == 200) {
                    alertify.success('Uspešno', 2);
                } else {
                    alertify.error('Prišlo je do napake!', 2);
                }
            }
        }).always(function() {
            $('.ajaxLoaderUpdateZaloga').addClass('hide');
        });

        return false;
    });


    seznam.find('tbody').on('click', '.duplicate', function() {
        if (confirm("Ali ste prepričani, da narediti kopijo artikla?")) {
            var self = this;
            var data = table.row($(this).parents('tr')).data();
            $('tr#' + data.id + ' img.ajaxLoaderCart').removeClass('hide');
            //console.log(data);


            $.ajax({
                url: $("meta[name=main_url]").attr("content") + "/api/articleDuplicate",
                //url: "http://127.0.0.1:8000/api/sendPersonalTakeoverNotification",
                data: { 'auth': $("meta[name=auth]").attr("content"), 'id': data.id },
                dataType: "jsonp",
                jsonpCallback: "logResults",
                complete: function(result) {
                    //console.log(result);
                    if (result.status == 200) {
                        alertify.success('Uspešno', 2);
                        $('#seznam').DataTable().ajax.reload(null, false);
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

    //param_sifra
    /*
    $(document).on('keyup', '.param_sifra', function() {
        var sifra = $(this).val();

        preveriPodvojeneSifre($(this), sifra);
    });

    function preveriPodvojeneSifre($this, sifra) {
        //var podvojene_sifra_out = $('#podvojene_sifra_out');
        //podvojene_sifra_out.html('');
        
        if(sifra.length == 6) {
            console.log(sifra);
            //podvojene_sifra_out.html(sifra);
        }
    }*/


    var artikel_id = 0;
    if (Helper.isNumeric(Helper.hash().nivo0)) {
        artikel_id = Helper.hash().nivo0;
    }

    $(document).on('keyup', '.param_sifra', function() {
        preveriSifroArtikla($(this), artikel_id);
    });



    function preveriSifroArtikla(element, artikel_id) {
        var $this = element;
        var sifra = $this.val();
        //var count_parents = $(".element").parents().length;

        $this.removeClass('bgred');
        $this.parent().siblings(".sifra-opozorilo").addClass('hide');
        //$this.parent().closest(".sifra-opozorilo").addClass('hide1');
        if (sifra.length > 5) {
            //console.log(sifra);
            $.ajax({
                type: 'POST',
                url: '/webapp/base/call',
                cache: false,
                data: {
                    c: 'Artikel',
                    m: 'preveriSifra',
                    sifra: sifra,
                    artikel_id: artikel_id
                },
                success: function(data) {
                    //console.log(data);
                    if (data.success) {
                        //156589
                        //showDataCalendar(data.data);
                        //console.log(data.data);

                        //$('#parameters_list').html(data.data);

                        var obj = data.data;
                        // 	//console.log(obj);
                        if (obj.obstaja === 'true') {
                            $this.addClass('bgred');
                            $this.parent().siblings(".sifra-opozorilo").removeClass('hide');
                            $this.parent().siblings(".sifra-opozorilo").find('.povezani-art').text(obj.naziv);
                        }
                    }

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                }
            });

            // $.post("/admin/library/ajax.php",
            // {
            // 	c: 'Artikel',
            // 	m: 'preveriSifra',
            // 	sifra: sifra,
            // 	artikel_id : artikel_id
            // },
            // function(data)
            // {
            // 	var obj = jQuery.parseJSON(data);
            // 	//console.log(obj);
            // 	if(obj.obstaja === 'true')
            // 	{
            // 		$this.addClass('bgred');
            // 		$this.next(".sifra-opozorilo").removeClass('hide');
            // 		$this.next(".sifra-opozorilo").find('.povezani-art').text(obj.naziv);
            // 	}
            // });
        }
    }

    //šifre
    $(document).on('click', '#k_add', function() {

        var id = $(this).data('id'),
            param_data = [];


        var i = 1;
        for (i; i <= 10; i++) {
            if ($('#k_parameter_' + i).length > 0) {
                //param_data[i] = [];
                //param_data.push($('#k_parameter_'+i).val());
                if ($('#k_parameter_' + i).val()) {
                    param_data = param_data.concat($('#k_parameter_' + i).val());
                }
            }
        }

        //console.log(param_data); return;


        // if(k_sifra.length != 6 || isNaN(k_sifra))
        // {
        //     alertify.error('Šifra ni v pravilni obliki!', 2);
        //     return;
        // }


        $.ajax({
            url: $("meta[name=main_url]").attr("content") + "/api/artikel-sifra-parameter/add",
            data: {
                'auth': $("meta[name=auth]").attr("content"),
                'artikel_id': id,
                'param_data': param_data
            },
            complete: function(result) {
                //console.log(result);
                var result_obj = JSON.parse(result.responseText);
                //console.log(result_obj);
                if (result.status == 200 && !result_obj.error) {
                    loadParameterListView(id);
                    alertify.success('Uspešno', 2);
                    //getDataCalendar(id);
                } else {
                    alertify.error((result_obj.error_msg) ? result_obj.error_msg : 'Prišlo je do napake!', 2);
                }
            }
        }).always(function() {

        });

        return false;
    });


    function loadParameterListView(id) {
        $.ajax({
            type: 'POST',
            url: '/webapp/base/call',
            cache: false,
            data: {
                c: 'Artikel',
                m: 'parameterListView',
                artikel_id: id
            },
            success: function(data) {
                if (data.success) {
                    //showDataCalendar(data.data);
                    //console.log(data.data);
                    
                    $('#parameters_list').html(data.data);

                    $(".param_sifra").each(function(index, domEle) {
                        //console.log($(this).val());
                        preveriSifroArtikla($(this), id);
                    });
                    //$('#parameters_list').html('data.data');
                    //$('#parameters_list').html('<tr> <td> <p> <input class="text-input form-control " type="text" name="param_sifra0" value="" /> </p> </td> <td> <p> <input class="text-input form-control " type="text" name="param_ean0" value="" /> </p> </td> <td>2609</td><td>2330</td><td>1078</td> <td>t</td> </tr> <tr> <td> <p> <input class="text-input form-control " type="text" name="param_sifra1" value="" /> </p> </td> <td> <p> <input class="text-input form-control " type="text" name="param_ean1" value="" /> </p> </td> <td>2609</td><td>2329</td><td>1078</td> <td>t</td> </tr> <tr> <td> <p> <input class="text-input form-control " type="text" name="param_sifra2" value="" /> </p> </td> <td> <p> <input class="text-input form-control " type="text" name="param_ean2" value="" /> </p> </td> <td>2609</td><td>2328</td><td>1078</td> <td>t</td> </tr> <tr> <td> <p> <input class="text-input form-control " type="text" name="param_sifra3" value="" /> </p> </td> <td> <p> <input class="text-input form-control " type="text" name="param_ean3" value="" /> </p> </td> <td>2608</td><td>2330</td><td>1078</td> <td>t</td> </tr> <tr> <td> <p> <input class="text-input form-control " type="text" name="param_sifra4" value="" /> </p> </td> <td> <p> <input class="text-input form-control " type="text" name="param_ean4" value="" /> </p> </td> <td>2608</td><td>2329</td><td>1078</td> <td>t</td> </tr> <tr> <td> <p> <input class="text-input form-control " type="text" name="param_sifra5" value="" /> </p> </td> <td> <p> <input class="text-input form-control " type="text" name="param_ean5" value="" /> </p> </td> <td>2608</td><td>2328</td><td>1078</td> <td>t</td> </tr>');
                }
                $('#loader').fadeOut();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $('#loader').fadeOut();
            }
        });
    }

    $(window).on('hashchange ready', function(e) {
        var id = location.hash.substring(1);
        if (isNumeric(id)) {
            setTimeout(() => { loadParameterListView(id); }, 800);
            //loadParameterListView(id);
            //console.log('test: ' + id);

        }
    });


    // function loadParameterListView() {

    // }



    // if($.cookie('test')) {

    //     $( "#uf_box2 tbody" ).sortable({
    //         update: function() {

    //             var item_data = [];
    //             $( '#uf_box1 tbody tr' ).each(function( index ) {
    //                 item_data.push({'order' : index, 'id' : $( this ).attr('del')});
    //                 //console.log( index + ": " + $( this ).attr('del') );
    //             });

    //             // console.log(item_data);

    //             // $.ajax({
    //             // 		type : 'POST',
    //             // 		url : '/admin/library/ajax.php',
    //             // 		cache: false,
    //             // 		data: {
    //             // 			data: JSON.stringify(item_data)
    //             // 		},
    //             // 		success : function(data) {
    //             // 			//console.log(data);
    //             // 			alertify.success('Uspešno', 2);
    //             // 		},
    //             // 		error : function(XMLHttpRequest, textStatus, errorThrown) {
    //             // 			alertify.error('Prišlo je do napake!', 2);
    //             // 		}
    //             // 	});


    //         }
    //     });
    //     $( "#uf_box2 tbody" ).disableSelection();
    // }


    $(document).on("click", "#galery_tab", function(e) {

        $("#uf_box1 tbody").sortable({
            update: function() {

                var item_data = [];
                $('#uf_box1 tbody tr').each(function(index) {
                    item_data.push({ 'order': index, 'id': $(this).data('uid') });
                });

                //console.log(item_data);

                $.ajax({
                    type: 'POST',
                    url: '/webapp/base/call',
                    cache: false,
                    data: {
                        c: 'Artikel',
                        m: 'orderGaleryItems',
                        data: item_data
                    },
                    success: function(data) {

                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {

                    }
                });

                // $.ajax({
                // 		type : 'POST',
                // 		url : '/admin/library/ajax.php',
                // 		cache: false,
                // 		data: {
                // 			data: JSON.stringify(item_data)
                // 		},
                // 		success : function(data) {
                // 			//console.log(data);
                // 			alertify.success('Uspešno', 2);
                // 		},
                // 		error : function(XMLHttpRequest, textStatus, errorThrown) {
                // 			alertify.error('Prišlo je do napake!', 2);
                // 		}
                // 	});


            }
        });
        $("#uf_box1 tbody").disableSelection();
    });

});