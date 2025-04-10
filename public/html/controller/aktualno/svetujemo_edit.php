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
                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>      
                    <?php $handler->html_editor(['label' => 'Kratki opis', 'name' => 'kratki_opis', 'value' => (!empty($data->kratki_opis)) ? $data->kratki_opis : '', 'height' => '150']); ?>      
                    <?php $handler->html_select2(['id' => 'kategorija', 'label' => 'Kategorija', 'name' => 'kategorije[]', 'multiple' => true, 'url' => '/webapp/select2?table=svetujemo_kategorija', 'get_list' =>  ['id' => (!empty($data->id)) ? $data->id : 0, 'table1' => 'svetujemo_kategorija_mm', 'table2' => 'svetujemo_kategorija', 'field1' => 'id_kategorija', 'field2' => 'id_clanek']]); ?>
                    <?php $handler->html_input(['label' => 'Datum', 'name' => 'datum', 'value' => (!empty($data->datum)) ? $data->datum : '', 'required' => true, 'datepicker' => true]); ?>
                </div>
                <div class="col-lg-6">
                    <?php $handler->html_editor(['label' => 'Vsebina', 'name' => 'dolgi_opis', 'value' => (!empty($data->dolgi_opis)) ? $data->dolgi_opis : '', 'height' => '400']); ?>                           
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
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
            $opt = ['id' => '1', 'type' => '14', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=svetujemo'];
            $handler->html_file_upload($opt); ?>
            <?php $handler->html_save_button($data, ['close' => true]); ?>
        </div>
    <?php endif; ?>
</div>
        