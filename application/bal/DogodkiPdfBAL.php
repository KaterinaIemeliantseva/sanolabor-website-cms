<?php
class DogodkiPdfBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'dogodki_pdf';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'dogodki_pdf';
    public $audit_message_single = 'dogodki_pdf';

    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
    }
    /**************************************************************************/

}  
