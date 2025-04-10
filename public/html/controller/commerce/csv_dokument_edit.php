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
                                <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>                            
                            </div>
                        </div>
                        <?php if(!empty($data)): ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="drop-zone-espremnica">
                                        <span class="drop-zone-espremnica__prompt">Spusti datoteke v polje ali klikni za nalaganje</span>
                                        <input type="file" accept=".csv" name="dokument" class="drop-zone-espremnica__input" onchange="upload(<?=$data->id?>)">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>Naložena datoteka</label><br/><br/> <a href="data:text/csv;base64, <?= $data->dokument ?>"><?= $data->naziv_dokumenta ?></a>                
                                </div>
                            </div>
                        <?php endif; ?>  
                        <div class="row">
                            <div class="col-lg-12">
                                <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>                            
                            </div>
                        </div>
                        <?php $handler->html_save_button($data); ?>                    
                    </form>
                </div>
            </div>         

            <script>
                function upload(id){
                    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv|.txt)$/;
                    var fileData = $('.drop-zone-espremnica__input').prop('files')[0];
                    if(regex.test($('.drop-zone-espremnica__input').val())){
                        var formData = new FormData();
                        formData.append('id', id)
                        formData.append('file', fileData, fileData.Name);

                        $.ajax({
                            url: $("meta[name=main_url]").attr("content") + "/api/espremnice-upload-file",
                            type: "POST", 
                            data: formData,
                            processData: false,
                            contentType: false
                        });
                    }else{
                        alert('Prosim naložite datoteko formata .csv');
                    }
                    
                    // var xhr = new XMLHttpRequest();
                    // var csrf_token = document.querySelector("meta[name='csrf-token']").getAttribute("content");
                    // xhr.open("POST", $("meta[name=main_url]").attr("content") + "/api/espremnice-upload-file", true);
                    // xhr.setRequestHeader('x-csrf-token', csrf_token); 
                    // xhr.send(formData);
                }

                document.querySelectorAll(".drop-zone-espremnica__input").forEach((inputElement) => {
                    const dropZoneElement = inputElement.closest(".drop-zone-espremnica");
                    
                    dropZoneElement.addEventListener("click", (e) => {
                        inputElement.click();
                    });
                    
                    inputElement.addEventListener("change", (e) => {
                        if (inputElement.files.length) {
                        updateThumbnail(dropZoneElement, inputElement.files[0]);
                        }
                    });
                    
                    dropZoneElement.addEventListener("dragover", (e) => {
                        e.preventDefault();
                        dropZoneElement.classList.add("drop-zone-espremnica--over");
                    });
                    
                    ["dragleave", "dragend"].forEach((type) => {
                        dropZoneElement.addEventListener(type, (e) => {
                        dropZoneElement.classList.remove("drop-zone-espremnica--over");
                        });
                    });
                    
                    dropZoneElement.addEventListener("drop", (e) => {
                        e.preventDefault();
                    
                        if (e.dataTransfer.files.length) {
                        inputElement.files = e.dataTransfer.files;
                        updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
                        }
                    
                        dropZoneElement.classList.remove("drop-zone-espremnica--over");
                    });
                    });
                    
                    /**
                     * Updates the thumbnail on a drop zone element.
                     *
                     * @param {HTMLElement} dropZoneElement
                     * @param {File} file
                     */
                    function updateThumbnail(dropZoneElement, file) {
                    let thumbnailElement = dropZoneElement.querySelector(".drop-zone-espremnica__thumb");
                    
                    // First time - remove the prompt
                    if (dropZoneElement.querySelector(".drop-zone-espremnica__prompt")) {
                        dropZoneElement.querySelector(".drop-zone-espremnica__prompt").remove();
                    }
                    
                    // First time - there is no thumbnail element, so lets create it
                    if (!thumbnailElement) {
                        thumbnailElement = document.createElement("div");
                        thumbnailElement.classList.add("drop-zone-espremnica__thumb");
                        dropZoneElement.appendChild(thumbnailElement);
                    }
                    
                    thumbnailElement.dataset.label = file.name;
                    
                    // Show thumbnail for image files
                    if (file.type.startsWith("image/")) {
                        const reader = new FileReader();
                    
                        reader.readAsDataURL(file);
                        reader.onload = () => {
                        thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
                        };
                    } else {
                        // thumbnailElement.style.backgroundImage = null;
                        setTimeout(function(){
                            window.location.reload();
                        }, 500);
                    }
                }
            </script>