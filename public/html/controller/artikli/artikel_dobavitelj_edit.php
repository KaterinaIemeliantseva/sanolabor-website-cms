<?php include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');   ?>      
<script type="text/javascript" src="/public/resources/scripts/diff_match_patch.js"></script>
<script type="text/javascript" src="/public/resources/scripts/jquery.pretty-text-diff.min.js"></script>

<div class="content-box-header">
    <h3><?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
    <ul class="content-box-tabs">
        <li><a href="#tab1" class="default-tab current">Uredi</a></li>
    </ul>
    <div class="clear"></div>
</div>
<?php
// if(DE)
// {
//     die();
// }
?>

<div class="content-box-content">
    <div class="tab-content default-tab active" id="tab1">
    <input  id="save_button_all_changes" class="btn btn-success button  save_button_keep_state" type="submit" value="Potrdi vse">

    <?php
    $check_count = 0;
    $artikel = (array)$foo->getArtikel($data->artikel_id);  //echo $data->artikel_id;
    $bz = (array)$foo->getBZ($artikel['blagovna_znamka']);
    $predogled_artikel = json_decode($data->podatki_json, true);
    $kategorija = (array)$foo->getKategorija($artikel['id']);

//     if(DE)
// {
//     die();
// }

    //print_r($bz);
    // if(DE)
    // {
    //     print_r($predogled_artikel);
    //     die();
    // }
    //
    //echo 'no'.$predogled_artikel['naziv'].'<br />';

    ?>
