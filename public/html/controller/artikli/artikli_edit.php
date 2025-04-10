<?php
include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');
$bal = new ArtikelRevizijaBAL();  
if(isset($data->id)) {
    $reg_skupina = $bal->getRegulatornaSkupina($data->id);
} 
?>
<style>
    .custom_header {
        height: auto !important; 
    }
    .custom_header ul {
        top: unset !important;
        bottom: -1px !important;
    }
    .custom_header h3 {
        margin-bottom: 0px;
    }
</style>
<div class="content-box-header custom_header">
    <h3><?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
 	<ul class="content-box-tabs">
 	    <li><a href="#tab1" class="default-tab current">Osnovno</a></li>
        <?php if($data): ?><li><a href="#tab5" class="">Šifre</a></li><?php endif; ?>
        <?php if($data): ?><li><a href="#tab4" class="">Zavihki</a></li><?php endif; ?>
 	    <?php if($data): ?><li><a href="#tab3" class="">Kategorija</a></li><?php endif; ?>
        <?php if($data): ?><li><a id="galery_tab" href="#tab2" class="">Galerija</a></li><?php endif; ?>
 	</ul>
     <?php if(isset($reg_skupina)): ?>
        <p style="padding: 0; font-weight: bold;">Regulatorna skupina:  <span style="color: #28a745; maring: 0;"><?php echo $reg_skupina; ?></span></p>
    <?php endif; ?>
    <div class="clear"></div>
</div>

