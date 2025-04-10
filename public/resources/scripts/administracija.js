/*$.validator.addMethod("checkCode", function(value, element) {
    return 'false';
}, 'Please enter a valid code.');*/

//nalozi izbrani element
if(Helper.isNumeric(Helper.hash().nivo0)) {
    showEdit({'id' : Helper.hash().nivo0});
}

/*seznam*/
var datatable_language = {
    "sEmptyTable": "Nobenih podatkov ni na voljo",
    "sInfo": "Prikazujem _START_ do _END_ od _TOTAL_ zapisov",
    "sInfoEmpty": "Prikazujem 0 do 0 od 0 zapisov",
    "sInfoFiltered": "(filtrirano od _MAX_ vseh zapisov)",
    "sInfoPostFix": "",
    "sInfoThousands": ",",
    "sLengthMenu": "Prikaži _MENU_ zapisov",
    "sLoadingRecords": "Nalagam ...",
    "sProcessing": "Obdelujem ...",
    "sSearch": "Išči:",
    "sZeroRecords": "Nobeden zapis ne ustreza",
    "oPaginate": {
        "sFirst": "Prvi",
        "sLast": "Zadnji",
        "sNext": "Nasl.",
        "sPrevious": "Pred."
    },
    "oAria": {
        "sSortAscending": ": vključite za naraščujoči sort",
        "sSortDescending": ": vključite za padajoči sort"
    }
};

var seznam_button_status = {
    "data": "status", "width" : "10px", 'className': 'dt-body-center',
    'render': function (data, type, full, meta) {
        var status = ($('<div/>').text(data).text() == 1) ? ' checked ' : '';
        var status_text = (status) ? 'Da' : 'Ne';
        return '<span class="hide">'+status_text+'</span><input class="change_status" type="checkbox" name="status[]" value="1" ' + status + ' >';
    }
}

var seznam_button_edit = "<img src=\"/public/resources/images/ajax-loader.gif\" class=\"ajaxLoaderEdit hide\" /> <a class=\"edit\" href=\"#\" title=\"Uredi\"><i class=\"icon-edit icon-1_5x\"></i></a>";
var seznam_button_delete = "<a class=\"delete\" href=\"#\" title=\"Izbriši\"><i class=\"icon-trash icon-1_5x\"></i></a>";

var seznam_default = {
    "ordering": false,
    //"pageLength": 15,
    //"bLengthChange": false,
    "language": datatable_language,
    //"dom": '<"top"f>rt<"bottom"ilp><"clear">',
    dom: 'flBrtip<"clear">',
    //dom: 'Rlfrtlip',
    "ajax": {
        "type": "POST"
    },
    rowId : 'id',
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Vse"]]
}

seznam_default.ajax.data = {'token':$("meta[name=token]").attr("content"), 'c' : $('#magic_box').data('c')};
seznam_default.ajax.url = "/webapp/base/get";

//if($.cookie('test')) {
    seznam_default.processing = true;
    seznam_default.serverSide = true;
    seznam_default.ajax.url = "/webapp/base/get-list";
  
//}

var current_row_edited = {};
var table_seznam;
var dateFormat = "dd.mm.yy";

function getDate( element ) 
{
    var date;
    try {
        date = $.datepicker.parseDate( dateFormat, element.value );
    } catch( error ) {
        date = null;
    }

    return date;
} 

function getDateFromSloFormat( element ) 
{
    
    var date = null;
    if(element) {
        try {
            var parts = element.split(".");
            date = parts[2] + "-" + parts[1] + "-" + parts[0];
            //date = $.datepicker.parseDate( "yy-mm-dd", element );
        } catch( error ) {
            date = null;
        }
    }
  
    return date;
} 

