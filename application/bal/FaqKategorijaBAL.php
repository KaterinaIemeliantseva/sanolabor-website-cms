<?php
class FaqKategorijaBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'faq_kategorije';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'faq_kategorije';
    public $audit_message_single = 'faq_kategorije';

    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
    }
    /**************************************************************************/

}  
        