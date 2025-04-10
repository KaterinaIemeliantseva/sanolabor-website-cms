<?php
class PriporocamoBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'priporocamo';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'priporocamo';
    public $audit_message_single = 'priporocamo';

    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
    }
    /**************************************************************************/

    function update($data)
    {

        //povezani artikli
        $povezani_artikli = false;
        if(isset($data['povezani_artikli']))
        {
            $povezani_artikli = $data['povezani_artikli'];
            unset($data['povezani_artikli']);
        }

     

        //vstavi artikel
        $result = parent::update($data);
        $id = (!empty($data['id'])) ? $data['id'] : $result;

        /**/
        if($povezani_artikli)
        {
            parent::query("delete from artikel_povezani where tip = 7 and id_item = ".$id.";");
            foreach ($povezani_artikli as $key => $value)
            {
                parent::potrdiSpremembeCMS('artikel_povezani', ['id_item' => $id, 'id_artikel' => $value, 'tip' => 7], false);
            }
        }
        /**/

        return $result;
    }
}  
        