function addQueryParams(data, input)
{
    return function ( d ) {
        var query_list = {};
        query_list.query_params = [];

        if(input) {
            input.forEach(function(item, index) {
                var item_value = $(item.selector).val();
                var item_name = item.name;
         
                if(item.name.search('created_at') > -1) {
                    item_value = getDateFromSloFormat(item_value) ;
                    item_name = 'date(' + item.name + ')'
                }

                query_list.query_params.push({
                    'name' : item_name, 
                    'value' : item_value,
                    'operator' : (item.operator) ? item.operator : ''
                });
            });
        }

        return $.extend(d, data, query_list );
    }
}

function table_checkbox(name) {
    return {
    "data": name, "width" : "10px", 'className': 'dt-body-center',
        'render': function (data, type, full, meta) {
            var status = ($('<div/>').text(data).text() == 1) ? ' checked ' : '';
            var status_text = (status) ? 'Da' : 'Ne';
            return '<span class="hide">'+status_text+'</span><input data-name="' + name + '" class="change_checkbox" type="checkbox" name="' + name + '[]" value="1" ' + status + ' >';
        }
    }
}

function setDeleteButton(seznam, table) {
    seznam.find('tbody').on( 'click', '.delete', function () {
        if(confirm('Ali ste prepričani, da želite izbrisati zapis?')){
            var data = table.row( $(this).parents('tr') ).data();
            $.post("/webapp/base/delete",
            {'token':$("meta[name=token]").attr("content"), 'c' : $('#magic_box').data('c'), 'data' : data},
            function(data) {
                if(data.success) {
                    alertify.success('Element je bil izbrisan.', 2);
                } else {
                    alertify.error('Prišlo je do napake! Spremembe niso bile shranjene.', 2);
                }
            }).fail(function() {
                alertify.error('Prišlo je do napake! Spremembe niso bile shranjene.', 2);
            });
            //console.log(data);
            $(this).parents('tr').fadeOut(400);
        }
        return false;
    });
}

function setEditButton(seznam, table) {
    seznam.find('tbody').on( 'click', '.edit', function () {
        var data = table.row( $(this).parents('tr') ).data();
        $('tr#' + data.id + ' img.ajaxLoaderEdit').removeClass('hide');
        data.c = $('#magic_box').data('c');
        data.token = $("meta[name=token]").attr("content");
        current_row_edited = data;
        table_seznam = table;

        if(data.c == 'Narocila') {
            $(this).parents('tr').removeClass('nepregledano');
        }

        showEdit(data);
    });
}

function setChangeStatusButton(seznam, table) {
    seznam.find('tbody').on( 'click', '.change_status', function () {
        var data = table.row( $(this).parents('tr') ).data();
        data.status = (!this.checked) ? 0 : 1;
        
        update(data);
        applyData(table, data, false);
    });
}

function setChangeCheckboxButton(seznam, table) {
    seznam.find('tbody').on( 'click', '.change_checkbox', function () {
        //console.log('tets');
        var data = table.row( $(this).parents('tr') ).data();
        var name = $(this).data('name');

         data[name] = (!this.checked) ? 0 : 1;
         update(data);
         applyData(table, data, false);
         
    });
}

function setAddButton(table) {
    current_row_edited = {};
    table_seznam = table;
    showEdit(null);
}

function showEdit(data) {
    var u = getUrlVal(url);
    if(data === null)
    {
        data = {};
    }

    if($('#filter_kat').length > 0) {
        data.parent_kat_id = $("#filter_kat option:selected").val();
        data.parent_kat_text = $("#filter_kat option:selected").text();
    }

    if($('#filter_tip').length > 0) {
        data.tip_id = $("#filter_tip option:selected").val();
        data.tip_text = $("#filter_tip option:selected").text();
    }

    data.c = $('#magic_box').data('c');
    //console.log(data);
    //data['c'] = $('#magic_box').data('c');
    //console.log($('#magic_box').data('c'));
    $.post("/public/html/controller/" + u.nivo0 + "/" + u.nivo1 + "_edit.php",
    {'data' : data},
    function(content) {
        //console.log(data);
        if(data.magic_box_edit) {
            $('#'+data.magic_box_edit).html(content);
        } else {
            $('#magic_box_edit').html(content);
        }
        
        if(data != null) {
            $('tr#' + data.id + ' img.ajaxLoaderEdit').addClass('hide');

            window.location.hash = data.id;
        }

        $('.ajaxLoader').addClass('hide');
        initialize();

        if(!data.magic_box_edit) {
            toggle_visibility();
        }

        loadDatePicker();
        initFileManager();
    });
}

