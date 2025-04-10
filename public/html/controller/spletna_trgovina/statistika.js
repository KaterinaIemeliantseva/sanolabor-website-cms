$(function(){
    var self = this;
    var dateFormat = "dd.mm.yy";
    
    var from = $( "#from" )
    .datepicker({
        dateFormat: dateFormat,
        changeMonth: true
    })
    .on( "change", function() {
        to.datepicker( "option", "minDate", getDate( this ) );
    });

    var to = $( "#to" ).datepicker({
        dateFormat: dateFormat,
        changeMonth: true
    })
    .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
    });

    function getDate( element ) {
        var date;
        
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
            date = null;
        }

        return date;
    }

    $('#potrdiIskanje').on('click', function() {
        //console.log('ok');
        $('.ajaxLoader').removeClass('hide');

        //showEdit({'magic_box_edit' : 'statistika_box', 'from' : '07.06.2018', 'to' : '07.06.2018'});
        showEdit({'magic_box_edit' : 'statistika_box', 'from' : from.val(), 'to' : to.val()});
        return false;
    });

    $(document).on('click', '#export_excel', function() {
        $("#stat_table").table2excel({
            exclude: ".noExl",
            name: "Worksheet",
            filename: "Izvoz", //do not include extension
            fileext: ".xls" // file extension
        }); 
    
        return;
    });
});

$.getScript("/public/resources/table2excel/src/jquery.table2excel.js", function() {
});