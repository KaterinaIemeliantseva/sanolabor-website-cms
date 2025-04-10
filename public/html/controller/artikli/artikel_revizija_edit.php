<?php 

    include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php'); 

    // PHP instance of class ArtikelRevizijaBAL
    $bal = new ArtikelRevizijaBAL();  
    $artikel = $bal->getArtikelRevizija($data->id);
    $kombinacije = $bal->getArtikelKombinacije($data->id);
    $galerija = $bal->getPhotos($data->id);
    $dokumenti = $bal->getDocuments($data->id);

    $pdf_icon = '<?xml version="1.0" encoding="iso-8859-1"?>
        <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
        <svg height="40px" width="40px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
            viewBox="0 0 512 512" xml:space="preserve">
        <path style="fill:#E2E5E7;" d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z"/>
        <path style="fill:#B0B7BD;" d="M384,128h96L352,0v96C352,113.6,366.4,128,384,128z"/>
        <polygon style="fill:#CAD1D8;" points="480,224 384,128 480,128 "/>
        <path style="fill:#F15642;" d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16
            V416z"/>
        <g>
            <path style="fill:#FFFFFF;" d="M101.744,303.152c0-4.224,3.328-8.832,8.688-8.832h29.552c16.64,0,31.616,11.136,31.616,32.48
                c0,20.224-14.976,31.488-31.616,31.488h-21.36v16.896c0,5.632-3.584,8.816-8.192,8.816c-4.224,0-8.688-3.184-8.688-8.816V303.152z
                M118.624,310.432v31.872h21.36c8.576,0,15.36-7.568,15.36-15.504c0-8.944-6.784-16.368-15.36-16.368H118.624z"/>
            <path style="fill:#FFFFFF;" d="M196.656,384c-4.224,0-8.832-2.304-8.832-7.92v-72.672c0-4.592,4.608-7.936,8.832-7.936h29.296
                c58.464,0,57.184,88.528,1.152,88.528H196.656z M204.72,311.088V368.4h21.232c34.544,0,36.08-57.312,0-57.312H204.72z"/>
            <path style="fill:#FFFFFF;" d="M303.872,312.112v20.336h32.624c4.608,0,9.216,4.608,9.216,9.072c0,4.224-4.608,7.68-9.216,7.68
                h-32.624v26.864c0,4.48-3.184,7.92-7.664,7.92c-5.632,0-9.072-3.44-9.072-7.92v-72.672c0-4.592,3.456-7.936,9.072-7.936h44.912
                c5.632,0,8.96,3.344,8.96,7.936c0,4.096-3.328,8.704-8.96,8.704h-37.248V312.112z"/>
        </g>
        <path style="fill:#CAD1D8;" d="M400,432H96v16h304c8.8,0,16-7.2,16-16v-16C416,424.8,408.8,432,400,432z"/>
        </svg>';
    $doc_icon = '<?xml version="1.0" encoding="iso-8859-1"?>
        <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
        <svg height="40px" width="40px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
            viewBox="0 0 512 512" xml:space="preserve">
        <path style="fill:#E2E5E7;" d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z"/>
        <path style="fill:#B0B7BD;" d="M384,128h96L352,0v96C352,113.6,366.4,128,384,128z"/>
        <polygon style="fill:#CAD1D8;" points="480,224 384,128 480,128 "/>
        <path style="fill:#50BEE8;" d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16
            V416z"/>
        <g>
            <path style="fill:#FFFFFF;" d="M92.576,384c-4.224,0-8.832-2.32-8.832-7.936v-72.656c0-4.608,4.608-7.936,8.832-7.936h29.296
                c58.464,0,57.168,88.528,1.136,88.528H92.576z M100.64,311.072v57.312h21.232c34.544,0,36.064-57.312,0-57.312H100.64z"/>
            <path style="fill:#FFFFFF;" d="M228,385.28c-23.664,1.024-48.24-14.72-48.24-46.064c0-31.472,24.56-46.944,48.24-46.944
                c22.384,1.136,45.792,16.624,45.792,46.944C273.792,369.552,250.384,385.28,228,385.28z M226.592,308.912
                c-14.336,0-29.936,10.112-29.936,30.32c0,20.096,15.616,30.336,29.936,30.336c14.72,0,30.448-10.24,30.448-30.336
                C257.04,319.008,241.312,308.912,226.592,308.912z"/>
            <path style="fill:#FFFFFF;" d="M288.848,339.088c0-24.688,15.488-45.92,44.912-45.92c11.136,0,19.968,3.328,29.296,11.392
                c3.456,3.184,3.84,8.816,0.384,12.4c-3.456,3.056-8.704,2.688-11.776-0.384c-5.232-5.504-10.608-7.024-17.904-7.024
                c-19.696,0-29.152,13.952-29.152,29.552c0,15.872,9.328,30.448,29.152,30.448c7.296,0,14.08-2.96,19.968-8.192
                c3.952-3.072,9.456-1.552,11.76,1.536c2.048,2.816,3.056,7.552-1.408,12.016c-8.96,8.336-19.696,10-30.336,10
                C302.8,384.912,288.848,363.776,288.848,339.088z"/>
        </g>
        <path style="fill:#CAD1D8;" d="M400,432H96v16h304c8.8,0,16-7.2,16-16v-16C416,424.8,408.8,432,400,432z"/>
        </svg>';
    
    $jpg_icon = '<?xml version="1.0" encoding="iso-8859-1"?>
    <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
    <svg height="40px" width="40px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
        viewBox="0 0 512 512" xml:space="preserve" fill="#000000">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <path style="fill:#E2E5E7;" d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z"></path>
            <path style="fill:#B0B7BD;" d="M384,128h96L352,0v96C352,113.6,366.4,128,384,128z"></path>
            <polygon style="fill:#CAD1D8;" points="480,224 384,128 480,128"></polygon>
            <path style="fill:#50BEE8;" d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16 V416z"></path>
        <g>
            <path style="fill:#FFFFFF;"d="M141.968,303.152c0-10.752,16.896-10.752,16.896,0v50.528c0,20.096-9.6,32.256-31.728,32.256 c-10.88,0-19.952-2.96-27.888-13.184c-6.528-7.808,5.76-19.056,12.416-10.88c5.376,6.656,11.136,8.192,16.752,7.936 c7.152-0.256,13.44-3.472,13.568-16.128v-50.528H141.968z"></path>
            <path style="fill:#FFFFFF;"d="M181.344,303.152c0-4.224,3.328-8.832,8.704-8.832H219.6c16.64,0,31.616,11.136,31.616,32.48 c0,20.224-14.976,31.488-31.616,31.488h-21.36v16.896c0,5.632-3.584,8.816-8.192,8.816c-4.224,0-8.704-3.184-8.704-8.816 L181.344,303.152L181.344,303.152z M198.24,310.432v31.872h21.36c8.576,0,15.36-7.568,15.36-15.504 c0-8.944-6.784-16.368-15.36-16.368H198.24z"></path>
            <path style="fill:#FFFFFF;"d="M342.576,374.16c-9.088,7.552-20.224,10.752-31.472,10.752c-26.88,0-45.936-15.344-45.936-45.808 c0-25.824,20.096-45.904,47.072-45.904c10.112,0,21.232,3.44,29.168,11.248c7.792,7.664-3.456,19.056-11.12,12.288 c-4.736-4.608-11.392-8.064-18.048-8.064c-15.472,0-30.432,12.4-30.432,30.432c0,18.944,12.528,30.464,29.296,30.464 c7.792,0,14.448-2.32,19.184-5.76V348.08h-19.184c-11.392,0-10.24-15.616,0-15.616h25.584c4.736,0,9.072,3.584,9.072,7.552v27.248 C345.76,369.568,344.752,371.712,342.576,374.16z"></path>
        </g>
            <path style="fill:#CAD1D8;"d="M400,432H96v16h304c8.8,0,16-7.2,16-16v-16C416,424.8,408.8,432,400,432z"></path>
        </g>
    </svg>';

    $png_icon = '<?xml version="1.0" encoding="iso-8859-1"?>
    <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
    <svg height="40px" width="40px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
        viewBox="0 0 512 512" xml:space="preserve" fill="#000000">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier"> 
            <path style="fill:#E2E5E7;" d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z">
            </path> <path style="fill:#B0B7BD;" d="M384,128h96L352,0v96C352,113.6,366.4,128,384,128z"></path> 
            <polygon style="fill:#CAD1D8;" points="480,224 384,128 480,128 "></polygon> 
            <path style="fill:#A066AA;" d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16 V416z"></path> 
        <g> 
            <path style="fill:#FFFFFF;" d="M92.816,303.152c0-4.224,3.312-8.848,8.688-8.848h29.568c16.624,0,31.6,11.136,31.6,32.496 c0,20.224-14.976,31.472-31.6,31.472H109.68v16.896c0,5.648-3.552,8.832-8.176,8.832c-4.224,0-8.688-3.184-8.688-8.832 C92.816,375.168,92.816,303.152,92.816,303.152z M109.68,310.432v31.856h21.376c8.56,0,15.344-7.552,15.344-15.488 c0-8.96-6.784-16.368-15.344-16.368L109.68,310.432L109.68,310.432z"></path> 
            <path style="fill:#FFFFFF;" d="M178.976,304.432c0-4.624,1.024-9.088,7.68-9.088c4.592,0,5.632,1.152,9.072,4.464l42.336,52.976 v-49.632c0-4.224,3.696-8.848,8.064-8.848c4.608,0,9.072,4.624,9.072,8.848v72.016c0,5.648-3.456,7.792-6.784,8.832 c-4.464,0-6.656-1.024-10.352-4.464l-42.336-53.744v49.392c0,5.648-3.456,8.832-8.064,8.832s-8.704-3.184-8.704-8.832v-70.752 H178.976z"></path> 
            <path style="fill:#FFFFFF;" d="M351.44,374.16c-9.088,7.536-20.224,10.752-31.472,10.752c-26.88,0-45.936-15.36-45.936-45.808 c0-25.84,20.096-45.92,47.072-45.92c10.112,0,21.232,3.456,29.168,11.264c7.808,7.664-3.456,19.056-11.12,12.288 c-4.736-4.624-11.392-8.064-18.048-8.064c-15.472,0-30.432,12.4-30.432,30.432c0,18.944,12.528,30.448,29.296,30.448 c7.792,0,14.448-2.304,19.184-5.76V348.08h-19.184c-11.392,0-10.24-15.632,0-15.632h25.584c4.736,0,9.072,3.6,9.072,7.568v27.248 C354.624,369.552,353.616,371.712,351.44,374.16z"></path> 
        </g> 
            <path style="fill:#CAD1D8;" d="M400,432H96v16h304c8.8,0,16-7.2,16-16v-16C416,424.8,408.8,432,400,432z"></path> 
        </g>
    </svg>';