<div class="content-box-content">
<div class="tab-content default-tab active" id="tab1">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="save" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-6">
                    <?php if($data):  ?>
                    <?php $handler->html_label(['label' => 'Povezava', 'value' => (!empty($data->nicename)) ? '<a target="_blank" href="https://www.sanolabor.si/artikel/'.$data->nicename.'?force_show=1">https://www.sanolabor.si/artikel/<strong>'.$data->nicename.'</strong></a>' : '']); ?>
                    <?php endif; ?>
                    <?php if(!empty($data->id)): ?>
                        <p>
                            <a href="#" id="update_cene_zaloga" data-sifra="<?php echo $data->id; ?>" class="btn btn-success button ">Posodobi ceno in zalogo</a> &nbsp;&nbsp;<img src="/public/resources/images/ajax-loader.gif" class="ajaxLoaderUpdateZaloga hide" />
                        
                            <!-- <a class="button" id="posodobi-artikel" data-options='{"c":"UpdateCene", "m":"posodobiCenik", "id":"<?=$post['id']?>", "out":"posodobi-artikel-out"}'>Posodobi cene	in zalogo</a> -->
                            <img src="/admin/public/resources/images/ajax-loader.gif" class="ajaxLoader1 hide" />
                            <div style="display:none;" id="posodobi-artikel-out"></div>
                            <small>Pred klikom na gumb shranite vse spremembe. Proces lahko traja nekaj časa.</small>
                        </p>
                    <?php endif; ?>
                   
                    <div class="row">
                    <div class="col-lg-7">
                        <?php $handler->html_single_file_upload(['label' => 'Prikazna slika', 'name' => 'prikazna_slika', 'value' => (!empty($data->prikazna_slika)) ? $data->prikazna_slika : '']); ?>
                    </div>
                         <div class="col-lg-5 ">
                            <?php $handler->html_checkbox(['label' => 'Brez datumske omejitve', 'name' => 'uporabi_prikazno_sliko', 'status' => (!empty($data->uporabi_prikazno_sliko))]); ?>
                            <?php $handler->html_checkbox(['label' => 'Datumska omejitev prikazne slike', 'name' => 'prikazna_slika_od_do', 'status' => (!empty($data->prikazna_slika_od_do))]); ?>
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">
                                        <?php $handler->html_label(['label' => 'Od:', 'value' => '']);  ?>
                                    </td>
                                    <td style="padding-left:0;">
                                        <?php $handler->html_input(['name' => 'prikazna_slika_datum_od', 'value' => (!empty($data->prikazna_slika_datum_od)) ? $data->prikazna_slika_datum_od : '', 'required' => false, 'datepicker' => true]); ?>
                                    </td>
                                    </tr>
                                    <tr>
                                    <td style="vertical-align: middle;">
                                        <?php $handler->html_label(['label' => 'Do:', 'value' => '']);  ?>
                                    </td>
                                    <td style="padding-left:0px;">
                                        <?php $handler->html_input(['name' => 'prikazna_slika_datum_do', 'value' => (!empty($data->prikazna_slika_datum_do)) ? $data->prikazna_slika_datum_do : '', 'required' => false, 'datepicker' => true]); ?>
                                    </td>
                                </tr>
                            </table>
                           
                        </div>
                    </div>


                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>

                    
                    <div class="row no-border">
                        <div class="col-lg-2 col-lg-offset-1 no-border"><?php $handler->html_input(['label' => 'Število kosov', 'type' => 'number', 'custom' => 'style="width:100% !important;" min="0"', 'name' => 'st_kosov', 'value' => (!empty($data->st_kosov)) ? $data->st_kosov : '', 'class' => 'small', 'required' => true]); ?></div>
                        <div class="col-lg-0 no-border"><?php //$handler->html_input(['label' => 'Pakiranje', 'name' => 'pakiranje', 'value' => (!empty($data->pakiranje)) ? $data->pakiranje : '', 'class' => '', 'required' => false]); ?></div>
                        <div class="col-lg-2 no-border"><?php $handler->html_input(['label' => 'Omejitev naročila', 'type' => 'number', 'custom' => 'style="width:100% !important;" min="0"', 'name' => 'omejitev_narocila', 'value' => (!empty($data->omejitev_narocila) && (int)$data->omejitev_narocila > 0) ? $data->omejitev_narocila : 0, 'class' => 'small', 'required' => true]); ?></div>
                    </div>

                    <?php  $handler->html_select2(['id' => 'blagovna_znamka', 'label' => 'Blagovna znamka', 'name' => 'blagovna_znamka', 'url' => '/webapp/select2?table=blagovna_znamka', 'get_single' => ['id' => (!empty($data->blagovna_znamka)) ? $data->blagovna_znamka : 0, 'table' => 'blagovna_znamka']]); ?>
                    
                    <!-- Old version -->
                    <?php  // $handler->html_select2(['id' => 'dobavitelj_id', 'label' => 'Dobavitelj', 'name' => 'dobavitelj_id', 'url' => '/webapp/select2?table=dobavitelj', 'get_single' => ['id' => (!empty($data->dobavitelj_id)) ? $data->dobavitelj_id : 0, 'table' => 'dobavitelj']]); ?>
                    
                    <!-- New version -->
                    <?php  $handler->html_select2(['id' => 'dobavitelj_id', 'label' => 'Dobavitelj', 'name' => 'dobavitelj_id', 'url' => '/webapp/select2?table=dobavitelj_users', 'get_single' => ['id' => (!empty($data->dobavitelj_id)) ? $data->dobavitelj_id : 0, 'table' => 'dobavitelj_users', 'dobavitelj_name' => (isset($data->dobavitelj_id)) ? $foo->getDobaviteljName($data->dobavitelj_id) : '']]); ?>

                    <?php $handler->html_select2(['id' => 'povezani_artikli', 'label' => 'Povezani artikli',  'name' => 'povezani_artikli[]', 'multiple' => true, 'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect', 'get_selected' => ['id' => (!empty($data->id)) ? $data->id : 0, 'c' => 'Artikel', 'm' => 'getPovezaniSelected', 'tip' => 1]]); ?>
                    <?php $handler->html_select2(['id' => 'certifikati', 'label' => 'Certifikati', 'name' => 'certifikati[]', 'multiple' => true, 'url' => '/webapp/select2?table=certifikati', 'get_list' =>  ['id' => (!empty($data->id)) ? $data->id : 0, 'table1' => 'artikel_certifikat', 'table2' => 'certifikati', 'field1' => 'id_certifikat', 'field2' => 'id_artikel']]); ?>
                    <?php $handler->html_tags(['id' => (!empty($data->id)) ? $data->id : 0, 'tip' => 3]); ?>
                    <?php 
                        if(!empty($data->skladisce))
                        $enote = $foo->getEnote(['sifra' => $data->skladisce]);
                        $handler->html_select2(['id' => 'skladisce', 'label' => 'Enota za naročila', 'name' => 'skladisce', 'url' => '/webapp/select2?table=enota', 'get_single' => ['id' => (!empty($enote['id'])) ? $enote['id'] : 0, 'table' => 'enota']]);
                    ?> 
                    <?php  $handler->html_select2(['id' => 'piktogram', 'label' => 'Piktogram', 'name' => 'piktogram', 'url' => '/webapp/select2?table=piktogram', 'get_single' => ['id' => (!empty($data->piktogram)) ? $data->piktogram : 0, 'table' => 'piktogram']]); ?> 
                </div>
                <div class="col-lg-6">
                    <?php $handler->html_editor(['label' => 'Kratki opis', 'name' => 'kratki_opis', 'value' => (!empty($data->kratki_opis)) ? $data->kratki_opis : '', 'required' => false, 'height' => 100]); ?>
                
                    <?php //$handler->html_select2(['id' => 'blagovna_znamka', 'label' => 'Blagovna znamka', 'name' => 'blagovna_znamka', 'url' => '/webapp/select2?table=blagovna_znamka', 'get_single' => ['id' => (!empty($data->blagovna_znamka)) ? $data->blagovna_znamka : 0, 'table' => 'blagovna_znamka']]); ?>  
                    <div class="row">
                        <div class="col-lg-4 ">
                            <?php $handler->html_checkbox(['label' => 'Izpostavljeni artikel', 'name' => 'top_artikel', 'status' => (!empty($data->top_artikel))]); ?><br />
                            <hr />
                            <?php $handler->html_checkbox(['label' => 'Datumska omejitev', 'name' => 'top_artikel_od_do', 'status' => (!empty($data->priporocamo_status_od_do))]); ?>
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">
                                        <?php // $handler->html_checkbox(['label' => 'Od:', 'name' => 'top_artikel_status_od', 'status' => (!empty($data->top_artikel_status_od))]); ?>
                                        <?php $handler->html_label(['label' => 'Od:', 'value' => '']);  ?>
                                    </td>
                                    <td style="padding-left:0;">
                                        <?php $handler->html_input(['name' => 'top_artikel_datum_od', 'value' => (!empty($data->top_artikel_datum_od)) ? $data->top_artikel_datum_od : '', 'required' => false, 'datepicker' => true]); ?>
                                    </td>
                                    </tr>
                                    <tr>
                                    <td style="vertical-align: middle;">
                                        <?php //$handler->html_checkbox(['label' => 'Do:', 'name' => 'top_artikel_status_do', 'status' => (!empty($data->top_artikel_status_do))]); ?>
                                        <?php $handler->html_label(['label' => 'Do:', 'value' => '']);  ?>
                                    </td>
                                    <td style="padding-left:0px;">
                                        <?php $handler->html_input(['name' => 'top_artikel_datum_do', 'value' => (!empty($data->top_artikel_datum_do)) ? $data->top_artikel_datum_do : '', 'required' => false, 'datepicker' => true]); ?>
                                    </td>
                                </tr>
                            </table>
                           
                        </div>
                        <div class="col-lg-4 ">
                            <?php $handler->html_checkbox(['label' => 'Izbrano za vas', 'name' => 'izbrano_za_vas', 'status' => (!empty($data->izbrano_za_vas))]); ?><br />
                            <hr />
                            <?php $handler->html_checkbox(['label' => 'Datumska omejitev', 'name' => 'izbrano_za_vas_od_do', 'status' => (!empty($data->izbrano_za_vas_od_do))]); ?>
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">
                                        <?php //$handler->html_checkbox(['label' => 'Od:', 'name' => 'izbrano_za_vas_status_od', 'status' => (!empty($data->izbrano_za_vas_status_od))]); ?>
                                        <?php $handler->html_label(['label' => 'Od:', 'value' => '']);  ?>
                                    </td>
                                    <td style="padding-left:0; vertical-align: middle;">
                                        <?php $handler->html_input(['name' => 'izbrano_za_vas_datum_od', 'value' => (!empty($data->izbrano_za_vas_datum_od)) ? $data->izbrano_za_vas_datum_od : '', 'required' => false, 'datepicker' => true]); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;" >
                                        <?php //$handler->html_checkbox(['label' => 'Do:', 'name' => 'izbrano_za_vas_status_do', 'status' => (!empty($data->izbrano_za_vas_status_do))]); ?>
                                        <?php $handler->html_label(['label' => 'Do:', 'value' => '']);  ?>
                                    </td>
                                    <td style="padding-left:0px; vertical-align: middle;">
                                        <?php $handler->html_input(['name' => 'izbrano_za_vas_datum_do', 'value' => (!empty($data->izbrano_za_vas_datum_do)) ? $data->izbrano_za_vas_datum_do : '', 'required' => false, 'datepicker' => true]); ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-lg-4 ">
                            <?php $handler->html_checkbox(['label' => 'Novo v ponudbi', 'name' => 'novo_v_ponudbi', 'status' => (isset($data->novo_v_ponudbi) ? !empty($data->novo_v_ponudbi) : true)]); ?><br />
                            <hr />
                            <?php $handler->html_checkbox(['label' => 'Datumska omejitev', 'name' => 'novo_v_ponudbi_od_do', 'status' => (isset($data->novo_v_ponudbi_od_do) ? !empty($data->novo_v_ponudbi_od_do) : true)]); ?>
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">
                                        <?php //$handler->html_checkbox(['label' => 'Od:', 'name' => 'novo_v_ponudbi_status_od', 'status' => (!empty($data->novo_v_ponudbi_status_od))]); ?>
                                        <?php $handler->html_label(['label' => 'Od:', 'value' => '']);  ?>
                                    </td>
                                    <td style="padding-left:0;">
                                        <?php $handler->html_input(['name' => 'novo_v_ponudbi_datum_od', 'value' => (!empty($data->novo_v_ponudbi_datum_od)) ? $data->novo_v_ponudbi_datum_od : '', 'required' => false, 'datepicker' => true]); ?>
                                    </td>
                                    </tr>
                                    <tr>
                                    <td style="vertical-align: middle;">
                                        <?php //$handler->html_checkbox(['label' => 'Do:', 'name' => 'novo_v_ponudbi_status_do', 'status' => (!empty($data->novo_v_ponudbi_status_do))]); ?>
                                        <?php $handler->html_label(['label' => 'Do:', 'value' => '']);  ?>
                                    </td>
                                    <td style="padding-left:0px;">
                                        <?php 
                                            $current_date = date("Y-m-d");
                                            $new_date = date("Y-m-d", strtotime($current_date . "+30 days")); 
                                        ?>
                                        <?php $handler->html_input(['name' => 'novo_v_ponudbi_datum_do', 'value' => (!empty($data->novo_v_ponudbi_datum_do)) ? $data->novo_v_ponudbi_datum_do : $new_date, 'required' => false, 'datepicker' => true]); ?>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-lg-4 ">
                            <?php $handler->html_checkbox(['label' => 'Aktualne ugodnosti', 'name' => 'aktualne_ugodnosti', 'status' => (!empty($data->aktualne_ugodnosti))]); ?><br />
                            <hr />
                            <?php $handler->html_checkbox(['label' => 'Datumska omejitev', 'name' => 'aktualne_ugodnosti_od_do', 'status' => (!empty($data->priporocamo_status_od_do))]); ?>
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">
                                        <?php //$handler->html_checkbox(['label' => 'Od:', 'name' => 'aktualne_ugodnosti_status_od', 'status' => (!empty($data->aktualne_ugodnosti_status_od))]); ?>
                                        <?php $handler->html_label(['label' => 'Od:', 'value' => '']);  ?>
                                    </td>
                                    <td style="padding-left:0;">
                                        <?php $handler->html_input(['name' => 'aktualne_ugodnosti_datum_od', 'value' => (!empty($data->aktualne_ugodnosti_datum_od)) ? $data->aktualne_ugodnosti_datum_od : '', 'required' => false, 'datepicker' => true]); ?>
                                    </td>
                                    </tr>
                                    <tr>
                                    <td style="vertical-align: middle;">
                                        <?php //$handler->html_checkbox(['label' => 'Do:', 'name' => 'aktualne_ugodnosti_status_do', 'status' => (!empty($data->aktualne_ugodnosti_status_do))]); ?>
                                        <?php $handler->html_label(['label' => 'Do:', 'value' => '']);  ?>
                                    </td>
                                    <td style="padding-left:0px;">
                                        <?php $handler->html_input(['name' => 'aktualne_ugodnosti_datum_do', 'value' => (!empty($data->aktualne_ugodnosti_datum_do)) ? $data->aktualne_ugodnosti_datum_do : '', 'required' => false, 'datepicker' => true]); ?>
                                    </td>
                                </tr>
                            </table>
                        </div>


                        <div class="col-lg-4 ">
                            <?php $handler->html_checkbox(['label' => 'Priporočamo', 'name' => 'priporocamo', 'status' => (!empty($data->priporocamo))]); ?><br />
                            <hr />
                            <?php $handler->html_checkbox(['label' => 'Datumska omejitev', 'name' => 'priporocamo_status_od_do', 'status' => (!empty($data->priporocamo_status_od_do))]); ?>
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">
                                        <?php //$handler->html_checkbox(['label' => 'Od:', 'name' => 'priporocamo_status_od', 'status' => (!empty($data->priporocamo_status_od))]); ?>
                                        <?php $handler->html_label(['label' => 'Od:', 'value' => '']);  ?>
                                    </td>
                                    <td style="padding-left:0;">
                                        <?php $handler->html_input(['name' => 'priporocamo_datum_od', 'value' => (!empty($data->priporocamo_datum_od)) ? $data->priporocamo_datum_od : '', 'required' => false, 'datepicker' => true]); ?>
                                    </td>
                                    </tr>
                                    <tr>
                                    <td style="vertical-align: middle;">
                                        <?php //$handler->html_checkbox(['label' => 'Do:', 'name' => 'priporocamo_status_do', 'status' => (!empty($data->priporocamo_status_do))]); ?>
                                        <?php $handler->html_label(['label' => 'Do:', 'value' => '']);  ?>
                                    </td>
                                    <td style="padding-left:0px;">
                                        <?php $handler->html_input(['name' => 'priporocamo_datum_do', 'value' => (!empty($data->priporocamo_datum_do)) ? $data->priporocamo_datum_do : '', 'required' => false, 'datepicker' => true]); ?>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
         
                  
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-5" style="outline: none">
                        <?php $handler->html_checkbox(['label' => 'Onemogoči naročilo v tujino', 'name' => 'onemogoci_tujino', 'status' => (!empty($data->onemogoci_tujino))]); ?><br />
                        <?php $handler->html_checkbox(['label' => 'Onemogoči nakup po povzetju', 'name' => 'onemogoci_nakup_po_povzetju', 'status' => (!empty($data->onemogoci_nakup_po_povzetju))]); ?><br />
                        <?php $handler->html_checkbox(['label' => 'Onemogoči nakup', 'name' => 'onemogoci_nakup', 'status' => (!empty($data->onemogoci_nakup))]); ?><br /> 

                        <?php //if ( $_SESSION['userSessionValue'] ==  94 || $_SESSION['userSessionValue'] == 96): ?>
                        <?php $handler->html_checkbox(['label' => 'Onemogoči nakup za pravne osebe', 'name' => 'onemogoci_nakup_za_pravne_osebe', 'status' => (!empty($data->onemogoci_nakup_za_pravne_osebe))]); ?><br /> 
                        <?php //endif; ?>

                        <?php $handler->html_checkbox(['label' => 'Onemogoči osebni prevzem', 'name' => 'onemogoci_osebni_prevzem', 'status' => (!empty($data->onemogoci_osebni_prevzem))]); ?><br />               
                        <?php $handler->html_checkbox(['label' => 'Brezplačna montaža', 'name' => 'brezplacna_montaza', 'status' => (!empty($data->brezplacna_montaza))]); ?><br />
                        <?php $handler->html_checkbox(['label' => 'Zdravilo', 'name' => 'zdravilo', 'status' => (!empty($data->zdravilo))]); ?><br />
                        <?php $handler->html_checkbox(['label' => 'Veterinarsko zdravilo', 'name' => 'veterinarsko_zdravilo', 'status' => (!empty($data->veterinarsko_zdravilo))]); ?><br />
                        <?php $handler->html_checkbox(['label' => 'Black Friday', 'name' => 'black_friday', 'status' => (!empty($data->black_friday))]); ?><br />
                        <?php $handler->html_checkbox(['label' => 'Artikel v skladišču', 'name' => 'artikel_skladisce', 'status' => (!empty($data->artikel_skladisce))]); ?> <br />
                        <?php 
                            if($data)
                            {
                                $artikel_sifra = $foo->getArtikelSifra($data->id);              
                                $artikel_cena = $foo->getArtikelCena($artikel_sifra[0]['sifra']);                
                                $handler->html_checkbox(['label' => 'Prikaži artikel brez zaloge', 'name' => 'zaloga_izjema', 'status' => (!empty($artikel_cena['zaloga_izjema']))]); 
                            }
                        ?><br />
                        <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>
                        <?php $handler->html_input_hidden( ['name' => 'nicename', 'value' => (!empty($data->nicename)) ? $data->nicename : '']); ?>
                        </div>

                        <div class="col-md-7" style="outline: none">
                        <?php 
                            if($data)
                            {
                                $artikel_sifra = $foo->getArtikelSifra($data->id);              
                                $artikel_cena = $foo->getArtikelCena($artikel_sifra[0]['sifra']);                
                                $handler->html_checkbox(['label' => 'Odstrani spletni popust <small>(Spremembe veljajo po posodobitvi cene.)</small>', 'name' => 'ni_popusta', 'status' => (!empty($artikel_cena['ni_popusta']))]); 
                            }
                        ?><br />
                            <?php $handler->html_checkbox(['label' => 'Arhiv', 'name' => 'arhiv', 'status' => (!empty($data->arhiv))]); ?><br />
                            <label for="opomba">Opomba</label>
                            <textarea class="form-control" style="width: 100% !important" name="opomba"><?= (!empty($data->opomba))? $data->opomba : '' ?></textarea>
                        </div>
                    </div>
                </div> 
                <div class="col-lg-6">
                    <?php $handler->html_input(['label' => 'Onemogoči nakup - opis', 'name' => 'onemogoci_nakup_opis', 'value' => (!empty($data->onemogoci_nakup_opis)) ? $data->onemogoci_nakup_opis : '']); ?>
                </div>  

            </div>
            <?php $handler->html_save_button($data); ?>
        </form>
    </div>

    <?php if($data): ?>
    <div class="tab-content hide" id="tab2">
        
        <?php
        $opt = ['id' => '1', 'type' => '1', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=artikli'];
        $handler->html_file_upload($opt); ?>

        <?php $handler->html_save_button($data, ['close' => true]); ?>
    </div>
    <?php endif; ?>

    <?php if($data ): ?>
    <div class="tab-content hide" id="tab3">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="shraniRavrstitev" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-12 nastavitev_kategorije_main ">
                    <div class="nastavitev_kategorije_wrapper">
                    <?php
                        $foo->getKategorijeRazvrstiIzpis(0, 1, $data->id);
                    ?>
                    </div>
                </div>
            </div>
        <?php $handler->html_save_button($data); ?>
        </form>
    </div>
    <?php endif; ?>


    <?php if($data ): ?>
    <div class="tab-content hide" id="tab4">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="update" method="post" class="edit_form_validate form-group">
            <div class="subtabs">
              <ul>
                <li><a href="#tabs-1">Podrobno o izdelku</a></li>
                <li><a href="#tabs-2">Navodila za uporabo</a></li>
                <li><a href="#tabs-3">Sestavine</a></li>
                <li><a href="#tabs-4">Tabela velikosti</a></li>
                <li><a href="#tabs-5">Tehnična dokumentacija</a></li>
                <li><a href="#tabs-6">Opozorila</a></li>
                <li><a href="#tabs-7">Dokumenti</a></li>
               
              </ul>
              <div id="tabs-1">
                  <?php $handler->html_editor(['label' => '', 'name' => 'vsebina', 'value' => (!empty($data->vsebina)) ? $data->vsebina : '']); ?>
                  <?php //$handler->html_file_upload(['id' => '10', 'type' => '8', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=dokumenti']); ?>
                  <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'vsebina_status', 'status' => (!empty($data->vsebina_status))]); ?>
              </div>
              <div id="tabs-2">
                  <?php $handler->html_editor(['label' => '', 'name' => 'navodila', 'value' => (!empty($data->navodila)) ? $data->navodila : '']); ?>
                  <?php $handler->html_file_upload(['id' => '11', 'type' => '18', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=dokumenti']); ?>
                  <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'navodila_status', 'status' => (!empty($data->navodila_status))]); ?>
              </div>
              <div id="tabs-3">
                  <?php $handler->html_editor(['label' => '', 'name' => 'sestava', 'value' => (!empty($data->sestava)) ? $data->sestava : '']); ?>
                  <?php //$handler->html_file_upload(['id' => '12', 'type' => '10', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=dokumenti']); ?>
                  <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'sestava_status', 'status' => (!empty($data->sestava_status))]); ?>
              </div>
              <div id="tabs-4">
                  <?php $handler->html_editor(['label' => '', 'name' => 'mere', 'value' => (!empty($data->mere)) ? $data->mere : '']); ?>
                  <?php $handler->html_file_upload(['id' => '13', 'type' => '22', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=dokumenti']); ?>
                  <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'mere_status', 'status' => (!empty($data->mere_status))]); ?>
              </div>
              <div id="tabs-5">
                  <?php $handler->html_editor(['label' => '', 'name' => 'tehnicna_dokumentacija', 'value' => (!empty($data->tehnicna_dokumentacija)) ? $data->tehnicna_dokumentacija : '']); ?>
                  <?php $handler->html_file_upload(['id' => '14', 'type' => '12', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=dokumenti']); ?>
                  <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'tehnicna_dokumentacija_status', 'status' => (!empty($data->tehnicna_dokumentacija_status))]); ?>
              </div>
              <div id="tabs-6">
                  <?php $handler->html_editor(['label' => '', 'name' => 'opozorilo', 'value' => (!empty($data->opozorilo)) ? $data->opozorilo : '']); ?>
                  <?php //$handler->html_file_upload(['id' => '14', 'type' => '10', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=dokumenti']); ?>
                  <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'opozorilo_status', 'status' => (!empty($data->opozorilo_status))]); ?>
              </div>
              <div id="tabs-7">
                <div style="padding: 15px 20px 10px 20px; border: 1px solid #aaaaaa;">
                  <h4 style="color: #28a745;">Dokumenti novega tipa</h4>
                  <h5>Deklaracija</h5>
                  <?php $handler->html_file_upload(['id' => '15', 'type' => '17', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=dokumenti']); ?>
                  <hr style="margin: 20px 0px;">
                  <h5>Varnostni list</h5>
                  <?php $handler->html_file_upload(['id' => '16', 'type' => '21', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=dokumenti']); ?>
                  <hr style="margin: 20px 0px;">
                  <h5>EU certifikat skladnosti</h5>
                  <?php $handler->html_file_upload(['id' => '17', 'type' => '20', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=dokumenti']); ?>
                  <hr style="margin: 20px 0px;">
                  <h5>Izjava EU skladnosti</h5>
                  <?php $handler->html_file_upload(['id' => '18', 'type' => '19', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=dokumenti']); ?>
                </div>
                <div style="padding: 15px 20px 10px 20px; border: 1px solid #aaaaaa; margin-top: 20px;">
                  <h4 style="color: #28a745;">Dokumenti starega tipa</h4>
                  <h5>Navodila za uporabo</h5>
                  <?php $handler->html_file_upload(['id' => '19', 'type' => '15', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=dokumenti']); ?>
                </div>
              </div>
            </div>
            <?php $handler->html_save_button($data); ?>
        </form>
    </div>
    <?php endif; ?>

    <?php if($data): ?>
    <div class="tab-content hide" id="tab5">
        <div class="row">
        <?php 
        $parameter_types = $foo->getParameterTypes();
        if($parameter_types)
        {
            foreach ($parameter_types as $key => $type) 
            {
                ?>
                <div class="col-lg-3 no-border">
                    <?php $handler->html_select2(['label' => $type['naziv'], 'id' => 'k_parameter_'.$type['id'], 'multiple' => true, 'name' => 'k_parameter_'.$type['id'], 
                    'url' => '/webapp/select2?table=artikel_parameter&where=tip_id='.$type['id']
                    ,'get_list' =>  [
                        'id' => (!empty($data->id)) ? $data->id : 0, 
                        'table1' => 'artikel_sifra_parameter', 
                        'table2' => 'artikel_parameter', 
                        'field1' => 'parameter_id', 
                        'field2' => 'artikel_id',
                        'where' => ' and artikel_parameter.tip_id = '.$type['id'].' ',
                    ]
                    ]); ?> 
                </div>
                <?php
            }
        }
        ?>
        </div>

        <button data-id="<?php echo $data->id; ?>" style=" margin-top: 10px;" id="k_add" class="btn btn-success button ">Posodobi seznam</button>

        <hr />
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="updateItemCode" method="post" class="edit_form_validate form-group">
        <div class="row">
            <div class="col-lg-12  no-border">
                <table  class="display dataTable " cellspacing="0" width="100%">
					<thead>
						<tr>
							<th style="width:90px;">Šifra</th>
                            <th style="width:160px;">EAN</th>
							<th style="">Parametri</th>
							<th style="width:1px;"></th>
						</tr>
					</thead>
					<tbody id="parameters_list">
					</tbody>
				</table>
                <div style="text-align:center; padding:20px;"><img id="loader" src="/public/resources/images/loader2.gif"></div>
            </div>

 
        </div>
        <?php $handler->html_save_button($data); ?>
        </form>
    </div>

        

    <?php endif; ?>

 </div>
