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
                <div class="col-lg-2">
                    <?php $handler->html_input(['label' => 'Aktivno od', 'name' => 'datum_od', 'value' => (!empty($data->datum_od)) ? $data->datum_od : '', 'required' => true, 'datepicker' => true]); ?>
                    <?php $handler->html_input(['label' => 'Aktivno do', 'name' => 'datum_do', 'value' => (!empty($data->datum_do)) ? $data->datum_do : '', 'required' => true, 'datepicker' => true]); ?>
                    <?php $handler->html_checkbox(['label' => 'Omejitev na uporabnika', 'name' => 'omejitev_uporabnik', 'status' => (!empty($data->omejitev_uporabnik))]); ?>
                </div>
                <div class="col-lg-5">
                    <?php $handler->html_select2(['id' => 'povezani_artikli', 'label' => 'Pogojni artikli', 'name' => 'povezani_artikli[]', 'multiple' => true, 'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect', 'get_selected' => ['id' => (!empty($data->id)) ? $data->id : 0, 'c' => 'Artikel', 'm' => 'getPovezaniSelected', 'tip' => 5]]); ?>
                    <?php $handler->html_input(['label' => 'Za izpolnitev pogoja mora biti v košarici vsaj toliko pogojnih artiklov <small>(0 - upoštevaj vse)</small>', 'name' => 'st_pogojnih_artiklov', 'custom' => 'style="width:50px;"', 'value' => (!empty($data->st_pogojnih_artiklov)) ? $data->st_pogojnih_artiklov : '0', 'required' => true]); ?>
                </div>
                <div class="col-lg-5">
                    <?php $handler->html_select2(['id' => 'povezani_artikli2', 'label' => 'Nagradni artikli', 'name' => 'povezani_artikli2[]', 'multiple' => true, 'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect', 'get_selected' => ['id' => (!empty($data->id)) ? $data->id : 0, 'c' => 'Artikel', 'm' => 'getPovezaniSelected', 'tip' => 6]]); ?>
                    <?php //$handler->html_input(['label' => 'Popust (%)', 'name' => 'popust', 'custom' => 'style="width:50px;"', 'value' => (!empty($data->popust)) ? $data->popust : '', 'required' => true]); ?>
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
