<?php
include (ROOT . DS . 'application' . DS . 'dal' . DS . 'BaseDAL.php');

class BaseBAL
{
  public $dal;
  public $handler;
  public $table;

  var $user;

  function __construct()
  {
  	global $user;

  	$this->user = $user;
    $this->dal = new BaseDAL();
    $this->handler = new SuperClass();
    $this->db = Database::obtain();
  }

  function potrdiSpremembeCMS($table, $data, $id, $costum = '')
  { 
      if($table == 'cms_menu')
      {
          
      }
      else if(!empty($data['naziv']) && isset($data['nicename']) && empty($data['nicename']))
      {
          $data['nicename'] = $this->checkNicename($table, $data['naziv']);
      }
      else if(isset($data['nicename']))
      {
          unset($data['nicename']);
      }
      
      $translate_data = [];
      foreach ($data as $key => $value)
      {
          if(substr($key, 0, 1) == '_')
          {
              unset($data[$key]);
          }

          if((strpos($key, 'datum') !== false || strpos($key, 'rok_veljavnosti') !== false) && strpos($data[$key], '-') === false && $table != 'javna_narocila')
          {
              $data[$key] = $this->handler->slo_to_date($data[$key]);
          }

          if((strpos($key, '_translate') !== false))
          {
            $n_key = str_replace('_translate', '', $key);
            $data[$n_key] = $value;
            $translate_data[$n_key] = $value;
            unset($data[$key]);
          }
      }

      if(isset($data['sort']) && !is_numeric($data['sort']))
      {
          $data['sort'] = 0;
      }
      
      
 
      //html_tags
      $tags = false;
      if(isset($data['tags']))
      {
          $tags = $data['tags'];
          unset($data['tags']);
      }

      //audit - get old values
      if(!empty($this->audit) && !empty($id))
      {
        $old_value_data = (array)$this->dal->getSingle($table, $id);
      }

      //jezik
      $jezik = (!empty($_SESSION['jezik'])) ? $_SESSION['jezik'] : 1;

      

      $result = $this->dal->potrdiSpremembe($table, $data, $id, $costum);
      $new_id = (!empty($data['id'])) ? $data['id'] : $result;


      //vnos prevodov
    if($jezik && $translate_data)
    {
        foreach ($translate_data as $field_name => $value_item) 
        {

            $jezik_data = [];
            $jezik_data['item_id'] = $new_id;
            $jezik_data['tabela'] = $table;
            $jezik_data['jezik'] = $jezik;
            $jezik_data['polje'] = $field_name;
            $jezik_data['status'] = 1;
            $jezik_data['vsebina'] = $value_item;
           
            $this->query("delete from vsebina_prevodi where item_id = ".$new_id." and tabela = '".$table."' and jezik = ".$jezik." and polje = '".$field_name."';");
            $this->dal->potrdiSpremembe('vsebina_prevodi', $jezik_data, null, '');
        }
    }

    //audit
    if(!empty($this->audit))
    {
        if($data)
        {
            $audit_entries['Entries'] = [];

            foreach ($data as $field_name => $value_item) 
            {
                $old_value = (!empty($old_value_data[$field_name])) ? $old_value_data[$field_name] : '';

                $audit_entries['Entries'][] = ['FieldName' => $field_name, 'TrailType' => 2, 'OldValue' => $old_value, 'NewValue' => $value_item, 'OldDescription' => '', 'NewDescription' => '', 'TableName' => $this->table];
            }
            
            $GLOBALS['audit_config']['UserLoginID'] = $_SESSION['userSessionValue'];
            $GLOBALS['audit_config']['UserLoginName'] = (!empty($_SESSION['userSessionName'])) ? $_SESSION['userSessionName'] : 'anon';
            $GLOBALS['audit_config']['Message'] = $this->audit_message_single;
            $GLOBALS['audit_config']['Code'] = (empty($id)) ? 'CREATE' : 'UPDATE';
            Audit::add($GLOBALS['audit_config'], $audit_entries);
        }
    }

      /**/
      if($tags)
      {
          $tip = 0;
          foreach ($tags as $key => $value)
          {
              $tip = $key;
              break;
          }

          $this->query("delete from tag_item where tip = ".$tip." and item_id = ".$new_id.";");
          foreach ($tags[$tip] as $key => $value)
          {
              if($value == '0')
              {
                  continue;
              }

              if(!is_numeric($value))
              {
                  $value = $this->dal->potrdiSpremembe('tag', ['naziv' => $value], false);
              }

              $this->dal->potrdiSpremembe('tag_item', ['item_id' => $new_id, 'tag_id' => $value, 'tip' => $tip], false);
          }
      }
      /**/

      return $result;
  }