function update(data) {
    $.post("/webapp/base/update",
    {'token':$("meta[name=token]").attr("content"), 'c' : $('#magic_box').data('c'), 'data' : data},
    function(data) {
        if(!("success" in data) || !data.success) {
            //alertify.success('Spremembe so bile shranjene!', 2);
            alertify.error('Prišlo je do napake! Spremembe niso bile shranjene.', 2);
        } else {
            alertify.success('Spremembe so bile shranjene!', 2);
        }
        //console.log(data);
    }).fail(function() {
        alertify.error('Prišlo je do napake! Spremembe niso bile shranjene.', 2);
    });
}

function seznamUpdateCallbackFunction (updatedCell, updatedRow, oldValue) {
    update(updatedRow.data());
}

function toggle_visibility() {


   var seznam = $('#magic_box');
   var edit = $('#magic_box_edit');

   if(seznam.hasClass("hide")) {
       seznam.removeClass("hide");
       edit.addClass("hide").empty();
   } else {
       edit.removeClass("hide");
       seznam.addClass("hide");
   }
}

var wto;
$(document).on('input', '.file_title', function() {
    clearTimeout(wto);
    var id = $(this).data('fid');
    var title = $(this).val();
    //console.log(id);
    //console.log($(this).val());
    wto = setTimeout(function() {
        $.post("/webapp/base/call",
        {'token':$("meta[name=token]").attr("content"), 'c' : 'Base', 'm' : 'changeFileTitle', 'data' : {'guid':id, 'title' : title}},
        function(data) {
            alertify.success('Spremembe so bile shranjene!', 2);
        }).fail(function() {
            alertify.error('Prišlo je do napake! Spremembe niso bile shranjene.', 2);
        });
    }, 900);

    return false;
});

$(document).on('input', '.file_sort', function() {
    clearTimeout(wto);
    var id = $(this).data('fid');
    var order = $(this).val();
    //console.log(id);
    //console.log($(this).val());
    if(order > -1) {
        wto = setTimeout(function() {
            $.post("/webapp/base/call",
            {'token':$("meta[name=token]").attr("content"), 'c' : 'Base', 'm' : 'changeFileOrder', 'data' : {'guid':id, 'order' : order}},
            function(data) {
                alertify.success('Spremembe so bile shranjene!', 2);
            }).fail(function() {
                alertify.error('Prišlo je do napake! Spremembe niso bile shranjene.', 2);
            });
        }, 900);
    }

    return false;
});


$(document).on('click', '.close_edit', function() {
    toggle_visibility();
    window.location.hash = '';
    return false;
});

function applyData(table, data) {
    var index;

    index = table.row('#' + data.id);

    if(index.length > 0){
        table.row(index[0]).data(data).invalidate();
    } else {
        table.row.add(data);
    }

    //Redraw table maintaining paging
    table.draw(false);
}


/*tabs*/
$(document).on('click', '.content-box ul.content-box-tabs li a', function() {
        $(this).parent().siblings().find("a").removeClass('current'); // Remove "current" class from all tabs
        $(this).addClass('current'); // Add class "current" to clicked tab
        var currentTab = $(this).attr('href'); // Set variable "currentTab" to the value of href of clicked tab
        $(currentTab).siblings().hide().removeClass('active'); // Hide all content divs
        $(currentTab).show().addClass('active'); // Show the content div with the id equal to the id of clicked tab
        return false;
});

function CKupdate(){
    for ( instance in CKEDITOR.instances ) {
        CKEDITOR.instances[instance].updateElement();
    }
}

function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

