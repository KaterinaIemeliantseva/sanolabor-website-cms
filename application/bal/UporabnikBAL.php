<?php
class UporabnikBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'users';
    var $user;

    public $audit = true; 
    public $audit_message_list = 'Uporabniki'; 
    public $audit_message_single = 'Uporabnik'; 

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
 
        $data['array_search'] = ['username', 'email','ime', 'priimek', 'naslov', 'mesto', 'postna_st', 'tel'];

        $data['where'] = " and status = 1 and username not like 'hitrinakup%'  ";
        $data['orderby'] = 'order by id desc';
        $data['limit'] = ' 3000 ';

        return parent::getList($this->table, $data);
    }

      /**************************************************************************/

      function save($data)
      {
          if(!empty($data['password_old']))
          {
              $data['password'] = '';
              $data['password_old'] = password_hash($data['password_old'], PASSWORD_DEFAULT);
          }
          else
          {
              unset($data['password_old']);
              unset($data['password']);
          }

         // print_r($data);

          return parent::update($data);
      }
}
