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
                    <?php //$handler->html_input(['label' => 'Napis', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?> 
                    <?php $handler->html_editor(['label' => 'Napis', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true, 'height' => 70]); ?>

                    <?php $handler->html_input(['label' => 'Datum od', 'name' => 'datum_od', 'value' => (!empty($data->datum_od)) ? $data->datum_od : '', 'required' => true, 'datepicker' => true]); ?>
                    <?php $handler->html_input(['label' => 'Datum do', 'name' => 'datum_do', 'value' => (!empty($data->datum_do)) ? $data->datum_do : '', 'required' => true, 'datepicker' => true]); ?>     
                    <?php $handler->html_input(['label' => 'Barva besedila', 'name' => 'barva_napis', 'value' => (!empty($data->barva_napis)) ? $data->barva_napis : '', 'required' => true, 'type' => 'color']); ?>     

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-2 no-border">
                                <?php $handler->html_input(['label' => 'Barva ozadja 1', 'name' => 'barva_ozadje', 'value' => (!empty($data->barva_ozadje)) ? $data->barva_ozadje : '', 'required' => true, 'type' => 'color']); ?>    
                            </div>
                            <div class="col-xs-2 no-border">
                                <?php $handler->html_input(['label' => 'Barva ozadja 2', 'name' => 'barva_ozadje_do', 'value' => (!empty($data->barva_ozadje_do)) ? $data->barva_ozadje_do : '', 'required' => true, 'type' => 'color']); ?>
                            </div>
                        </div>
                    </div>
              
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
        