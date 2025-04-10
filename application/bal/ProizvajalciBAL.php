<?php
class ProizvajalciBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'blagovna_znamka';
    var $user;

    public $audit = true; 
    public $audit_message_list = 'Blagovna_znamka';
    public $audit_message_single = 'Blagovna_znamka';

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
        
        //$this->handler->apiCallAsync(MAIN_URL.'/api/update-cache', ['auth' => $_SESSION['auth_key'] , 'type' => 'blagovna_znamka', 'nosignal' => true]);

        return parent::update($this->table, $data);
    }

}  
        