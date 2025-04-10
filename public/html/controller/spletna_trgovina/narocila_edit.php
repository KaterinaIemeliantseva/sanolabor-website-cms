<?php
include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');
?>
<div class="content-box-header">
    <h3><?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
 	<ul class="content-box-tabs">
 	    <li><a href="#tab1" class="default-tab current">Pregled</a></li>
         <?php if(1== 2 && DE && !$data->stevilka_narocila): ?>
 	    <li><a href="#tab2" class=" ">Uredi</a></li>
         <?php endif; ?>
 	</ul>
    <div class="clear"></div>
</div>
<div class="content-box-content">
    <div class="tab-content default-tab active" id="tab1">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="update" method="post" class="edit_form_validate form-group">
            <div class="row">

            <script>
                $(function() {

               
                    <?php if($data->stevilka_narocila): ?>
                    $.ajax({
                        url:$("meta[name=main_url]").attr("content") + "/api/narocilo-status?id=<?php echo $data->id; ?>",
                        data:{'auth':$("meta[name=auth]").attr("content")},
                        dataType: "jsonp",
                        //jsonpCallback: "logResults",
                        success: function(result) {
                            //console.log(result);
                            if(!result.error) {
                                $('#zakljuceno_label').text(result.description);
                            }
                            
                        }
                    }).always(function() {
                        
                    });
                    <?php endif;?>

                    //označi, da je bilo naročilo pregledano
                    // $.ajax({
                    //     url:$("meta[name=main_url]").attr("content") + "/api/order-reviewed?id=<?php echo $data->id; ?>",
                      
                    //     data:{'auth':$("meta[name=auth]").attr("content")},
                    //     dataType: "jsonp",
                    //     success: function(result) {
                    //         //console.log(result);                            
                    //     }
                    // }).always(function() {
                    // });


                    $.ajax({
                        url:$("meta[name=main_url]").attr("content") + "/api/orderHtml?id=<?php echo $data->id; ?>",
                        // url: "http://127.0.0.1:8000/api/confirmOrder",
                        data:{'auth':$("meta[name=auth]").attr("content")},
                        dataType: "jsonp",
                        jsonpCallback: "logResults",
                        success: function(result) {
                            // console.log(result);
                            $('#narocilo_vsebina').html(result);
                            
                        }
                    }).always(function(result) {
                        //console.log(result);
                        //console.log('okkkk');
                    });
                    
                    <?php if($data->racun_podjetje == 1): ?>
                    $.ajax({
                        url:$("meta[name=main_url]").attr("content") + "/api/company-code?tax_number=<?php echo $data->podjetje_davcna; ?>",
                        data:{'auth':$("meta[name=auth]").attr("content")},
                        dataType: "jsonp",
                        //jsonpCallback: "logResults",
                        success: function(result) {
                                //console.log(result);
                               
                                $('#tax_code_result').text(result.success);
                            
                        }
                    }).always(function() {
                        
                    });
                    <?php endif;?>


                    //$('.edit_form_validate ').validate().form();
                });
            </script>
                     

            <div class="col-lg-3">
                <h3>Naročilo</h3>
                <?php 
                // if($data->stevilka_narocila != '')
                // {
                //     $handler->html_label(['label' => 'Naročila ni možno spreminjati!<br />', 'value' => '', 'custom' => 'style="color:#ff0000;"']); 
                // }
                ?>

                <?php 
                if($data->nacin_dostave == '148969')
                {
                    $status_mail = $foo->statusMail($data->id, 7);
                    $status_mail2 = $foo->statusMail($data->id, 3);
                    $handler->html_label(['label' => 'Poslano obvestilo za osebni prevzem', 'value' => ($status_mail || $status_mail2) ? 'Da' : 'Ne']);
                    
                }
                ?>

                <?php $handler->html_label(['label' => 'Status naročila', 'span_id' => 'zakljuceno_label', 'value' => '']); ?>
                <?php $handler->html_label(['label' => 'Št. Naročila', 'value' => (!empty($data->id)) ? $data->id : '']); ?>

                <?php if($data->nacin_placila == 45 && !empty($data->payment_reference_id)): ?>
                <?php $handler->html_label(['label' => 'UUID', 'value' => (!empty($data->payment_reference_id)) ? $data->payment_reference_id : '']); ?>
                <?php endif; ?>

                <?php $handler->html_label(['label' => 'Ustvarjeno', 'value' =>  $data->created_at]); ?>
                <?php $handler->html_label(['label' => 'Zadnja sprememba', 'value' =>  $data->updated_at]); ?>

                <?php $handler->html_input(['label' => 'E-naslov/uporabniško ime', 'name' => 'e_posta', 'value' => (!empty($data->e_posta)) ? $data->e_posta : '', 'required' => true]); ?>
                
                <?php //if ( $_SESSION['userSessionValue'] ==  94 || $_SESSION['userSessionValue'] == 96 || $_SESSION['userSessionValue'] == 77): ?>
                    <br />
                    <div>
                        <div style="display:inline">
                            <span style="font-size: 16px; font-weight: bold">Naslov za dostavo </span>
                        </div>
                        <div style="display: inline; float:right;">
                            <img class="image_dostava" onclick="show_podatki()" src="../public/resources/images/plus-5-32.png" width="24" height="24"/>
                        </div>
                    </div>
                    <div class="podatki hide" style="border-bottom:1px solid #d9d9d9; padding-bottom:10px;">
                        <div class="row no-border">
                            <div class="col-lg-6 col-lg-offset-1 no-border">
                                <?php $handler->html_input(['label' => 'Ime', 'name' => 'dostava_ime', 'value' => (!empty($data->dostava_ime)) ? $data->dostava_ime : '', 'maxlength' => 40, 'required' => true]); ?>
                            </div>
                            <div class="col-lg-6 no-border">
                                <?php $handler->html_input(['label' => 'Priimek', 'name' => 'dostava_priimek', 'value' => (!empty($data->dostava_priimek)) ? $data->dostava_priimek : '', 'maxlength' => 40, 'required' => true]); ?>
                            </div>
                        </div>
                        <?php $handler->html_input(['label' => 'Naslov', 'name' => 'dostava_naslov', 'value' => (!empty($data->dostava_naslov)) ? $data->dostava_naslov : '', 'maxlength' => 40, 'required' => true]); ?>
                        
                        <div class="row no-border">
                            <div class="col-lg-6 col-lg-offset-1 no-border">
                                <?php $handler->html_input(['label' => 'Poštna št.', 'name' => 'dostava_postna_st', 'value' => (!empty($data->dostava_postna_st)) ? $data->dostava_postna_st : '', 'required' => true]); ?>
                            </div>
                            <div class="col-lg-6 no-border">
                                <?php $handler->html_input(['label' => 'Pošta', 'name' => 'dostava_mesto', 'value' => (!empty($data->dostava_mesto)) ? $data->dostava_mesto : '', 'maxlength' => 30,  'required' => true]); ?>
                            </div>
                        </div>

                        <?php //$handler->html_label(['label' => 'E-naslov/uporabniško ime', 'value' =>  $data->e_posta ]); ?>
                        <?php //$handler->html_label(['label' => 'Naslov', 'value' =>  $data->dostava_ime.' '.$data->dostava_priimek.', '.$data->dostava_naslov.', '.$data->dostava_postna_st.' '.$data->dostava_mesto ]); ?>
                        
                        <?php //$handler->html_input(['label' => 'Ime', 'name' => 'e_podostava_imesta', 'value' => (!empty($data->e_posta)) ? $data->e_posta : '', 'required' => true]); ?>
                        
                        <?php if($data->racun_podjetje == 1): ?>
                        <?php $handler->html_input(['label' => 'Naziv podjetja', 'name' => 'podjetje_naziv', 'value' => (!empty($data->podjetje_naziv)) ? $data->podjetje_naziv : '', 'maxlength' => 60, 'required' => true]); ?>
                        <?php //$handler->html_label(['label' => 'Davčna št.', 'value' =>  $data->podjetje_davcna ]); ?>
                        <?php  $handler->html_input(['label' => 'Davčna št.', 'name' => 'podjetje_davcna', 'value' => (!empty($data->podjetje_davcna)) ? $data->podjetje_davcna : '', 'maxlength' => 11, 'required' => true]); ?>
                        <?php $handler->html_label(['label' => 'Šifra podjetja', 'value' =>  '<span id="tax_code_result">Čakam na podatke ...</span>' ]); ?>
                        <?php endif;?>
                        <?php //$handler->html_label(['label' => 'Telefon', 'value' =>  $data->tel ]); ?>
                    </div>
                <?php //endif; ?>

                <?php if ( $data->racun_podjetje != 1): ?>
                    <br />
                    <div>
                        <div style="display:inline">
                            <span style="font-size: 16px; font-weight: bold">Naslov za račun </span>
                        </div>
                        <div style="display: inline; float:right;">
                            <img class="image_narocilo_dostava" onclick="show_narocilo_podatki()" src="../public/resources/images/plus-5-32.png" width="24" height="24"/>
                        </div>
                    </div>
                    <div class="naslov_za_racun hide" style="border-bottom:1px solid #d9d9d9; padding-bottom:10px;">
                        <div class="row no-border">
                            <div class="col-lg-6 col-lg-offset-1 no-border">
                                <?php $handler->html_input(['label' => 'Ime', 'name' => 'ime', 'value' => (!empty($data->ime)) ? $data->ime : '', 'maxlength' => 40, 'required' => true]); ?>
                            </div>
                            <div class="col-lg-6 no-border">
                                <?php $handler->html_input(['label' => 'Priimek', 'name' => 'priimek', 'value' => (!empty($data->priimek)) ? $data->priimek : '', 'maxlength' => 40, 'required' => true]); ?>
                            </div>
                        </div>
                        <?php $handler->html_input(['label' => 'Naslov', 'name' => 'naslov', 'value' => (!empty($data->naslov)) ? $data->naslov : '', 'maxlength' => 40, 'required' => true]); ?>
                        
                        <div class="row no-border">
                            <div class="col-lg-6 col-lg-offset-1 no-border">
                                <?php $handler->html_input(['label' => 'Poštna št.', 'name' => 'postna_st', 'value' => (!empty($data->postna_st)) ? $data->postna_st : '', 'required' => true]); ?>
                            </div>
                            <div class="col-lg-6 no-border">
                                <?php $handler->html_input(['label' => 'Pošta', 'name' => 'mesto', 'value' => (!empty($data->mesto)) ? $data->mesto : '', 'maxlength' => 30,  'required' => true]); ?>
                            </div>
                        </div>
                        <?php $handler->html_input(['name' => 'drzava', 'value' => (!empty($data->drzava)) ? $data->drzava : '', 'maxlength' => 40, 'required' => false]); ?>
                    </div>
                    
                <?php else : ?>
                    <br />
                        <div>
                            <div style="display:inline">
                                <span style="font-size: 16px; font-weight: bold">Naslov za račun </span>
                            </div>
                            <div style="display: inline; float:right;">
                                <img class="image_narocilo_dostava" onclick="show_narocilo_podatki()" src="../public/resources/images/plus-5-32.png" width="24" height="24"/>
                            </div>
                        </div>
                        <div class="naslov_za_racun hide" style="border-bottom:1px solid #d9d9d9; padding-bottom:10px;">
                            <div class="row no-border">
                                <div class="col-lg-6 col-lg-offset-1 no-border">
                                    <?php $handler->html_input(['label' => 'Ime', 'name' => 'podjetje_naziv', 'value' => (!empty($data->podjetje_naziv)) ? $data->podjetje_naziv : '', 'maxlength' => 40, 'required' => true]); ?>
                                </div>
                                <div class="col-lg-6 no-border">
                                    <?php //$handler->html_input(['label' => 'Priimek', 'name' => 'priimek', 'value' => (!empty($data->priimek)) ? $data->priimek : '', 'maxlength' => 40, 'required' => true]); ?>
                                </div>
                            </div>
                            <?php $handler->html_input(['label' => 'Naslov', 'name' => 'podjetje_sedez', 'value' => (!empty($data->podjetje_sedez)) ? $data->podjetje_sedez : '', 'maxlength' => 40, 'required' => true]); ?>
                            
                            <div class="row no-border">
                                <div class="col-lg-6 col-lg-offset-1 no-border">
                                    <?php $handler->html_input(['label' => 'Poštna št.', 'name' => 'podjetje_posta', 'value' => (!empty($data->podjetje_posta)) ? $data->podjetje_posta : '', 'required' => true]); ?>
                                </div>
                                <div class="col-lg-6 no-border">
                                    <?php $handler->html_input(['label' => 'Pošta', 'name' => 'podjetje_kraj', 'value' => (!empty($data->podjetje_kraj)) ? $data->podjetje_kraj : '', 'maxlength' => 30,  'required' => true]); ?>
                                </div>
                            </div>
                            <?php $handler->html_input(['name' => 'drzava', 'value' => (!empty($data->drzava)) ? $data->drzava : '', 'maxlength' => 40, 'required' => false]); ?>
                        </div>
                <?php endif; ?>

                <div class="row no-border">
                    <div class="col-lg-6 col-lg-offset-1 no-border">
                        <?php $handler->html_input(['label' => 'Telefon', 'name' => 'tel', 'value' => (!empty($data->tel)) ? $data->tel : '', 'maxlength' => 100, 'required' => false]); ?>
                    </div>
                    <div class="col-lg-6 no-border">
                        <?php //$handler->html_input(['label' => 'Št. KZ', 'name' => 'st_kz', 'value' => (!empty($data->st_kz)) ? $data->st_kz : '', 'maxlength' => 100, 'required' => false]); ?>
                    </div>
                </div>

                <?php 
                 $nacin_placila = $foo->getNacinPlacila(['sifra' => $data->nacin_placila]);
                 $handler->html_select2(['id' => 'nacin_placila', 'label' => 'Način plačila', 'name' => 'nacin_placila', 'url' => '/webapp/select2?table=nacin_placila', 'get_single' => ['id' => (!empty($nacin_placila['id'])) ? $nacin_placila['id'] : 0, 'table' => 'nacin_placila']]); 
    
                 $enote = $foo->getEnote(['sifra' => $data->skladisce, 'naziv' => substr($data->osebni_prevzem_naslov, 0, strpos($data->osebni_prevzem_naslov, ','))]);
                 $handler->html_select2(['id' => 'skladisce', 'label' => 'Enota', 'name' => 'skladisce', 'url' => '/webapp/select2?table=enota', 'get_single' => ['id' => (!empty($enote['id'])) ? $enote['id'] : 0, 'table' => 'enota']]); 
    
                //  if ($_SESSION['userSessionValue'] == 96)
                //     echo str_replace('-', ' ',substr($data->osebni_prevzem_naslov, 0, strpos($data->osebni_prevzem_naslov, ',')));
                // $handler->html_select2(['id' => 'skladisce', 'label' => 'Enota', 'name' => 'skladisce', 'url' => '/webapp/select2?table=enota', 'get_single' => ['id' => (!empty($enote['id'])) ? $enote['id'] : 0, 'table' => 'enota']]); 

                 $dostava = $foo->getNacinDostave(['sifra' => $data->nacin_dostave]); 
                // $handler->html_select2(['id' => 'nacin_dostave', 'label' => 'Način dostave', 'name' => 'nacin_dostave', 'url' => '/webapp/select2?table=dostava', 'get_single' => ['id' => (!empty($dostava['id'])) ? $dostava['id'] : 0, 'table' => 'dostava']]); 
                 $handler->html_input(['label' => 'Znesek dostave', 'name' => 'znesek_dostave', 'type' => 'number', 'custom' => 'step=".01"' , 'value' => (!empty($data->znesek_dostave)) ? $data->znesek_dostave : '0', 'required' => true]); 
             
                ?>

                <?php $handler->html_label(['label' => 'Znesek plačila', 'value' =>  $data->znesek_placila - $data->popust_zaposleni_skupaj]); ?>
                <?php $handler->html_label(['label' => 'Opomba', 'value' =>  $data->opombe ]); ?>
                <?php if(!empty($data->napaka)) $handler->html_label(['label' => 'Napaka', 'value' =>  substr ($data->napaka,0, 300) ]); ?>
            </div>
            <div class="col-lg-9">
                <?php if($data->payment_pending == 1 && $data->nacin_placila == 45 ): ?>
                    <div style="font-weight:bold; color:#ff0000;">Plačilo s kartico čaka na potrditev iz strani banke.</div>
                <?php endif;?>
 
                <?php if($data->nacin_placila == 41 && 1 == 2 ): ?>
                 <div>
                    <img src="https://www.sanolabor.si/qrcodetest?id=<?php echo $data->id; ?>" />
                </div> 
                <?php endif;?>
    
                <div id="narocilo_vsebina"></div> 

                <?php //if(DE): ?>
                <a href="#" id="ponovnoPosljiObvestilo" data-id="<?php echo $data->id; ?>" class="btn btn-success">Ponovno pošlji obvestilo</a>
                <?php //endif; ?>

                <?php if($data->nacin_placila == 45 && DE): ?>
                    <!-- <a href="#" id="pokaziPredracun" data-id="<?php echo $data->id; ?>" class="btn btn-warning">Pokaži predračun</a> -->
                <?php endif; ?>

                <?php if($data->stevilka_narocila != ''): ?>
                    <a href="#" id="ponovnoPosljiNarocilo" data-id="<?php echo $data->id; ?>" class="btn btn-danger">Ponovno pošlji naročilo v enoto</a>
                <?php endif; ?>

                <?php if(DE): ?>
                    <a href="#" id="podvojiNarocilo" data-id="<?php echo $data->id; ?>" class="btn btn-primary">Duplikat naročila</a>
                <?php endif; ?>

                <img src="/public/resources/images/ajax-loader.gif" class="ajaxLoader1 hide">
            </div>

         

            </div>

            <?php 
            $handler->html_save_button($data); 
            //$handler->html_save_button($data, ['close' => true]); 
            ?>
        </form>
    </div>

    <?php if(1== 2 && DE && !$data->stevilka_narocila): ?>
    <div class="tab-content hide" id="tab2">
    <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="update-narocilo" method="post" class="edit_form_validate form-group">
            <div class="row">

            <script>
                $(function() {

  
                });
            </script>

                <div class="col-lg-12">
                    <h3>Postavke</h3>
                    <?php
                    $postavke =  $foo->getPostavke($data->id);
                    if($postavke)
                    {
                        ?><table class="postavke">
                        <tr>
                                    <td nowrap><?php $handler->html_label(['label' => 'Šifra', 'value' =>  '', 'custom' => 'style="width:60px;"']); ?></td>
                                    <td nowrap><?php $handler->html_label(['label' => 'Količina',  'value' => '']); ?></td>
                                    <td nowrap><?php $handler->html_label(['label' => 'Naziv', 'value' => '', 'custom' => 'style="width:260px;"']); ?></td>
                                    <td ><?php $handler->html_label(['label' => 'MPC', 'value' => '', 'custom' => 'style="width:60px;"']); ?></td>
                                    <td nowrap><?php $handler->html_label(['label' => 'Spletna<br />cena', 'value' => '', 'custom' => 'style="width:60px;"']); ?></td>
                                    <td nowrap><?php $handler->html_label(['label' => 'KZ', 'value' => '', 'custom' => 'style="width:60px;"']); ?></td>
                                    <td nowrap><?php $handler->html_label(['label' => 'KZ<br />popust (%)', 'value' => '', 'custom' => 'style="width:60px;"']); ?></td>
                                    <td nowrap><?php $handler->html_label(['label' => 'Cena', 'value' => '', 'custom' => 'style="width:60px;"']); ?></td>
                                    <td nowrap></td>
                                    <td style="width:100%;"></td>
                                </tr>
                        
                        <?php
                        foreach ($postavke as $key => $value)
                        {
                            ?>
                                <tr>
                                    <td nowrap><?php $handler->html_input(['label' => '', 'name' => 'sifra', 'value' => (!empty($value['sifra'])) ? $value['sifra'] : '', 'maxlength' => 6, 'required' => true]); ?></td>
                                    <td nowrap><?php $handler->html_input(['label' => '', 'name' => 'kolicina', 'value' => (!empty($value['kolicina'])) ? $value['kolicina'] : '',  'required' => true]); ?></td>
                                    <td nowrap><?php $handler->html_input(['label' => '', 'name' => 'naziv', 'value' => (!empty($value['naziv'])) ? $value['naziv'] : '',  'required' => true]); ?></td>
                                    <td ><?php $handler->html_input(['label' => '', 'name' => 'mpc', 'value' => (!empty($value['mpc'])) ? $value['mpc'] : '',  'required' => true]); ?></td>
                                    <td nowrap><?php $handler->html_input(['label' => '', 'name' => 'mpc_web', 'value' => (!empty($value['mpc_web'])) ? $value['mpc_web'] : '',  'required' => true]); ?></td>
                                    <td nowrap><?php $handler->html_input(['label' => '', 'name' => 'mpc_kz', 'value' => (!empty($value['mpc_kz'])) ? $value['mpc_kz'] : '',  'required' => true]); ?></td>
                                    <td nowrap><?php $handler->html_input(['label' => '', 'name' => 'popust_kz', 'value' => (!empty($value['popust_kz'])) ? $value['popust_kz'] : '',  'required' => true]); ?></td>
                                    <td nowrap><?php $handler->html_input(['label' => '', 'name' => 'cena', 'value' => (!empty($value['cena'])) ? $value['cena'] : '',  'required' => true]); ?></td>
                                    <td nowrap><p><button class="btn btn-danger button">Odstrani</button></p></td>
                                    <td style="width:100%;"></td>
                                    
                                </tr>
                            <?php
                        }
                        ?></table><?php
                    }
                    ?>

                </div>

            </div>

            <?php 
            $handler->html_save_button($data); 
            //$handler->html_save_button($data, ['close' => true]); 
            ?>
        </form>
    </div>
    <?php endif; ?>
    
    
 </div>

 <script type="text/javascript">
    function show_narocilo_podatki(){
        if($(".naslov_za_racun").hasClass("hide"))
        {
            $(".naslov_za_racun").removeClass("hide");
            $(".image_narocilo_dostava").attr("src", "../public/resources/images/minus-5-32.png");
        }else{
            $(".naslov_za_racun").addClass("hide");
            $(".image_narocilo_dostava").attr("src", "../public/resources/images/plus-5-32.png");
        }
    }

    function show_podatki(){
        if($(".podatki").hasClass("hide"))
        {
            $(".podatki").removeClass("hide");
            $(".image_dostava").attr("src", "../public/resources/images/minus-5-32.png");
        }else{
            $(".podatki").addClass("hide");
            $(".image_dostava").attr("src", "../public/resources/images/plus-5-32.png");
        }
    }
</script>