function initialize()
{
    //select2
    if($('.select2_multiple').length > 0 || $('.select2').length > 0) {
        
        $.fn.select2.amd.define('select2/selectAllAdapter', [
            'select2/utils',
            'select2/dropdown',
            'select2/dropdown/attachBody',
            "select2/selection/placeholder",
            "select2/selection/eventRelay",
            "select2/dropdown/search",
            "select2/selection/single"
        ], function (Utils, Dropdown, AttachBody) {
        
            function SelectAll() { }
            SelectAll.prototype.render = function (decorated) {
                var self = this,
                    $rendered = decorated.call(this),
                    $selectAll = $(
                        '<button class="btn btn-xs btn-default" type="button" style="margin-left:6px;"><i class="fa fa-check-square-o"></i> Izberi vse</button>'
                    ),
                    $unselectAll = $(
                        '<button class="btn btn-xs btn-default" type="button" style="margin-left:6px;"><i class="fa fa-square-o"></i> Unselect All</button>'
                    ),
                    //$btnContainer = $('<div style="margin-top:3px;">').append($selectAll).append($unselectAll);
                    $btnContainer = $('<div style="margin-top:3px;">').append($selectAll);
                
                    
                if (!this.$element.prop("multiple")) {
                    // this isn't a multi-select -> don't add the buttons!
                    
                    return $rendered;
                }

                $rendered.find('.select2-dropdown').prepend($btnContainer);
             
                
                $selectAll.on('click', function (e) {
                    var $results = $rendered.find('.select2-results__option[aria-selected=false]');
                    $results.each(function () {
                        //console.log($(this).data('data'));
                        if($(this).data('data').id != 0) {
                            self.trigger('select', {
                                data: $(this).data('data')
                            });
                        }
                    });
                    self.trigger('close');
                });
                
                $unselectAll.on('click', function (e) {
                    var $results = $rendered.find('.select2-results__option[aria-selected=true]');
                    $results.each(function () {
                        self.trigger('unselect', {
                            data: $(this).data('data')
                        });
                    });
                    self.trigger('close');
                });

                return $rendered;
            };
        
            return Utils.Decorate(
                Utils.Decorate(
                    Dropdown,
                    AttachBody
                ),
                SelectAll
            );
        
        });

        // if($.cookie('test')) {
        //     var s2 = $('.select2').prop('multiple');
        //     console.log(s2);
        // }

        $('.select2').select2({
            
            width: '100%',
            ajax: {
                dataType: 'json',
                data: function (params) {
                    var query = {
                        token: $("meta[name=token]").attr("content"),
                        search: params.term
                    }
                    return query;
                }
            }
        });

        $('.select2_multiple').select2({
            dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),
            width: '100%',
            ajax: {
                dataType: 'json',
                data: function (params) {
                    var query = {
                        token: $("meta[name=token]").attr("content"),
                        search: params.term
                    }
                    return query;
                }
            }
        });



    }

    //subtabs
    $( ".subtabs" ).tabs();
}
initialize();

$(document).on('click', '.odstraniFileRow', function() {
    if(confirm('Ali ste prepričane, da želite izbrisati datoteko?')) {
        $(this).parent().parent().remove();
        //$(this).parent().parent().addClass('hide');
        //console.log({'item_id' : $(this).data('itemid'), 'type' : $(this).data('type'), 'guid' : $(this).data('uid')});

        $.post("/webapp/base/call",
        {'token':$("meta[name=token]").attr("content"), 'c' : 'Base', 'm' : 'deleteFile', 'data' : {'item_id' : $(this).data('itemid'), 'type' : $(this).data('type'), 'guid' : $(this).data('uid')}},
        function(data) {
            //console.log(data);
        }).fail(function() {
            alertify.error('Prišlo je do napake! ', 2);
        });

        return false;
    }
});

function saveFile(guid, url, thumbnail, item_id, type) {
    $.post("/webapp/base/call",
    {'token':$("meta[name=token]").attr("content"), 'c' : 'Base', 'm' : 'saveFile', 'data' : {'guid':guid, 'url' : url, 'thumbnail' : thumbnail, 'item_id' : item_id, 'type' : type}},
    function(data) {
        //console.log(data);
    }).fail(function() {
        alertify.error('Prišlo je do napake! Spremembe niso bile shranjene.', 2);
    });
}

