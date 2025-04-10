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
        <form action="#" data-c="<?php echo $handler->getClass($foo); ?>" data-m="update" method="post" class="edit_form_validate form-group">
            <div class="row">
              <div class="col-lg-6">
                  <?php if($data): ?><?php $handler->html_label(['label' => 'Slug', 'value' => (!empty($data->nicename)) ? $data->nicename : '']); ?><?php endif; ?>
                
                    <?php 
                    $select_tip = 0;
                    if(!empty($data->tip))
                    {
                        $select_tip = $data->tip;
                    }
                    else if(!empty($_POST['data']['tip_id']))
                    {
                        $select_tip = $_POST['data']['tip_id'];
                    }

                    $handler->html_select2(['id' => 'tip', 'label' => 'Tip navigacije', 'name' => 'tip', 'url' => '/webapp/select2?table=vsebina_tip', 'get_single' => ['id' => $select_tip, 'table' => 'vsebina_tip']]); ?>

                  <?php
                  $opt = ['label' => 'Nadrejena kategorija', 'name' => 'parent', 'url' => '/webapp/vsebina/s2/kategorije', 'required' => false];

                  if(!empty($data->parent))
                  {
                      //echo $data->parent;
                      $selected_kat = $foo->getSingle($data->parent);
                      if($selected_kat)
                      {
                        $opt['selected'] = ['id' => $selected_kat->id, 'text' => $selected_kat->naziv];
                      }
                  }
                  else if(!empty($_POST['data']['parent_kat_id']) && !empty($_POST['data']['parent_kat_text']))
                  {
                      $opt['selected'] = ['id' => $_POST['data']['parent_kat_id'], 'text' => $_POST['data']['parent_kat_text']];
                  }

                  $handler->html_select2($opt);
                  ?>

                  <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>
                  <?php $handler->html_input(['label' => 'Preusmeritev (url)', 'name' => 'preusmeritev', 'value' => (!empty($data->preusmeritev)) ? $data->preusmeritev : '']); ?>
                  <?php $handler->html_input(['label' => 'Vrstni red', 'name' => 'sort', 'type' => 'number', 'value' => (!empty($data->sort)) ? $data->sort : '0', 'required' => false]); ?>
                  
                  <?php $handler->html_input(['label' => 'Meta naslov', 'name' => 'meta_title', 'value' => (!empty($data->meta_title)) ? $data->meta_title : '', 'required' => false]); ?>
                  <?php $handler->html_input(['label' => 'Meta opis', 'name' => 'meta_desc', 'value' => (!empty($data->meta_desc)) ? $data->meta_desc : '', 'required' => false]); ?>
                 
                  <?php $handler->html_checkbox(['label' => 'Aktiven', 'name' => 'status', 'status' => (!empty($data->status))]); ?>
              </div>
              <div class="col-lg-6">
                  
                  <?php $handler->html_editor(['label' => 'Vsebina', 'name' => 'vsebina', 'value' => (!empty($data->vsebina)) ? $data->vsebina : '']); ?>
              </div>
            </div>


            <?php $handler->html_input_hidden( ['name' => 'nicename', 'value' => (!empty($data->nicename)) ? $data->nicename : '']); ?>
            <?php $handler->html_save_button($data); ?>
        </form>
    </div>
 </div>