  function potrdiSpremembeNoLog($table, $data, $id, $costum = '')
  {

 		return $this->dal->potrdiSpremembeNoLog($table, $data, $id, $costum);
  }

  function saveFile($data)
  {
      //print_r($data);
      if(!empty($data['sort']))
      {
        return $this->potrdiSpremembeCMS('files', [
                'guid' => $data['guid'],
                'type' => $data['type'],
                'item_id' => $data['item_id'],
                'path' => $data['url'],
                'sort' => $data['sort'],
                'thumbnail' => (!empty($data['thumbnail'])) ? $data['thumbnail'] : ''
            ], false);
      }
      else 
      {
        return $this->potrdiSpremembeCMS('files', [
                'guid' => $data['guid'],
                'type' => $data['type'],
                'item_id' => $data['item_id'],
                'path' => $data['url'],
                'thumbnail' => (!empty($data['thumbnail'])) ? $data['thumbnail'] : ''
            ], false);
      }
      
  }

    function saveCategories($data){
        // print_r($data);
        return $this->potrdiSpremembeCMS('artikel_kategorija_mm', [
            'id_artikel' => $data['item_id'],
            'id_kategorija' =>$data['data']
        ], false);
    }

  function changeFileTitle($data)
  {
      return $this->potrdiSpremembeCMS('files', [
          'title' => $data['title']
      ], false, "guid = '". $data['guid']."'");
  }

  function changeFileOrder($data)
  {
      return $this->potrdiSpremembeCMS('files', [
          'sort' => $data['order']
      ], false, "guid = '". $data['guid']."'");
  }

  function deleteFile($data)
  {
      $file = $this->getFile($data);
      if(!$file)
      {
          return false;
      }

      $res = $this->dal->deleteFile($file['id']);
      if($res) {
          try
          {
              @unlink('../..'.$file['path']);

              if(!empty($file['thumbnail']))
              {
                  @unlink('../..'.$file['thumbnail']);
              }
          }
          catch (Exception $e)
          {
              echo 'Caught exception: ',  $e->getMessage(), "\n";
              return false;
          }
      }
      return $res;
  }

  function getFiles($data)
  {
      $res = $this->dal->getFiles($data['type'], $data['item_id']);
      return ($res) ? $res : [];
  }

  function getFile($data)
  {
      return $this->dal->getFile($data['type'], $data['item_id'], $data['guid']);
  }

  function getVsebinaByNicename($nicename)
  {
    $record = $this->dal->getVsebinaByNicename($nicename);
    return $record;
  }

  function getArtikelKategorijaa($id)
  {
    $record = $this->dal->getArtikelKategorijaa($id);
    return $record;
  }

  function getArtikelKategorijaNaziv($array)
  {
    $record = $this->dal->getArtikelKategorijaNaziv($array);
    return $record;
  }

  function GetMetaTitle()
  {
      return 'CMS';
  }

  function mainMenuCMS2($parent, $user)
  {

    $rows = $this->dal->getMenuCMS2($parent, $user);
    $i=0;
    if($rows)
    {
    ?>
    <ul id="main-nav">
    <?php

      foreach($rows as $key=>$val)
      {
          $class = '';
          if(defined('NIVO0_NICENAME'))
          {
              if(NIVO0_NICENAME == $val['nicename'])
              {
                  $class = 'current';
              }
          }

          if($val['preusmeritev'])
          {
              $link = $val['preusmeritev'];
          } else $link = '/'.$val['nicename'].'?jezik=si';

          $pos = strpos($link, 'http://');
          if ($pos !== false) $target = ' target="_blank" ';
          else $target = ' target="_self" ';

          ?><li><a <?php echo $target; ?> href="<?php echo $link; ?>" class="nav-top-item <?php echo $class; ?> <? if(!empty($val['preusmeritev'])) echo 'no-submenu'; ?>"><?php echo $val['naziv']; ?></a><?php

          //nivo2
                  $rows2 = $this->dal->getMenuCMS2($val['id'], $user);
                  if($rows2)
                  {
                    ?><ul><?php
                    foreach($rows2 as $key=>$val2)
                    {
                      $class2 = '';
                      if(defined('NIVO1_NICENAME'))
                      {
                        if(NIVO1_NICENAME == $val2['nicename'])
                        {
                          $class2 = 'class="current"';
                        }
                      }
                      if($val2['preusmeritev'])
                      {
                        $link2 = $val2['preusmeritev'];
                      } else $link2 = '/'.$val['nicename'].'/'.$val2['nicename'].'?jezik=si';

                      $pos = strpos($link2, 'http://');
                      if ($pos !== false) $target = ' target="_blank" ';
                      else $target = ' target="_self" ';
                      ?><li><a <?php echo $target;?> href="<?php echo $link2;?>" <?=$class2?>><?php echo $val2['naziv']; ?></a></li><?php
                    }
                    ?></ul><?php
                  }
                  ?></li><?php


          $i++;
      }
    ?>
    </ul>
    <?php
    }
  }


