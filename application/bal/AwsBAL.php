<?php
class AWSBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = '';
    var $user;

    public $audit = false; 

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
       
        if(empty($data['search']))
        {
            return [];
        }

        $post_data = [];
        $post_data['Destination'] = $data['search'];
        $post_data['Source'] = '@sanolabor.si';
        $result = $this->handler->apiCall('http://aws.seslog/api/ses/search', $post_data);
        if($result && $result->success)
        { 
            return (array)$result->data;
        }

        return [];
    }

    /**************************************************************************/

    
}
