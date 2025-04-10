<div id="magic_box" data-c="Narocila" class="content-box">
    <div class="content-box-header"><h3><?php echo $this->dobiKategorijaNaziv(); ?></h3> </div>
    <div class="content-box-content">
    	<div class="tab-content default-tab" >
            <table class="table_filter" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td style="padding-left:15px;"><label for="from">Datum od</label>
                        <input autocomplete="off" type="text" id="from" name="from">
                        <br /><label for="to">Datum do</label>
                        <input autocomplete="off" type="text" id="to" name="to"></td>
                        <td><input data-close="0" class="btn btn-success " type="submit" value="Potrdi" id="potrdiIskanje" /> <img src="/public/resources/images/ajax-loader.gif" class="ajaxLoader hide" /></td>
                    </tr>
                </tbody>
            </table>

            
        </div>
    </div>

   
        <div id="statistika_box" class="content-box-content"></div>
    
</div>




