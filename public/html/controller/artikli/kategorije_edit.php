<?php
include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');
$bal = new ArtikelKategorijaBAL();  

if(!isset($data->id)) {
    $data = new stdClass();
    $data->id = $bal->getMaxCategoryId();
}

$_category_cards = $bal->getCategoryCardsList($data->id);
$_category_list = $bal->getCategorysList();

// Fetch the modules and their custom tiles for this category
$modules = $bal->getModulesAndTilesByCategory($data->id);

function drawCategoryCardsTable($id) {
    $bal = new ArtikelKategorijaBAL(); 
    $_category_cards = $bal->getCategoryCardsList($id);
    echo("<table class='main_table' style='margin-top: 10px;'>");
    echo("<thead>");
    echo("<tr>");
    echo("<th>ID</th>");
    echo("<th>Naziv</th>");
    echo("<th>Uredi</th>");
    echo("</tr>");
    echo("</thead>");
    echo("<tbody>");
    foreach($_category_cards as $card) {
        echo("<tr class='table_row'>");
                echo("<td>" . $card->id . "</td>");
                echo("<td>" . $card->naziv . "</td>");
                echo("<td><div class='edit_button' onclick='editCategoryCard(" . $card->id . ")'>Uredi</div><div class='edit_button' onclick='deleteCategoryCard(" . $card->id . ")'>Izbriši</div></td>");
        echo("</tr>");
    }
    echo("<tbody>");
}                       
?>
<div class="content-box-header">
    <h3><?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?></h3>
 	<ul class="content-box-tabs">
 	    <li><a href="#tab1" class="default-tab current">Uredi</a></li>
        <!-- <li><a href="#tab2" class="default-tab">Bannerji</a></li> -->
        <li><a href="#tab3" class="default-tab">Ploščice kategorija</a></li>
        <li><a href="#tab4" class="default-tab">Ploščice po meri</a></li>
        <li><a href="#tab5" class="default-tab">Izdelki izbrano za vas</a></li>
        <li><a href="#tab6">Bannerji</a></li>
 	</ul>
     <div class="clear"></div>
</div>

