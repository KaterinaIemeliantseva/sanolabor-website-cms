<?php
class VsebinaDAL
{
  var $db;

  function __construct()
  {
    $this->db = Database::obtain();
    $this->table = 'vsebina';

  }

  /**************************************************************************/
  public function getList($data = [])
  {
      $sql = "SELECT vsebina.id, vsebina.naziv, vsebina.parent,
              vsebina.status, vsebina.sort, vsebina.nicename
              , if(parent.naziv is not null, parent.naziv, '-') as '_parent'
              FROM ". $this->table ."

      left join vsebina as parent on parent.id = vsebina.parent
      where 1 and vsebina.parent > 0 ";
      if(!empty($data['search']))
      {
          $sql .= " and vsebina.naziv like :search ";
      }

      if(!empty($data['parent']))
      {
          $sql .= " and vsebina.parent = :parent ";
      }

      $sql .= " ORDER BY parent, sort asc ";

      //echo $sql;

      //return array();
      $stmt = $this->db->link_id->prepare($sql);

      if(!empty($data['search']))
      {
          $stmt->bindValue(':search', '%'.$data['search'].'%', PDO::PARAM_STR);
      }

      if(!empty($data['parent']))
      {
          $stmt->bindValue(':parent', $data['parent'], PDO::PARAM_INT);
      }

      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);


//       select  *
// from    (select * from vsebina
//          order by parent, id) vsebina_sorted,
//         (select @pv := '1') initialisation
// where   find_in_set(parent, @pv) > 0
// and     @pv := concat(@pv, ',', id)
  }

  /**************************************************************************/

}

?>
