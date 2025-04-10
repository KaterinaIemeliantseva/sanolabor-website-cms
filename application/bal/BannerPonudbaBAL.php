<?php
class BannerPonudbaBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'banner_ponudba';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'banner_ponudba';
    public $audit_message_single = 'banner_ponudba';

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
        FROM banner_ponudba ";

        $data['orderby'] = ' order by banner_ponudba.status desc ';
        $result = parent::getList($this->table, $data);
    
        return $result;
    }
}  
        