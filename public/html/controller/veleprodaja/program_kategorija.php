
        <div id="magic_box" data-c="ProgramKategorija" class="content-box">
            <div class="content-box-header">
            <h3><?php echo $this->dobiKategorijaNaziv(); ?></h3>
            </div>
            <div class="content-box-content">
                <div class="tab-content default-tab" >
                <table class="table_filter" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td><?php $this->handler->html_select2(['id' => 'filter_kat', 'label' => 'Nadrejena kategorija', 'name' => 'parent', 'url' => '/webapp/select2?table=program_kategorija']); ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                    <table id="seznam" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Aktiven*</th>
                                <th>Naziv*</th>
                                <th>Nadrejena kategorija</th>
                                <th>Kategorija id</th>
                                <th>Vrstni red*</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        