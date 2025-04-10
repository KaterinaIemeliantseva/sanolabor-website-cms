<div id="magic_box" data-c="Artikel" class="content-box">
    <div class="content-box-header"><h3><?php echo $this->dobiKategorijaNaziv(); ?></h3> </div>
    <div class="content-box-content">
    	<div class="tab-content default-tab" >
        <?php if(1 == 1): ?>
             <table class="table_filter" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td>
                            <?php $this->handler->html_checkbox(['label' => 'Prikaži samo artikle v arhivu', 'name' => 'status_arhiv', 'value' => '1', 'status' => false]); ?><br />
                            <?php $this->handler->html_checkbox(['label' => 'Prikaži artikle brez splene akcije', 'name' => 'ni_popusta', 'value' => '1', 'status' => false]); ?><br />
                        </td>
                    </tr>
                </tbody>
            </table> 
        <?php endif; ?>

            <table id="seznam" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Aktiven*</th>
                        <th>Arhiv*</th>
                        <th>Naziv</th>
                        <th>Šifra</th>
                        <th>Zadnja sprememba</th>
                        <th>ID</th>
                        <th>Kategorija</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
