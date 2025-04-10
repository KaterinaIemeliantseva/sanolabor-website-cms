<div id="magic_box" data-c="Narocila" class="content-box">
    <div class="content-box-header"><h3><?php echo $this->dobiKategorijaNaziv(); ?></h3> </div>
    <div class="content-box-content">
    	<div class="tab-content default-tab" >
            <table class="table_filter" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td><?php $this->handler->html_select2(['id' => 'filter_np', 'label' => 'Način plačila', 'name' => 'nacin_placila', 'url' => '/webapp/s2?c=Narocila&m=getNacinPlacilaSelect']); ?></td>
                        <td><?php $this->handler->html_select2(['id' => 'filter_nd', 'label' => 'Način dostave', 'name' => 'nacin_dostave', 'url' => '/webapp/s2?c=Narocila&m=getNacinDostaveSelect']); ?></td>
                        <td style="padding-left:15px;"><label for="from">Datum od</label>
                            <input autocomplete="off" type="text" id="from" name="from">
                            <br /><label for="to">Datum do</label>
                            <input autocomplete="off" type="text" id="to" name="to">
                        </td>
                   
                        <td>

                        <?php $this->handler->html_checkbox(['label' => 'Nezaključena naročila', 'name' => 'nezakljucena_narocila', 'value' => 3, 'status' => false]); ?><br />
                        <?php $this->handler->html_checkbox(['label' => 'Neplačani predračuni', 'name' => 'neplacani_predracuni', 'value' =>41, 'status' => false]); ?><br />
                        <?php $this->handler->html_checkbox(['label' => 'Pravne osebe', 'name' => 'racun_podjetje', 'value' =>1, 'status' => false]); ?><br />
                        <?php $this->handler->html_checkbox(['label' => 'Naročila v tujino', 'name' => 'tujina', 'value' =>26, 'status' => false]); ?>
                        <?php $this->handler->html_checkbox(['label' => 'Naročila, ki vsebujejo zdravilo', 'name' => 'vsebuje_zdravilo', 'value' =>1, 'status' => false]); ?><br />
   
                        </td>
                      
                    </tr>
                </tbody>
            </table>

            <table id="seznam" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Št. naročila</th>
                        <th>Ustvarjeno</th>
                        <th>E-naslov</th>
                        <th>Ime in priimek</th>
                        <th>Način plačila</th>
                        <th>Znesek</th>
                        <th>Znesek dostave</th>
                        <th></th>
                        <th>Opombe</th>
                        <th></th>
                        <th>Artikli</th>
                        <th>Naročilo zaključeno</th>
                        <th>Enota</th>
                        <th>Potrditev/Poslano v enoto</th>
                        <th>Kupon</th>
                        <th>Brezplačna dostava (kupon)</th>
                        <th>UUID</th>
                        <th>Potrditev kartice</th>
                        <th>Tip kartice</th>
                        <th>Zadnja sprememba</th>
                        <th>Oznaka</th>
                        <th>Št. računa</th>

                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
