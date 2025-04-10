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
                <div class="col-lg-6">
                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>     
                    <?php $handler->html_editor(['label' => 'Opis', 'name' => 'opis', 'value' => (!empty($data->opis)) ? $data->opis : '', 'height' => '100']); ?>  
                    <?php $handler->html_input(['label' => 'Aktivno od', 'name' => 'datum_od', 'value' => (!empty($data->datum_od)) ? $data->datum_od : '', 'required' => true, 'datepicker' => true]); ?>
                    <?php $handler->html_input(['label' => 'Aktivno do', 'name' => 'datum_do', 'value' => (!empty($data->datum_do)) ? $data->datum_do : '', 'required' => true, 'datepicker' => true]); ?>   
                    <?php $handler->html_single_file_upload(['label' => 'Banner', 'name' => 'banner', 'value' => (!empty($data->banner)) ? $data->banner : '', 'required' => false]); ?>                 
                </div>
                <div class="col-lg-6">
                    <?php $handler->html_select2(['id' => 'povezani_artikli', 'label' => 'Izberi artikle iz seznama', 'name' => 'povezani_artikli[]', 'multiple' => true, 'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect', 'get_selected' => ['id' => (!empty($data->id)) ? $data->id : 0, 'c' => 'Artikel', 'm' => 'getPovezaniSelected', 'tip' => 8]]); ?>                   
                    <?php  $handler->html_select2(['id' => 'blagovna_znamka', 'label' => 'Izberi blagovno znamko', 'name' => 'blagovna_znamka', 'url' => '/webapp/select2?table=blagovna_znamka', 'get_single' => ['id' => (!empty($data->blagovna_znamka)) ? $data->blagovna_znamka : 0, 'table' => 'blagovna_znamka']]); ?>
                    <?php  //$handler->html_select2(['id' => 'dobavitelj_id', 'label' => 'Izberi dobavitelja', 'name' => 'dobavitelj_id', 'url' => '/webapp/select2?table=dobavitelj', 'get_single' => ['id' => (!empty($data->dobavitelj_id)) ? $data->dobavitelj_id : 0, 'table' => 'dobavitelj']]); ?>
                    <?php  $handler->html_select2(['id' => 'dobavitelj_id', 'label' => 'Izberi dobavitelja', 'name' => 'dobavitelj_id', 'url' => '/webapp/select2?table=dobavitelj_users', 'get_single' => ['id' => (!empty($data->dobavitelj_id)) ? $data->dobavitelj_id : 0, 'table' => 'dobavitelj_users', 'dobavitelj_name' => (isset($data->dobavitelj_id)) ? $foo->getDobaviteljName($data->dobavitelj_id) : '']]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php $handler->html_checkbox(['label' => 'Skrij v meniju', 'name' => 'skrij_menu', 'status' => (!empty($data->skrij_menu))]); ?>      <br />                      
                    <?php $handler->html_checkbox(['label' => 'Prikaži naslov', 'name' => 'prikazi_naslov', 'status' => (!empty($data->prikazi_naslov))]); ?>      <br />                      
                    <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>                            
                </div>
            </div>
            <?php $handler->html_input_hidden( ['name' => 'nicename', 'value' => (!empty($data->nicename)) ? $data->nicename : '']); ?>
            <?php $handler->html_save_button($data); ?>                    
        </form>
    </div>
</div>
        