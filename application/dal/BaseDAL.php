<?php
class BaseDAL
{
  var $db;

  function __construct()
  {
    $this->db = Database::obtain();
    $this->handler = new SuperClass();
  }


  function potrdiSpremembe($tabel, $data, $id, $costum = '')
  {
    if(isset($data['created_at']) )
    {
      unset($data['created_at']);
    }
 
    $data['updated_at'] = date('Y-m-d H:i:s');

   // print_r($data);

    if($costum)
    {
      return $this->db->update_cms($tabel, $data, $costum);
    }
    else
    {
      if(!$id)
      {
        if($tabel == 'black_lista')
          {
            $data['kontrola'] = $data['ime'] ? "ime !='' " : "ime='' ";
            $data['kontrola'] .= $data['priimek'] ? "and priimek !='' " : "and priimek='' ";
            $data['kontrola'] .= $data['naslov_blacklista'] ? "and naslov_blacklista !='' " : "and naslov_blacklista='' ";
            $data['kontrola'] .= $data['postna_st'] ? "and postna_st !='' " : "and postna_st='' ";
            $data['kontrola'] .= $data['email'] ? "and email !='' " : "and email='' ";
            $data['kontrola'] .= $data['drzava'] ? "and drzava !='' " : "and drzava='' ";
            $data['kontrola'] .= $data['telefon'] ? "and telefon !='' " : "and telefon='' ";
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['naslov_blacklista'] = str_replace(" ", "",$data['naslov_blacklista']);
          }
        $primary_id = $this->db->insert_cms($tabel, $data);
       // if(DE)        $this->handler->apiCall(MAIN_URL.'/api/update-cache', ['auth' => $_SESSION['auth_key'] , 'table' => $tabel, 'id' => $primary_id, 'updated_at' => $data['updated_at'] ]);
        return $primary_id;
      } 
      else
      {
        if($tabel == 'black_lista')
          {
            $data['kontrola'] = $data['ime'] ? "ime !='' " : "ime='' ";
            $data['kontrola'] .= $data['priimek'] ? "and priimek !='' " : "and priimek='' ";
            $data['kontrola'] .= $data['naslov_blacklista'] ? "and naslov_blacklista !='' " : "and naslov_blacklista='' ";
            $data['kontrola'] .= $data['postna_st'] ? "and postna_st !='' " : "and postna_st='' ";
            $data['kontrola'] .= $data['email'] ? "and email !='' " : "and email='' ";
            $data['kontrola'] .= $data['drzava'] ? "and drzava !='' " : "and drzava='' ";
            $data['kontrola'] .= $data['telefon'] ? "and telefon !='' " : "and telefon='' ";
            $data['naslov_blacklista'] = str_replace(" ", "",$data['naslov_blacklista']);
          }
        //if(DE)          $this->handler->apiCall(MAIN_URL.'/api/update-cache', ['auth' => $_SESSION['auth_key'] , 'table' => $tabel, 'id' => $id, 'updated_at' => $data['updated_at'] ]);
          return $this->db->update_cms($tabel, $data, "id='".$id."'");
      }
    }

    return false;
  }

  function potrdiSpremembeNoLog($tabel, $data, $id, $costum = '')
  {
    if($costum)
    {
      return $this->db->update($tabel, $data, $costum);
    }
    else
    {
      if(!$id)
      {
        //echo $tabel;
        return $primary_id = $this->db->insert($tabel, $data);
      }
      else
      {
        return $this->db->update($tabel, $data, "id='".$id."'");
      }
    }

	return false;
  }

  function query($sql)
  {
      $stmt = $this->db->link_id->prepare($sql);
      return $stmt->execute();
  }

  function delete($table, $id)
  {
      $sql = "DELETE FROM ".$table." WHERE id = :id ";
      $stmt = $this->db->link_id->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      return $stmt->execute();
  }

  function getSingle($table, $id)
  {
      $sql = "SELECT * FROM $table where id = :id "; //echo $sql;
      $stmt = $this->db->link_id->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_OBJ);

      // if($result)
      // {
      //   $jezik = 1;
      //   if(!empty($_SESSION['jezik']))
      //   {
      //     $jezik = $_SESSION['jezik'];
      //   }

      //   $result = $this->getTranslate($id, $table, $jezik, $result);
      // }

