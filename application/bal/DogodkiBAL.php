<?php
class DogodkiBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'dogodki';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'dogodki';
    public $audit_message_single = 'dogodki';

    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
    }
    /**************************************************************************/

    // function update($data)
    // {
    //     $kategorija = false;
    //     if(isset($data['tip_dogodka']))
    //     {
    //         $kategorija = $data['tip_dogodka'];
    //         unset($data['tip_dogodka']);
    //     }

        
    //     $result = parent::update($data);
    //     $id_artikel = (!empty($data['id'])) ? $data['id'] : $result;

    //     if($kategorija)
    //     {
    //         parent::query("delete from dogodki_tip_mm where id_dogodek = ".$id_artikel.";");
    //         foreach ($kategorija as $key => $value)
    //         {
    //             parent::potrdiSpremembeCMS('dogodki_tip_mm', ['id_dogodek' => $id_artikel, 'id_tip' => $value], false);
    //         }
    //     }

    //     return $result;
    // }


    function getEnota($id)
    {
        $enota = new EnoteBAL();
        return $enota->getSingle($id);
    }

    function getProizvajalec($id)
    {
        $enota = new ProizvajalciBAL();
        return $enota->getSingle($id);
    }


    function getKoledarDogodkov($data)
    {
        $data['query'] = "SELECT enota.naziv, dogodki_koledar.datum_od, dogodki_koledar.cas_od, dogodki_koledar.cas_do, dogodki_koledar.id  
        FROM dogodki_koledar
        inner join enota on enota.id = dogodki_koledar.id_lokacija

            ";
    
            $data['where'] = ' and  dogodki_koledar.id_dogodek = '.$data['dogodek_id'];
            $data['orderby'] = ' ORDER BY dogodki_koledar.datum_od desc ';

            return parent::getList($this->table, $data);
    }

}  
