<?php
class ProgramKontaktBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'program_kontakt';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'program_kontakt';
    public $audit_message_single = 'program_kontakti';

    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
    }
    /**************************************************************************/

}  
        