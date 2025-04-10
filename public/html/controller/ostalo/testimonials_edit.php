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
                    <?php $handler->html_input(['label' => 'Ime Priimek', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '']); ?>
                    <hr />
                    <?php $handler->html_input(['label' => 'Naslov', 'name' => 'naslov', 'value' => (!empty($data->naslov)) ? $data->naslov : '']); ?>
                    <hr />
                    <?php $handler->html_input(['label' => 'Vsebina', 'name' => 'vsebina', 'value' => (!empty($data->vsebina)) ? $data->vsebina : '']); ?>
                    <hr />
                    <?php $handler->html_single_file_upload(['label' => 'Slika', 'name' => 'slika', 'value' => (!empty($data->slika)) ? $data->slika : '', 'required' => false]); ?>
                    <hr />

                 </div>
                <div class="col-lg-6">
                    <?php $handler->html_input(['label' => 'Datum od', 'name' => 'datum_od', 'value' => (!empty($data->datum_od)) ? $data->datum_od : '', 'datepicker' => true]); ?>
                    <?php $handler->html_input(['label' => 'Datum do', 'name' => 'datum_do', 'value' => (!empty($data->datum_do)) ? $data->datum_do : '', 'datepicker' => true]); ?>                    
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
