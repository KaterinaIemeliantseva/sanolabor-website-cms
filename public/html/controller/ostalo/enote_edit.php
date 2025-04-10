<?php
include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');
?>
<div class="content-box-header">
    <h3><?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
 	<ul class="content-box-tabs">
 	    <li><a href="#tab1" class="default-tab current">Uredi</a></li>
        <?php if($data && isset($galerija)): ?><li><a href="#tab3" class="">Galerija</a></li><?php endif; ?>
 	</ul>
    <div class="clear"></div>
</div>
<div class="content-box-content">
    <div class="tab-content default-tab active" id="tab1">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="save" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-6">
                    <?php if($data): ?><?php $handler->html_label(['label' => 'Slug', 'value' => (!empty($data->nicename)) ? $data->nicename : '']); ?><?php endif; ?>
                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Naslov', 'name' => 'naslov', 'value' => (!empty($data->naslov)) ? $data->naslov : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Vodja', 'name' => 'vodja', 'value' => (!empty($data->vodja)) ? $data->vodja : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Telefon', 'name' => 'telefon', 'value' => (!empty($data->telefon)) ? $data->telefon : '', 'required' => false, 'class' => 'small']); ?>
                    <?php //$handler->html_editor(['label' => 'Konkakt', 'name' => 'kontakt', 'value' => (!empty($data->kontakt)) ? $data->kontakt : '']); ?>
                    <?php $handler->html_input(['label' => 'Zemljevid', 'name' => 'zemljevid', 'value' => (!empty($data->zemljevid)) ? $data->zemljevid : '', 'required' => false]); ?>
                    <?php $handler->html_select2(['id' => 'storitve', 'label' => 'Storitve', 'name' => 'storitve[]', 'multiple' => true, 'url' => '/webapp/s2?c=Enote&m=getStoritveSelect', 'get_selected' => ['id' => (!empty($data->id)) ? $data->id : 0, 'c' => 'Enote', 'm' => 'getStoritveSelected']]); ?>
                    <?php $handler->html_select2(['id' => 'tip_enote', 'label' => 'Tip enote', 'name' => 'tip_enote[]', 'multiple' => true, 'url' => '/webapp/s2?c=Enote&m=getTipSelect', 'get_selected' => ['id' => (!empty($data->id)) ? $data->id : 0, 'c' => 'Enote', 'm' => 'getTipSelected']]); ?>
                    
                    <?php $handler->html_input(['label' => 'Meta naslov', 'name' => 'meta_title', 'value' => (!empty($data->meta_title)) ? $data->meta_title : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Meta opis', 'name' => 'meta_desc', 'value' => (!empty($data->meta_desc)) ? $data->meta_desc : '', 'required' => false]); ?>
                  

                </div>
                <div class="col-lg-6">
                    <?php $handler->html_editor(['label' => 'Odpiralni čas', 'name' => 'odpiralni_cas', 'height' => '130', 'value' => (!empty($data->odpiralni_cas)) ? $data->odpiralni_cas : '']); ?>
                    <hr />
                    <?php $handler->html_editor(['label' => 'Posebni odpiralni čas', 'name' => 'posebni_odpiralni_cas', 'height' => '130', 'value' => (!empty($data->posebni_odpiralni_cas)) ? $data->posebni_odpiralni_cas : '']); ?>
                    
                    <div class="row no-border">
                        <div class="col-lg-2 col-lg-offset-1 no-border"><?php $handler->html_input(['label' => 'od', 'name' => 'datum_veljavno_od', 'value' => (!empty($data->datum_veljavno_od)) ? $data->datum_veljavno_od : '', 'required' => false, 'datepicker' => true]); ?></div>
                        <div class="col-lg-2 no-border"><?php $handler->html_input(['label' => 'do', 'name' => 'datum_veljavno_do', 'value' => (!empty($data->datum_veljavno_do)) ? $data->datum_veljavno_do : '', 'required' => false, 'datepicker' => true]); ?></div>
                    </div>
                    <?php  $handler->html_checkbox(['label' => 'Aktivno', 'name' => 'posebni_odpiralni_cas_aktivno', 'status' => (!empty($data->posebni_odpiralni_cas_aktivno))]); ?>
                    <hr />
                    <?php $handler->html_editor(['label' => 'Obvestilo', 'name' => 'obvestilo', 'height' => '130', 'value' => (!empty($data->obvestilo)) ? $data->obvestilo : '']); ?>
                    <?php $handler->html_single_file_upload(['label' => 'Slika', 'name' => 'slika', 'value' => (!empty($data->slika)) ? $data->slika : '', 'required' => false]); ?>
                    <?php //$handler->html_input(['label' => 'Posebno obvestilo', 'name' => 'del_cas', 'value' => (!empty($data->del_cas)) ? $data->del_cas : '', 'required' => false]); ?>
                    <?php //$handler->html_checkbox(['label' => 'Posebni delovni čas', 'name' => 'del_cas2_status', 'status' => (!empty($data->del_cas2_status))]); ?>
                    <?php //$handler->html_editor(['label' => '', 'name' => 'del_cas2', 'value' => (!empty($data->del_cas2)) ? $data->del_cas2 : '']); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php //$handler->html_checkbox(['label' => 'Izdaja homeopatskega zdravila', 'name' => 'homeopatija', 'status' => (!empty($data->homeopatija))]); ?>
                    <?php  //$handler->html_checkbox(['label' => 'Prikaži na seznamu poslovalnic', 'name' => 'lekarna', 'status' => (!empty($data->lekarna))]); ?>
                    <?php  $handler->html_checkbox(['label' => 'Skrij na seznamu poslovalnic', 'name' => 'skrij_splet', 'status' => (!empty($data->skrij_splet))]); ?><br />
                    <?php //$handler->html_checkbox(['label' => 'Dežurna lekarna', 'name' => 'dezurna', 'status' => (!empty($data->dezurna))]); ?>
                    <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>
                </div>
            </div>

            <?php $handler->html_input_hidden( ['name' => 'nicename', 'value' => (!empty($data->nicename)) ? $data->nicename : '']); ?>
            <?php $handler->html_save_button($data); ?>
        </form>
    </div>



    <?php if($data && isset($galerija)): ?>
    <div class="tab-content hide" id="tab3">
        <?php
        $opt = ['id' => '1', 'type' => '3', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=enote'];
        $handler->html_file_upload($opt); ?>
        <?php $handler->html_save_button($data, ['close' => true]); ?>
    </div>
    <?php endif; ?>
 </div>
