<?php
class TagBAL
{
    public $dal;
    private $handler;
    private $table = 'tag';
    var $user;

    function __construct()
    {
        global $user;

        $this->user = $user;
        $this->handler = new SuperClass();
        $this->base = new BaseBAL();

        //audit
        $this->base->table = $this->table;
        $this->base->audit = false; 
        $this->base->audit_message_single = 'Tag'; 
    }

    /**************************************************************************/
    function getList($data = [])
    {
        $data['groupBy'] = 'naziv';
        $data['orderby'] = 'order by id desc';
        return $this->base->getList($this->table, $data);
    }

    function getSingle($id)
    {
        return $this->base->getSingle($this->table, $id);
    }

    function update($data)
    {
        return $this->base->potrdiSpremembeCMS($this->table, $data, (!empty($data['id'])) ? $data['id'] : false);
    }

    function delete($data)
    {
        return $this->base->delete($this->table, $data['id']);
    }
    /**************************************************************************/

    /**/
    function getAllSelect($data)
    {
        $tags = $this->getList(['search' => $data['search'], 'orderby' => 'order by naziv asc']);
        return $this->base->getKategorijeSelect($tags);
    }

    function getPovezaniSelected($data)
    {
        $tags = $this->base->getList('tag_item ', [
            'query' => ' SELECT tag.id, tag.naziv FROM tag_item
            inner join tag on tag.id = tag_item.tag_id and tag_item.tip = '.$data['tip'].' and tag_item.item_id = '.$data['id'].' ',
            'orderby' => 'order by tag_item.id asc']);

        return $this->base->getKategorijeSelect($tags);
    }
    /**/
}
