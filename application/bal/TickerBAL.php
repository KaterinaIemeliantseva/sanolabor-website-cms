<?php
class TickerBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'ticker';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'ticker';
    public $audit_message_single = 'ticker';

    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
    }
    /**************************************************************************/

}  
        