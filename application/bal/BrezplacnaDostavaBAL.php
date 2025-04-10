<?php

class BrezplacnaDostavaBAL
{
    public $dal;
    private $handler;
    private $table = 'brezplacna_dostava';
    var $user;

    function __construct()
    {
        global $user;

        $this->user = $user;
        $this->handler = new SuperClass();
        $this->base = new BaseBAL();
    }

  /**************************************************************************/
  function getList($data = [])
  {
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

  function save($data)
    {

        //povezani artikli
        $povezani_artikli = false;
        if(isset($data['povezani_artikli']))
        {
            $povezani_artikli = $data['povezani_artikli'];
            unset($data['povezani_artikli']);
        }

        //vstavi artikel
        $result = $this->update($data);
        $id = (!empty($data['id'])) ? $data['id'] : $result;

        /**/
        if($povezani_artikli)
        {
            $this->base->query("delete from artikel_povezani where tip = 3 and id_item = ".$id.";");
            foreach ($povezani_artikli as $key => $value)
            {
                $this->base->potrdiSpremembeCMS('artikel_povezani', ['id_item' => $id, 'id_artikel' => $value, 'tip' => 3], false);
            }
        }
        /**/

        return $result;
    }

}
