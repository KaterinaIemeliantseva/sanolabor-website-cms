<?php include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');   ?>            
    <div class="content-box-header">
        <h3><?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
        <ul class="content-box-tabs">
            <li><a href="#tab1" class="default-tab current">Uredi</a></li>
        </ul>
        <div class="clear">

        </div>
    </div>
    <div class="content-box-content">
        <div class="tab-content default-tab active" id="tab1">
            <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="update" method="post" class="edit_form_validate form-group">
                <div class="row">
                    <div class="col-lg-6">
                        <?php $handler->html_input(['label' => 'Šifra kupona', 'name' => 'sifra_kupona', 'value' => (!empty($data->sifra_kupona)) ? $data->sifra_kupona : '', 'required' => true]); ?>
                        <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>
                        <?php $handler->html_input(['label' => 'Kratki opis', 'name' => 'kratki_opis', 'value' => (!empty($data->kratki_opis)) ? $data->kratki_opis : '', 'required' => true]); ?>
                        <?php $handler->html_input(['label' => 'Opis', 'name' => 'opis', 'value' => (!empty($data->opis)) ? $data->opis : ' ', 'required' => false]); ?>
                        <?php $handler->html_input(['label' => 'Daljši opis', 'name' => 'daljsi_opis', 'value' => (!empty($data->daljsi_opis)) ? $data->daljsi_opis : '', 'required' => false]); ?>
                        <?php $handler->html_input(['label' => 'Vrstni red', 'name' => 'vrstni_red', 'value' => (!empty($data->vrstni_red)) ? $data->vrstni_red : 0, 'required' => false]); ?>
                        <!-- <?php $handler->html_input(['label' => 'Minimalni znesek za koriščenje kupona', 'name' => 'minimalni_znesek', 'value' => (!empty($data->minimalni_znesek)) ? $data->minimalni_znesek : '0', 'required' => false]); ?> -->
                        <!-- <?php $handler->html_input(['label' => 'Znesek popusta na celotni nakup', 'name' => 'popust_znesek', 'value' => (!empty($data->popust_znesek)) ? $data->popust_znesek : '0', 'required' => false]); ?> -->
                        <!-- <?php $handler->html_input(['label' => 'Popust (%)', 'name' => 'popust_odstotek', 'value' => (!empty($data->popust_odstotek)) ? $data->popust_odstotek : '0', 'required' => false]); ?> -->
                        <?php $handler->html_input(['label' => 'Število potrebnih točk', 'name' => 'tocke', 'value' => (!empty($data->tocke)) ? $data->tocke : '', 'required' => true]); ?>
                        <?php $handler->html_input(['label' => 'Aktivno od', 'name' => 'datum_veljavno_od', 'value' => (!empty($data->datum_veljavno_od)) ? $data->datum_veljavno_od : '', 'required' => true, 'datepicker' => true]); ?>
                        <?php $handler->html_input(['label' => 'Aktivno do', 'name' => 'datum_veljavno_do', 'value' => (!empty($data->datum_veljavno_do)) ? $data->datum_veljavno_do : '', 'required' => true, 'datepicker' => true]); ?>
                    </div>
                    <div class="col-lg-6">
                        <?php $handler->html_single_file_upload(['label' => 'Logo', 'name' => 'logo', 'value' => (!empty($data->logo)) ? $data->logo : '', 'required' => true]); ?>
                        <?php $handler->html_single_file_upload(['label' => 'Kupon', 'name' => 'kupon_dokument', 'value' => (!empty($data->kupon_dokument)) ? $data->kupon_dokument : '', 'required' => true]); ?>
                    </div>
                
                    <div class="col-lg-6">
                        <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>
                    </div>
                
               
                    
                </div>
                
                <?php $handler->html_save_button($data); ?>                    
            </form>
        </div>
    </div>
        