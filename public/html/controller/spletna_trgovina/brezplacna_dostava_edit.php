<?php
include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');
?>
<div class="content-box-header">
    <h3><?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
 	<ul class="content-box-tabs">
 	    <li><a href="#tab1" class="default-tab current">Uredi</a></li>
 	</ul>
    <div class="clear"></div>
</div>
<div class="content-box-content">
    <div class="tab-content default-tab active" id="tab1">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="save" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-12">
                    <?php $handler->html_input(['label' => 'Aktivno od', 'name' => 'datum_od', 'value' => (!empty($data->datum_od)) ? $data->datum_od : '', 'required' => true, 'datepicker' => true]); ?>
                    <?php $handler->html_input(['label' => 'Aktivno do', 'name' => 'datum_do', 'value' => (!empty($data->datum_do)) ? $data->datum_do : '', 'required' => true, 'datepicker' => true]); ?>
                    <?php $handler->html_input(['label' => 'Minimalni znesek', 'type' => 'number', 'custom' => 'style="width:100px !important;" min="0"', 'name' => 'min_znesek', 'value' => (!empty($data->min_znesek)) ? $data->min_znesek : '0', 'required' => true]); ?>
                
                    <?php $handler->html_select2(['id' => 'povezani_artikli', 'label' => 'Artikli',  'name' => 'povezani_artikli[]', 'multiple' => true, 'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect', 'get_selected' => ['id' => (!empty($data->id)) ? $data->id : 0, 'c' => 'Artikel', 'm' => 'getPovezaniSelected', 'tip' => 3]]); ?>
                    <?php $handler->html_checkbox(['label' => 'Vsi artikli', 'name' => 'vsi_artikli', 'status' => (!empty($data->vsi_artikli))]); ?><br />
                    <?php $handler->html_checkbox(['label' => 'Samo za imetnike kartice ugodnosti', 'name' => 'kz', 'status' => (!empty($data->kz))]); ?>
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
