<?php include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');   ?>            <div class="content-box-header">
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
                                <div class="row">
                                    <div class="col-lg-2 no-border" style="padding-left:30px;">
                                        <?php $handler->html_input(['label' => 'Ime', 'name' => 'ime', 'value' => (!empty($data->ime)) ? $data->ime : '']); ?>                                                      
                                        <?php $handler->html_input(['label' => 'Naslov <small>(Brez presledka)</small><br/><small><strong>Preverite, da naslov ne pripada večstanovanjskim objektom!</strong></small>', 'name' => 'naslov_blacklista', 'value' => (!empty($data->naslov_blacklista)) ? $data->naslov_blacklista : '']); ?>                                    
                                    </div>
                                    <div class="col-lg-2 no-border" style="padding-right:10px;">                         
                                        <?php $handler->html_input(['label' => 'Priimek', 'name' => 'priimek', 'value' => (!empty($data->priimek)) ? $data->priimek : '']); ?>                            
                                        <?php $handler->html_input(['label' => 'Poštna številka<br/><div style="display: none">Prazno</div>', 'name' => 'postna_st', 'value' => (!empty($data->postna_st)) ? $data->postna_st : '']); ?>                                                                                    
                                    </div>
                                </div>
                                <div class="col-lg-4">                                                      
                                    <?php $handler->html_input(['label' => 'Email', 'name' => 'email', 'value' => (!empty($data->email)) ? $data->email : '']); ?>                            
                                    <?php $handler->html_input(['label' => 'Država', 'name' => 'drzava', 'value' => (!empty($data->drzava)) ? $data->drzava : '']); ?>                            
                                    <?php $handler->html_input(['label' => 'Telefon', 'name' => 'telefon', 'value' => (!empty($data->telefon)) ? $data->telefon : '']); ?>                                                      
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