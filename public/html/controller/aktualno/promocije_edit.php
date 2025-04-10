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
                <div class="col-lg-12">
                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Akcija', 'name' => 'opis', 'value' => (!empty($data->opis)) ? $data->opis : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Povezava', 'name' => 'povezava', 'value' => (!empty($data->povezava)) ? $data->povezava : '']); ?>
                    <?php //$handler->html_single_file_upload(['label' => 'Logotip', 'name' => 'logotip', 'value' => (!empty($data->logotip)) ? $data->logotip : '', 'required' => true]); ?>
                    <?php
                    $opt = ['label' => 'Proizvajalec', 'name' => 'bz', 'url' => '/webapp/proizvajalci/s2/all', 'required' => false];
                    if(!empty($data->bz))
                    {
                        $selected_kat = $handler->apiCall('/webapp/base/get', ['id' => $data->bz, 'c' => 'Proizvajalci']);
                        if($selected_kat)
                        {
                            $opt['selected'] = ['id' => $selected_kat->data->id, 'text' => $selected_kat->data->naziv];
                        }
                    }
                    $handler->html_select2($opt);
                    ?>

                    <?php $handler->html_input(['label' => 'Od', 'name' => 'datum_od', 'value' => (!empty($data->datum_od)) ? $data->datum_od : '', 'required' => true, 'datepicker' => true]); ?>
                    <?php $handler->html_input(['label' => 'Do', 'name' => 'datum_do', 'value' => (!empty($data->datum_do)) ? $data->datum_do : '', 'required' => true, 'datepicker' => true]); ?>


                    <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>
                </div>
            </div>

            <?php $handler->html_save_button($data); ?>
        </form>
    </div>

 </div>