<div class="content-box-content">
    <div class="tab-content default-tab active" id="tab1">
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="update" method="post" class="edit_form_validate form-group">
            <div class="row">
                <div class="col-lg-6">
                    <?php if($data): ?><?php $handler->html_label(['label' => 'Slug', 'value' => (!empty($data->nicename)) ? $data->nicename : '']); ?><?php endif; ?>
                    <?php
                    $opt = ['label' => 'Nadrejena kategorija', 'name' => 'parent', 'url' => '/webapp/artikel/s2/kategorije', 'required' => false];
                    if(!empty($data->parent))
                    {
                        $selected_kat = $foo->getSingle($data->parent);
                        $opt['selected'] = ['id' => $selected_kat->id, 'text' => $selected_kat->naziv];
                    }
                    else if(!empty($_POST['data']['parent_kat_id']) && !empty($_POST['data']['parent_kat_text']))
                    {
                        $opt['selected'] = ['id' => $_POST['data']['parent_kat_id'], 'text' => $_POST['data']['parent_kat_text']];
                    }

                    $handler->html_select2($opt);
                    ?>

                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>
                    <?php $handler->html_input(['label' => 'Preusmeritev', 'name' => 'preusmeritev', 'value' => (!empty($data->preusmeritev)) ? $data->preusmeritev : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Vrstni red', 'name' => 'sort', 'type' => 'number', 'value' => (!empty($data->sort)) ? $data->sort : '10', 'required' => true]); ?>
                    <hr />
                    <?php $handler->html_single_file_upload(['label' => 'Banner', 'name' => 'banner', 'value' => (!empty($data->banner)) ? $data->banner : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Povezava', 'name' => 'banner_povezava', 'value' => (!empty($data->banner_povezava)) ? $data->banner_povezava : '']); ?>
                    <hr />
                    <?php $handler->html_single_file_upload(['label' => 'Banner v navigaciji', 'name' => 'banner_nav', 'value' => (!empty($data->banner_nav)) ? $data->banner_nav : '', 'required' => false]); ?>
                    <?php $handler->html_input(['label' => 'Povezava', 'name' => 'banner_povezava_navigacija', 'value' => (!empty($data->banner_povezava_navigacija)) ? $data->banner_povezava_navigacija : '']); ?>
                </div>
                <div class="col-lg-6">
                    <?php $handler->html_editor(['label' => 'Opis', 'name' => 'opis', 'value' => (!empty($data->opis)) ? $data->opis : '']); ?>
                    <?php 
                        if(isset($data->nicename) && ($data->nicename == "prehranska-dopolnila" || $data->nicename == "medicinski-pripomocki")) 
                        {
                            echo "<br />";
                            echo$handler->html_input(['label' => 'Regulatorna trditev', 'name' => 'regulatorna_trditev', 'value' => (!empty($data->regulatorna_trditev)) ? $data->regulatorna_trditev : '']);
                        } else 
                        {   
                            if(!isset($data->nicename)) {
                                echo "<br />";
                                echo$handler->html_input(['label' => 'Regulatorna trditev', 'name' => 'regulatorna_trditev', 'value' => (!empty($data->regulatorna_trditev)) ? $data->regulatorna_trditev : '']);
                            }
                        }
                    ?>  
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php $handler->html_checkbox(['label' => 'Prikaz elementa z logotipi', 'name' => 'show_logo', 'status' => (!empty($data->show_logo))]); ?><br />
                    <?php $handler->html_checkbox(['label' => 'Prikaz v meniju', 'name' => 'menu', 'status' => (!empty($data->menu))]); ?><br />
                    <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>
                </div>
            </div>
            <?php $handler->html_input_hidden( ['name' => 'nicename', 'value' => (!empty($data->nicename)) ? $data->nicename : '']); ?>
            <?php $handler->html_save_button($data); ?>
        </form>
    </div>
    <div class="tab-content hide" id="tab2">
        <div class="row">
            <div class="col-lg-12">

            </div>
        </div>
    </div>
    <div class="tab-content hide" id="tab3">
        <div class="row">
            <div class="col-lg-12">
                <button class="btn btn-success" onclick="newCategoryCard()">Nova ploščica</button>
                <div id="category_cards_table">
                </div>
            </div>
             
        </div>
        <div style="width: 100%; display: flex; flex-direction: column; align-items: end; justify-items: end;">
            <div style="display: flex; padding: 0 10px; text-align: center; color: #ffffff;" id="message_box">test</div>
        </div>
        <!-- Nova kartica - ploščica -->
        <div id="addCategoryCardModal" class="modal">
            <div class="card_modal">
                <div class="card_wrapper">
                    <h4 class="modal_title">Nova ploščica</h4>

                    <!-- <label style="margin-top: 10px;">Tip kartice</label>
                    <select class="select_input" id="card_type"> -->
                            <!-- <option value=""  disabled hidden>Izberi tip kartice</option> -->
                            <!-- <option value="1" selected>Kartica s sliko</option>
                            <option value="2">Kartica z besedilom</option>
                    </select> -->

                    <?php 
                        if(isset($_category_list) && !empty($_category_list))
                        {   
                            echo("<label style='margin-top: 10px;' id='card_category_id_label'>Prikazana kategorija</label>");
                            echo("<select class='select_input' id='card_category_id'>");
                            echo("<option value='' selected disabled hidden>Izberi kategorijo</option>");
                            foreach($_category_list as $category) {
                                echo("<option value=" . $category->id . ">". $category->naziv ."</option>");
                            }
                            echo("</select>");
                        }
                    ?>
                    <label style="margin-top: 10px;">Naziv</label>
                    <input type="text" class="text_input" id="card_title" />

                    <!-- Vrstni red -->
                    <label for="card_order">Vrstni red:</label>
                    <input type="number" class="text_input" id="card_order" name="order" required>

                    <!-- Vsebina ploščice -->
                    <label for="card_vsebina">Vsebina ploščice:</label>
                    <textarea class="text_editor" id="card_vsebina" name="vsebina"></textarea>

                    <!-- <label style="margin-top: 10px;" id="card_content_label">Vsebina</label>
                    <textarea class="text_editor" spellcheck="false" id="card_content"></textarea> -->
                    <?php $handler->html_single_file_upload(['label' => 'Slika', 'name' => 'card_banner', 'value' => '' , 'required' => false]); ?>
                    <!-- <div id="custom_artikli_wrapper">
                        <?php// $handler->html_select2(['id' => 'custom_artikli', 'label' => 'Izberi artikle iz seznama', 'name' => 'custom_artikli[]', 'multiple' => true, 'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect', 'get_selected' => ['id' => (!empty($data->id)) ? $data->id : 0, 'c' => 'Artikel', 'm' => 'getPovezaniSelected']]); ?>                   
                    </div> -->
                    
                </div>
                <div style="display: flex; justify-content: end; gap: 5px; padding-top: 20px;">
                    <button class="btn btn-back" onclick="closeCategoryCardModal()"> Prekliči </button>
                    <button class="btn btn-success" onclick="saveCategoryCard('create')"> Dodaj </button>
                </div>
            </div>    
        </div>
        <!-- Edit kartica - ploščica -->
        <div id="editCategoryCardModal" class="modal">
            <div class="card_modal">
                <div class="card_wrapper">
                    <h4 class="modal_title">Urejanje kartice</h4>

                    <!-- <label style="margin-top: 10px;">Tip kartice</label> -->
                    <!-- <select class="select_input" id="card_type_edit">
                            <option value="1">Kartica s sliko</option>
                            <option value="2">Kartica z besedilom</option>
                    </select> -->

                    <?php 
                        if(isset($_category_list) && !empty($_category_list))
                        {   
                            echo("<label style='margin-top: 10px;' id='card_category_id_edit_label'>Prikazana kategorija</label>");
                            echo("<select class='select_input' id='card_category_id_edit'>");
                            echo("<option value='' selected disabled hidden>Izberi kategorijo</option>");
                            foreach($_category_list as $category) {
                                echo("<option value=" . $category->id . ">". $category->naziv ."</option>");
                            }
                            echo("</select>");
                        }
                    ?>
                    <label style="margin-top: 10px;">Naziv</label>
                    <input type="text" class="text_input" id="card_title_edit"></input>

                    <!-- Vrstni red -->
                    <label for="card_order_edit">Vrstni red:</label>
                    <input type="number" class="text_input" id="card_order_edit" name="order" required>

                    <!-- Vsebina ploščice -->
                    <label for="card_vsebina_edit">Vsebina ploščice:</label>
                    <textarea class="text_editor" id="card_vsebina_edit" name="vsebina"></textarea>

                    <!-- <label style="margin-top: 10px;" id="card_content_edit_label">Vsebina</label> -->
                    <!-- <textarea class="text_editor" spellcheck="false" id="card_content_edit"></textarea> -->

                    <div id="card_banner_edit_preview" style="margin-top: 10px;"></div>

                    <?php $handler->html_single_file_upload(['label' => 'Slika', 'name' => 'card_banner_edit', 'value' => '' , 'required' => false]); ?>
                    <!-- <div id="custom_artikli_wrapper_edit">
                        <?php// $handler->html_select2(['id' => 'custom_artikli', 'label' => 'Izberi artikle iz seznama', 'name' => 'custom_artikli[]', 'multiple' => true, 'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect', 'get_selected' => ['id' => (!empty($data->id)) ? $data->id : 0, 'c' => 'Artikel', 'm' => 'getPovezaniSelected']]); ?>                   
                    </div> -->
                    
                </div>
                <div style="display: flex; justify-content: end; gap: 5px; padding-top: 20px;">
                    <button class="btn btn-back" onclick="closeCategoryCardEditModal()"> Prekliči </button>
                    <button class="btn btn-success" id="save_button_edit"> Shrani spremembe </button>
                </div>
            </div>    
        </div>
        <!-- Delete kartica - ploščica -->
        <div id="deleteCategoryCardModal" class="modal">
            <div class="card_modal">
                <div class="card_wrapper">
                    <h4 class="modal_title">Izbris kartice</h4>

                    <span>Ste prepričani daželite izbrisati izbrano kartico?</span>
                </div>
                <div style="display: flex; justify-content: end; gap: 5px; padding-top: 20px;">
                    <button class="btn btn-back" onclick="closeCategoryCardDeleteModal()"> Prekliči </button>
                    <button class="btn btn-success" id="delete_button"> Izbrisi </button>
                </div>
            </div>    
        </div>
               
    </div>
    <div class="tab-content hide" id="tab4">
        <div class="row">
            <div class="col-lg-12">
                <!-- New Module Form -->
                <div id="newModuleForm" style="display: none; margin-top: 20px;">
                    <h3>Ustvari novi modul</h3>
                    <form id="moduleForm">
                        <label for="moduleTitle">Naziv modula:</label>
                        <input type="text" id="moduleTitle" name="moduleTitle" class="text_input" style="width:200px;" required>
                        <br><br>
                        <label for="moduleOrder">Vrstni red:</label>
                        <input type="number" id="moduleOrder" name="moduleOrder" class="text_input" style="width:50px;" required>

                        <input type="hidden" name="categoryId" value="<?php echo htmlspecialchars($data->id); ?>">
                        <br><br>
                        <button type="submit" class="btn btn-success">Shrani modul</button>
                        <button type="button" id="cancelModuleButton" class="btn btn-back">Zapri</button>
                    </form>
                </div>

                <!-- Edit Module Form -->
                <div id="editModuleForm" style="display: none; margin-top: 20px;">
                    <h3>Uredi modul</h3>
                    <form id="editModuleFormElement">
                        <input type="hidden" id="editModuleId">
                        <label for="editModuleTitle">Naziv modula:</label>
                        <input type="text" id="editModuleTitle" name="editModuleTitle" class="text_input" style="width:200px;" required>
                        <br><br>
                        <label for="editModuleOrder">Vrstni red:</label>
                        <input type="number" id="editModuleOrder" name="moduleOrder" class="text_input" style="width:50px;" required>

                        <br><br>
                        <button type="submit" class="btn btn-success">Shrani spremembe</button>
                        <button type="button" id="cancelEditModuleButton" class="btn btn-back">Prekliči</button>
                    </form>
                </div>


                <!-- Add New Module Button -->
                <button id="addModuleButton" class="btn btn-success">Dodaj modul</button>

                <!-- Modules List Container -->
                <div id="modulesList">
                    <?php
                    // Fetch and display modules as before
                    if (!empty($modules)) {
                        foreach ($modules as $module) {
                            echo '<div class="module-container" data-module-id="' . $module['id'] . '" style="margin-top: 20px;">';

                            echo '<h3>Modul: ' . htmlspecialchars($module['naziv']) . '</h3>';

                            // Add Edit and Delete buttons
                            echo '<div class="module-actions">';
                            echo '<button class="btn btn-primary edit-module-button margin-right-3" data-module-id="' . $module['id'] . '">Uredi modul</button>';
                            echo '<button class="btn btn-danger delete-module-button margin-right-3" data-module-id="' . $module['id'] . '">Izbriši modul</button>';
                            echo '<button class="btn btn-success add-tile-button" data-module-id="' . $module['id'] . '">Dodaj ploščico</button>';
                            echo '</div>';

                            if (!empty($module['tiles'])) {
                                echo '<table class="main_table">';
                                // Table Headers
                                echo '<tr>';
                                echo '<th class="col-id">ID ploščice</th>';
                                echo '<th class="col-order">Vrstni red</th>';
                                echo '<th class="col-nicename">Nicename</th>';
                                echo '<th class="col-naziv">Naziv ploščice</th>';
                                echo '<th class="col-vsebina">Vsebina ploščice</th>';
                                echo '<th class="col-vsebina"></th>';
                                echo '</tr>';

                                foreach ($module['tiles'] as $tile) {
                                    echo '<tr class="table_row" data-tile-id="' . $tile['id'] . '">';
                                    echo '<td class="col-id">' . htmlspecialchars($tile['id']) . '</td>';
                                    echo '<td class="col-order">' . htmlspecialchars($tile['order']) . '</td>';
                                    echo '<td class="col-nicename">' . htmlspecialchars($tile['nicename']) . '</td>'; // Nicename column
                                    echo '<td class="col-naziv">' . htmlspecialchars($tile['naziv']) . '</td>';
                                    echo '<td class="col-vsebina">' . htmlspecialchars($tile['vsebina']) . '</td>';
                                    // Add edit and delete buttons
                                    echo '<td class="col-actions">';
                                    echo '<button class="btn btn-primary edit-tile-button margin-bottom-3" data-tile-id="' . $tile['id'] . '">Uredi</button>';
                                    echo '<button class="btn btn-danger delete-tile-button" data-tile-id="' . $tile['id'] . '">Izbriši</button>';
                                    echo '</td>';
                                    echo '</tr>';
                                }

                                echo '</table>';
                            } else {
                                echo '<p>Ta modul še nima ploščic po meri.</p>';
                            }

                            echo '</div>';
                        }
                    } else {
                        echo '<p>Kategorija nima še nobenih modulov za ploščice po meri.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Add Tile Modal -->
        <div id="addTileModal" class="modal">
            <div class="card_modal">
                <div class="card_wrapper">
                    <h4 class="modal_title">Dodaj novo ploščico</h4>
                    <form id="addTileForm">
                        <input type="hidden" id="tileModuleId" name="moduleId">

                        <label for="tileNaziv">Naziv ploščice:</label>
                        <input type="text" class="text_input" id="tileNaziv" name="naziv" required>

                        <label for="tileOrder">Vrstni red:</label>
                        <input type="number" id="tileOrder" name="order" class="text_input" required>

                        <label for="tileVsebina">Vsebina ploščice:</label>
                        <textarea class="text_editor" id="tileVsebina" name="vsebina"></textarea>

                        <!-- File uploader field -->
                        <?php $handler->html_single_file_upload([
                            'label' => 'Slika',
                            'name' => 'tileImage',
                            'value' => '',
                            'required' => false
                        ]); ?>

                        <!-- Article Multi-select Field -->
                        <?php $handler->html_select2([
                            'id' => 'povezani_artikli',
                            'label' => 'Izberi artikle iz seznama',
                            'name' => 'povezani_artikli[]',
                            'multiple' => true,
                            'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect'
                        ]); ?>
                    </form>
                </div>
                <!-- Add the buttons here -->
                <div style="display: flex; justify-content: end; gap: 5px; padding-top: 20px;">
                    <button class="btn btn-back" id="cancelAddTileButton"> Prekliči </button>
                    <button class="btn btn-success" id="saveTileButton"> Shrani ploščico </button>
                </div>
            </div>
        </div>


        <!-- Edit Tile Modal -->
        <div id="editTileModal" class="modal">
            <div class="card_modal">
                <div class="card_wrapper">
                    <h4 class="modal_title">Uredi ploščico</h4>
                    <form id="editTileForm">
                        <input type="hidden" id="editTileId" name="tileId">
                        <input type="hidden" id="editTileModuleId" name="moduleId">

                        <label for="editTileNaziv">Naziv ploščice:</label>
                        <input type="text" class="text_input" id="editTileNaziv" name="naziv" required>

                        <label for="editTileOrder">Vrstni red:</label>
                        <input type="number" id="editTileOrder" name="order" class="text_input" required>

                        <label for="editTileVsebina">Vsebina ploščice:</label>
                        <textarea class="text_editor" id="editTileVsebina" name="vsebina"></textarea>

                        <!-- Add the image preview container -->
                        <div id="editTileImagePreview"></div>

                        <!-- File uploader field -->
                        <?php $handler->html_single_file_upload([
                            'label' => 'Slika',
                            'name' => 'tileImage_edit',
                            'value' => '',
                            'required' => false
                        ]); ?>

                        <!-- Article Multi-select Field -->
                        <?php $handler->html_select2([
                            'id' => 'povezani_artikli_edit',
                            'label' => 'Izberi artikle iz seznama',
                            'name' => 'povezani_artikli_edit[]',
                            'multiple' => true,
                            'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect',
                            'get_selected' => false
                        ]); ?>
                    </form>
                </div>
                <!-- Add the buttons here -->
                <div style="display: flex; justify-content: end; gap: 5px; padding-top: 20px;">
                    <button class="btn btn-back" id="cancelEditTileButton"> Prekliči </button>
                    <button class="btn btn-success" id="saveEditTileButton"> Shrani spremembe </button>
                </div>
            </div>
        </div>




    </div>
    <div class="tab-content hide" id="tab5">
        <div class="row">
            <div class="col-lg-12">
                <form id="izbranoZaVasForm">
                    <?php
                    // Select2 component for articles
                    $handler->html_select2([
                        'id' => 'izbrano_za_vas_artikli',
                        'label' => 'Izberite artikle',
                        'name' => 'izbrano_za_vas_artikli[]',
                        'multiple' => true,
                        'url' => '/webapp/s2?c=Artikel&m=getPovezaniSelect',
                        'get_selected' => [
                            'id' => (!empty($data->id)) ? $data->id : 0,
                            'c' => 'Artikel',
                            'm' => 'getPovezaniSelected',
                            'tip' => 14
                        ]
                    ]);
                    ?>
                    <button type="button" class="btn btn-success" onclick="saveIzbranoZaVas()">Shrani</button>
                </form>
            </div>
        </div>
    </div>

    <div class="tab-content hide" id="tab6">
        <div class="row">
            <div class="col-lg-12">
                <h3>Bannerji</h3>
                <button class="btn btn-success margin-bottom-3" onclick="openAddBannerModal()">Dodaj banner</button>
                <div id="bannersList">
                    <?php
                    $banners = $bal->getBannersByCategory($data->id);
                    if (!empty($banners)) {
                        echo '<table class="main_table">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>ID</th>';
                        echo '<th>Tip</th>';
                        echo '<th>Vrstni red</th>';
                        echo '<th>Slika</th>';
                        echo '<th>Akcije</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        foreach ($banners as $banner) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($banner->id) . '</td>';
                            echo '<td>' . ($banner->tip == 1 ? 'Normalni banner' : 'Manjši banner') . '</td>';
                            echo '<td>' . htmlspecialchars($banner->order) . '</td>';
                            echo '<td><img src="/files' . htmlspecialchars($banner->path) . '" alt="Banner Image" style="max-width: 100px;"></td>';
                            echo '<td>';
                            echo '<button class="btn btn-primary margin-right-3" onclick="openEditBannerModal(' . $banner->id . ')">Uredi</button>';
                            echo '<button class="btn btn-danger" onclick="deleteBanner(' . $banner->id . ')">Izbriši</button>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                    } else {
                        echo '<p>Ni bannerjev za prikaz.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Add Banner Modal -->
        <div id="addBannerModal" class="modal">
            <div class="card_modal">
                <div class="card_wrapper">
                    <h4 class="modal_title">Dodaj Banner</h4>

                    <form id="addBannerForm">
                        <label for="bannerTip" style="margin-top: 10px;">Tip bannerja:</label>
                        <select id="bannerTip" name="tip" class="select_input" required>
                            <option value="1">Normalni banner</option>
                            <option value="2">Manjši banner</option>
                        </select>

                        <label for="bannerOrder" style="margin-top: 10px;">Vrstni red:</label>
                        <input type="number" id="bannerOrder" name="order" class="text_input" required>

                        <!-- Integrated File Picker -->
                        <div style="margin: 0px 7px;">
                            <?php $handler->html_single_file_upload([
                                'label' => 'Banner',
                                'name' => 'banner_add',
                                'value' => '',
                                'required' => true
                            ]); ?>
                        </div>
                        <div style="display: flex; flex-direction: row; gap: 15px;">
                            <div style="display: flex; flex-direction: column; width: 100%;">
                                <?php $handler->html_input(['name' => 'datum_od', 'label' => 'Datum od', 'id' => 'bannerDatumOd', 'value' => "", 'required' => false, 'datepicker' => true]); ?>
                            </div>
                            <div style="display: flex; flex-direction: column; width: 100%;">
                                <?php $handler->html_input(['name' => 'datum_do', 'label' => 'Datum do', 'id' => 'bannerDatumDo', 'value' => "", 'required' => false, 'datepicker' => true]); ?>
                            </div>
                        </div>
                        <?php $handler->html_checkbox(['label' => 'Upoštevaj datum od-do', 'id' => 'bannerStatusOdDo', 'name' => 'status_od_do', 'status' => false]); ?>
                    </form>
                </div>

                <div style="display: flex; justify-content: end; gap: 5px; padding-top: 20px;">
                    <button class="btn btn-back" onclick="closeAddBannerModal()">Prekliči</button>
                    <button class="btn btn-success" onclick="saveNewBanner()">Dodaj</button>
                </div>
            </div>
        </div>



        <!-- Edit Banner Modal -->
        <div id="editBannerModal" class="modal">
            <div class="card_modal">
                <div class="card_wrapper">
                    <h4 class="modal_title">Uredi Banner</h4>

                    <form id="editBannerForm">
                        <input type="hidden" id="editBannerId">

                        <label for="editBannerTip" style="margin-top: 10px;">Tip bannerja:</label>
                        <select id="editBannerTip" name="tip" class="select_input" required>
                            <option value="1">Normalni banner</option>
                            <option value="2">Manjši banner</option>
                        </select>

                        <label for="editBannerOrder" style="margin-top: 10px;">Vrstni red:</label>
                        <input type="number" id="editBannerOrder" name="order" class="text_input" required>

                        <!-- Integrated File Picker -->
                        <div style="margin: 0px 7px;">
                            <?php $handler->html_single_file_upload([
                                'label' => 'Banner',
                                'name' => 'banner_edit',
                                'value' => '',
                                'required' => true
                            ]); ?>
                        </div>

                        <div style="display: flex; flex-direction: row; gap: 15px;">
                            <div style="display: flex; flex-direction: column; width: 100%;">
                                <?php $handler->html_input(['name' => 'datum_od', 'label' => 'Datum od', 'id' => 'editBannerDatumOd', 'value' => '', 'required' => false, 'datepicker' => true]); ?>
                            </div>
                            <div style="display: flex; flex-direction: column; width: 100%;">
                                <?php $handler->html_input(['name' => 'datum_do', 'label' => 'Datum do', 'id' => 'editBannerDatumDo', 'value' => '', 'required' => false, 'datepicker' => true]); ?>
                            </div>
                        </div>
                        <?php $handler->html_checkbox(['label' => 'Upoštevaj datum od-do', 'name' => 'status_od_do_edit', 'status' => '']); ?>

                    </form>
                </div>

                <div style="display: flex; justify-content: end; gap: 5px; padding-top: 20px;">
                    <button class="btn btn-back" onclick="closeEditBannerModal()">Prekliči</button>
                    <button class="btn btn-success" onclick="saveEditedBanner()">Shrani spremembe</button>
                </div>
            </div>
        </div>




    </div>

 </div>

 <style>
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
    .table_row:nth-child(even) {
        background-color: #f9f9f9;
    }
    .table_row:hover {
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
        justify-content: center; 
        align-items: center;
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
    .card_modal {
        position: relative;
        margin: auto;
        width: 80%;
        max-width: 750px;
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    }
    .modal_title {
        font-size: 18px;
        margin: 0 !important;
        padding: 0 !important;
    }
    .card_wrapper {
        display: flex;
        flex-direction: column;
        width: 100%;
    }
    .text_editor {
        width: 100%;
        height: 100px;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px 7px;
        font-size: 14px;
        color: #333;
        /* resize: none; */
    }
    .text_input {
        width: 100%;
        color: #333;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px 7px;
        font-size: 14px;
    }
    .select_input {
        width: 100%;
        color: #333;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 6px 7px;
        font-size: 14px; 
    }
    .edit_button:hover {
        text-decoration: underline;
        cursor: pointer;
    }

    .module-container {
        margin-top: 20px; /* Adjust the value as needed */
    }

    .module-container h3 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .col-id {
        width: 5%; /* Narrower column for ID */
    }

    .col-naziv {
        width: 20%; /* Moderate width for Naziv */
    }

    .col-order {
        width: 10%; /* Adjust as needed */
    }

    .col-vsebina {
        width: 75%; /* Widest column for Vsebina */
    }

    .col-nicename {
        width: 15%; /* Adjust as needed */
    }

    .col-actions {
        width: 15%; /* Adjust as needed */
    }

    .margin-right-3 {
        margin-right: 3px;
    }
    .margin-bottom-3 {
        margin-bottom: 3px;
    }
</style>
<script>
    drawCategoryCardsTable();
    var addCategoryCardModal = document.getElementById("addCategoryCardModal");
    var editCategoryCardModal = document.getElementById("editCategoryCardModal");
    var deleteCategoryCardModal = document.getElementById("deleteCategoryCardModal");

    var addCustomCardModal = document.getElementById("addCustomCardModal");
    var editCustomCardModal = document.getElementById("editCustomCardModal");
    var deleteCustomCardModal = document.getElementById("deleteCustomCardModal");

    var addBannerModal = document.getElementById('addBannerModal');
    var editBannerModal = document.getElementById('editBannerModal');

    function newCustomCard() {
        addCustomCardModal.style.display = "flex";
        document.getElementById('card_banner').value = '';
        document.getElementById('card_title').value = '';
    }
    function closeCustomCardModal() {
        addCustomCardModal.style.display = "none";
    }
    function closeCustomCardEditModal() {
        editCustomCardModal.style.display = "none";
    }
    function closeCustomCardDeleteModal() {
        deleteCustomCardModal.style.display = "none";
    }

    function newCategoryCard() {
        addCategoryCardModal.style.display = "flex";
        document.getElementById('card_banner').value = '';
        document.getElementById('card_title').value = '';
        document.getElementById('card_category_id').value ='';
        document.getElementById('card_vsebina').value = '';

        // Calculate max order from existing category cards
        var cardRows = document.querySelectorAll('#category_cards_table table tbody tr');
        var maxOrder = 0;

        cardRows.forEach(function(row) {
            // Assuming the order is in the second cell
            var orderCell = row.cells[1];
            if (orderCell) {
                var order = parseInt(orderCell.textContent, 10);
                if (order > maxOrder) {
                    maxOrder = order;
                }
            }
        });

        // Set the order input value to maxOrder + 1
        document.getElementById('card_order').value = maxOrder + 1;

    }
    function closeCategoryCardModal() {
        addCategoryCardModal.style.display = "none";
    }
    function closeCategoryCardEditModal() {
        editCategoryCardModal.style.display = "none";
    }
    function closeCategoryCardDeleteModal() {
        deleteCategoryCardModal.style.display = "none";
    }


    function saveCategoryCard(action, card_id = null) {
        var id = <?php echo $data->id; ?>;

        if (action == 'create') {
            var path = document.getElementById('card_banner').value;
            var naziv = document.getElementById('card_title').value;
            var kategorija_id = document.getElementById('card_category_id').value;
            var order = document.getElementById('card_order').value;
            var vsebina = document.getElementById('card_vsebina').value;

            // Add validation
            if (!kategorija_id) {
                alert('Prosimo, izberite kategorijo.');
                return;
            }

            _saveCategoryCard(
                {
                    action: action,
                    id: id,
                    naziv: naziv,
                    path: path,
                    kategorija_id: kategorija_id,
                    order: order,
                    vsebina: vsebina,
                }
            );
            addCategoryCardModal.style.display = 'none';
        }

        if (action == 'edit') {
            var path = document.getElementById('card_banner_edit').value;
            var naziv = document.getElementById('card_title_edit').value;
            var kategorija_id = document.getElementById('card_category_id_edit').value;
            var order = document.getElementById('card_order_edit').value;
            var vsebina = document.getElementById('card_vsebina_edit').value;

            // Add validation
            if (!kategorija_id) {
                alert('Prosimo, izberite kategorijo.');
                return;
            }

            _saveCategoryCard(
                {
                    action: action,
                    id: id,
                    naziv: naziv,
                    path: path,
                    kategorija_id: kategorija_id,
                    order: order,
                    vsebina: vsebina,
                    card_id: card_id,
                }
            );
            editCategoryCardModal.style.display = 'none';
        }

        if (action == 'delete') {
            _saveCategoryCard({ action: action, card_id: card_id });
            deleteCategoryCardModal.style.display = 'none';
        }
    }


    function editCategoryCard(id) {
        _getCategoryCard({ id: id }, function (data) {
            if(data) {

                editCategoryCardModal.style.display = "flex";
                document.getElementById("card_title_edit").value = data.naziv;
                document.getElementById("card_banner_edit").value = data.path;
                document.getElementById("card_category_id_edit").value = data.kategorija_id;
                document.getElementById("card_order_edit").value = data.order;
                document.getElementById("card_vsebina_edit").value = data.vsebina;

                // Display existing image in the preview div
                var imagePreviewDiv = document.getElementById("card_banner_edit_preview");
                if (data.path && data.path !== '') {
                    imagePreviewDiv.innerHTML = '<p>Obstoječa slika:</p><img src="/files' + data.path + '" alt="Card Image" style="max-width: 30%; height: auto;">';
                } else {
                    imagePreviewDiv.innerHTML = '<p>Ni slike</p>';
                }

                const saveButton = document.getElementById("save_button_edit");
                saveButton.onclick = function () {
                    saveCategoryCard('edit', id);
                };
            }
        });
    }

    function deleteCategoryCard(id) {
        deleteCategoryCardModal.style.display = "flex";
        const saveButton = document.getElementById("delete_button");
        saveButton.onclick = function () {
            saveCategoryCard('delete', id);
        };
    }

    function drawCategoryCardsTable() {
        var categoryId = <?php echo $data->id; ?>; // Assuming you have access to $data->id

        $.get(
            "/webapp/base/get-category-cards",
            { categoryId: categoryId, token: $("meta[name=token]").attr("content") },
            function (htmlContent) {
                $("#category_cards_table").html(htmlContent);
            }
        ).fail(function() {
            alertify.error('Napaka pri osveževanju ploščic kategorije.', 2);
        });
    }









    // cardTypeSelect.addEventListener("change", changeFormAdd);
    // cardTypeSelect_edit.addEventListener("change", changeFormEdit);

    // function changeFormAdd() {
    //     const selectedOption = cardTypeSelect.options[cardTypeSelect.selectedIndex].text;
    //     console.log("Selected option:", selectedOption);
        
    //     switch(cardTypeSelect.value) {
    //         case "1":
    //             document.getElementById("card_content").style.display = "none";
    //             document.getElementById("card_content_label").style.display = "none";
    //             document.getElementById("card_category_id").style.display = "block";
    //             document.getElementById("card_category_id_label").style.display = "block";
    //             document.getElementById("custom_artikli_wrapper").style.display = "none";
    //             break;
    //         case "2":
    //             document.getElementById("card_content").style.display = "block";
    //             document.getElementById("card_content_label").style.display = "block";
    //             document.getElementById("card_category_id").style.display = "none";
    //             document.getElementById("card_category_id_label").style.display = "none";
    //             document.getElementById("custom_artikli_wrapper").style.display = "block";
    //             break;
    //         default:
    //             document.getElementById("card_content").style.display = "none";
    //             document.getElementById("card_content_label").style.display = "none";
    //             document.getElementById("card_category_id").style.display = "none";
    //             document.getElementById("card_category_id_label").style.display = "none";
    //             document.getElementById("custom_artikli_wrapper").style.display = "none";
    //             break;
    //     }
    // }

    // function changeFormEdit() {
    //     const selectedOption = cardTypeSelect_edit.options[cardTypeSelect_edit.selectedIndex].text;
    //     console.log("Selected option:", selectedOption);
        
    //     switch(cardTypeSelect_edit.value) {
    //         case "1":
    //             document.getElementById("card_content_edit").style.display = "none";
    //             document.getElementById("card_content_edit_label").style.display = "none";
    //             document.getElementById("card_category_id_edit").style.display = "block";
    //             document.getElementById("card_category_id_edit_label").style.display = "block";
    //             document.getElementById("custom_artikli_wrapper_edit").style.display = "none";
    //             break;
    //         case "2":
    //             document.getElementById("card_content_edit").style.display = "block";
    //             document.getElementById("card_content_edit_label").style.display = "block";
    //             document.getElementById("card_category_id_edit").style.display = "none";
    //             document.getElementById("card_category_id_edit_label").style.display = "none";
    //             document.getElementById("custom_artikli_wrapper_edit").style.display = "block";
    //             break;
    //         default:
    //             document.getElementById("card_content_edit").style.display = "none";
    //             document.getElementById("card_content_edit_label").style.display = "none";
    //             document.getElementById("card_category_id_edit").style.display = "none";
    //             document.getElementById("card_category_id_edit_label").style.display = "none";
    //             document.getElementById("custom_artikli_wrapper_edit").style.display = "none";
    //             break;
    //     }
    // }


    // Custom ploščice

    // Variables for module management
    var addModuleButton = document.getElementById('addModuleButton');
    var newModuleForm = document.getElementById('newModuleForm');
    var cancelModuleButton = document.getElementById('cancelModuleButton');
    var moduleForm = document.getElementById('moduleForm');

    // Show the form when "Add New Module" button is clicked
    addModuleButton.addEventListener('click', function() {
        newModuleForm.style.display = 'block';
        addModuleButton.style.display = 'none';

        // Calculate max module order from existing modules
        var moduleContainers = document.querySelectorAll('.module-container');
        var maxOrder = 0;

        moduleContainers.forEach(function(container) {
            // Assuming the order is displayed in the module title, e.g., "Modul: [name] (Vrstni red: [order])"
            var moduleTitle = container.querySelector('h3').textContent;
            var orderMatch = moduleTitle.match(/\(Vrstni red:\s*(\d+)\)/);
            if (orderMatch && orderMatch[1]) {
                var order = parseInt(orderMatch[1], 10);
                if (order > maxOrder) {
                    maxOrder = order;
                }
            }
        });

        // Set the order input value to maxOrder + 1
        document.getElementById('moduleOrder').value = maxOrder + 1;
    });

    // Hide the form when "Cancel" button is clicked
    cancelModuleButton.addEventListener('click', function() {
        newModuleForm.style.display = 'none';
        addModuleButton.style.display = 'inline-block';
    });

    // Handle form submission
    moduleForm.addEventListener('submit', function(event) {
        event.preventDefault();
        saveNewModule();
    });

    // Function to save a new module
    function saveNewModule() {
        var moduleTitle = document.getElementById('moduleTitle').value;
        var moduleOrder = document.getElementById('moduleOrder').value;
        var categoryId = document.querySelector('input[name="categoryId"]').value;
        var token = document.querySelector('meta[name="token"]').getAttribute('content');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/webapp/base/saveModule', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Module saved successfully
                    addModuleToDOM(response.data.module);
                    // Reset the form and UI
                    moduleForm.reset();
                    newModuleForm.style.display = 'none';
                    addModuleButton.style.display = 'inline-block';
                } else {
                    alert('Error saving module. Please try again.');
                }
            } else {
                alert('Error saving module. Please try again.');
            }
        };

        // Prepare data for POST
        var params = 'moduleTitle=' + encodeURIComponent(moduleTitle) +
            '&moduleOrder=' + encodeURIComponent(moduleOrder) +
            '&categoryId=' + encodeURIComponent(categoryId) +
            '&token=' + encodeURIComponent(token);

        xhr.send(params);
    }

    // Function to add a new module to the DOM
    function addModuleToDOM(module) {
        // Create the module container
        var moduleContainer = document.createElement('div');
        moduleContainer.className = 'module-container';
        moduleContainer.setAttribute('data-module-id', module.id);
        moduleContainer.style.marginTop = '20px';

        // Add the module title
        var moduleTitle = document.createElement('h3');
        moduleTitle.textContent = 'Modul: ' + module.naziv + ' (Vrstni red: ' + module.order + ')';
        moduleContainer.appendChild(moduleTitle);

        // Add Edit, Delete, and Add Tile buttons
        var moduleActions = document.createElement('div');
        moduleActions.className = 'module-actions';

        var editButton = document.createElement('button');
        editButton.className = 'btn btn-primary edit-module-button margin-right-3';
        editButton.textContent = 'Uredi modul';
        editButton.setAttribute('data-module-id', module.id);
        moduleActions.appendChild(editButton);

        var deleteButton = document.createElement('button');
        deleteButton.className = 'btn btn-danger delete-module-button margin-right-3';
        deleteButton.textContent = 'Izbriši modul';
        deleteButton.setAttribute('data-module-id', module.id);
        moduleActions.appendChild(deleteButton);

        var addTileButton = document.createElement('button');
        addTileButton.className = 'btn btn-success add-tile-button';
        addTileButton.textContent = 'Dodaj ploščico';
        addTileButton.setAttribute('data-module-id', module.id);
        moduleActions.appendChild(addTileButton);

        moduleContainer.appendChild(moduleActions);

        // Add the message for no tiles
        var noTilesMessage = document.createElement('p');
        noTilesMessage.textContent = 'Ta modul še nima ploščic po meri.';
        moduleContainer.appendChild(noTilesMessage);

        // Append the new module container to the modules list
        document.getElementById('modulesList').appendChild(moduleContainer);
    }

    // Event delegation for module actions (Edit, Delete, Add Tile)
    document.getElementById('modulesList').addEventListener('click', function(event) {
        var target = event.target;

        if (target.classList.contains('edit-module-button')) {
            var moduleId = target.getAttribute('data-module-id');
            editModule(moduleId);
        } else if (target.classList.contains('delete-module-button')) {
            var moduleId = target.getAttribute('data-module-id');
            deleteModule(moduleId);
        } else if (target.classList.contains('add-tile-button')) {
            var moduleId = target.getAttribute('data-module-id');
            openAddTileModal(moduleId);
        }
    });

    // Function to edit a module
    function editModule(moduleId) {
        var token = document.querySelector('meta[name="token"]').getAttribute('content');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/webapp/base/getModule', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    showEditModuleForm(response.data.module);
                } else {
                    alert('Napaka pri pridobivanju podatkov o modulu.');
                }
            } else {
                alert('Napaka pri pridobivanju podatkov o modulu.');
            }
        };

        var params = 'moduleId=' + encodeURIComponent(moduleId) +
            '&token=' + encodeURIComponent(token);

        xhr.send(params);
    }

    // Function to show the edit module form
    function showEditModuleForm(module) {
        // Show the edit form with module data
        document.getElementById('editModuleForm').style.display = 'block';
        document.getElementById('editModuleTitle').value = module.naziv;
        document.getElementById('editModuleOrder').value = module.order;
        document.getElementById('editModuleId').value = module.id;
        addModuleButton.style.display = 'none';
        newModuleForm.style.display = 'none';
    }

    // Handle Cancel button in edit module form
    document.getElementById('cancelEditModuleButton').addEventListener('click', function() {
        document.getElementById('editModuleForm').style.display = 'none';
        addModuleButton.style.display = 'inline-block';
    });

    // Handle edit module form submission
    document.getElementById('editModuleFormElement').addEventListener('submit', function(event) {
        event.preventDefault();
        saveEditedModule();
    });

    function saveEditedModule() {
        var moduleId = document.getElementById('editModuleId').value;
        var moduleTitle = document.getElementById('editModuleTitle').value;
        var moduleOrder = document.getElementById('editModuleOrder').value;
        var token = document.querySelector('meta[name="token"]').getAttribute('content');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/webapp/base/updateModule', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    updateModuleInDOM(response.data.module);
                    // Hide the edit form
                    document.getElementById('editModuleForm').style.display = 'none';
                    addModuleButton.style.display = 'inline-block';
                } else {
                    alert('Napaka pri shranjevanju sprememb modula.');
                }
            } else {
                alert('Napaka pri shranjevanju sprememb modula.');
            }
        };

        var params = 'moduleId=' + encodeURIComponent(moduleId) +
            '&moduleTitle=' + encodeURIComponent(moduleTitle) +
            '&moduleOrder=' + encodeURIComponent(moduleOrder) +
            '&token=' + encodeURIComponent(token);

        xhr.send(params);
    }

    function updateModuleInDOM(module) {
        // Find the module container and update the title
        var moduleContainer = document.querySelector('.module-container[data-module-id="' + module.id + '"]');
        if (moduleContainer) {
            var moduleTitle = moduleContainer.querySelector('h3');
            moduleTitle.textContent = 'Modul: ' + module.naziv + ' (Vrstni red: ' + module.order + ')';
        }
    }

    // Function to delete a module
    function deleteModule(moduleId) {
        if (confirm('Ste prepričani, da želite izbrisati ta modul in vse povezane ploščice?')) {
            var token = document.querySelector('meta[name="token"]').getAttribute('content');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/webapp/base/deleteModule', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        removeModuleFromDOM(moduleId);
                    } else {
                        alert('Napaka pri brisanju modula.');
                    }
                } else {
                    alert('Napaka pri brisanju modula.');
                }
            };

            var params = 'moduleId=' + encodeURIComponent(moduleId) +
                '&token=' + encodeURIComponent(token);

            xhr.send(params);
        }
    }

    function removeModuleFromDOM(moduleId) {
        var moduleContainer = document.querySelector('.module-container[data-module-id="' + moduleId + '"]');
        if (moduleContainer) {
            moduleContainer.parentNode.removeChild(moduleContainer);
        }
    }

    // Variables for tile management
    var addTileModal = document.getElementById('addTileModal');
    var addTileForm = document.getElementById('addTileForm');
    var cancelAddTileButton = document.getElementById('cancelAddTileButton');
    var saveTileButton = document.getElementById('saveTileButton');

    // Event delegation for Add Tile buttons
    document.getElementById('modulesList').addEventListener('click', function(event) {
        var target = event.target;

        if (target.classList.contains('add-tile-button')) {
            var moduleId = target.getAttribute('data-module-id');
            openAddTileModal(moduleId);
        }
        // No need for additional code here since edit and delete are already handled above
    });

    // Function to open the Add Tile modal
    function openAddTileModal(moduleId) {
        addTileModal.style.display = 'flex';
        document.getElementById('tileModuleId').value = moduleId;

        // Calculate max tile order from existing tiles in the module
        var moduleContainer = document.querySelector('.module-container[data-module-id="' + moduleId + '"]');
        var tileRows = moduleContainer.querySelectorAll('table.main_table tbody tr');
        var maxOrder = 0;

        tileRows.forEach(function(row) {
            // Assuming the order is in the cell with class 'col-order'
            var orderCell = row.querySelector('.col-order');
            if (orderCell) {
                var order = parseInt(orderCell.textContent, 10);
                if (order > maxOrder) {
                    maxOrder = order;
                }
            }
        });

        // Set the order input value to maxOrder + 1
        document.getElementById('tileOrder').value = maxOrder + 1;
    }

    // Handle Cancel button in Add Tile modal
    cancelAddTileButton.addEventListener('click', function() {
        addTileModal.style.display = 'none';
        addTileForm.reset();
    });

    // Handle Save Tile button click
    saveTileButton.addEventListener('click', function() {
        saveNewTile();
    });

    function saveNewTile() {
        var moduleId = document.getElementById('tileModuleId').value;
        var token = document.querySelector('meta[name="token"]').getAttribute('content');

        var naziv = document.getElementById('tileNaziv').value;

        var vsebina = document.getElementById('tileVsebina').value;
        var path = document.getElementById('tileImage').value; // Get the image path

        var order = document.getElementById('tileOrder').value;

        // Get selected articles
        var povezanArtikelSelect = $('#povezani_artikli');
        var selectedArticles = povezanArtikelSelect.val() || []; // This will be an array of selected article IDs

        var data = {
            'moduleId': moduleId,
            'naziv': naziv,
            'vsebina': vsebina,
            'path': path,
            'order': order,
            'selectedArticles': selectedArticles,
            'token': token
        };

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/webapp/base/saveTile', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Tile saved successfully
                    addTileToDOM(response.tile);
                    addTileModal.style.display = 'none';
                    addTileForm.reset();
                    alert('Ploščica uspešno shranjena.');
                } else {
                    alert('Napaka pri shranjevanju ploščice: ' + response.message);
                }
            } else {
                alert('Napaka pri komunikaciji s strežnikom.');
            }
        };
        xhr.send('data=' + encodeURIComponent(JSON.stringify(data)));
    }


    // Function to add the new tile to the DOM
    function addTileToDOM(tile) {
        // Find the module's tile table or create one if it doesn't exist
        var moduleContainer = document.querySelector('.module-container[data-module-id="' + tile.promocija_modul_iskanje_TK + '"]');
        if (moduleContainer) {
            var table = moduleContainer.querySelector('table.main_table');
            if (!table) {
                // Create the table if it doesn't exist
                table = document.createElement('table');
                table.className = 'main_table';

                var thead = document.createElement('thead');
                var headerRow = document.createElement('tr');
                headerRow.innerHTML = '<th class="col-id">ID ploščice</th>' +
                    '<th class="col-order">Order</th>' +
                    '<th class="col-nicename">Nicename</th>' +
                    '<th class="col-naziv">Naziv ploščice</th>' +
                    '<th class="col-vsebina">Vsebina ploščice</th>' +
                    '<th class="col-actions"></th>';
                thead.appendChild(headerRow);
                table.appendChild(thead);

                var tbody = document.createElement('tbody');
                table.appendChild(tbody);

                moduleContainer.appendChild(table);
            } else {
                var tbody = table.querySelector('tbody');
            }

            // Add the new tile to the table
            var row = document.createElement('tr');
            row.className = 'table_row';
            row.setAttribute('data-tile-id', tile.id);
            row.innerHTML = '<td class="col-id">' + tile.id + '</td>' +
                '<td class="col-order">' + tile.order + '</td>' +
                '<td class="col-nicename">' + tile.nicename + '</td>' +
                '<td class="col-naziv">' + tile.naziv + '</td>' +
                '<td class="col-vsebina">' + tile.vsebina + '</td>' +
                '<td class="col-actions">' +
                '<button class="btn btn-primary edit-tile-button margin-bottom-3" data-tile-id="' + tile.id + '">Uredi</button>' +
                '<button class="btn btn-danger delete-tile-button" data-tile-id="' + tile.id + '">Izbriši</button>' +
                '</td>';
            tbody.appendChild(row);
        }
    }


    // Variables for tile management
    var editTileModal = document.getElementById('editTileModal');
    var editTileForm = document.getElementById('editTileForm');
    var cancelEditTileButton = document.getElementById('cancelEditTileButton');
    var saveEditTileButton = document.getElementById('saveEditTileButton');

    // Event delegation for tile actions
    document.getElementById('modulesList').addEventListener('click', function(event) {
        var target = event.target;

        if (target.classList.contains('edit-tile-button')) {
            var tileId = target.getAttribute('data-tile-id');
            openEditTileModal(tileId);
        } else if (target.classList.contains('delete-tile-button')) {
            var tileId = target.getAttribute('data-tile-id');
            deleteTile(tileId);
        }
    });


    function openEditTileModal(tileId) {
        var token = document.querySelector('meta[name="token"]').getAttribute('content');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/webapp/base/getTile', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    populateEditTileForm(response.data.tile);
                    editTileModal.style.display = 'flex';
                } else {
                    alert('Napaka pri pridobivanju podatkov o ploščici.');
                }
            } else {
                alert('Napaka pri pridobivanju podatkov o ploščici.');
            }
        };

        var params = 'tileId=' + encodeURIComponent(tileId) +
            '&token=' + encodeURIComponent(token);

        xhr.send(params);
    }

    function getCookie(name) {
        let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    }

    function populateEditTileForm(tile) {
        document.getElementById('editTileId').value = tile.id;
        document.getElementById('editTileModuleId').value = tile.promocija_modul_iskanje_TK;
        document.getElementById('editTileNaziv').value = tile.naziv;
        document.getElementById('editTileVsebina').value = tile.vsebina;

        document.getElementById('editTileOrder').value = tile.order;

        // Display the existing image
        var imagePreview = document.getElementById('editTileImagePreview');
        if (tile.path && tile.path !== '') {
            imagePreview.innerHTML = '<p>Obstoječa slika:</p><img src="/files' + tile.path + '" alt="Tile Image" style="max-width: 30%; height: auto;">';
        } else {
            imagePreview.innerHTML = '';
        }

        document.querySelector('#editTileForm input[name="tileImage_edit"]').value = tile.path;

        // Initialize and populate the Select2 component
        var povezanArtikelSelect = $('#povezani_artikli_edit');

        // Destroy any existing Select2 instance and delete data
        povezanArtikelSelect.select2('destroy').empty().off().removeData();

        // Re-initialize Select2
        povezanArtikelSelect.select2({
            placeholder: 'Izberi artikle iz seznama',
            multiple: true,
            width: '100%',
            ajax: {
                url: '/webapp/s2?c=Artikel&m=getPovezaniSelect',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        token: getCookie('token') || '',
                        search: params.term // search term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                }
            }
        });

        // Populate with selected articles
        if (tile.selectedArticles && tile.selectedArticles.length > 0) {
            var data = tile.selectedArticles.map(function(article) {

                return {
                    id: article.id,
                    text: article.naziv
                };
            });

            data.forEach(function(item) {
                // Create a new option element
                var option = new Option(item.text, item.id, true, true);
                // Append it to the Select2 component
                povezanArtikelSelect.append(option);
            });

            // Trigger change to update the display
            povezanArtikelSelect.trigger('change');
        }
    }



    cancelEditTileButton.addEventListener('click', function() {
        editTileModal.style.display = 'none';
        editTileForm.reset();
    });

    saveEditTileButton.addEventListener('click', function() {
        saveEditedTile();
    });

    function saveEditedTile() {
        var token = document.querySelector('meta[name="token"]').getAttribute('content');

        var tileId = document.getElementById('editTileId').value;
        var moduleId = document.getElementById('editTileModuleId').value;
        var naziv = document.getElementById('editTileNaziv').value;

        var vsebina = document.getElementById('editTileVsebina').value;
        var path = document.getElementById('tileImage_edit').value; // Get the image path

        var order = document.getElementById('editTileOrder').value;

        // Get selected articles
        var povezanArtikelSelect = $('#povezani_artikli_edit');
        var selectedArticles = povezanArtikelSelect.val() || []; // This will be an array of selected article IDs

        var data = {
            'tileId': tileId,
            'moduleId': moduleId,
            'naziv': naziv,
            'vsebina': vsebina,
            'path': path,
            'order': order,
            'selectedArticles': selectedArticles,
            'token': token
        };

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/webapp/base/updateTile', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Tile updated successfully
                    updateTileInDOM(response.tile);
                    editTileModal.style.display = 'none';
                    editTileForm.reset();
                    alert('Ploščica uspešno posodobljena.');
                } else {
                    alert('Napaka pri posodabljanju ploščice: ' + response.message);
                }
            } else {
                alert('Napaka pri komunikaciji s strežnikom.');
            }
        };
        xhr.send('data=' + encodeURIComponent(JSON.stringify(data)));
    }


    function updateTileInDOM(tile) {
        var moduleContainer = document.querySelector('.module-container[data-module-id="' + tile.promocija_modul_iskanje_TK + '"]');
        if (moduleContainer) {
            var table = moduleContainer.querySelector('table.main_table');
            if (table) {
                var row = table.querySelector('tr[data-tile-id="' + tile.id + '"]');
                if (row) {
                    // Update the row's cells
                    row.querySelector('.col-order').textContent = tile.order;
                    row.querySelector('.col-naziv').textContent = tile.naziv;
                    row.querySelector('.col-nicename').textContent = tile.nicename;
                    row.querySelector('.col-vsebina').textContent = tile.vsebina;
                    // Update other fields as necessary
                }
            }
        }
    }

    function deleteTile(tileId) {
        if (confirm('Ste prepričani, da želite izbrisati to ploščico?')) {
            var token = document.querySelector('meta[name="token"]').getAttribute('content');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/webapp/base/deleteTile', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        removeTileFromDOM(tileId);
                    } else {
                        alert('Napaka pri brisanju ploščice.');
                    }
                } else {
                    alert('Napaka pri brisanju ploščice.');
                }
            };

            var params = 'tileId=' + encodeURIComponent(tileId) +
                '&token=' + encodeURIComponent(token);

            xhr.send(params);
        }
    }

    function removeTileFromDOM(tileId) {
        var row = document.querySelector('tr[data-tile-id="' + tileId + '"]');
        if (row) {
            row.parentNode.removeChild(row);
        }
    }

    function saveIzbranoZaVas() {
        var token = document.querySelector('meta[name="token"]').getAttribute('content');
        var categoryId = <?php echo $data->id; ?>;

        // Get selected articles
        var selectedArticles = $('#izbrano_za_vas_artikli').val() || [];

        var data = {
            'categoryId': categoryId,
            'selectedArticles': selectedArticles,
            'token': token
        };

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/webapp/base/saveIzbranoZaVas', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert('Izbrano za vas uspešno shranjeno.');
                } else {
                    alert('Napaka pri shranjevanju: ' + response.message);
                }
            } else {
                alert('Napaka pri komunikaciji s strežnikom.');
            }
        };
        xhr.send('data=' + encodeURIComponent(JSON.stringify(data)));
    }

    function displayBanners(banners) {
        var bannersList = document.getElementById('bannersList');
        bannersList.innerHTML = ''; // Clear existing content

        if (banners.length === 0) {
            bannersList.innerHTML = '<p>Ni bannerjev za prikaz.</p>';
            return;
        }

        var table = document.createElement('table');
        table.className = 'main_table';

        var thead = document.createElement('thead');
        var headerRow = document.createElement('tr');
        headerRow.innerHTML = '<th>ID</th><th>Tip</th><th>Vrstni red</th><th>Slika</th><th>Akcije</th>';
        thead.appendChild(headerRow);
        table.appendChild(thead);

        var tbody = document.createElement('tbody');
        banners.forEach(function(banner) {
            var row = document.createElement('tr');
            row.innerHTML = '<td>' + banner.id + '</td>' +
                '<td>' + (banner.tip == 1 ? 'Normalni banner' : 'Manjši banner') + '</td>' +
                '<td>' + banner.order + '</td>' +
                '<td><img src="/files' + banner.path + '" alt="Banner Image" style="max-width: 100px;"></td>' +
                '<td>' +
                '<button class="btn btn-primary margin-right-3" onclick="openEditBannerModal(' + banner.id + ')">Uredi</button>' +
                '<button class="btn btn-danger" onclick="deleteBanner(' + banner.id + ')">Izbriši</button>' +
                '</td>';
            tbody.appendChild(row);
        });
        table.appendChild(tbody);

        bannersList.appendChild(table);
    }

    function openAddBannerModal() {
        var modal = document.getElementById('addBannerModal');
        modal.style.display = 'flex';
        document.getElementById('addBannerForm').reset();
        document.getElementById('bannerDatumOd').value = '';
        document.getElementById('bannerDatumDo').value = '';

        // Calculate max banner order from existing banners
        var bannerRows = document.querySelectorAll('#bannersList table.main_table tbody tr');
        var maxOrder = 0;

        bannerRows.forEach(function(row) {
            // Assuming the order is in the third cell (adjust the index if necessary)
            var orderCell = row.cells[2];
            if (orderCell) {
                var order = parseInt(orderCell.textContent, 10);
                if (order > maxOrder) {
                    maxOrder = order;
                }
            }
        });

        // Set the order input value to maxOrder + 1
        document.getElementById('bannerOrder').value = maxOrder + 1;
    }

    function closeAddBannerModal() {
        var modal = document.getElementById('addBannerModal');
        modal.style.display = 'none';
        document.getElementById('addBannerForm').reset();
    }

    function formatDateString(dateString) {
        // Split the date string into parts
        if(dateString === null || dateString === "") return '';
        let parts = dateString.split('-'); // ["2025", "02", "01"]
        let year = parts[0]; // "2025"
        let month = parts[1]; // "02"
        let day = parts[2]; // "01"

        // Return the formatted date
        return `${day}.${month}.${year}`; // "01.02.2025"
    }

    function openEditBannerModal(bannerId) {
        // Fetch banner data and populate form
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/webapp/base/getGraphicItem?graphicItemId=' + bannerId, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    var banner = response.banner;
                    document.getElementById('editBannerId').value = banner.id;
                    document.getElementById('editBannerTip').value = banner.tip;
                    document.getElementById('editBannerOrder').value = banner.order;

                    document.getElementById('editBannerDatumOd').value = formatDateString(banner.datum_od);
                    document.getElementById('editBannerDatumDo').value = formatDateString(banner.datum_do);

                    if(banner.status_od_do == 1) {
                        document.getElementById('status_od_do_edit').checked = true;
                        document.getElementById('status_od_do_edit').value = 1;
                    } else {
                        document.getElementById('status_od_do_edit').checked = false;
                        document.getElementById('status_od_do_edit').value = 0;
                    }
                    

                    document.querySelector('#editBannerForm input[name="banner_edit"]').value = banner.path;

                    editBannerModal.style.display = 'flex';
                } else {
                    alert('Napaka pri pridobivanju podatkov o bannerju: ' + response.message);
                }
            } else {
                alert('Napaka pri komunikaciji s strežnikom.');
            }
        };
        xhr.send();
    }

    function closeEditBannerModal() {
        var modal = document.getElementById('editBannerModal');
        modal.style.display = 'none';
        document.getElementById('editBannerForm').reset();
    }

    function saveNewBanner() {
        var categoryId = <?php echo $data->id; ?>;
        var token = document.querySelector('meta[name="token"]').getAttribute('content');

        var tip = document.getElementById('bannerTip').value;
        var order = document.getElementById('bannerOrder').value;

        var datum_od = document.getElementById('bannerDatumOd').value;
        var datum_do = document.getElementById('bannerDatumDo').value;

        if(document.querySelector('#status_od_do').checked){
            var status_od_do = 1;
        } else {
            var status_od_do = 0;
        }

        var path = document.querySelector('#addBannerForm input[name="banner_add"]').value;

        var data = {
            'categoryId': categoryId,
            'tip': tip,
            'order': order,
            'path': path,
            'datum_od': datum_od,
            'datum_do': datum_do,
            'status_od_do': status_od_do,
            'token': token
        };

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/webapp/base/saveBanner', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    closeAddBannerModal();
                    // Append the new banner to the table
                    addBannerToDOM(response.banner);
                    alert('Banner uspešno dodan.');
                } else {
                    alert('Napaka pri shranjevanju bannerja: ' + response.message);
                }
            } else {
                alert('Napaka pri komunikaciji s strežnikom.');
            }
        };
        xhr.send('data=' + encodeURIComponent(JSON.stringify(data)));
    }

    function addBannerToDOM(banner) {
        var bannersList = document.getElementById('bannersList');
        var tableBody = bannersList.querySelector('table.main_table tbody');
        if (!tableBody) {
            // No banners yet, need to create the table
            bannersList.innerHTML = '';
            var tableHTML = '<table class="main_table"><thead><tr><th>ID</th><th>Tip</th><th>Vrstni red</th><th>Slika</th><th>Akcije</th></tr></thead><tbody></tbody></table>';
            bannersList.innerHTML = tableHTML;
            tableBody = bannersList.querySelector('table.main_table tbody');
        }
        var row = document.createElement('tr');
        row.innerHTML = '<td>' + banner.id + '</td>' +
            '<td>' + (banner.tip == 1 ? 'Normalni banner' : 'Manjši banner') + '</td>' +
            '<td>' + banner.order + '</td>' +
            '<td><img src="/files' + banner.path + '" alt="Banner Image" style="max-width: 100px;"></td>' +
            '<td>' +
            '<button class="btn btn-primary margin-right-3" onclick="openEditBannerModal(' + banner.id + ')">Uredi</button>' +
            '<button class="btn btn-danger" onclick="deleteBanner(' + banner.id + ')">Izbriši</button>' +
            '</td>';
        tableBody.appendChild(row);
    }

    function saveEditedBanner() {
        var bannerId = document.getElementById('editBannerId').value;
        var token = document.querySelector('meta[name="token"]').getAttribute('content');

        var tip = document.getElementById('editBannerTip').value;
        var order = document.getElementById('editBannerOrder').value;

        var datum_od = document.getElementById('editBannerDatumOd').value;
        var datum_do = document.getElementById('editBannerDatumDo').value;

        if(document.querySelector('#status_od_do_edit').checked){
            var status_od_do = 1;
        } else {
            var status_od_do = 0;
        }
        var path = document.querySelector('#editBannerForm input[name="banner_edit"]').value;

        var data = {
            'bannerId': bannerId,
            'tip': tip,
            'order': order,
            'path': path,
            'datum_od': datum_od,
            'datum_do': datum_do,
            'status_od_do': status_od_do,
            'token': token
        };
        console.log(data);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/webapp/base/updateBanner', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    closeEditBannerModal();
                    // Update the banner in the table
                    updateBannerInDOM(response.banner);
                    alert('Banner uspešno posodobljen.');
                } else {
                    alert('Napaka pri posodabljanju bannerja: ' + response.message);
                }
            } else {
                alert('Napaka pri komunikaciji s strežnikom.');
            }
        };
        xhr.send('data=' + encodeURIComponent(JSON.stringify(data)));
    }

    function updateBannerInDOM(banner) {
        var bannersList = document.getElementById('bannersList');
        var rows = bannersList.querySelectorAll('table.main_table tbody tr');
        rows.forEach(function(row) {
            var idCell = row.cells[0];
            if (idCell.textContent == banner.id) {
                // Update the row
                row.cells[1].textContent = (banner.tip == 1 ? 'Normalni banner' : 'Manjši banner');
                row.cells[2].textContent = banner.order;
                row.cells[3].innerHTML = '<img src="/files' + banner.path + '" alt="Banner Image" style="max-width: 100px;">';
            }
        });
    }


    function deleteBanner(bannerId) {
        if (confirm('Ste prepričani, da želite izbrisati ta banner?')) {
            var token = document.querySelector('meta[name="token"]').getAttribute('content');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/webapp/base/deleteBanner', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Remove the banner from the table
                        removeBannerFromDOM(bannerId);
                        alert('Banner uspešno izbrisan.');
                    } else {
                        alert('Napaka pri brisanju bannerja: ' + response.message);
                    }
                } else {
                    alert('Napaka pri komunikaciji s strežnikom.');
                }
            };
            var data = {
                'bannerId': bannerId,
                'token': token
            };
            xhr.send('data=' + encodeURIComponent(JSON.stringify(data)));
        }
    }

    function removeBannerFromDOM(bannerId) {
        var bannersList = document.getElementById('bannersList');
        var rows = bannersList.querySelectorAll('table.main_table tbody tr');
        rows.forEach(function(row) {
            var idCell = row.cells[0];
            if (idCell.textContent == bannerId) {
                row.parentNode.removeChild(row);
            }
        });

        // If no banners left, display the message
        var tableBody = bannersList.querySelector('table.main_table tbody');
        if (tableBody.children.length === 0) {
            bannersList.innerHTML = '<p>Ni bannerjev za prikaz.</p>';
        }
    }



    //
</script>