<div class="row">
<?php
    $list_fields = [];
    
    $list_fields[] = ['polje' => 'ean_dobavitelj', 'label' => 'Šifre', 'type' => '5'];
    $list_fields[] = ['polje' => 'naziv', 'label' => 'Naziv', 'type' => '1'];
    $list_fields[] = ['polje' => 'blagovna_znamka', 'label' => 'Blagovna znamka', 'type' => '4'];
    $list_fields[] = ['polje' => 'kategorija', 'label' => 'Kategorije', 'type' => '6'];
    $list_fields[] = ['polje' => 'kratki_opis', 'label' => 'Kratki opis', 'type' => '2'];
    $list_fields[] = ['polje' => 'vsebina', 'label' => 'Vsebina', 'type' => '2'];
    $list_fields[] = ['polje' => 'navodilo', 'label' => 'Navodilo', 'type' => '2'];
    $list_fields[] = ['polje' => 'opozorilo', 'label' => 'Opozorilo', 'type' => '2'];
    $list_fields[] = ['polje' => 'tehnicna_dokumentacija', 'label' => 'Dokumentacija', 'type' => '2'];
    $list_fields[] = ['polje' => 'path_dokument_deklaracija', 'label' => 'Deklaracija', 'type' => '3', 'file' => 15];
    $list_fields[] = ['polje' => 'path_slika', 'label' => 'Fotografija artikla', 'type' => '3', 'file' => 1];
    $list_fields[] = ['polje' => 'path_dokument', 'label' => 'Promocijski materiali', 'type' => '3', 'file' => 16];
    $list_fields[] = ['polje' => 'arhiv', 'label' => 'Arhiv', 'type' => '1'];
    #######################################################
    $time_start = microtime(true);
    foreach($list_fields as $key => $element)
    {
        $polje = $element['polje'];
        $artikel_polje = $polje;
        if($polje == 'navodilo')
        {
            $artikel_polje = 'navodila';
        }

        //datoteke
        if($element['type'] == 5)
        { 
            //if(DE) continue;
            //$data['type'], $data['item_id']
            //echo $polje;
            // if(DE)
            // {
            //     echo $polje;
            // }
            

            $check_stanje1 = [];
            $check_stanje2 = [];
            
            if(1 == 1)
            {
                if(DE)
                {
                    // print_r($predogled_artikel['ean_dobavitelj']);
                    //unset($predogled_artikel['ean_dobavitelj']);
                }
               
                ?>
                <div id="eanPO" class="col-lg-6">
                <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="updateSpremembe" method="post" class="edit_form_validate form-group <?php echo $polje; ?>">
                    
                        
                            <label><?php echo $element['label']; ?></label>
                            <div class="<?php echo $polje; ?>_diff">
                                <p>
                                    <small>Obstoječe stanje</small></p>
                                    <table  class="display dataTable " cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:90px;">Šifra</th>
                                                <th style="width:160px;">EAN</th>
                                                <th style="">Parametri</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="parameters_list">
                                        <?php 
                                        $combinations = $foo->parameterList(['artikel_id' => $data->artikel_id]);
                                        
                                                                                
                                        if(!empty($combinations[0]))
                                        {
                                            // new optimized code
                                            if (true) {
                                                $api_res = $handler->apiCall(MAIN_URL.'/api/artikel-params', ['auth' => $_SESSION['auth_key'] , 'artikel_id' => $data->artikel_id, 'attributes' => [], 'combinations' => $combinations ]);
                                                
                                                foreach ($api_res as $key => $item) 
                                                {
                                                    if(!empty($item->ean))
                                                    {
                                                        $check_stanje1[] = $item->ean;
                                                    }
                                                    
                                                    ?>
                                                    <tr>
                                                        <td><?php echo (!empty($item->sifra)) ? $item->sifra : ''; ?></td>
                                                        <td><?php echo (!empty($item->ean)) ? $item->ean : ''; ?></td>
                                                        <td style="vertical-align: middle;"><label>
                                                        <?php 

                                                        if($combinations[$key])
                                                        {
                                                            foreach ($combinations[$key] as $combo_key => $value) 
                                                            {
                                                                ?><?php 
                                                                $param = $foo->getParameter($value);
                                                                echo $param->naziv.'; ';
                                                            }
                                                        } 
                                                        
                                                        ?>
                                                        </label></td>
                                                        
                                                    </tr>
                                                    <?php
                                                }                
                                            } 
                                            // old code
                                            else {                                              
                                                foreach ($combinations as $key => $combo) 
                                                {
                                                    $api_res = $handler->apiCall(MAIN_URL.'/api/artikel-params', ['auth' => $_SESSION['auth_key'] , 'artikel_id' => $data->artikel_id, 'attributes' => $combo ]);

                                                    
                                                    if(!empty($api_res->ean))
                                                    {
                                                        $check_stanje1[] = $api_res->ean;
                                                    }
                                                    
                                                    ?>
                                                    <tr>
                                                        <td><?php echo (!empty($api_res->sifra)) ? $api_res->sifra : ''; ?></td>
                                                        <td><?php echo (!empty($api_res->ean)) ? $api_res->ean : ''; ?></td>
                                                        <td style="vertical-align: middle;"><label>
                                                        <?php 
                                                        //$check_stanje1[$api_res->ean][] = $value;

                                                        //if(DE) echo 'test: '.$combo;

                                                        if($combo)
                                                        {
                                                            foreach ($combo as $combo_key => $value) 
                                                            {
                                                                ?><?php 
                                                                $param = $foo->getParameter($value);
                                                                echo $param->naziv.'; ';

                                                                //$check_stanje1[$api_res->ean][] = $value;
                                                            }
                                                        } 
                                                        else 
                                                        {

                                                        }
                                                        
                                                        ?>
                                                        </label></td>
                                                        
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $artikel_sifra = null;
                                            $artikel_sifra_list = $foo->getArtikelSifra($data->artikel_id);
                                            if(!empty($artikel_sifra_list[0]))
                                            {
                                                $artikel_sifra = $artikel_sifra_list[0];
                                            }
                                            
                                            ?>
                                            <tr>
                                                <td><?php echo (!empty($artikel_sifra['sifra'])) ? $artikel_sifra['sifra'] : ''; ?></td>
                                                <td><?php echo (!empty($artikel_sifra['ean'])) ? $artikel_sifra['ean'] : ''; ?></td>
                                               
                                                <td></td>
                                            </tr>
                                        <?php
                                            if(!empty($artikel_sifra['ean']))
                                            {
                                                $check_stanje1[] = $artikel_sifra['ean'];
                                                //$check_stanje1[$artikel_sifra['ean']][] = '0#0';
                                            }
                                            
                                        }
                                        
                                        ?>
                                        </tbody>
				                    </table>
                                
                                <p>
                                    <small>Sprememba</small> </p>
                                    <div><?php

                                    // if(DE)
                                    // {
                                    //     print_r($predogled_artikel['ean_dobavitelj']);
                                    // }

                                    if(isset($predogled_artikel['ean_dobavitelj']) && $predogled_artikel['ean_dobavitelj'])
                                    {
                                        ?>
                                        <table  class="display dataTable " cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:90px;">Šifra</th>
                                                <th style="width:160px;">EAN</th>
                                                <th style="">Parametri</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                        <?php
                                        //print_r($predogled_artikel['ean_dobavitelj']);
                                        $combinations = [];
                                        foreach ($predogled_artikel['ean_dobavitelj'] as $key => $value)
                                        {
                                            $e = explode('#', $key);
                                            
                                            //$combinations[$param['tip_id']][] = $param['id'];
                                        }

                                        // if(DE)
                                        //     {
                                        //         print_r($predogled_artikel['ean_dobavitelj']);
                                        //     }

                                        if (count($predogled_artikel['ean_dobavitelj']) > 1) {
                                            $api_res = $handler->apiCall(MAIN_URL.'/api/artikel-params', ['auth' => $_SESSION['auth_key'] , 'artikel_id' => $data->artikel_id, 'attributes' => [], 'dobaviteli' => $predogled_artikel['ean_dobavitelj'] ]);
                                                                                        
                                            $i = 0;
                                            foreach ($api_res as $key => $item) 
                                            {
                                                $combo = explode('#', $key);
                                                $value = $predogled_artikel['ean_dobavitelj'][$key];
                                                ?>
                                                <tr>
                                                    <td><?php $handler->html_input(['name' => 'param_sifra['.$i.']', 'maxlength' => 6,  'type' => 'number', 'value' => (!empty($item->sifra)) ? $item->sifra : '', 'required' => false]); ?></td>
                                                    <td><?php echo $value; ?><?php $handler->html_input_hidden(['name' => 'param_ean['.$i.']', 'value' => $value]); ?></td>
                    
                                                    <td ><?php 
                                                    $check_stanje2[] = $value;
                                                    if($combo)
                                                    {
                                                        foreach ($combo as $combo_key => $parameter) 
                                                        {
                                                            $param_res = $foo->getParameter($parameter);
                                                            if($param_res)
                                                            {
                                                                echo $param_res->naziv.'; ';
                                                                ?><input type="hidden" name="param_attributes[<?php echo $i; ?>][<?php echo $combo_key; ?>]" value="<?php echo $parameter ?>" ><?php
                                                            }
                                                        }
                                                    }                                                  
                                                    ?></td>
                                                </tr>
                                                <?php
                                            }
                                            $i++;
                                        } 
                                        else { 
                                            $i = 0;
                                            foreach ($predogled_artikel['ean_dobavitelj'] as $key => $value)
                                            {
                                                // if(DE)
                                                // {
                                                //     print_r($key);
                                                // }
                                                $combo = explode('#', $key);
                                                //$combo = [];

                                                $api_res = $handler->apiCall(MAIN_URL.'/api/artikel-params', ['auth' => $_SESSION['auth_key'] , 'artikel_id' => $data->artikel_id, 'attributes' => $combo ]);
                    
                                                ?>
                                                <tr>
                                                    <td><?php $handler->html_input(['name' => 'param_sifra['.$i.']', 'maxlength' => 6,  'type' => 'number', 'value' => (!empty($api_res->sifra)) ? $api_res->sifra : '', 'required' => false]); ?></td>
                                                    <td><?php echo $value; ?><?php $handler->html_input_hidden(['name' => 'param_ean['.$i.']', 'value' => $value]); ?></td>
                    
                                                    <td ><?php 
                                                    //if(DE) echo $key;
                                                    //if(DE) print_R($combo);
                                                    $check_stanje2[] = $value;
                                                    if($combo)
                                                    {
                                                        
                                                        foreach ($combo as $combo_key => $parameter) 
                                                        {
                                                            //if(DE) print_R($parameter);
                                                            $param_res = $foo->getParameter($parameter);
                                                            if($param_res)
                                                            {
                                                                echo $param_res->naziv.'; ';
                                                                ?><input type="hidden" name="param_attributes[<?php echo $i; ?>][<?php echo $combo_key; ?>]" value="<?php echo $parameter ?>" ><?php
                                                            // $check_stanje2[$value][] = $value;
                                                            }

                                                            
                                                        }
                                                    }
                                                    

                                                    ?></td>
                                                    
                                                </tr>
                                                <?php

                                                //$ean_podatki['sprememba'][] = array('ean' => $value, 'velikost' => $e[0], 'barva' => $e[1]);

                                                //$res_spr[] = $value.'#'.$vel.'#'.$bar;

                                                $i++;
                                            }
                                        }
                                        ?>
                                        </tbody>
                                        </table><?php
                                    }
                                    ?></div>
                                
                           
                            </div>

                            

                            <?php //$handler->html_input_hidden( ['name' => 'file', 'value' => $element['file']]); ?>
                            <?php $handler->html_input_hidden( ['name' => 'type', 'value' => $element['type']]); ?>
                            <?php $handler->html_input_hidden( ['name' => 'field', 'value' => $polje]); ?>
                            <?php $handler->html_save_button($data, ['save' => true, 'discard' => true, 'reload' => true, 'form_name' => '.active form.edit_form_validate.'.$polje ]); ?> 
                        
                    
                </form>
                </div>
                <?php 
                
                $check_stanje1 = array_filter($check_stanje1);         
                $check_stanje2 = array_filter($check_stanje2);         
                //$check_stanje1 = $handler->sort_array($check_stanje1);
                //$check_stanje2 = $handler->sort_array($check_stanje2);
                sort($check_stanje1);
                sort($check_stanje2);

                // if(DE)
                // {
                //     echo '<div>'; print_r($check_stanje1);  echo '</div>';
                //     echo '<div>'; print_r($check_stanje2); echo '</div>';
                // }

                
                

                if(json_encode($check_stanje1) != json_encode($check_stanje2))
                {
                    $check_count++;
                }
                else
                {
                    //if(!DE):
                    ?>
                    <script type="text/javascript">
                    $('#eanPO').remove();
                    </script>
                    <?php
                   // endif;
                }



            }
        }
        //datoteke
        elseif($element['type'] == 3)
        {
            
            //$data['type'], $data['item_id']
            $datoteke = $foo->getFiles(['type' => $element['file'] , 'item_id' => $data->artikel_id]);
            $datoteke_org_array = [];
            if($datoteke)
            {
                foreach ($datoteke as $key => $value)
                {
                    if(!empty($value['path']))
                    {
                        $datoteke_org_array[] = $value['path'];
                    }
                }
            }

            // if(DE && $polje == 'path_dokument')
            // {
            //     print_r($datoteke_org_array);
            //     echo '<hr />';
            //     print_r($predogled_artikel[$polje]);
            // }

            // if(DE && $polje == 'kratki_opis')
            // {
            //     //echo 'test';
            //     //print_r($datoteke_org_array);
            //     echo '<hr />';
            //     print_r($predogled_artikel[$polje]);
            //     die();
            // }

            if(!isset($predogled_artikel[$polje]) || !$predogled_artikel[$polje] || empty($predogled_artikel[$polje][0]))
            {
                $predogled_artikel[$polje] = [];
            }

            $datoteke_org_array = array_filter($datoteke_org_array);
            $predogled_artikel[$polje] = array_filter($predogled_artikel[$polje]);


            if(array_diff($datoteke_org_array, $predogled_artikel[$polje]) || array_diff($predogled_artikel[$polje], $datoteke_org_array))
            {
                $check_count++;
                ?>
                <div class="col-lg-6">
                <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="updateSpremembe" method="post" class="edit_form_validate form-group <?php echo $polje; ?>">
                    
                        
                            <label><?php echo $element['label']; ?></label>
                            <div class="<?php echo $polje; ?>_diff">
                                <p>
                                    <small>Obstoječe stanje</small>
                                    <?php
                                    foreach ($datoteke_org_array as $key => $value): ?>
                                        <a href="<?php echo $value; ?>" target="_blank"><?php echo $value; ?></a><br />
                                    <?php
                                    endforeach;
                                    ?>
                                </p>
                                <p>
                                    <small>Sprememba</small>
                                    <?php
                                    foreach ($predogled_artikel[$polje] as $key => $value): ?>
                                        <a href="<?php echo $value; ?>" target="_blank"><?php echo $value; ?></a><br />
                                    <?php
                                    endforeach;
                                    ?>
                                </p>
                           
                            </div>

                            <script type="text/javascript">
                                $(".<?php echo $polje; ?>_diff").prettyTextDiff({
                                    cleanup: true,
                                    originalContent: '<?php echo (empty($artikel[$artikel_polje])) ? '' : json_encode($artikel[$artikel_polje]);?>',
                                    changedContent: '<?php echo (empty($predogled_artikel[$polje])) ? '' : json_encode($predogled_artikel[$polje]);?>'
                                });
                            </script>

                            <?php $handler->html_input_hidden( ['name' => 'file', 'value' => $element['file']]); ?>
                            <?php $handler->html_input_hidden( ['name' => 'type', 'value' => $element['type']]); ?>
                            <?php $handler->html_input_hidden( ['name' => 'field', 'value' => $polje]); ?>
                            <?php $handler->html_save_button($data, ['save' => true, 'discard' => true, 'reload' => true, 'form_name' => '.active form.edit_form_validate.'.$polje ]); ?> 
                        
                    
                </form>
                </div>
                <?php 

            }
        }
        elseif($element['type'] == 6)
        {
            if(isset($predogled_artikel[$polje]))
            {
                

                    $cat_pred = !empty($predogled_artikel[$polje]) ? json_encode($predogled_artikel[$polje]) : 0;
                    $cat = (str_replace(array('[', ']', '"'), '', $cat_pred));
                    $kategorije_predogled_naziv = (array)$foo->getKategorijaNaziv($cat);
                    $currentCategories = (array)$foo->getKategorija($artikel['id']);

                
                if($currentCategories != $kategorije_predogled_naziv )
                {
                    $check_count++;
                
                ?>
                    <div class="col-lg-6">
                        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="updateSpremembe" method="post" class="edit_form_validate form-group <?php echo $polje; ?>"> 
                            <label><?php echo $element['label']; ?></label>
                            <div class="<?php echo $polje; ?>_diff">
                                <p>
                                    <small>Obstoječe stanje</small><br/>
                                    <?php
                                    foreach ($currentCategories as $key => $value): ?>
                                        <?php echo $value->naziv; ?></a><br />
                                    <?php
                                    endforeach;
                                    ?>
                                </p>
                                <p>
                                    <small>Sprememba</small><br/>
                                    <?php
                                    foreach ($kategorije_predogled_naziv as $key => $value): ?>
                                        <?php echo $value->naziv; ?></a><br />
                                    <?php
                                    endforeach;
                                    ?>
                                </p>
                        
                            </div>
                            <?php $handler->html_input_hidden( ['name' => 'type', 'value' => $element['type']]); ?>
                            <?php foreach($kategorije_predogled_naziv as $key => $value): ?>
                                <?php $handler->html_input_hidden( ['name' => 'field', 'value' => $polje]); ?>
                            <?php endforeach; ?>
                            <?php $handler->html_save_button($data, ['save' => true, 'discard' => true, 'reload' => true, 'form_name' => '.active form.edit_form_validate.'.$polje ]); ?> 
                        </form>
                    </div>
                <?php
                }
            }
        }
        else
        {
            // if(DE && $polje == 'kratki_opis')
            // {
            //     //print_r($artikel);
            //     //echo $artikel_polje;
            //     //echo $artikel[$artikel_polje];
            //     if(isset($artikel['kratki_opis']))
            //     {
            //         echo 'test';
            //     }

            //    // echo 'test';
            //    // print_r($artikel[$artikel_polje]);
            //     //echo 'testxxxxx';
            //     //echo '<hr />';
            //    // print_r($predogled_artikel[$polje]);
            //     echo $handler->cleanTextDiff($predogled_artikel[$polje]);
            //     die();
            // }

            // print_r($predogled_artikel[$polje]); die;

            if(empty($predogled_artikel['arhiv']))
            {
                $predogled_artikel['arhiv'] = 0;
            }


            if(!empty($predogled_artikel['naziv']))
            {
                $predogled_artikel['naziv'] = str_replace("&amp;", "&", $predogled_artikel['naziv']);
                $predogled_artikel['naziv'] = $predogled_artikel['naziv'];
            }

            if( isset($predogled_artikel[$polje]) &&  $handler->cleanTextDiff($artikel[$artikel_polje]) != $handler->cleanTextDiff($predogled_artikel[$polje]))
            {
                $check_count++;
                ?>
                <div class="col-lg-6">
                <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="updateSpremembe" method="post" class="edit_form_validate form-group <?php echo $polje; ?>">
                    
                            <label><?php echo $element['label']; ?></label>
                            <div class="<?php echo $polje; ?>_diff">
                                <p>
                                    <small>Obstoječe stanje</small>
                                    <div ><?php 
                                    if($element['type'] == 4 && $bz && !empty($bz['naziv']))
                                    {
                                        echo $bz['naziv'];
                                    }
                                    else
                                    {
                                        echo $artikel[$artikel_polje];
                                    }
                                    
                                    ?></div>
                                </p>
                                
                                    <small>Sprememba</small>
                                    <?php if($element['type'] == 2): ?>
                                        <?php $handler->html_editor(['label' => '', 'name' => $polje.'sprememba', 'value' => htmlspecialchars($predogled_artikel[$polje])]); ?>
                                    <?php elseif($element['type'] == 4): ?>
                                        <div><?php echo $predogled_artikel['blagovna_znamka_label'];?></div>
                                        <?php $handler->html_input_hidden( ['name' => $polje.'sprememba', 'value' => $predogled_artikel[$polje]]); ?>
                                    <?php elseif (!empty($predogled_artikel['naziv'])): ?>
                                        <?php $handler->html_input(['label' => '', 'name' => $polje.'sprememba', 'value' => $predogled_artikel[$polje]]); ?>
                                    <?php else: ?>
                                        <?php $handler->html_input(['label' => '', 'name' => $polje.'sprememba', 'value' => htmlspecialchars($predogled_artikel[$polje])]); ?>
                                    <?php endif; ?>

                                <p>
                                    <small>Razlika</small>
                                    <div class="diff"></div>
                                </p>
                            </div>

                            <script type="text/javascript">
                                $(".<?php echo $polje; ?>_diff").prettyTextDiff({
                                    cleanup: true,
                                    originalContent: "<?php echo (empty($artikel[$artikel_polje])) ? '' : substr(json_encode(strip_tags ($artikel[$artikel_polje])), 1, -1);?>",
                                    changedContent: "<?php echo (empty($predogled_artikel[$polje])) ? '' : substr(json_encode(strip_tags ($predogled_artikel[$polje])), 1, -1);?>"
                                });
                            </script>

                            <?php $handler->html_input_hidden( ['name' => 'type', 'value' => $element['type']]); ?>
                            <?php $handler->html_input_hidden( ['name' => 'field', 'value' => $polje]); ?>
                            <?php $handler->html_save_button($data, ['save' => true, 'discard' => true, 'reload' => true, 'form_name' => '.active form.edit_form_validate.'.$polje ]); ?> 
                        
                    
                </form>
                </div>
                <?php 
            }
        }

        // if(DE)
        // {
        //     echo  $polje.': '.number_format(microtime(true)-$time_start,3); //die();
        // }
    }
    
	#######################################################
	?>
    </div>

            <?php if(!empty($predogled_artikel['textarea_komentar_spremembe'])): ?>
            <div class="row">
                <div class="col-lg-12">
                    <label>Razlog spremembe/popravka</label>
		            <div class="content_predogled"><?php echo $predogled_artikel['textarea_komentar_spremembe']; ?></div>
                </div>
            </div>
            <?php endif; ?>
        
    </div>
</div>

<?php
if($check_count == 0):

    //if(DE) die();
	$foo->predogledSpremembeDeaktiviraj($data->id);
?>
    <div style="color:#ff0000; margin:0 0 10px 20px; font-weight:bold;">Ni sprememb oz. vse spremembe so bile potrjene!</div>

    <script type="text/javascript">
        $("#save_button_all_changes").hide();
    </script>
<?php
endif; ?>
<script type="text/javascript">
var save_button_count = $('.save_button').length;
//console.log(save_button_count);
if (save_button_count > 0) {
    setInterval(function(){ 
        var el_count = $('.save_button').length;
        console.log(el_count);
        if (el_count < 1) {
            location.reload();
        }
        
    }, 1000);
}
    
</script>

<?php $handler->html_save_button($data, ['close' => true]); ?>

        