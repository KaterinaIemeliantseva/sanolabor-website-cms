<?php
class VsebinaBAL extends BaseBAL
{
  public $dal;
  public $handler;
  public $bannerDAL;
  var $user;
  public $response_select = [];

  public $audit = false; 
    public $audit_message_list = 'Strani'; 
    public $audit_message_single = 'Stran'; 

  function __construct()
  {
  	global $user;
    $this->table = 'vsebina';
    $this->dal = new BaseDAL();
    $this->user = $user;
    $this->handler = new SuperClass();
  }

  /**************************************************************************/
  function getList($data = [])
  {
      $data['query'] = "SELECT vsebina.id, vsebina.naziv, vsebina.parent,
      vsebina.status, vsebina.sort, vsebina.nicename, vsebina.tip
      , if(vsebina_tip.naziv is not null, vsebina_tip.naziv, '-') as '_tip'
      , if(parent.naziv is not null, parent.naziv, '-') as '_parent'
      FROM ". $this->table ."
      left join vsebina as parent on parent.id = vsebina.parent
      left join vsebina_tip on vsebina_tip.id = vsebina.tip
      ";

      $data['where'] = (!empty($data['where'])) ? $data['where'] : '';
      $data['where'] .= ' and vsebina.parent > 0 ';
      
      return parent::getList($data);
  }

  function update($data)
  {
      if(empty($data['parent']))
      {
          $data['parent'] = 100;
      }

      if(DE)
      {
          //echo MAIN_URL.'/api/update-cache';
      }
      //$this->handler->apiCallAsync(MAIN_URL.'/api/update-cache', ['auth' => $_SESSION['auth_key'] , 'type' => 'vsebina', 'nosignal' => true]);

      return parent::update($this->table, $data);
  }


  /**************************************************************************/



  function getKategorijeSelects($search)
  {
      $this->response_select = [];
      $this->prepareCategoriesSelect(100, '');
      $tags = $this->response_select;
     // print_R($tags);
      if(!empty($search))
      {
          $tags = parent::select2Search($tags, $search);
      }

      //$tags = $this->getList(['search' => $search]);
      return parent::getKategorijeSelect($tags);
  }

  function prepareCategoriesSelect($parent, $title)
  {
      $rows = $this->getList(['where' => ' and vsebina.parent = '. $parent]);
      if($rows)
      {
          foreach ($rows as $key => $value)
          {
              $item = array();
              $item['id'] = $value['id'];
              $item['naziv'] = ($title != '') ? $title.' | '.$value['naziv'] : $value['naziv'];

              //  print_r($item);
              //echo $item['naziv'].'\n';
              $this->response_select[] = $item;

              $children = $this->getList(['parent' => $value['id']]);

              if($children)
              {
                  $this->prepareCategoriesSelect($value['id'], $item['naziv']);
              }
          }
      }
  }

}