  function dobiKategorijaNaziv($nicename)
  {
      $record = $this->dal->dobiKategorijaNaziv($nicename);
       return $record;
  }

  function query($sql)
  {
      //if(DE) echo $sql;
      return $this->dal->query($sql);
  }

  function getNicename($table, $slug)
  {
      return $this->dal->getNicename($table, $this->handler->generateSlug($slug, 50));
  }

  function checkNicename($table, $val)
  {
      $st = $this->getNicename($table, $val); //echo $st; die();
      if($st > 0)
      {
          $nicename = $this->handler->generateSlug($val, 50).'-'.$st;
      }
      else
      {
          $nicename = $this->handler->generateSlug($val, 50);
      }

      if(is_numeric($nicename)) return '';

      return $nicename;
  }

  function updateStatus($index)
  {
      return $this->dal->updateStatus($index);
  }

    //   Old version
    //   function select2prepare($data)
    //   {
    //       $search = (isset($data['search'])) ? $data['search'] : $data;
    //         $param = ['search' => $data['search'], 'orderby' => ' order by naziv asc'];
    //         if(!empty($data['where']))
    //         {
    //             $param['where'] = ' and '.$data['where'];
    //         }
    //       $tags = $this->getList($data['table'], $param);
    //       //$tags = $this->getList(['search' => $search]);
    //       return $this->getKategorijeSelect($tags);
    //   }

    // New verison
    function select2prepare($data)
    {
        $sql = "SHOW COLUMNS FROM `" . $data['table'] . "`";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $search = (isset($data['search'])) ? $data['search'] : $data;

        if (in_array('naziv', $columns)) {
            $param = ['search' => $data['search'], 'orderby' => ' order by naziv asc'];
        } else {
            $param = ['search' => $data['search'], 'orderby' => ' order by name asc'];
        }
        
        if(!empty($data['where']))
        {
            $param['where'] = ' and '.$data['where'];
        }

        if($data['table'] == 'dobavitelj_users') {
            $_SESSION['columns'] = 'dobavitelj_users';
        }

        $tags = $this->getList($data['table'], $param);
        //$tags = $this->getList(['search' => $search]);

        return $this->getKategorijeSelect($tags);
    }


    function getKategorijeSelect($list, $opt = [])
    {
        $response = array();
        if($list)
        {
            // Old version
            // $id_name = (!empty($opt['id_name'])) ? $opt['id_name'] : 'id';
            // $text_name = (!empty($opt['text_name'])) ? $opt['text_name'] : 'naziv';

            // New version
            $id_name = !empty($opt['id_name']) ? $opt['id_name'] : ((isset($_SESSION['columns']) && $_SESSION['columns'] == 'dobavitelj_users') ? 'dobavitelj_id' : 'id');
            $text_name = !empty($opt['text_name']) ? $opt['text_name'] : ((isset($_SESSION['columns']) && $_SESSION['columns'] == 'dobavitelj_users') ? 'name' : 'naziv');
            if(isset($_SESSION['columns'])) {
                unset($_SESSION['columns']);
            } 


            foreach ($list as $key => $value)
            {
                if(!empty($post['q']) && strpos( $value['naziv'], $post['q']) === false)
                {
                    continue;
                }

                $naziv = $value[$text_name];
                if(!empty($value['sifra']))
                {
                    $naziv = $value['sifra'] . ' - ' . $naziv;
                }

                $response[] = array('id' => $value[$id_name], 'text' => $naziv);
            }
        }

        return $response;
    }

  function select2Search($response_select, $search)
  {
      $tags = [];
      if($response_select)
      {
          foreach ($response_select as $key => $value)
          {
              if(strpos(strtolower($value['naziv']), strtolower($search)) !== false)
              {
                  $tags[] = $value;
              }
          }
      }

      return $tags;
  }

