<?php
class TekstBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'tekst';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'Tekst'; 
    public $audit_message_single = 'Tekst'; 


    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();



    }

    function getList($data = [])
    {
        $data['array_search'] = ['naziv', 'id', 'opis'];

        return parent::getList($this->table, $data);
    }

    function update($data)
    {
        
       // $this->handler->apiCallAsync(MAIN_URL.'/api/update-cache', ['auth' => $_SESSION['auth_key'] , 'type' => 'tekst', 'nosignal' => true]);

        return parent::update($this->table, $data);
    }

}
