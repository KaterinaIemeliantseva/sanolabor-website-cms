<?php
class FaqBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'faq';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'faq';
    public $audit_message_single = 'faq';

    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
    }
    /**************************************************************************/

}  
        