  /**/
  function updateOrg($data)
  {
        
      return $this->potrdiSpremembeCMS($this->table, $data, (!empty($data['id'])) ? $data['id'] : false);
  }
  
  function updateExt($table, $data)
  {
   
      return $this->potrdiSpremembeCMS($table, $data, (!empty($data['id'])) ? $data['id'] : false);
  }
  /**/

  /**/
  function getListOrg($table, $data = [])
  {
      return $this->dal->getList($table, $data);
  }

  function getListExt($data = [])
  {
      return  $this->getListOrg($this->table, $data);
  }
  /**/

  /**/
    function getSingleOrg($table, $id)
    {
        $result = $this->dal->getSingle($table, $id);

        if( !empty($this->audit))
        {
            if($result)
            {
                try 
                {
                    $audit_entries['Entries'] = [];

                    foreach ($result as $field_name => $value_item) 
                    {
                        $audit_entries['Entries'][] = ['FieldName' => $field_name, 'TrailType' => 2, 'OldValue' => $value_item, 'NewValue' => '', 'OldDescription' => '', 'NewDescription' => '', 'TableName' => $this->table];
                    }
                    
                    $GLOBALS['audit_config']['UserLoginID'] = $_SESSION['userSessionValue'];
                    $GLOBALS['audit_config']['UserLoginName'] = (!empty($_SESSION['userSessionName'])) ? $_SESSION['userSessionName'] : 'anon';
                    $GLOBALS['audit_config']['Message'] = $this->audit_message_single;
                    $GLOBALS['audit_config']['Code'] = 'READ';
                    Audit::add($GLOBALS['audit_config'], $audit_entries);
                }
                catch(Exception $e) 
                {
                    
                }
            }
        }

        return $result;
    }

  function getSingleExt($id)
  {
      return $this->getSingleOrg($this->table, $id);
  }
  /**/

  /**/
  function deleteOrg($table, $id)
  {
      //audit - get old values
      if(!empty($this->audit) && !empty($id))
      {
        $data = (array)$this->dal->getSingle($table, $id);
        if($data)
        {
            $audit_entries['Entries'] = [];

            foreach ($data as $field_name => $value_item) 
            {
                $audit_entries['Entries'][] = ['FieldName' => $field_name, 'TrailType' => 2, 'OldValue' => $value_item, 'NewValue' => '', 'OldDescription' => '', 'NewDescription' => '', 'TableName' => $this->table];
            }
            
            $GLOBALS['audit_config']['UserLoginID'] = $_SESSION['userSessionValue'];
            $GLOBALS['audit_config']['UserLoginName'] = (!empty($_SESSION['userSessionName'])) ? $_SESSION['userSessionName'] : 'anon';
            $GLOBALS['audit_config']['Message'] = $this->audit_message_single;
            $GLOBALS['audit_config']['Code'] = 'DELETE';
            Audit::add($GLOBALS['audit_config'], $audit_entries);
        }
      }
      

      return $this->dal->delete($table, $id);
  }

  function deleteExt($data)
  {
      return $this->deleteOrg($this->table, $data['id']);
  }
  /**/

  public function __call($method, $arguments)
  {
      if($method == 'update')
      {
         
          if(count($arguments) == 1)
          { 
             return call_user_func_array(array($this,'updateOrg'), $arguments);
          }
          else if(count($arguments) == 2)
          {
             return call_user_func_array(array($this,'updateExt'), $arguments);
          }
      }

      if($method == 'delete')
      {
          if(count($arguments) == 1)
          {
             return call_user_func_array(array($this,'deleteExt'), $arguments);
          }
          else if(count($arguments) == 2)
          {
             return call_user_func_array(array($this,'deleteOrg'), $arguments);
          }
      }

      if($method == 'getSingle')
      {
          if(count($arguments) == 1)
          {
             return call_user_func_array(array($this,'getSingleExt'), $arguments);
          }
          else if(count($arguments) == 2)
          {
             return call_user_func_array(array($this,'getSingleOrg'), $arguments);
          }
      }

      if($method == 'getList')
      {
          //if(DE) echo $method.': '.count($arguments);
          if(count($arguments) <= 1)
          {
             return call_user_func_array(array($this,'getListExt'), $arguments);
          }
          else if(count($arguments) == 2)
          {
             return call_user_func_array(array($this,'getListOrg'), $arguments);
          }
      }
  }

}
