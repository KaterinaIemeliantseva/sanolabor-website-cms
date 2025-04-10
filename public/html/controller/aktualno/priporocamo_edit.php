<?php include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');   ?>            
<div class="content-box-header">
    <h3><?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
    <ul class="content-box-tabs">
        <li><a href="#tab1" class="default-tab current">Uredi</a></li>
    </ul>
    <div class="clear"></div>
</div>
<div class="content-box-content">
    <div class="tab-content default-tab active" id="tab1">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="update" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-12">
                    <?php $handler->html_input(['label' => 'Naslov', 'name' => 'naziv2', 'value' => (!empty($data->naziv2)) ? $data->naziv2 : '', 'required' => false]); ?>  
                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>                                              
                    <?php $handler->html_input(['label' => 'Opis', 'name' => 'opis', 'value' => (!empty($data->opis)) ? $data->opis : '', 'required' => false]); ?>   
                    <?php $handler->html_select2(['id' => 'povezani_artikli', 'label' => 'Artikli',  'name' => 'povezani_artikli[]', 'multiple' => true, 'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect', 'get_selected' => ['id' => (!empty($data->id)) ? $data->id : 0, 'c' => 'Artikel', 'm' => 'getPovezaniSelected', 'tip' => 7]]); ?>
                    <?php $handler->html_input(['label' => 'Aktivno od', 'name' => 'datum_veljavno_od', 'value' => (!empty($data->datum_veljavno_od)) ? $data->datum_veljavno_od : '', 'required' => false, 'datepicker' => true]); ?>
                    <?php $handler->html_input(['label' => 'Aktivno do', 'name' => 'datum_veljavno_do', 'value' => (!empty($data->datum_veljavno_do)) ? $data->datum_veljavno_do : '', 'required' => false, 'datepicker' => true]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">                          
                    <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>                            
                </div>
            </div>
            <?php $handler->html_save_button($data); ?>                    
        </form>
    </div>
</div>
        