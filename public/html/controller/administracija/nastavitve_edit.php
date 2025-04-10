<?php
include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');
?>
<div class="content-box-header">

    <h3><?php //if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
 	<ul class="content-box-tabs">
 	    <li><a href="#tab1" class="default-tab current">Uredi</a></li>
 	    <?php if($data): ?><li><a href="#tab2">Dostopi</a></li><?php endif; ?>
 	</ul>
     <div class="clear"></div>
 </div>
<div class="content-box-content">
    <div class="tab-content default-tab active" id="tab1">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="save" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-12">
            <?php $handler->html_input(['label' => 'Uporabniško ime', 'name' => 'username', 'value' => (!empty($data->username)) ? $data->username : '', 'required' => true]); ?>
            <?php $handler->html_input(['label' => 'Naziv', 'name' => 'ime_priimek', 'value' => (!empty($data->ime_priimek)) ? $data->ime_priimek : '', 'required' => true]); ?>
            <?php $handler->html_input(['label' => 'Novo geslo', 'name' => 'password', 'value' => '', 'required' => (!$data), 'custom' => 'minlength="6"']); ?>
            <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>
                </div>
            </div>
            <?php $handler->html_save_button($data); ?>
        </form>
    </div>

    <?php if($data): ?>
    <div class="tab-content hide" id="tab2">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="shraniRavrstitev" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-12">
		<?php
        $foo->getKategorijeRazvrstiIzpis(0, 1, $data->id);
        ?>
                </div>
            </div>
        <?php $handler->html_save_button($data); ?>
        </form>
    </div>
    <?php endif; ?>
 </div>
