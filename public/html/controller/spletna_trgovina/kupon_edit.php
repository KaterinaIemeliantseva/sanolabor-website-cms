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
                    <?php $handler->html_input(['label' => 'Šifra kupona', 'name' => 'bon_sifra', 'value' => (!empty($data->bon_sifra)) ? $data->bon_sifra : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Minimalni znesek za koriščenje kupona', 'name' => 'minimalni_znesek', 'value' => (!empty($data->minimalni_znesek)) ? $data->minimalni_znesek : '0', 'required' => false]); ?>
                    <!-- <?php $handler->html_input(['label' => 'Znesek popusta na celotni nakup', 'name' => 'popust_znesek', 'value' => (!empty($data->popust_znesek)) ? $data->popust_znesek : '0', 'required' => false]); ?> -->
                    <?php $handler->html_input(['label' => 'Popust (%)', 'name' => 'popust_odstotek', 'value' => (!empty($data->popust_odstotek)) ? $data->popust_odstotek : '0', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Aktivno od', 'name' => 'datum_veljavno_od', 'value' => (!empty($data->datum_veljavno_od)) ? $data->datum_veljavno_od : '', 'required' => true, 'datepicker' => true]); ?>
                    <?php $handler->html_input(['label' => 'Aktivno do', 'name' => 'datum_veljavno_do', 'value' => (!empty($data->datum_veljavno_do)) ? $data->datum_veljavno_do : '', 'required' => true, 'datepicker' => true]); ?>
                </div>
                <div class="col-lg-6">
                    <?php $handler->html_checkbox(['label' => 'Vsi artikli (razen zdravil, ...)', 'name' => 'vsi_artikli', 'status' => (!empty($data->vsi_artikli))]); ?><br />
                    <?php $handler->html_select2(['id' => 'povezani_artikli', 'label' => 'Izberi samo določene artikle iz seznama', 'name' => 'povezani_artikli[]', 'multiple' => true, 'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect', 'get_selected' => ['id' => (!empty($data->id)) ? $data->id : 0, 'c' => 'Artikel', 'm' => 'getPovezaniSelected', 'tip' => 2]]); ?>
                    
                    <div class="row" style="font-size:9px; white-space:no-wrap;">
                        <div class="col-lg-12 nastavitev_kategorije_main ">
                            <div class="nastavitev_kategorije_wrapper">
                            <?php
                                $foo->getKategorijeRazvrstiIzpis(0, 1, (empty($data->id)) ? 0 : $data->id);
                            ?>
                            </div>
                        </div>
                    </div>    
                    <?php $handler->html_select2(['id' => 'povezani_artikli2', 'label' => 'Onemogoči popust na artikel', 'name' => 'povezani_artikli2[]', 'multiple' => true, 'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect', 'get_selected' => ['id' => (!empty($data->id)) ? $data->id : 0, 'c' => 'Artikel', 'm' => 'getPovezaniSelected', 'tip' => 10]]); ?>
                </div>
            </div>
         
            <div class="row">
                <div class="col-lg-12">
                    <?php //$handler->html_checkbox(['label' => 'Omejitev na en artikel', 'name' => 'omejitev_artikel', 'status' => (!empty($data->omejitev_artikel))]); ?>
                    <?php //$handler->html_checkbox(['label' => 'Omejitev na uporabnika', 'name' => 'omejitev_uporabnik', 'status' => (!empty($data->omejitev_uporabnik))]); ?>
      
                    <?php $handler->html_checkbox(['label' => 'Brezplačna dostava', 'name' => 'brezplacna_dostava', 'status' => (!empty($data->brezplacna_dostava))]); ?><br />
                    <?php //$handler->html_checkbox(['label' => 'Brez kupnine', 'name' => 'brez_kupnine', 'status' => (!empty($data->brez_kupnine))]); ?><br />
                    <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>
                </div>
            </div>

            <?php $handler->html_save_button($data); ?>
        </form>
    </div>



 </div>
