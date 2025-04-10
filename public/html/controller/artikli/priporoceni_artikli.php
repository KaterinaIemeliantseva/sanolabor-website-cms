<div id="magic_box" data-c="PriporoceniArtikli" class="content-box">
  <div class="content-box-header">
    <h3><?php echo $this->dobiKategorijaNaziv(); ?></h3>
  </div>
  <div class="content-box-content">
    <div class="tab-content default-tab">
      <table id="seznam" class="display" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Šifra</th>
            <th>Naziv</th>
            <th>Vrstni red</th>
            <th></th>
          </tr>
        </thead>
        <tfoot style="background-color: #eeeeee;">
          <tr role="row" class="even">
            <td>
              <input type="text" id="sifra_new" name="sifra_new" class="form-control form-control-sm" value="">
            </td>
            <td>
            </td>
            <td>
              <input type="text" id="red_new" name="red_new" class="form-control form-control-sm" value="">
            </td>
            <td>
              <button id="artikel_new" class="btn btn-success">Dodaj</button>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>