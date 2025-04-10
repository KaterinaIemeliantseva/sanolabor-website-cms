<?php
include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');
?>
<div class="content-box-header">
    <h3><?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
 	<ul class="content-box-tabs">
 	    <li><a href="#tab1" class="default-tab current">Uredi</a></li>
        <?php if($data): ?><li><a href="#tab2" class="">Galerija</a></li><?php endif; ?>
        <?php if($data): ?><li><a href="#tab3" class="">Dokumenti</a></li><?php endif; ?>
 	</ul>
    <div class="clear"></div>
</div>
<div class="content-box-content">
    <div class="tab-content default-tab active" id="tab1">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="save" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-6">
                    <?php if($data): ?><?php $handler->html_label(['label' => 'Slug', 'value' => (!empty($data->nicename)) ? $data->nicename : '']); ?><?php endif; ?>
                    <?php $handler->html_input(['label' => 'Datum objave', 'name' => 'datum_objave', 'value' => (!empty($data->datum_objave)) ? $data->datum_objave : '', 'datepicker' => true]); ?>
                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>
                    <?php //$handler->html_select2(['id' => 'kategorija', 'label' => 'Kategorija', 'name' => 'kategorije[]', 'multiple' => true, 'url' => '/webapp/select2?table=novice_kategorija', 'get_list' =>  ['id' => (!empty($data->id)) ? $data->id : 0, 'table1' => 'novice_kategorija_mm', 'table2' => 'novice_kategorija', 'field1' => 'id_kategorija', 'field2' => 'id_novica']]); ?>
                    <?php //$handler->html_select2(['id' => 'enota', 'label' => 'Kje', 'name' => 'enota', 'url' => '/webapp/s2?c=Enote&m=getAllSelect', 'get_selected' => ['id' => (!empty($data->enota)) ? $data->enota : 0, 'c' => 'Enote', 'm' => 'getSingleSelect']]); ?>
                    <?php //$handler->html_input(['label' => 'Kdaj', 'name' => 'kdaj', 'value' => (!empty($data->kdaj)) ? $data->kdaj : '', 'required' => false]); ?>
                    <?php $handler->html_editor(['label' => 'Kratki opis', 'name' => 'kratki_opis', 'height' => '150', 'value' => (!empty($data->kratki_opis)) ? $data->kratki_opis : '']); ?>
                    <?php //$handler->html_input(['label' => 'Kratki opis', 'name' => 'kratki_opis', 'value' => (!empty($data->kratki_opis)) ? $data->kratki_opis : '', 'required' => false]); ?>
                    <?php $handler->html_tags(['id' => (!empty($data->id)) ? $data->id : 0, 'tip' => 1]); ?>


                    <?php $handler->html_input(['label' => 'Meta naslov', 'name' => 'meta_title', 'value' => (!empty($data->meta_title)) ? $data->meta_title : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Meta opis', 'name' => 'meta_desc', 'value' => (!empty($data->meta_desc)) ? $data->meta_desc : '', 'required' => false]); ?>
                  
                </div>
                <div class="col-lg-6">
                    <?php $handler->html_editor(['label' => 'Vsebina', 'name' => 'dolgi_opis', 'height' => '600', 'value' => (!empty($data->dolgi_opis)) ? $data->dolgi_opis : '']); ?>
                    <small>Za prikaz galerije je znotraj vsebine potrebno vpisati: #galerija#</small>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">

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
        $opt = ['id' => '1', 'type' => '4', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=novice'];
        $handler->html_file_upload($opt); ?>
        <?php $handler->html_save_button($data, ['close' => true]); ?>
    </div>

    <div class="tab-content hide" id="tab3">
        <?php
        // $opt = ['id' => '2', 'type' => '6', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=novice_navodila'];
        // $handler->html_file_upload($opt); 
        // $handler->html_save_button($data, ['close' => true]); 
        ?>
    </div>
    <?php endif; ?>
 </div>
