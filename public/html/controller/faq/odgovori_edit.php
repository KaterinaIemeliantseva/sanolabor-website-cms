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
                    <?php $handler->html_input(['label' => 'Vprašanje', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>     
                    <?php $handler->html_editor(['label' => 'Odgovor', 'name' => 'odgovor', 'height' => '150', 'value' => (!empty($data->kratki_opis)) ? $data->kratki_opis : '']); ?>   
                    <?php $handler->html_select2(['id' => 'kategorija_id', 'label' => 'Kategorija', 'name' => 'kategorija_id', 'url' => '/webapp/select2?table=faq_kategorije', 'get_single' => ['id' => (!empty($data->kategorija_id)) ? $data->kategorija_id : 0, 'table' => 'faq_kategorije']]); ?>                      
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
        