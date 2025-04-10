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
                <div class="col-lg-6">
                    <?php $handler->html_input(['label' => 'Aktivno od', 'name' => 'datum_od', 'value' => (!empty($data->datum_od)) ? $data->datum_od : '', 'required' => true, 'datepicker' => true]); ?>
                    <?php $handler->html_input(['label' => 'Aktivno do', 'name' => 'datum_do', 'value' => (!empty($data->datum_do)) ? $data->datum_do : '', 'required' => true, 'datepicker' => true]); ?>
                    <?php $handler->html_select2(['id' => 'sticker_id', 'label' => 'Sticker', 'name' => 'sticker_id', 'url' => '/webapp/s2?c=Sticker&m=getSelect', 'get_selected' => ['id' => (!empty($data->sticker_id)) ? $data->sticker_id : 0, 'c' => 'Sticker', 'm' => 'getSelected']]); ?>
                    <?php $handler->html_input(['label' => 'Popust (%)', 'name' => 'odstotek', 'value' => (!empty($data->odstotek)) ? $data->odstotek : '', 'class' => 'small', 'required' => false]); ?>
                    <small>Popust se upošteva v primeru, da ni izbran noben sticker</small>
                    <?php //$handler->html_input(['label' => 'Besedilo po meri', 'name' => 'text', 'value' => (!empty($data->text)) ? $data->text : '', 'class' => 'small', 'required' => false]); ?>
                </div>
                <div class="col-lg-6">
                    <?php $handler->html_select2(['id' => 'povezani_artikli', 'label' => 'Artikli', 'name' => 'povezani_artikli[]', 'multiple' => true, 'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect', 'get_selected' => ['id' => (!empty($data->id)) ? $data->id : 0, 'c' => 'Artikel', 'm' => 'getPovezaniSelected', 'tip' => 4]]); ?> 
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
