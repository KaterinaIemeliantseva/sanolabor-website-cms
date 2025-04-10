<?php
class SvetujemoBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'svetujemo';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'svetujemo';
    public $audit_message_single = 'svetujemo';

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
        $kategorija = false;
        if(isset($data['kategorije']))
        {
            $kategorija = $data['kategorije'];
            unset($data['kategorije']);
        }

        
        $result = parent::update($data);
        $id_artikel = (!empty($data['id'])) ? $data['id'] : $result;

        if($kategorija)
        {
            parent::query("delete from svetujemo_kategorija_mm where id_clanek = ".$id_artikel.";");
            foreach ($kategorija as $key => $value)
            {
                parent::potrdiSpremembeCMS('svetujemo_kategorija_mm', ['id_clanek' => $id_artikel, 'id_kategorija' => $value], false);
            }
        }

        //$this->handler->apiCallAsync(MAIN_URL.'/api/update-cache', ['auth' => $_SESSION['auth_key'] , 'type' => 'svetovanje', 'nosignal' => true]);
        return $result;
    }

}  
        