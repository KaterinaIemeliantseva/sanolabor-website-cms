<?php include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');   ?>            
<div class="content-box-header">
    <h3><?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
    <ul class="content-box-tabs">
        <li><a href="#tab1" class="default-tab current">Uredi</a></li>
        <?php if($data): ?><li><a href="#tab2" class="">Galerija</a></li><?php endif; ?>
    </ul>
    <div class="clear"></div>
</div>
<div class="content-box-content">
    <div class="tab-content default-tab active" id="tab1">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="update" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-6">
                    <?php $handler->html_input(['label' => 'Šifra', 'name' => 'sifra', 'value' => (!empty($data->sifra)) ? $data->sifra : 0, 'required' => false]); ?>                            
                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>                            
                    <?php $handler->html_input(['label' => 'Naslov', 'name' => 'naslov', 'value' => (!empty($data->naslov)) ? $data->naslov : '', 'required' => true]); ?>                            
                    <?php $handler->html_input(['label' => 'E-naslov', 'name' => 'enaslov', 'value' => (!empty($data->enaslov)) ? $data->enaslov : '', 'required' => true]); ?>                            
                    <?php $handler->html_input(['label' => 'Telefon', 'name' => 'tel_st', 'value' => (!empty($data->tel_st)) ? $data->tel_st : '', 'required' => false]); ?>                            
                    <?php $handler->html_input(['label' => 'Telefon', 'name' => 'tel_st2', 'value' => (!empty($data->tel_st2)) ? $data->tel_st2 : '', 'required' => false]); ?>                            
                    <?php $handler->html_input(['label' => 'Koordinate', 'name' => 'koordinate', 'value' => (!empty($data->koordinate)) ? $data->koordinate : '', 'required' => false]); ?>                            
                </div>
                <div class="col-lg-6 ">
                    <?php $handler->html_label(['label' => 'Delovni čas']); ?>
                    <div class="d-flex multi_inputs" style="flex-wrap: wrap;">
                    <?php $handler->html_input(['label' => 'Pon.:', 'name' => 'del_cas5', 'value' => (!empty($data->del_cas5)) ? $data->del_cas5 : '', 'required' => false]); ?>  
                    <?php $handler->html_input(['label' => 'Tor.:', 'name' => 'del_cas10', 'value' => (!empty($data->del_cas10)) ? $data->del_cas10 : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Sreda.:', 'name' => 'del_cas2', 'value' => (!empty($data->del_cas2)) ? $data->del_cas2 : '', 'required' => false]); ?> 
                    <?php $handler->html_input(['label' => 'Čet.:', 'name' => 'del_cas13', 'value' => (!empty($data->del_cas13)) ? $data->del_cas13 : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Pet.:', 'name' => 'del_cas12', 'value' => (!empty($data->del_cas12)) ? $data->del_cas12 : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Sobota.:', 'name' => 'del_cas3', 'value' => (!empty($data->del_cas3)) ? $data->del_cas3 : '', 'required' => false]); ?>  
                    <?php $handler->html_input(['label' => 'Nedelja.:', 'name' => 'del_cas4', 'value' => (!empty($data->del_cas4)) ? $data->del_cas4 : '', 'required' => false]); ?>  
                    <?php $handler->html_input(['label' => 'Pon - tor.:', 'name' => 'del_cas7', 'value' => (!empty($data->del_cas7)) ? $data->del_cas7 : '', 'required' => false]); ?>  
                    <?php $handler->html_input(['label' => 'Pon. - pet.:', 'name' => 'del_cas1', 'value' => (!empty($data->del_cas1)) ? $data->del_cas1 : '', 'required' => false]); ?>  
                    <?php $handler->html_input(['label' => 'Pon. - sob.:', 'name' => 'del_cas9', 'value' => (!empty($data->del_cas9)) ? $data->del_cas9 : '', 'required' => false]); ?>  
                    <?php $handler->html_input(['label' => 'Tor. - pet.:', 'name' => 'del_cas6', 'value' => (!empty($data->del_cas6)) ? $data->del_cas6 : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Sre. - čet.:', 'name' => 'del_cas11', 'value' => (!empty($data->del_cas11)) ? $data->del_cas11 : '', 'required' => false]); ?>   
                    <?php $handler->html_input(['label' => 'Čet. - pet.:', 'name' => 'del_cas8', 'value' => (!empty($data->del_cas8)) ? $data->del_cas8 : '', 'required' => false]); ?>  
                     
                     
                     
                    </div>
                    <hr />
                    <?php $handler->html_editor(['label' => 'Posebni delovni čas', 'name' => 'posebni_delovni_cas', 'height' => '150', 'value' => (!empty($data->posebni_delovni_cas)) ? $data->posebni_delovni_cas : '']); ?>
                    <div class="d-flex multi_inputs" style="flex-wrap: wrap;">
                    <?php $handler->html_input(['label' => 'Prikaz od', 'name' => 'datum_posebni_delovni_cas_od', 'value' => (!empty($data->datum_posebni_delovni_cas_od)) ? $data->datum_posebni_delovni_cas_od : '', 'datepicker' => true]); ?>
                    <?php $handler->html_input(['label' => 'Prikaz do', 'name' => 'datum_posebni_delovni_cas_do', 'value' => (!empty($data->datum_posebni_delovni_cas_do)) ? $data->datum_posebni_delovni_cas_do : '', 'datepicker' => true]); ?>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php $handler->html_checkbox(['label' => 'Onemogoči izbiro prodajalne', 'name' => 'onemogoci_izbiro', 'status' => (!empty($data->onemogoci_izbiro))]); ?>   <br />                         
                    <?php $handler->html_checkbox(['label' => 'Omogočena prodaja zdravil', 'name' => 'zdravila', 'status' => (!empty($data->zdravila))]); ?>   <br />                         
                    <?php $handler->html_checkbox(['label' => 'Franšiza', 'name' => 'fransiza', 'status' => (!empty($data->fransiza))]); ?>   <br />                         
                    <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>                            
                </div>
            </div>

            <?php $handler->html_input_hidden( ['name' => 'nicename', 'value' => (!empty($data->nicename)) ? $data->nicename : '']); ?>
            <?php $handler->html_save_button($data); ?>                    
        </form>
    </div>

    <?php if($data): ?>
    <div class="tab-content hide" id="tab2">
        <?php
        $opt = ['id' => '1', 'type' => '3', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=enote'];
        $handler->html_file_upload($opt); ?>
        <?php $handler->html_save_button($data, ['close' => true]); ?>
    </div>

    <?php endif; ?>
</div>

        