<?php
class AdministracijaDAL
{
	private $db;
    public $table;

	function __construct()
	{
    	$this->db = Database::obtain();
        $this->table = 'cms_user';
	}

	public function getList()
	{
	    $sql = "SELECT id, ime_priimek, username, email, status FROM cms_user ";
		$sql .= " ORDER BY ime_priimek asc ";

        $stmt = $this->db->link_id->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getKategorijeRazvrsti($parent)
	{
        $sql = "SELECT * FROM cms_menu where parent = :parent and status = 1 ORDER BY naslov; "; //echo $sql;
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindValue(':parent', $parent, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function izbrisiUporabnikDostop($id)
	{
		$sql="DELETE FROM cms_user_dostop WHERE id_item = :id ";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
	}

	function getuporabnikDostopMenu($kat_id, $art_id)
	{
		$sql ="SELECT * FROM cms_user_dostop where id_kategorija= :kat_id and id_item = :art_id ";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindValue(':kat_id', $kat_id, PDO::PARAM_INT);
        $stmt->bindValue(':art_id', $art_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
	}

}
