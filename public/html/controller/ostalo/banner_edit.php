<?php
include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');
?>
<div class="content-box-header">
    <h3><?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
 	<ul class="content-box-tabs">
 	    <li><a href="#tab1" class="default-tab current">Uredi</a></li>
        <?php if($data && 1 == 2): ?><li><a href="#tab2" class="">Galerija</a></li><?php endif; ?>
 	</ul>
    <div class="clear"></div>
</div>
<div class="content-box-content">
    <div class="tab-content default-tab active" id="tab1">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="update" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-12">
                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Povezava', 'name' => 'povezava', 'value' => (!empty($data->povezava)) ? $data->povezava : '']); ?>
                    <?php $handler->html_input(['label' => 'Vrstni red', 'name' => 'sort', 'type' => 'number', 'value' => (!empty($data->sort)) ? $data->sort : '0', 'required' => false]); ?>
                    <?php $handler->html_single_file_upload(['label' => 'Banner', 'name' => 'pot', 'value' => (!empty($data->pot)) ? $data->pot : '', 'required' => true]); ?>
                    <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>
                </div>
            </div>

            <?php $handler->html_save_button($data); ?>
        </form>
    </div>

    <?php if($data && 1 == 2): ?>
    <div class="tab-content hide" id="tab2">
        <div class="row">
            <div class="col-lg-12">
                <?php
                $opt = ['id' => '1', 'type' => '2', 'item_id' => $data->id, 'url' => '/webapp/base/uploadImage?dir=banner', 'limit' => 1];
                $handler->html_file_upload($opt); ?>
            </div>
        </div>
        <?php $handler->html_save_button($data, ['close' => true]); ?>
    </div>
    <?php endif; ?>
 </div>
