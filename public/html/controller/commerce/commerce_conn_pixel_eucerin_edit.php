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
                            <div class="col-lg-12" style="word-break:break-word;">
                                <?php $handler->html_label(['label' => 'Naročilo', 'value' => (!empty($data->narocilo_id)) ? $data->narocilo_id : '']); ?>
                                <?php $handler->html_label(['label' => 'Šifra artiklov', 'value' => (!empty($data->artikli)) ? $data->artikli : '']); ?>
                                <?php $handler->html_label(['label' => 'Podatki', 'value' => (!empty($data->podatki)) ? $data->podatki : '']); ?>                           
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        