<?php
class BannerMtpBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'banner_mtp';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'banner_mtp';
    public $audit_message_single = 'banner_mtp';

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
        FROM banner_mtp ";

        $data['orderby'] = ' order by banner_mtp.status desc, created_at desc ';
        $result = parent::getList($this->table, $data);
    
        return $result;
    }

}  
        