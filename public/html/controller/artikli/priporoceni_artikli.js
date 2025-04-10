$(function () {
  var self = this;

  /*table - start*/
  seznam_default.columns = [
    { "data": "sifra" },
    { "data": "naziv" },
    { "data": "red" },
    { "data": "null", "width": "130px", "defaultContent": seznam_button_delete }
  ];
  seznam_default.buttons = [];

  var seznam = $('#seznam');
  var table = seznam.DataTable(seznam_default);
  setDeleteButton(seznam, table);

  table.MakeCellsEditable({
    "onUpdate": seznamUpdateCallbackFunction,
    "columns": [2]
});
  /*table - end*/

  $('#artikel_new').on('click', function () {
    if ($('#sifra_new').val() != '') {
      $.ajax({
        url: $("meta[name=main_url]").attr("content") + "/api/priporoceni_artikli/add",
        //url: $("meta[name=main_url]").attr("content") + "/api/artikel-sifra-parameter/add",
        data: {
          'auth': $("meta[name=auth]").attr("content"),
          'sifra': $('#sifra_new').val(),
          'red': $('#red_new').val()
        },
        complete: function (result) {
          var result_obj = JSON.parse(result.responseText);
          if (result.status == 200 && !result_obj.error) {
            alertify.success('Dodano', 2);
            $('#seznam').DataTable().ajax.reload(null, false);
          } else {
            alertify.error((result_obj.error_msg) ? result_obj.error_msg : 'Prišlo je do napake!', 2);
          }
        }
      }).always(function () {

      });
    }
  });
});


