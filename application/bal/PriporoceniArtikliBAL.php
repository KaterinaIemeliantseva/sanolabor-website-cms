<?php
class PriporoceniArtikliBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'priporoceni_artikli';
    var $user;

    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
    }
    /**************************************************************************/
    function getList($data = [])
    {
        $data['orderby'] = 'order by red asc';
        
        return parent::getList($this->table, $data);
    }
}
