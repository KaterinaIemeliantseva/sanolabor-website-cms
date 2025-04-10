<?php include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');   ?>          

<script type="text/javascript"> 
	$(document).ready(function(){ 

        var copyCode = new Clipboard('.clipboard', {
            text: function(trigger) {

                return trigger.val = 'https://www.sanolabor.si' + $('#pot').val();
                //return trigger.getAttribute('aria-label');
            }
        });
	});
</script>

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
                <div class="col-lg-12">
                    <?php $handler->html_input(['label' => 'Naziv', 'name' => 'naziv', 'value' => (!empty($data->naziv)) ? $data->naziv : '', 'required' => true]); ?>         
                    <?php $handler->html_single_file_upload(['label' => 'Datoteka', 'name' => 'pot', 'value' => (!empty($data->pot)) ? $data->pot : '', 'required' => false]); ?>        
                    <a class="btn btn-success button clipboard" style="color:#fff;"  data-clipboard-target="#pot" aria-label="tgf">Kopiraj povezavo v odložišče</a>           
                </div>
            </div>
            
            <?php $handler->html_save_button($data); ?>                    
        </form>
    </div>
</div>
        