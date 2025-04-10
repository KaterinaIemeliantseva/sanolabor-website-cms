<?php
include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');
?>
<div class="content-box-header">
    <h3><?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
 	<ul class="content-box-tabs">
 	    <li><a href="#tab1" class="default-tab current">Uredi</a></li>
        <?php if($data): ?><li><a href="#tab3" class="">Koledar</a></li><?php endif; ?>
        <?php if($data): ?><li><a href="#tab2" class="">Galerija</a></li><?php endif; ?>
 	</ul>
    <div class="clear"></div>
</div>
<div class="content-box-content">
    <div class="tab-content default-tab active" id="tab1">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="update" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-6">
                    <?php if($data): ?><?php $handler->html_label(['label' => 'Slug', 'value' => (!empty($data->nicename)) ? $data->nicename : '']); ?><?php endif; ?>
                    <?php $handler->html_input(['label' => 'Datum objave', 'name' => 'datum_objave', 'value' => (!empty($data->datum_objave)) ? $data->datum_objave : '', 'required' => true, 'datepicker' => true]); ?>
                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>
                    <?php $handler->html_editor(['label' => 'Kratki opis', 'name' => 'kratki_opis', 'height' => '150', 'value' => (!empty($data->kratki_opis)) ? $data->kratki_opis : '']); ?>
                    <?php $handler->html_select2(['id' => 'kategorija', 'label' => 'Tip dogodka', 'name' => 'kategorija', 'url' => '/webapp/select2?table=dogodki_tip', 'get_single' => ['id' => (!empty($data->kategorija)) ? $data->kategorija : 0, 'table' => 'dogodki_tip']]); ?>  
                    
                    <?php //$handler->html_select2(['id' => 'tip_dogodka', 'label' => 'Tip dogodka', 'name' => 'tip_dogodka[]', 'multiple' => true, 'url' => '/webapp/select2?table=dogodki_tip', 'get_list' =>  ['id' => (!empty($data->id)) ? $data->id : 0, 'table1' => 'dogodki_tip_mm', 'table2' => 'dogodki_tip', 'field1' => 'id_tip', 'field2' => 'id_dogodek']]); ?>
                

                    <!-- <div class="container">
                        <div class="row">
                            <div class="col-lg-2 no-border">
                                <?php //$handler->html_input(['label' => 'Datum', 'name' => 'datum', 'value' => (!empty($data->datum)) ? $data->datum : '', 'required' => true, 'datepicker' => true]); ?>
                            </div>

                            <div class="col-lg-2 no-border">
                                <?php //$handler->html_input(['label' => 'Ura od', 'name' => 'zacetek', 'class' => 'cas small', 'value' => (!empty($data->zacetek)) ? $data->zacetek : '']); ?>
                            </div>
                            <div class="col-lg-2 col-lg-offset-6 no-border">
                                <?php //$handler->html_input(['label' => 'Ura do', 'name' => 'konec', 'class' => 'cas small', 'value' => (!empty($data->konec)) ? $data->konec : '']); ?>
                            </div>
                        </div>
                    </div> -->

       

                    <?php //$handler->html_select2(['id' => 'lekarna', 'label' => 'Kje', 'name' => 'lekarna', 'url' => '/webapp/s2?c=Enote&m=getAllSelect', 'get_selected' => ['id' => (!empty($data->lekarna)) ? $data->lekarna : 0, 'c' => 'Enote', 'm' => 'getSingleSelect']]); ?>
                   
                    <!-- <hr /> -->
                    <?php
                        //$handler->html_select2(['id' => 'novica_id', 'label' => 'Povezana novica', 'name' => 'novica_id', 'url' => '/webapp/select2?table=novice', 'get_single' => ['id' => (!empty($data->novica_id)) ? $data->novica_id : 0, 'table' => 'novice']]); 
                    ?>  

                    
                    <!-- <hr /> -->
                    <?php //$handler->html_tags(['id' => (!empty($data->id)) ? $data->id : 0, 'tip' => 2]); ?>

                </div>
                <div class="col-lg-6">
                    <?php $handler->html_editor(['label' => 'Vsebina', 'name' => 'opis',  'height' => '450', 'value' => (!empty($data->opis)) ? $data->opis : '']); ?>
                    <?php //$handler->html_input(['label' => 'Meta naslov', 'name' => 'meta_title', 'value' => (!empty($data->meta_title)) ? $data->meta_title : '', 'required' => false]); ?>
                    <?php //$handler->html_input(['label' => 'Meta opis', 'name' => 'meta_desc', 'value' => (!empty($data->meta_desc)) ? $data->meta_desc : '', 'required' => false]); ?>
                  
                </div>

            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>
                </div>
            </div>

            <?php $handler->html_input_hidden( ['name' => 'nicename', 'value' => (!empty($data->nicename)) ? $data->nicename : '']); ?>
            <?php $handler->html_save_button($data); ?>
        </form>
    </div>

    <?php if($data): ?>
    <div class="tab-content hide" id="tab2">
        <?php
        $opt = ['id' => '1', 'type' => '7', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=dogodki'];
        $handler->html_file_upload($opt); ?>
        <?php $handler->html_save_button($data, ['close' => true]); ?>
    </div>
    <div class="tab-content hide" id="tab3">
        <div class="row">
            <div class="col-lg-2 no-border">
                <?php $handler->html_select2(['id' => 'k_enota', 'label' => 'Kje', 'name' => 'k_enota', 'url' => '/webapp/select2?table=enota']); ?> 
            </div>
            <div class="col-lg-2 no-border">
                <?php $handler->html_input(['label' => 'Datum', 'id' => 'k_datum', 'name' => 'k_datum', 'value' => '', 'datepicker' => true]); ?>
            </div>
            <div class="col-lg-1 no-border">
                <?php $handler->html_input(['label' => 'Ura od', 'id' => 'k_zacetek', 'name' => 'k_zacetek', 'class' => 'cas small', 'value' =>  '']); ?>
            </div>
            <div class="col-lg-1   no-border">
                <?php $handler->html_input(['label' => 'Ura do', 'id' => 'k_konec', 'name' => 'k_konec', 'class' => 'cas small', 'value' => '']); ?>
            </div>
            <div class="col-lg-4  no-border">
                <p>
                    <a href="#" data-id="<?php echo $data->id; ?>" style=" margin-top: 31px;" id="k_add" class="btn btn-success button ">Dodaj</a>
                </p>
            </div>
        </div>
        <hr />
        <div class="row">
        <div class="col-lg-12  no-border">
            <table id="seznam_koledar" class="display dataTable " cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Kje</th>
							<th style="width:70px;">Datum</th>
							<th style="width:125px;">Cas</th>
							<th style="width:1px;"></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
            </div>
        </div>
        <?php      $handler->html_save_button($data, ['close' => true]); ?>

    
    </div>
    <?php endif; ?>

 </div>
