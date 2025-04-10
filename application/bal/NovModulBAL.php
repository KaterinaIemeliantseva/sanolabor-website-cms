<?php
class NovModulBAL extends BaseBAL
{
    public $dal;
    public $handler;
    var $user;

    function __construct()
    {
        global $user;
        $this->table = 'cms_menu';
        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
    }
    /**************************************************************************/

    function update($data)
    {
        
        if(!empty($data['nicename']))
        {
            $class='';
            if(!empty($data['class']))
            {
                $class=$data['class'];
                unset($data['class']);
            }

            $root = dirname(dirname(dirname(__FILE__)));
            $parent = parent::getSingle($this->table, $data['parent']);
            $fileList =  $root.DS.'public'.DS.'html'.DS.'controller'.DS.$parent->nicename.DS.$data['nicename'].'.php';
            $fileEdit =  $root.DS.'public'.DS.'html'.DS.'controller'.DS.$parent->nicename.DS.$data['nicename'].'_edit.php';
            $fileJS =  $root.DS.'public'.DS.'html'.DS.'controller'.DS.$parent->nicename.DS.$data['nicename'].'.js';
            $fileClass =  $root.DS.'application'.DS.'bal'.DS.$class.'BAL.php';

           // echo $fileClass.'';die();

            if(!file_exists($fileList))
            {
                $myfile = fopen($fileList, "w");
                fwrite($myfile, $this->templateList($class));
                fclose($myfile);
            }

            if(!file_exists($fileEdit))
            {
                $myfile = fopen($fileEdit, "w");
                fwrite($myfile, $this->templateEdit());
                fclose($myfile);
            }

            if(!file_exists($fileClass))
            {
                $myfile = fopen($fileClass, "w");
                fwrite($myfile, $this->templateClass($class, $data['nicename']));
                fclose($myfile);
            }

            if(!file_exists($fileJS))
            {
                $myfile = fopen($fileJS, "w");
                fwrite($myfile, $this->templateJS());
                fclose($myfile);
            }
        }

        //print_r($data); die();
        $result = parent::update($data);
        return $result;
    }

    function getList($data = [])
    {

        $data['array_search'] = ['cms_menu.naziv', 'cms_menu.nicename', 'cms_menu.id', 'cms_menu.parent'];

       // if(DE) print_r($data);
        $result = parent::getList($this->table, $data);
    
        return $result;
    }

    function templateList($class)
    {
        ob_start();
        ?>

        <div id="magic_box" data-c="<?php echo $class; ?>" class="content-box">
            <div class="content-box-header">
            <h3><?php echo '<?php echo $this->dobiKategorijaNaziv(); ?>'; ?></h3>
            </div>
            <div class="content-box-content">
                <div class="tab-content default-tab" >
                    <table id="seznam" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Aktiven*</th>
                                <th>Naziv</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <?php 
        $text = ob_get_contents();
        ob_end_clean();

        return $text;
    }


    function templateEdit()
    {
        ob_start();
        ?><?php echo "<?php include (dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/edit_header.php');   ?>"; ?>
            <div class="content-box-header">
                <h3><?php echo '<?php if(!empty($data->naziv)) echo $handler->mbCutText($data->naziv, 100);?>'; ?></h3>
                <ul class="content-box-tabs">
                    <li><a href="#tab1" class="default-tab current">Uredi</a></li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="content-box-content">
                <div class="tab-content default-tab active" id="tab1">
                    <form action="#" data-c="<?php echo '<?php echo $handler->getClass($foo); ?>'; ?>" data-m="update" method="post" class="edit_form_validate form-group">
                        <div class="row">
                            <div class="col-lg-12">
                            <?php echo '<?php $handler->html_input([\'label\' => \'Naziv\', \'name\' => \'naziv\', \'value\' => (!empty($data->naziv)) ? $data->naziv : \'\', \'required\' => true]); ?>'; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                            <?php echo '<?php $handler->html_checkbox([\'label\' => \'Aktiven\', \'name\' => \'status\', \'status\' => (!empty($data->status))]); ?>'; ?>
                            </div>
                        </div>
                        <?php echo '<?php $handler->html_save_button($data); ?>'; ?>
                    </form>
                </div>
            </div>
        <?php 
        $text = ob_get_contents();
        ob_end_clean();

        return $text;
    }

    function templateClass($class, $table)
    {
        ob_start();
        echo '<?php
        class '.$class.'BAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = \''.$table.'\';
            var $user;

            public $audit = true; 
            public $audit_message_list = \''.$table.'\';
            public $audit_message_single = \''.$table.'\';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  '; ?>

        <?php 
        $text = ob_get_contents();
        ob_end_clean();

        return $text;
    }


    function templateJS()
    {
        ob_start();
        ?>

$(function(){
    var self = this;

    /*table - start*/
    seznam_default.columns = [
        seznam_button_status,
        { "data": "naziv" },
        { "data": "null", "width" : "130px", "defaultContent": seznam_button_edit + seznam_button_delete }
    ];
    seznam_default.buttons = [{text: 'Dodaj', action: function ( e, dt, node, config ) { setAddButton(dt); }} ];

    var seznam = $('#seznam');
    var table = seznam.DataTable(seznam_default);
    setDeleteButton(seznam, table);
    setEditButton(seznam, table);
    setChangeStatusButton(seznam, table);

    table.MakeCellsEditable({
        "onUpdate": seznamUpdateCallbackFunction,
        "columns": []
    });
    /*table - end*/
});


        <?php 
        $text = ob_get_contents();
        ob_end_clean();

        return $text;
    }
}