?>   


<div class="content-box-header">
    <h3><?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
    <ul class="content-box-tabs">
        <li><a href="#container-1" class="current">Osnovno</a></li>
        <li><a href="#container-2" class="">Vsebina</a></li>
        <li><a href="#container-3" class="">Dokumentacija</a></li> 
        <li><a href="#container-4" class="">Galerija</a></li>
    </ul>
    <div class="clear"></div>
</div>

<div class="content-box-content" style="display: flex; flex-direction: column; height: 100%;">
    <div style="padding: 10px 0px;">
        <p class="default_text">Blagovna znamka: <b><?php echo $artikel->blagovna_znamka_naziv ?></b></span>
        <p class="default_text">Regulatorna skupina: <b style="color: #28a745;"><?php echo $artikel->regulatorna_skupina_naziv ?></b></span>
        <p class="default_text">Dobavitelj: <b><?php echo $artikel->dobavitelj_naziv ?></b></span>
    </div>

    <hr>

    <div class="main-container" style="display: flex; flex-direction: column;">
        <!-- Container OSNOVNO -->
        <div id="container-1">
            <h4>Osnovno</h4>
            <div class="main_table_container">
                <table class="main_table">
                    <thead>
                        <tr>
                            <th>Šifra</th>
                            <th>EAN</th>
                            <th>Parametri</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach($kombinacije as $kombinacija) {
                                echo("<tr class='row_parametri'>");
                                    $combinations = $bal->getParametersList($kombinacija->id);
                                        
                                    // Nova metoda - iskanje po EAN
                                    $api_res = $handler->apiCall(MAIN_URL.'/api/artikel-params', ['auth' => $_SESSION['auth_key'] , 'artikel_id' => $artikel->artikel_id, 'attributes' => [], 'artikel_ean' => $kombinacija->ean ]);

                                    if(!isset($api_res->sifra) && empty($api_res->sifra)) 
                                    {
                                        // Stara metoda - iskanje po kombinaciji
                                        $api_res = $handler->apiCall(MAIN_URL.'/api/artikel-params', ['auth' => $_SESSION['auth_key'] , 'artikel_id' => $artikel->artikel_id, 'attributes' => [], 'combinations' => $combinations ]);
                                    }

                                    if(isset($api_res->sifra)) 
                                        echo("<td><input type='number' id-data='" . $kombinacija->id . "' value='" . $api_res->sifra . "'></input></td>");
                                    else
                                        echo("<td><input type='number' id-data='" . $kombinacija->id . "'></input></td>");
                                    echo("<td>" . $kombinacija->ean . "</td>");
                                    if($kombinacija->velikost || $kombinacija->barva) {

                                        $velikost = (isset($kombinacija->velikost)) ? $kombinacija->velikost : "/";
                                        $barva = (isset($kombinacija->barva)) ? $kombinacija->barva : "/";

                                        echo("<td>" . $velikost . ", " . $barva . "</td>");
                                    }else {
                                        echo("<td>/</td>");
                                    }
                                echo("</tr>");
                            }
                        ?>
                    </tbody>
                </table>
                <?php
                    // $combinations = $bal->getParametersList($artikel->id);
                
                    // $api_res = $handler->apiCall('http://127.0.0.1:8001'.'/api/artikel-params', ['auth' => $_SESSION['auth_key'] , 'artikel_id' => $artikel->artikel_id, 'attributes' => [], 'combinations' => $combinations ]);
                    // print_r($api_res);
                ?>
            </div>
        </div>

        <!-- Container VSEBINA -->
        <div id="container-2" style="display: none;">
            <h4>Vsebina</h4>
            <div class="content_container" style="margin-bottom: 15px;">
                <div class="text_container">
                    <label>Kratki opis</label>
                    <hr>
                    <span><?php echo $artikel->kratki_opis ?></span>
                </div>
                <div class="text_container">
                    <label>Vsebina</label>
                    <hr>
                    <span><?php echo $artikel->vsebina ?></span>
                </div>
                <div class="text_container">
                    <label>Navodila</label>
                    <hr>
                    <span><?php echo $artikel->navodila ?></span>
                </div>
                <div class="text_container">
                    <label>Opozorila</label>
                    <hr>
                    <span><?php echo $artikel->opozorila ?></span>
                </div>
                <div class="text_container">
                    <label>Tehnične lastnosti</label>
                    <hr>
                    <span><?php echo $artikel->tehnicne_lastnosti ?></span>
                </div>
                <div class="text_container">
                    <label>Sestavine</label>
                    <hr>
                    <span><?php echo $artikel->sestavine ?></span>
                </div>
                <div class="text_container">
                    <label>Tabela velikosti</label>
                    <hr>
                    <span><?php echo $artikel->tabela_velikosti ?></span>
                </div>
                <div class="text_container">
                    <label>Komentar dobavitelja</label>
                    <hr>
                    <span><?php echo $artikel->dobavitelj_komentar ?></span>
                </div>
            </div>
        </div>

        <!-- Container DOKUMENTACIJA -->
        <div id="container-3" style="display: none;">
            <h4>Dokumentacija</h4>
            <div class="main_table_container">
                <table class="main_table">
                    <thead>
                        <tr>
                            <th>Datoteka</th>
                            <th>Naslov</th>
                            <th>Vrsta dokumenta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            function startsWith($string, $startString) {
                                return strpos($string, $startString) === 0;
                            }
                            
                            foreach($dokumenti as $file) {
                                echo "<tr class='gallery_row dokumentacija_tab'>";
                                $fileExtension = pathinfo($file->path, PATHINFO_EXTENSION);
                                
                                if(startsWith($file->path, "/public/")) {
                                    $file_path = "https://www.sanolabor.si". $file->path;
                                } else {
                                    $file_path = $file->path;
                                }

                                if (in_array($fileExtension, ['pdf'])) {
                                    echo "<td><a href='" . $file_path . "' target='_blank'>" . $pdf_icon . "</a></td>";
                                } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                                    echo "<td><a href='" . $file_path . "' target='_blank'>" . $doc_icon . "</a></td>";
                                } elseif (in_array($fileExtension, ['jpg', 'jpeg'])) {
                                    echo "<td><a href='" . $file_path . "' target='_blank'>" . $jpg_icon . "</a></td>";
                                } elseif (in_array($fileExtension, ['png', 'gif'])) {
                                    echo "<td><a href='" . $file_path . "' target='_blank'>" . $png_icon . "</a></td>";
                                } else {
                                    echo "<td><a href='" . $file_path . "' target='_blank'><img src='path/to/generic_file_icon.png' alt='File' class='file_icon'></a></td>";
                                }
                                
                                echo "<td><a class='file_column' href='" . $file_path . "'target='_blank'>" . basename($file->path) . "</a></td>";
                                echo "<td><span style='color: #28a745;'>".$bal->getFileType($file->type)."</span></td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Container GALERIJA -->
        <div id="container-4" style="display: none;">
            <h4>Galerija</h4>
            <div class="main_table_container">
                <table class="main_table">
                    <thead>
                        <tr>
                            <th>Slika</th>
                            <th>Naslov</th>
                            <th>Zaporedje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach($galerija as $slika) {
                                
                                echo "<tr class='gallery_row'>";

                                if(startsWith($slika->path, "/public/")) {
                                    echo "<td><img src='" . "https://www.sanolabor.si". $slika->path . "' alt='" . $slika->path . "' class='img_preview'></td>";
                                    echo "<td style='width: 100%;'><a href='" . "https://www.sanolabor.si". $slika->path . "' target='_blank'>" . basename($slika->path) . "</a></td>";
                                } else {
                                    echo "<td><img src='" . $slika->path . "' alt='" . $slika->path . "' class='img_preview'></td>";
                                    echo "<td style='width: 100%;'><a href='" . $slika->path . "' target='_blank'>" . basename($slika->path) . "</a></td>";
                                }
                                
                                echo "<td style='width: 40px; text-align: center;'>" . $slika->order . "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div id="imageModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <img id="img01" class="popup-image">
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div id="comment_modal" class="modal">
        <div class="comment_container">
            <h4>Komentar:</h4>
            <textarea id="comment_editor" class="comment_editor" spellcheck="false"></textarea>
            <div style="display: flex; justify-content: end; gap: 5px; padding-top: 10px;">
                <button class="btn btn-back" onclick="closeCommentModal()"> Prekliči </button>
                <button class="btn btn-success" onclick="saveRevison('reject')"> Oddaj zavrnitev </button>
            </div>
        </div>
    </div>

    <div id="confirm_modal" class="modal">
        <div class="confirm_modal">
            <h4>Potrditev artikla</h4>
            <span>Ste prepričani da želite potrditi izbran artikel?</span>
            <div style="display: flex; justify-content: end; gap: 5px; padding-top: 20px;">
                <button class="btn btn-back" onclick="closeConfirmModal()"> Prekliči </button>
                <button class="btn btn-success" onclick="saveRevison('approve')"> Potrdi </button>
            </div>
        </div>
    </div>

    <!-- Main button group -->
    <div style="width: 100%; display: flex; flex-direction: column; align-items: end; justify-items: end;">
        <div style="display: flex; padding: 0 10px; text-align: center; color: #ffffff;" id="message_box"></div>
    </div>
    <div class="button_container">
        
        <button id="close_button" class="btn btn-back close_edit"> Zapri </button>
        <span class="expand_filler"></span>
        <div>
            <button class="btn btn-success" onclick="confirmModal()"> Potrdi </button>
            <button class="btn btn-danger" onclick="commentReject()"> Zavrni </button>
        </div>
        
    </div>
</div>

<style>
    figure.image_resized > img {
        width: auto !important;
        max-width: 100% !important; /* or 100% of parent */
        height: auto !important;
    }
    .btn {
        padding-top: 6px !important;
    }
    .btn-back {
        color: #ffffff;
        background-color: #474749;
        border-color: #5a5a5a;
    }
    .btn-back:hover {
        color: #ffffff;
        background-color: #474749;
        border-color: #474749;
    }
    .default_text {
        font-size: 14px; 
        color: #666;

        margin: 0;
    }
    .expand_filler,
    .content-container {
        flex-grow: 1; 
        display: flex;
    }
    .content-container {
        flex-direction: column;
    }
    .button_container {
        margin-top: 5px;
        display: flex; 
        gap: 3px; 
        display: flex; 
        flex-direction: row;
    }
    .main-container {
        padding: 10px 0px;
    }
    .main-container h4 {
        color: #333;
    }
    .table_parametri {
        margin-bottom: 20px;
        width: 100%;
        max-width: 600px;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        border-radius: 2px;
        overflow: hidden;
    }
    .table_parametri th, .table_parametri td {
        padding: 10px;
        text-align: left;
        font-size: 14px;
        color: #333;
    }
    .table_parametri th {
        border-bottom: 2px solid #ddd;
        font-weight: 600;
        font-size: 14px;
        background-color: #f4f4f4;
    }
    .table_parametri th:nth-child(1) {
        width: 80px !important;
    }
    .table_parametri th:nth-child(3),
    .table_parametri td:nth-child(3) {
        text-align: center;
    }
    .row_parametri {
        font-size: 14px;
        border-bottom: 1px solid #ddd;
    }
    .row_parametri:nth-child(even) {
        background-color: #f9f9f9;
    }
    .row_parametri:hover {
        background-color: #f0f0f0;
    }
    .row_parametri input[type="number"] {
        color: #333;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px 10px;
    }
    .row_parametri input[type="number"]:focus {
        border-radius: 4px;
        padding: 4px 10px;
        box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25);
    }
    .text_container {
        border: 1px solid #ddd; 
        border-radius: 4px; 
        padding: 15px; 
        background-color: #fff; 
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        background-color: #f9f9f9;
    }
    .text_container hr{
        margin-bottom: 7px;
    }
    .text_container label {
        width: 100%;
        font-weight: 600; 
        font-size: 15px; 
        color: #333; 
        margin-bottom: 0;
    }
    .text_container span {
        font-size: 14px; 
        color: #666;
    }
    .content_container {
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); 
        gap: 20px; 
        width: 100%;
        max-width: 1400px;
    }
    .main_table_container {
        width: auto;
        max-width: 600px;
        margin: 20px 10px;
        border-radius: 2px;
        overflow: hidden;
        background-color: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .main_table {
        width: 100%;
        border-collapse: collapse;
    }
    .main_table th, .main_table td {
        padding: 12px 15px !important;
        text-align: left;
        font-size: 14px;
        color: #333;
        border-bottom: 1px solid #ddd;
        align-content: center;
    }
    .main_table td:first-child, .main_table th:first-child {
        width: 100px;
        text-align: left;
    }
    .main_table td:nth-child(3), .main_table th:nth-child(3) {
        width: 180px;
        text-align: left;
    }
    .main_table th {
        background-color: #f4f4f4;
        font-weight: 600;
    }
    .gallery_row:nth-child(even) {
        background-color: #f9f9f9;
    }
    .img_preview {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }
    .dokumentacija_tab td:nth-child(3), .dokumentacija_tab th:nth-child(3) {
        width: 200px !important;
    }
    .img_preview:hover{
        cursor: pointer;
    }
    .gallery_row:hover {
        background-color: #f0f0f0;
    }
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0;
        top: 0;
        width: 100%;
        height: 100%; 
        overflow: auto; 
        background-color: rgba(0, 0, 0, 0.8); 
    }
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }
    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto; 
        background-color: rgba(0, 0, 0, 0.8); 
        justify-content: center; 
        align-items: center;
    }
    .modal-content {
        margin: auto;
        display: block;
        position: relative;
        width: 80%; 
        max-width: 500px; 
        background-color: white;
        padding: 10px; 
        border-radius: 8px; 
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        text-align: center; 
    }
    .popup-image {
        width: 100%; 
        height: auto;
    }
    .close {
        right: 15px;
        top: 11px;
        color: black; 
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
    }
    .comment_container {
        position: absolute;
        top: 33%;
        margin: auto;
        width: 80%; 
        max-width: 450px; 
        background-color: white;
        padding: 15px 20px; 
        border-radius: 8px; 
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        
    }
    .comment_container h4 {
        margin: 0;
    }
    .comment_editor{
        width: 100%;
        height: 150px;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px 10px;
        font-size: 14px;
        resize: none;
    }
    .confirm_modal {
        position: absolute;
        top: 33%;
        margin: auto;
        width: 80%; 
        max-width: 350px; 
        background-color: white;
        padding: 20px;
        border-radius: 8px; 
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    }
    .confirm_modal h4 {
        margin: 0;
        color: #333;
        margin-bottom: 10px;
    }
    .confirm_modal span {
        font-size: 14px;
    }
    .file_column {
        width: 350px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>

    var modal = document.getElementById("imageModal");
    var comment_modal = document.getElementById("comment_modal");
    var confirm_modal = document.getElementById("confirm_modal");
    
    var img = document.getElementsByClassName("img_preview");
    var modalImg = document.getElementById("img01");

    for (let i = 0; i < img.length; i++) {
        img[i].onclick = function(){
            modal.style.display = "flex";
            modalImg.src = this.src;
        }
    }

    var span = document.getElementsByClassName("close")[0];

    span.onclick = function() { 
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
        if (event.target == comment_modal) {
            comment_modal.style.display = "none";
        }
        if (event.target == confirm_modal) {
            confirm_modal.style.display = "none";
        }

    }
    function saveRevison(action) {
        var id = <?php echo $data->id; ?>;
        var commnet = $('#comment_editor').val();
        var parameters = getSifraChanges();

        if(action == 'approve') {
            productRevision({ 'id': id, 'action': action, 'parameters': parameters }); 
            confirm_modal.style.display = "none";
        }
        if(action == 'reject') {
            comment_modal.style.display = "none";
            productRevision({ 'id': id, 'action': action, 'comment': commnet, 'parameters': parameters }); 
            comment_modal.style.display = "none";
        }
    }

    function getSifraChanges() {
        var kombinacije = <?php echo json_encode($kombinacije); ?>;

        const rows = document.querySelectorAll('.main_table .row_parametri');
        const data = [];

        rows.forEach(row => {
            const sifraInput = row.querySelector("td input[type='number']");
            const sifra = sifraInput ? sifraInput.value : null;
            const id = sifraInput ? sifraInput.getAttribute('id-data') : null;
            const ean = row.cells[1] ? row.cells[1].innerText : null;
            const parametri = row.cells[2] ? row.cells[2].innerText : null;

            data.push({
                id: id,
                sifra: sifra,
                ean: ean,
                parametri: parametri
            });
        });

        kombinacije = kombinacije.map(item => {
            const tableEntry = data.find(dataItem => parseInt(dataItem.id) === parseInt(item.id));
            if (tableEntry) {
                item.sifra = tableEntry.sifra;
            }
            return item;
        });
        return kombinacije;;
    }

    function commentReject() {
        comment_modal.style.display = "flex";
    }
    function closeCommentModal() {
        comment_modal.style.display = "none";
    }
    function confirmModal() {
        confirm_modal.style.display = "flex";
    }
    function closeConfirmModal() {
        confirm_modal.style.display = "none";
    }
</script>



