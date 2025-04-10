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
                    <?php $handler->html_input(['label' => 'Šifra popusta', 'name' => 'sifra', 'value' => (!empty($data->sifra)) ? $data->sifra : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Število potrebnih artiklov', 'name' => 'kolicina', 'value' => (!empty($data->kolicina)) ? $data->kolicina : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Popust (%)', 'name' => 'popust', 'value' => (!empty($data->popust)) ? $data->popust : '0', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Aktivno do', 'name' => 'datum_do', 'value' => (!empty($data->datum_do)) ? $data->datum_do : '', 'required' => true, 'datepicker' => true]); ?>
                </div>
                <div class="col-lg-6">
                    <?php $handler->html_select2(['id' => 'povezani_artikli', 'label' => 'Izberi artikle iz seznama', 'name' => 'povezani_artikli[]', 'multiple' => true, 'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect', 'get_selected' => ['id' => (!empty($data->id)) ? $data->id : 0, 'c' => 'Artikel', 'm' => 'getPovezaniSelected', 'tip' => 11]]); ?>                   
                    <?php  $handler->html_select2(['id' => 'blagovna_znamka', 'label' => 'Izberi blagovno znamko', 'name' => 'blagovna_znamka', 'url' => '/webapp/select2?table=blagovna_znamka', 'get_single' => ['id' => (!empty($data->blagovna_znamka)) ? $data->blagovna_znamka : 0, 'table' => 'blagovna_znamka']]); ?>
                    
                    
                    <div class="row" style="font-size:9px; white-space:no-wrap;">
                        <div class="col-lg-12 nastavitev_kategorije_main ">
                            <div class="nastavitev_kategorije_wrapper">
                            <?php
                                $foo->getKategorijeRazvrstiIzpis(0, 1, (empty($data->id)) ? 0 : $data->id);
                            ?>
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
