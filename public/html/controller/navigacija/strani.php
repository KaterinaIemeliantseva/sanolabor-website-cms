<div id="magic_box" data-c="Vsebina" class="content-box">
    <div class="content-box-header"><h3><?php echo $this->dobiKategorijaNaziv(); ?></h3> </div>
    <div class="content-box-content">
    	<div class="tab-content default-tab" >
            <table class="table_filter" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td><?php
                        $opt = ['id' => 'filter_kat', 'label' => 'Nadrejena kategorija', 'name' => 'parent', 'url' => '/webapp/vsebina/s2/kategorije'];
                        $this->handler->html_select2($opt);
                        ?></td>
                        <td><?php $this->handler->html_select2(['id' => 'filter_tip', 'label' => 'Tip navigacije', 'name' => 'filter_tip', 'url' => '/webapp/select2?table=vsebina_tip']); ?></td>
                    </tr>
                </tbody>
            </table>

            <table id="seznam" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Aktiven*</th>
                        <th>Naziv*</th>
                        <th>Tip id</th>
                        <th>Tip navigacije</th>
                        <th>Kategorija id</th>
                        <th>Nadrejena kategorija</th>
                        <th>Vrstni red*</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