function addFileUploadRow(guid, uf_box, fileUploadRow, item_id, type, url, thumbnail, title, sort) {
    if(title === undefined)
    {
        title = '';
    }

    uf_box.append(
        fileUploadRow.jqote({id:guid, item_id:item_id, type:type, url:url, thumbnail: thumbnail, title:title, sort:sort}, '*')
    );
}

function getFiles(uf_box, fileUploadRow, item_id, type) {
    $.post("/webapp/base/call",
    {'token':$("meta[name=token]").attr("content"), 'c' : 'Base', 'm' : 'getFiles', 'data' : {'item_id' : item_id, 'type' : type}},
    function(data) {
        //console.log(data);
        if(data.data.length > 0) {
            //uf_box.append(   $('#fileUploadRowHeader').jqote()  );
            $.each(data.data, function (index, file) {
                addFileUploadRow(file.guid, uf_box, fileUploadRow, item_id, type, file.path, file.thumbnail, file.title, file.sort);
            });
        }
    }).fail(function() {
        alertify.error('Prišlo je do napake!', 2);
    });
}

function responsive_filemanager_callback(field_id){
    // console.log(field_id);
     var url=jQuery('#'+field_id).val();
     //your code
}

function initFileManager() {
    $('.iframe-btn').fancybox({
   'width'		: 900,
   'height'	: 600,
   'type'		: 'iframe',
   'autoScale'    	: false
   });
}

