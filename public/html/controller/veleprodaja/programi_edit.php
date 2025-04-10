<?php include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');   ?>            
<div class="content-box-header">
    <h3><?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
    <ul class="content-box-tabs">
        <li><a href="#tab1" class="default-tab current">Uredi</a></li>
        <?php if($data): ?><li><a href="#tab3" class="">Kategorija</a></li><?php endif; ?>
    </ul>
    <div class="clear"></div>
</div>
<div class="content-box-content">
    <div class="tab-content default-tab active" id="tab1">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="update" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-6">
                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>         
                    <?php $handler->html_single_file_upload(['label' => 'Logotip', 'name' => 'logotip', 'value' => (!empty($data->logotip)) ? $data->logotip : '', 'required' => false]); ?>        
                    <?php $handler->html_input(['label' => 'Povezava', 'name' => 'povezava', 'value' => (!empty($data->povezava)) ? $data->povezava : '', 'required' => true]); ?>       
                    <?php $handler->html_select2(['id' => 'kontakti', 'label' => 'Kontakti', 'name' => 'kontakti[]', 'multiple' => true, 'url' => '/webapp/select2?table=program_kontakt', 'get_list' =>  ['id' => (!empty($data->id)) ? $data->id : 0, 'table1' => 'program_kontakt_mm', 'table2' => 'program_kontakt', 'field1' => 'id_kontakt', 'field2' => 'id_program']]); ?>
                </div>
                <div class="col-lg-6">
                <?php $handler->html_editor(['label' => 'Opis', 'name' => 'opis', 'value' => (!empty($data->opis)) ? $data->opis : '', 'height' => '350']); ?>
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

    <?php if($data): ?>
    <div class="tab-content hide" id="tab3">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="shraniRavrstitev" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-6">
                <?php
                $foo->getKategorijeRazvrstiIzpis(0, 1, $data->id, 1);
                ?>
                </div>

            </div>
        <?php $handler->html_save_button($data); ?>
        </form>
    </div>
    <?php endif; ?>
</div>
        