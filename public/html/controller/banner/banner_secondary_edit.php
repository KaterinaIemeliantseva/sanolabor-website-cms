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
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="update" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-6">
                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '']); ?>
                    <hr />
                    <?php $handler->html_single_file_upload(['label' => 'Slika', 'name' => 'slika', 'value' => (!empty($data->slika)) ? $data->slika : '', 'required' => false]); ?>
                    <hr />
                    <?php $handler->html_input(['label' => 'Opis na gumbu', 'name' => 'gumb_opis', 'value' => (!empty($data->gumb_opis)) ? $data->gumb_opis : '']); ?>
                    <?php $handler->html_input(['label' => 'Povezava', 'name' => 'gumb_povezava', 'value' => (!empty($data->gumb_povezava)) ? $data->gumb_povezava : '']); ?>

                 </div>
                <div class="col-lg-6">
                    <?php $handler->html_input(['label' => 'Opis', 'name' => 'opis', 'value' => (!empty($data->opis)) ? $data->opis : '']); ?>
                    <?php $handler->html_input(['label' => 'Datum od', 'name' => 'datum_od', 'value' => (!empty($data->datum_od)) ? $data->datum_od : '', 'datepicker' => true]); ?>
                    <?php $handler->html_input(['label' => 'Datum do', 'name' => 'datum_do', 'value' => (!empty($data->datum_do)) ? $data->datum_do : '', 'datepicker' => true]); ?>
                </div>
                
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php $handler->html_checkbox(['label' => 'Prikaz okna z opisom in gumbom', 'name' => 'okno_prikaz', 'status' => (!empty($data->okno_prikaz))]); ?><br />
                    <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>
                </div>
            </div>

            <?php $handler->html_save_button($data); ?>
        </form>
    </div>


 </div>