      return $result;
  }

  function getArtikelKategorijaa($id)
  {
    $sql = "SELECT artikel_kategorija.id, artikel_kategorija.naziv
    FROM artikel_kategorija
    INNER JOIN artikel_kategorija_mm ON artikel_kategorija.id = artikel_kategorija_mm.id_kategorija
    WHERE artikel_kategorija_mm.id_artikel = :id ";
    $stmt = $this->db->link_id->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $res;
  }

  function getArtikelKategorijaNaziv($array)
  {
    // $newarray = implode(',', $array);
    $sql = "SELECT id, naziv
    FROM artikel_kategorija
    where id in($array)";
    $stmt = $this->db->link_id->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $res;
  }

  function getTranslate($id, $table, $jezik, $data)
  {
      $sql = "SELECT * FROM vsebina_prevodi where item_id = :id and tabela = :tabela and jezik = :jezik  "; //echo $sql;
      $stmt = $this->db->link_id->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->bindValue(':tabela', $table, PDO::PARAM_INT);
      $stmt->bindValue(':jezik', $jezik, PDO::PARAM_INT);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
      if($result)
      {
        foreach ($result as $key => $value) 
        {
          $field = $value->polje;
            if(isset($data->$field))
            {
              $data->$field = $value->vsebina;
            }
        }
      }

      return $data;
  }

  function dobiPolja($table, $id)
  {
    $sql = "SELECT * FROM ".$table."
            WHERE id = '".$id."'";

    //echo $sql;
    $record = $this->db->query_first($sql);
    // print_r($record);
    if($record) return $record;
    return false;
  }


  function getVsebinaByNicename($nicename)
  {
    $sql = "SELECT * FROM vsebina where nicename = :nicename AND status=1"; //echo $sql;
    $stmt = $this->db->link_id->prepare($sql);
    $stmt->bindValue(':nicename', $nicename, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  function dobiKategorijaNaziv($nicename)
  {
    $sql = "SELECT * FROM cms_menu where nicename = :nicename"; //echo $sql;
    $stmt = $this->db->link_id->prepare($sql);
    $stmt->bindValue(':nicename', $nicename, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  function getFiles($type, $id_item)
  {
    $sql = "SELECT * FROM files where type = :type and item_id =:item_id order by sort asc "; //echo $sql;
    $stmt = $this->db->link_id->prepare($sql);
    $stmt->bindValue(':item_id', $id_item, PDO::PARAM_INT);
    $stmt->bindValue(':type', $type, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  function getFile($type, $id_item, $guid)
  {
    $sql = "SELECT * FROM files where type = :type and item_id =:item_id and guid =:guid "; //echo $sql;
    $stmt = $this->db->link_id->prepare($sql);
    $stmt->bindValue(':item_id', $id_item, PDO::PARAM_INT);
    $stmt->bindValue(':type', $type, PDO::PARAM_INT);
    $stmt->bindValue(':guid', $guid, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  function deleteFile($id)
  {
    $sql = "delete FROM files where id = :id  "; //echo $sql;
    $stmt = $this->db->link_id->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  function getMenuCMS2($parent, $user)
  {
    $sql = "SELECT cms_menu.id as id, nicename, naziv, preusmeritev
          FROM cms_menu
          inner join cms_user_dostop on cms_user_dostop.id_kategorija = cms_menu.id
            WHERE parent = '".$parent."' and status = 1 and cms_user_dostop.id_item = '".$user."'
            ORDER BY sort asc";      // echo $sql;
    $record = $this->db->fetch_array($sql);
    // print_r($record);
    if($record) return $record;
    else return false;
  }

  function getNicename($table, $nicename)
  {
      $sql = "SELECT count(*) as stevec FROM $table
      WHERE nicename LIKE :nicename
      ORDER BY id asc";

      $stmt = $this->db->link_id->prepare($sql);
      $stmt->bindValue(':nicename', $nicename.'%', PDO::PARAM_STR);
      $stmt->execute();
      $record = $stmt->fetch(PDO::FETCH_OBJ);

      if($record) return $record->stevec;
      return false;
  }

  function chekcColumn($table, $column)
     {
         $stmt = $this->db->link_id->prepare("SELECT *
           FROM information_schema.COLUMNS
           WHERE
               TABLE_SCHEMA = '".DB_DATABASE."'
           AND TABLE_NAME = '".$table."'
           AND COLUMN_NAME = '".$column."'");
           $stmt->execute();
           return $stmt->fetch(PDO::FETCH_OBJ);

     }

  function getList($table, $data = [])
  {
      $search_string = (!empty($data['search'])) ? $data['search'] : '';


      $sql = (!empty($data['query'])) ? $data['query'] : "SELECT * FROM ". $table ." ";

      $sql .= " where 1 ";

      if(!empty($data['array_search']) && !array_filter($data['array_search']))
      {
        unset($data['array_search']);
      }

      if(!empty($data['array_search']))
      {
        if(isset($data['search']))
        {
          unset($data['search']);
        }

        $sql .= " and (";
        foreach ($data['array_search'] as $key => $value) 
        {
          if($key > 0)
          {
            $sql .= (!empty($data['array_search_boolean'])) ? " and " : ' or ';
          }
          

          $sql .= " ".$value." like :search".$key." ";
        }
        $sql .= " ) ";
      }

      // Old version
      // if(!empty($data['search']))
      // {
      //     $sql .= " and ".$table.".naziv like :search ";
      // }

      // New version
      if(!empty($data['search']))
      {
        if(isset($_SESSION['columns']) && $_SESSION['columns'] == "dobavitelj_users") {
          $sql .= " and ".$table.".name like :search ";
        } else {
          $sql .= " and ".$table.".naziv like :search ";
        }
      }


      if(!empty($data['where']))
      {
           $sql .= $data['where'];
      }

      if(!empty($data['groupBy']))
      {
           $sql .= " group by ".$data['groupBy']." ";
      }

      if(!empty($data['orderby']))
      {
           $sql .= $data['orderby'];
      }
      else
      {
          $check_sort = $this->chekcColumn($table, 'sort');
          if($check_sort)
          {
              $sql .= " ORDER BY sort asc ";
          }
          else
          {
               $sql .= " ORDER BY id desc ";
          }
      }

      if(!empty($data['limit']))
      {
           $sql .= ' limit '.$data['limit'];
      }
      // if(DE)
      // {
      //   echo $sql;  die();
      // }
      //return array();
      $stmt = $this->db->link_id->prepare($sql);

      if(!empty($data['array_search']))
      {
        foreach ($data['array_search'] as $key => $value) 
        {
          //if(DE) echo '@'.$value.'# ';
          $stmt->bindValue(':search'.$key, '%'.$search_string.'%', PDO::PARAM_STR);
        }
      }

      if(!empty($data['search']))
      {
          $stmt->bindValue(':search', '%'.$data['search'].'%', PDO::PARAM_STR);
      }

      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  function updateStatus($index)
  {
    $sql = "update index_status set status = 1 where naziv = :naziv  "; //echo $sql;
    $stmt = $this->db->link_id->prepare($sql);
    $stmt->bindValue(':naziv', $index, PDO::PARAM_STR);
    return $stmt->execute();
  }

}

?>
