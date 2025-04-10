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
                    <h3>Osnovni podatki</h3>
                    <?php $handler->html_input(['label' => 'Uporabniško ime', 'name' => 'username', 'value' => (!empty($data->username)) ? $data->username : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Novo geslo', 'name' => 'password_old', 'value' => '', 'required' => (empty($data->password) && empty($data->password_old)), 'custom' => 'minlength="6"']); ?>
                    <hr />
                    <?php $handler->html_input(['label' => 'Enaslov', 'name' => 'email', 'value' => (!empty($data->email)) ? $data->email : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Ime', 'name' => 'ime', 'value' => (!empty($data->ime)) ? $data->ime : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Priimek', 'name' => 'priimek', 'value' => (!empty($data->priimek)) ? $data->priimek : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Naslov', 'name' => 'naslov', 'value' => (!empty($data->naslov)) ? $data->naslov : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Mesto', 'name' => 'mesto', 'value' => (!empty($data->mesto)) ? $data->mesto : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Poštna št.', 'name' => 'postna_st', 'value' => (!empty($data->postna_st)) ? $data->postna_st : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Telefon', 'name' => 'tel', 'value' => (!empty($data->tel)) ? $data->tel : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Šifra zaposlenega', 'name' => 'sifra_partnerja', 'value' => (!empty($data->sifra_partnerja)) ? $data->sifra_partnerja : '', 'required' => false]); ?>
      
                    <?php if(1 == 2): ?>
                    <hr />
                    <br /><h3>Dostava</h3>
                    <?php $handler->html_input(['label' => 'Ime', 'name' => 'dostava_ime', 'value' => (!empty($data->dostava_ime)) ? $data->dostava_ime : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Priimek', 'name' => 'dostava_priimek', 'value' => (!empty($data->dostava_priimek)) ? $data->dostava_priimek : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Naslov', 'name' => 'dostava_naslov', 'value' => (!empty($data->dostava_naslov)) ? $data->dostava_naslov : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Mesto', 'name' => 'dostava_mesto', 'value' => (!empty($data->dostava_mesto)) ? $data->dostava_mesto : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Poštna št.', 'name' => 'dostava_postna_st', 'value' => (!empty($data->dostava_postna_st)) ? $data->dostava_postna_st : '', 'required' => false]); ?>
                    <?php endif; ?>
                </div>
                <?php if(1 == 2): ?>
                <div class="col-lg-6">

                    <h3>Podjetje</h3>
                    <?php $handler->html_input(['label' => 'Naziv podjetja', 'name' => 'podjetje_naziv', 'value' => (!empty($data->podjetje_naziv)) ? $data->podjetje_naziv : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Davčna', 'name' => 'podjetje_davcna', 'value' => (!empty($data->podjetje_davcna)) ? $data->podjetje_davcna : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Matična', 'name' => 'podjetje_maticna', 'value' => (!empty($data->podjetje_maticna)) ? $data->podjetje_maticna : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Sedež', 'name' => 'podjetje_sedez', 'value' => (!empty($data->podjetje_sedez)) ? $data->podjetje_sedez : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Hišna št.', 'name' => 'podjetje_hisna_st', 'value' => (!empty($data->podjetje_hisna_st)) ? $data->podjetje_hisna_st : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Poštna št.', 'name' => 'podjetje_posta', 'value' => (!empty($data->podjetje_posta)) ? $data->podjetje_posta : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Kraj', 'name' => 'podjetje_kraj', 'value' => (!empty($data->podjetje_kraj)) ? $data->podjetje_kraj : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Telefon', 'name' => 'podjetje_tel', 'value' => (!empty($data->podjetje_tel)) ? $data->podjetje_tel : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Enaslov', 'name' => 'podjetje_email', 'value' => (!empty($data->podjetje_email)) ? $data->podjetje_email : '', 'required' => false]); ?>

                </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>
                </div>
                <div class="col-lg-6">
                <?php if (!empty($data->username) && 1 == 2): ?>
                    <a href="#" id="ponovnoPosljiObvestilo" data-id="<?php echo $data->id; ?>" class="btn btn-success">Ponovno pošlji potrditveni mail</a>
                <?php endif; ?>
                </div>
            </div>
            <?php $handler->html_save_button($data); ?>
        </form>
    </div>


 </div>