function initFileUpload(id, item_id, type, limit) {
    var uf_box = $('#uf_box'+id);
    var fileUploadRow = $('#fileUploadRow');

    $('#fileupload'+id).on('click', function() {
        if(uf_box.find('tr').length >= limit) {
            alert('Največjo število datotek: '+limit);
            return false;
        }

       
    });

    getFiles(uf_box, fileUploadRow, item_id, type);
    var uploading;
    $('#fileupload'+ id).fileupload({
        dataType: 'json',
        // change : function (e, data) {
        //     if(data.files.length>=2){
        //         alert("Max 5 files are allowed")
        //         return false;
        //     }
        // },
        //formData: {example: 'test'},
        done: function (e, data) {
           // console.log(data);

            $.each(data.result.files, function (index, file) {
                var guid = Helper.guid();
                addFileUploadRow(guid, uf_box, fileUploadRow, item_id, type, file.url, file.thumbnailUrl, file.title);
                saveFile(guid, file.url,file.thumbnailUrl, item_id, type);
            });

        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress'+ id +' .progress-bar').css('width', progress + '%');
        }
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');

    
}

function validateForm(method, form_name, reload) {

    var form = ".active form.edit_form_validate";
    if(form_name) {
        form = form_name;
    }

    $(form).validate({
        submitHandler: function(form) {
        

            //$('input[type=file]').prop('disabled', true);

            var form_data =getFormData($(form));
            //console.log(form_data);
            CKupdate();
            $(form).ajaxSubmit({
                dataType: 'json',
                type: 'POST',
                //contentType: "application/x-www-form-urlencoded; charset=utf-8",
                url: '/webapp/base/update',
                beforeSerialize: function($form, options) {
                    options.data = {
                        token: $("meta[name=token]").attr("content"),
                        id: (Helper.isNumeric(window.location.hash.replace("#", ""))) ? window.location.hash.replace("#", "") : current_row_edited.id,
                        c: $(form).data('c'),
                        m: (method) ? method : $(form).data('m')
                    };
                },
                beforeSubmit:  function(arr, $form, options) {
                    // arr.push({name:'token', value:$("meta[name=token]").attr("content")});
                    // arr.push({name:'id', value:(Helper.isNumeric(window.location.hash.replace("#", ""))) ? window.location.hash.replace("#", "") : current_row_edited.id});
                    // arr.push({name:'c', value:$(form).data('c')});
                    // arr.push({name:'m', value:$(form).data('m')});
                    // console.log(arr);
                    $('img.ajaxLoader').removeClass('hide');
                },
                success:  function(data) {
                    //console.log(data);
                    if(data.success == true) {
                        //console.log(data);

                        alertify.success('Spremembe so bile shranjene!', 2);

                        /*if($.isEmptyObject(current_row_edited)) {
                            location.reload();
                            return false;
                        }

                        $.each( form_data, function( key, value ) {
                            if(key in current_row_edited) {
                                current_row_edited[key] = value;
                            }
                        });

                        applyData(table_seznam, current_row_edited, false);*/

                        $('#seznam').DataTable().ajax.reload(null, false);

                        if(close_view == '1') {
                            toggle_visibility();
                            window.location.hash = '';
                        } else {
                            if(!Helper.isNumeric(window.location.hash.replace("#", "")) && Helper.isNumeric(data.data)) {
                                window.location.hash = data.data;
                                location.reload();
                            }
                        }

                        if(form_name && reload) {
                            $(form_name).parent().remove();
                        } else {
                            if(reload) {
                                location.reload();
                            }
                        }

                        

                    } else {
                        alertify.error('Prišlo je do napake!', 2);
                    }
                },
                error:  function(data) {
                    alertify.error('Prišlo je do napake!', 2);
                },
                complete:  function(data) {
                    $('img.ajaxLoader').addClass('hide');
                }
            });
        }
    });
}

$(window).bind('keydown', function(event) {
    if (event.ctrlKey || event.metaKey) {
        switch (String.fromCharCode(event.which).toLowerCase()) {
        case 's':
            event.preventDefault();
            $(".active .save_button_keep_state").trigger("click");
            //validateForm();
            break;
        // case 'f':
        //     event.preventDefault();
        //     alert('ctrl-f');
        //     break;
        // case 'g':
        //     event.preventDefault();
        //     alert('ctrl-g');
        //     break;
        }
    }
});

//shranjevanje
var close_view = '';
$(document).on('click', '.discard_changes', function() {
    
    var self = this;
    form_name = $(this).data('form_name');
    reload = $(this).data('reload');
     //console.log('discardChanges');

    
    validateForm('discardChanges', form_name, reload);
});

$(document).on('click', '.save_button', function() {
    
    var self = this;
    close_view = $(this).data('close');
    form_name = $(this).data('form_name');
    reload = $(this).data('reload');
    // console.log('close_view');

    
    validateForm(null, form_name, reload);
});

$(document).on('click', '.delete_single_file_upload', function() {
    var self = this,
        input_id = $(this).data('input_id');
    $('#' + input_id).val('');

    return false;
});


function checkFilter(value_table, value_filter) {
    var result = {};
    result.selected = true;
    result.match = false;

    if(value_filter == 0 || value_filter === undefined || value_filter == '-')
    {
        result.selected = false;
    }

    if(
        !result.selected
        || value_filter == value_table
        || (
            isNaN(value_filter) && isNaN(value_table) &&
            value_table.indexOf(value_filter) >= 0
        )
    )
    {
        result.match = true;
    }

    return result;
}

function createFilter(table, filters) {
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var i = 0;
            for(i; i < filters.length; i++) {
                var s = ( typeof filters[i].check_text !== 'undefined' && filters[i].check_text == true) ? $("#" + filters[i].naziv + " option:selected").text() : $("#" + filters[i].naziv + " option:selected").val();
                var check = checkFilter(data[filters[i].index], s);
                if(check.selected && !check.match) {
                    return false;
                }
            }

            return true;
        }
    );

    var i = 0;
    var ids = '';
    for(i; i < filters.length; i++) {
        if(i > 0) {
            ids += ', ';
        }

        ids += '#' + filters[i].naziv;
    }

    $(ids).on('change', function() {
        table.draw();
    });
}


