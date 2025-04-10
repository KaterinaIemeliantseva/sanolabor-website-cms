<?php
namespace model;

class Order
{
  private $helpers;
  private $errors = array();
  private $db;
  private $cart;

  function __construct($debug)
  {
    global $db;
    $this->db = $db;

    $this->helpers = new \library\Helpers;
  }

  function receive($data)
  {
    $response = $this->authUser($data);
    if($response)
    {
      return $response;
    }

    $response['Error'] = 'false';
    $response['Message'] = $this->getItems();
    return $response;
  }

  function authUser($data)
  {
    if(!isset($data['api_user_id']) || empty($data['api_user_id']))
    {
      $response['Error'] = 'true';
      $response['ErrorMsg'] = 'api_user_id error.';
      return $response;
    }

    if(!$this->checkUser($data['api_user_id']))
    {
      $response['Error'] = 'true';
      $response['ErrorMsg'] = 'Authentication error occurred.';
      return $response;
    }

    return array();
  }

  private function checkUser($value)
  {
    $sql= "SELECT * FROM api_users WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $value, \PDO::PARAM_STR);
    $stmt->execute();
    $obj = $stmt->fetchObject();

    if($obj)
    {
      return true;
    }
    return false;
  }

  private function getItems()
  {
    $sql= "SELECT un.narocilo_array, un.cas_narocila FROM users_narocila un
          inner join users u on un.id_upo = u.userid
          where un.vidno = 1 and un.cas_narocila >= DATE_SUB(NOW(), INTERVAL 10 DAY)
          order by un.cas_narocila desc";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $obj = $stmt->fetchAll(\PDO::FETCH_OBJ);

    $customers = array();
    if($obj)
    {
      foreach ($obj as $key => $value)
      {

        $param = $this->helpers->mbUnserialize($value->narocilo_array);
        $drzava = $this->getCountry($param['dostava_drzava']);

        $customer = new \stdClass;
        $customer->VrstaPosiljke = ($drzava->posta_id == 705) ? 101 : 103;
        $customer->IdDDV = ($param['uporabnik_idddv'] != '0' && !empty($param['uporabnik_idddv'])) ? $param['uporabnik_idddv'] : '';
        //$customer->IdDDV = $param['uporabnik_idddv'];
        $customer->Naziv = $this->helpers->convertCharsBack(str_replace(':', '', $param['dostava_naziv']));
        $customer->Naslov = $this->helpers->convertCharsBack($param['dostava_naslov']);
        $customer->PostnaSt = (int)$this->helpers->convertCharsBack($param['dostava_postna_stevilka']);
        $customer->Kraj = $this->helpers->convertCharsBack($param['dostava_kraj']);
        $customer->Drzava = $drzava->name;
        $customer->DrzavaID = $drzava->posta_id;
        $customer->TelSt = $this->helpers->convertCharsBack($param['telefon']);
        $customer->ENaslov = $this->helpers->convertCharsBack($param['enaslov']);
        $customer->Opomba = $this->helpers->convertCharsBack($param['komentar']);
        $customer->Znesek = $this->helpers->convertCharsBack(str_replace('.', ',',$param['znesek_narocila']));
        $customer->Odkupnina = ($param['nacin_placila'] != '39') ? 1 : 0;


        $customers[] = $customer;
      }
      return $customers;
    }
    return false;
  }

  function getCountry($iso2)
  {
      $sql= "SELECT * from country where iso2 = :iso2";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':iso2', $iso2, \PDO::PARAM_STR);
      $stmt->execute();
      $obj = $stmt->fetch(\PDO::FETCH_OBJ);

      return ($obj) ? $obj : null;
  }

}
