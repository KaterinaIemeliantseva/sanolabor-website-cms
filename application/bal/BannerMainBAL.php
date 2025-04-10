<?php
class BannerMainBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'banner_main';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'banner_main';
    public $audit_message_single = 'banner_main';

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
        $data['query'] = "SELECT *
        FROM banner_main ";

        $data['orderby'] = ' order by banner_main.status desc, created_at desc ';
        $result = parent::getList($this->table, $data);
    
        return $result;
    }

}  
        