function productRevision(data) {
  var u = getUrlVal(url);

  $.post(
    "/webapp/base/product-revison",
    { data: data, token: $("meta[name=token]").attr("content") },
    function (response) {
      var data = JSON.parse(response);

      if (data.success == true) {
        $("#message_box").css("background-color", "#28a745");
      } else {
        $("#message_box").css("background-color", "#dc3545");
      }
      $("#message_box").html(data.message);

      setTimeout(function () {
        $("#message_box").html("");
        window.location = "/artikli/artikel_revizija";
      }, 2500);
    }
  );
}

function _saveCategoryCard(data) {
    $.post(
        "/webapp/base/save-category-card",
        { data: data, token: $("meta[name=token]").attr("content") },
        function (response) {
            var data = JSON.parse(response);

            if (data.success == true) {
                $("#message_box").css("background-color", "#28a745");

                // Call drawCategoryCardsTable() to update the DOM
                drawCategoryCardsTable();
            } else {
                $("#message_box").css("background-color", "#dc3545");
            }
            $("#message_box").html(data.message);
            setTimeout(function () {
                $("#message_box").html("");
            }, 2500);
        }
    );
}


function _getCategoryCard(data, callback) {
  $.post(
    "/webapp/base/get-category-card",
    { data: data, token: $("meta[name=token]").attr("content") },
    function (response) {
      var parsedData = JSON.parse(response);
      callback(parsedData); // Call the provided callback with the data
    }
  );
}
  

function exportToFile(data) {
    data.token = $("meta[name=token]").attr("content");
    data.export_data = true;
    var filename = data.filename;


    //window.open(url, '_blank').focus();


    var form = document.createElement("form");
    form.setAttribute("id", 'export_file');
    form.setAttribute("method", 'post');
    form.setAttribute("action", '/webapp/base/call');

    for(var key in data) {
        if(data.hasOwnProperty(key)) {
                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", key);
                hiddenField.setAttribute("value", data[key]);

                form.appendChild(hiddenField);
            }
    }

    document.body.appendChild(form);
    form.submit();
    $("#export_file").remove();
    return;
    
 
    /*
    $.ajax({
        type:'POST',
        url:'/webapp/base/call',
        data: data,
 
        xhr: function() {
            var xhr = new XMLHttpRequest();
    
            xhr.onreadystatechange = function() {
                //console.log(xhr);
                //xhr.responseType = "blob";
                if (xhr.readyState == 2) {
                    if (xhr.status == 200) {
                        xhr.responseType = "blob";
                    } else {
                        xhr.responseType = "text";
                    }
                }
            };
            return xhr;
        },
        error: function(xhr, textStatus, errorThrown) {
            //console.log(xhr);
            //console.log(textStatus);
            //console.log(errorThrown);
            alertify.error('Prišlo je do napake!.', 2);
        },
        success: function(data) {
           
            var blob = new Blob([data], { type: "application/octetstream" });
            var url = window.URL || window.webkitURL;
                link = url.createObjectURL(blob);
                var a = $("<a />");
                a.attr("download", filename);
                a.attr("href", link);
                a.attr("id", "export_file");
                $("body").append(a);
                a[0].click();
                $("#export_file").remove();

        }
    });
    */
}

// $(document).mousedown(function(e){
//     if(e.which === 2 ){
//        alert("middle click");    
//        return false; // Or e.preventDefault()
//     }
// });

$(document).on("mousedown", function (e1) {
    $(document).one("mouseup", function (e2) {
      if (e1.which == 2 && e1.target == e2.target) {
        var e3 = $.event.fix(e2);
        e3.type = "middleclick";
        $(e2.target).trigger(e3)
      }
    });
});

$(document).on("middleclick", ".edit", function (e) {
    var id = $(this).parents('tr').prop('id');
    $(this).prop('href', '#' + id);
	//console.log(e);
    return false;
});



window.addEventListener('hashchange', function(e) {
    var q1 = parseInt(e.oldURL.split('#')[1]);
    var q2 = parseInt(e.newURL.split('#')[1]);

    if($('#magic_box').hasClass("hide") && !isNaN(q1) && isNaN(q2)) {
        toggle_visibility();
    }
}, false);




