<?php
class BannerSecondaryBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'banner_secondary';
    var $user;

    public $audit = true; 
    public $audit_message_list = 'banner_secondary';
    public $audit_message_single = 'banner_secondary';

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
        FROM banner_secondary ";

        $data['orderby'] = ' order by banner_secondary.status desc ';
        $result = parent::getList($this->table, $data);
    
        return $result;
    }
}  
        