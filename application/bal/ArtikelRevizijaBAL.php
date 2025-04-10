<?php
class ArtikelRevizijaBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'dobavitelj_artikel_revizija';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'dobavitelj_artikel_revizija';
    public $audit_message_single = 'dobavitelj_artikel_revizija';

    function __construct()
    {
        global $user;
        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
        $this->db = Database::obtain();
    }

    // function getList() {
    //     $sql = "SELECT 
    //         dar.id, 
    //         dar.naziv AS naziv, 
    //         dob.username as dobavitelj_username, 
    //         dob.name AS dobavitelj_naziv, 
    //         dar.updated_at as cas_spremembe
    //     FROM dobavitelj_artikel_revizija AS dar
    //     LEFT JOIN dobavitelj_users AS dob
    //     ON dar.dobavitelj_id = dob.dobavitelj_id
    //     WHERE dar.status like 'pending'";
    //     $stmt = $this->db->link_id->prepare($sql);
    //     $stmt->execute();
    //     return($stmt->fetchAll(PDO::FETCH_OBJ));
    // }

    function getList($data = [])
    {
        $data['array_search'] = ['dar.naziv', 'dob.username', 'dob.name'];

        $data['query'] = "SELECT 
            dar.id, 
            dar.naziv AS naziv, 
            dob.username as dobavitelj_username, 
            dob.name AS dobavitelj_naziv, 
            dar.updated_at as cas_spremembe
        FROM dobavitelj_artikel_revizija AS dar
        LEFT JOIN dobavitelj_users AS dob
        ON dar.dobavitelj_id = dob.dobavitelj_id";

        $where = "and dar.status LIKE 'pending'";

        if(!empty($data['where']))
        {
            $where .= $data['where'];
        }

        $data['where'] = $where;

        return parent::getList($this->table, $data);
    }



    function getArtikelRevizija(int $id) {
        $sql = "SELECT
            dob.name AS dobavitelj_naziv,
            rs.naziv AS regulatorna_skupina_naziv,
            bz.naziv AS blagovna_znamka_naziv,
            dar.*
        FROM dobavitelj_artikel_revizija AS dar 
        LEFT JOIN dobavitelj_users AS dob ON dar.dobavitelj_id = dob.dobavitelj_id
        LEFT JOIN regulatorna_skupina AS rs ON dar.regulatorna_kategorija = rs.id
        LEFT JOIN blagovna_znamka AS bz ON dar.blagovna_znamka = bz.id
        WHERE dar.id = :id";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return($stmt->fetch(PDO::FETCH_OBJ));
    }

    function getArtikelKombinacije(int $id) {
        $sql = "SELECT 
            dakr.*,
            dakr.id as sifra,
            (SELECT naziv FROM artikel_parameter WHERE id = dakr.barva_id) AS barva,
            (SELECT naziv FROM artikel_parameter WHERE id = dakr.velikost_id) AS velikost
        FROM dobavitelj_artikel_kombinacije_revizija AS dakr
        WHERE revizija_id = :id";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return($result);
    }   

    function getPhotos(int $id) {
        $sql = "SELECT * 
                FROM dobavitelj_file_revizija
                WHERE type = 1 AND revizija_id = :id
                ORDER BY `order` ASC";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }    

    function getDocuments(int $id) {
        $sql = "SELECT * 
                FROM dobavitelj_file_revizija
                WHERE type != 1 AND revizija_id = :id
                ORDER BY type ASC";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return($stmt->fetchAll(PDO::FETCH_OBJ));
    }

    function getParametersList(int $id) {
        $sql = "SELECT barva_id, velikost_id
                FROM dobavitelj_artikel_kombinacije_revizija
                WHERE revizija_id = :id";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }

    function getRegulatornaSkupina(int $id) {
        $sql = "SELECT naziv 
                FROM regulatorna_skupina
                WHERE id = (
                    SELECT regulatorna_kategorija
                    FROM dobavitelj_artikel_revizija 
                    WHERE artikel_id = :id AND status = 'approved'
                    ORDER BY id DESC 
                    LIMIT 1
                )";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }    

    function getFileType(int $type) {
        switch ($type) {
            case 17:
                return "Deklaracija";
                break;
            case 18:
                return "Navodila za uporabo";
                break;
            case 19:
                return "Izjava EU skladnosti";
                break;
            case 20:
                return "Certifikat EU skladnosti";
                break;
            case 21:
                return "Varnostni list";
                break;
            case 22:
                return "Tabela velikosti";
                break;
            default:
                return "Nekategoriziran dokument";
                break;
        }
    }

    function generateGuid() {
        if (function_exists('com_create_guid')) {
            return trim(com_create_guid(), '{}');
        } else {
            // Generate GUID manually if com_create_guid is unavailable
            $data = random_bytes(16);
            assert(strlen($data) == 16);
    
            // Set the version to 0100
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
            // Set the variant to 10xxxxxx
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }
    }

    function rejectProduct(int $id, string $comment) {
        $data = [
            'id' => $id,
            'comment' => $comment
        ];
    
        $sql = "UPDATE dobavitelj_artikel_revizija SET status = 'rejected', admin_komentar = :comment WHERE id = :id";
        $stmt = $this->db->link_id->prepare($sql);
    
        if ($stmt->execute($data)) {
            return ['success' => true, 'message' => 'Artikel je uspešno zavrjen'];
        } else {
            return ['success' => false, 'message' => 'Napaka pri zavrnitvi artikla'];
        }
    }

    function approveProduct(int $id, $parameters) {
        $revizija_data = $this->getArtikelRevizija($id);
        $dokumenti = $this->getDocuments($id);
        $slike = $this->getPhotos($id);
        $parameters = json_decode(json_encode($parameters)); // Convert to object
        
        $parameter_list = [];

        foreach($parameters as $item) {
            $parameter_list[] = $item->velikost_id;
            $parameter_list[] = $item->barva_id;
        }

        $parameter_list = array_unique($parameter_list);

        // Zapis v tabelo artikel
        $sql = "UPDATE artikel 
                SET 
                    naziv = :naziv,
                    kratki_opis = :kratki_opis,
                    vsebina = :vsebina,
                    vsebina_status = :vsebina_status,
                    navodila = :navodila,
                    navodila_status = :navodila_status,
                    opozorilo = :opozorilo,
                    opozorilo_status = :opozorilo_status, 
                    sestava  = :sestava,
                    sestava_status = :sestava_status,
                    active = 1,
                    dobavitelj_id = :dobavitelj_id,
                    tehnicna_dokumentacija = :tehnicna_dokumentacija,
                    blagovna_znamka = :blagovna_znamka,
                    regulatorna_skupina = :regulatorna_skupina,
                    novo_v_ponudbi_od_do = 1,
                    updated_at = NOW()
                WHERE id = :artikel_id";
        
        $stmt = $this->db->link_id->prepare($sql);

        $vsebina_status = 0;
        $navodila_status = 0;
        $opozorilo_status = 0;
        $sestava_status = 0;
        
        if(isset($revizija_data->vsebina) && !empty($revizija_data->vsebina)) {
            $vsebina_status = 1;
        }
        if(isset($revizija_data->navodila) && !empty($revizija_data->navodila)) {
            $navodila_status = 1;
        }
        if(isset($revizija_data->opozorila) && !empty($revizija_data->opozorila)) {
            $opozorilo_status = 1;
        }
        if(isset($revizija_data->sestavine) && !empty($revizija_data->sestavine)) {
            $sestava_status = 1;
        }


        $stmt->bindParam(':naziv', $revizija_data->naziv, PDO::PARAM_STR);
        $stmt->bindParam(':kratki_opis', $revizija_data->kratki_opis, PDO::PARAM_STR);
        $stmt->bindParam(':vsebina', $revizija_data->vsebina, PDO::PARAM_STR);
        $stmt->bindParam(':vsebina_status', $vsebina_status, PDO::PARAM_STR);
        $stmt->bindParam(':navodila', $revizija_data->navodila, PDO::PARAM_INT);
        $stmt->bindParam(':navodila_status', $navodila_status, PDO::PARAM_INT);
        $stmt->bindParam(':opozorilo', $revizija_data->opozorila, PDO::PARAM_INT);
        $stmt->bindParam(':opozorilo_status', $opozorilo_status, PDO::PARAM_INT);
        $stmt->bindParam(':sestava', $revizija_data->sestavine, PDO::PARAM_INT);
        $stmt->bindParam(':sestava_status', $sestava_status, PDO::PARAM_INT);
        $stmt->bindParam(':dobavitelj_id', $revizija_data->dobavitelj_id, PDO::PARAM_INT);
        $stmt->bindParam(':tehnicna_dokumentacija', $revizija_data->tehnicne_lastnosti, PDO::PARAM_INT);
        $stmt->bindParam(':blagovna_znamka', $revizija_data->blagovna_znamka, PDO::PARAM_INT);
        $stmt->bindParam(':artikel_id', $revizija_data->artikel_id, PDO::PARAM_INT);
        $stmt->bindParam(':regulatorna_skupina', $revizija_data->regulatorna_kategorija, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'Napaka pri potrditvi artikla'];
        }

        // Posodobitev statusa
        $sql = "UPDATE dobavitelj_artikel_revizija SET status = 'approved', admin_komentar = '', updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'Napaka pri potrditvi artikla'];
        }

        $documents_types = [];

        foreach ($dokumenti as $dokument) {
            if (!in_array($dokument->type, $documents_types)) {
                $documents_types[] = $dokument->type;
            }
        }
        $documents_types[] = 1;

        // Brisanje obstoječih datotek
        if($documents_types)
        {
            $sql = "DELETE FROM files WHERE item_id = :artikel_id AND (type IN (".implode(',', $documents_types)."))";
            $stmt = $this->db->link_id->prepare($sql);
            $stmt->bindParam(':artikel_id', $revizija_data->artikel_id, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                return ['success' => false, 'message' => 'Napaka pri potrditvi artikla'];
            }
        }
        // Vstavljanje dokumentov 
        foreach ($dokumenti as $dokument) {
            $guid = $this->generateGuid();
            $sql = "INSERT INTO files (type, item_id, path, guid, updated_at) VALUES (:file_type, :artikel_id, :path, :guid, NOW())";
            $stmt = $this->db->link_id->prepare($sql);
            $stmt->bindParam(':file_type', $dokument->type, PDO::PARAM_INT);
            $stmt->bindParam(':artikel_id', $revizija_data->artikel_id, PDO::PARAM_INT);
            $stmt->bindParam(':path', $dokument->path, PDO::PARAM_STR);
            $stmt->bindParam(':guid', $guid , PDO::PARAM_STR);

            if (!$stmt->execute()) {
                return ['success' => false, 'message' => 'Napaka pri potrditvi artikla'];
            }
        }

        // Vstavljanje slik
        foreach ($slike as $slika) {
            $guid = $this->generateGuid();
            $sql = "INSERT INTO files (type, item_id, path, guid, sort, updated_at) VALUES (1, :artikel_id, :path, :guid, :sort, NOW())";
            $stmt = $this->db->link_id->prepare($sql);
            $stmt->bindParam(':artikel_id', $revizija_data->artikel_id, PDO::PARAM_INT);
            $stmt->bindParam(':path', $slika->path, PDO::PARAM_STR);
            $stmt->bindParam(':guid', $guid , PDO::PARAM_STR);
            $stmt->bindParam(':sort', $slika->order, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                return ['success' => false, 'message' => 'Napaka pri potrditvi artikla'];
            }
        }

        // Brisanje artikel-sifra
        $sql = "DELETE FROM artikel_sifra  WHERE artikel_id = :artikel_id";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':artikel_id', $revizija_data->artikel_id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'Napaka pri potrditvi artikla'];
        }

        // Vstavljanje artikel-sifra
        if($parameters) {
            foreach ($parameters as $parameter) {
                $sql = "INSERT INTO artikel_sifra (artikel_id, sifra, ean, updated_at) VALUES (:artikel_id, :sifra, :ean, NOW())";
                $stmt = $this->db->link_id->prepare($sql);

                
                if (isset($parameter->sifra) && is_numeric($parameter->sifra)) {
                    $sifraValue = $parameter->sifra;
                } else {
                    $sifraValue = "";
                }
                $stmt->bindParam(':sifra', $sifraValue, PDO::PARAM_STR);
                $stmt->bindParam(':artikel_id', $revizija_data->artikel_id, PDO::PARAM_INT);
                $stmt->bindParam(':ean', $parameter->ean, PDO::PARAM_STR);
    
                if (!$stmt->execute()) {
                    return ['success' => false, 'message' => 'Napaka pri potrditvi artikla'];
                }

                // Pridobi sifra_id
                $sql = "SELECT id FROM artikel_sifra WHERE artikel_id = :artikel_id ORDER BY id DESC LIMIT 1";
                $stmt = $this->db->link_id->prepare($sql);
                $stmt->bindParam(':artikel_id', $revizija_data->artikel_id, PDO::PARAM_INT);
                $stmt->execute();

                $parameter->sifra_id = $stmt->fetchColumn();
            }
        }

        // Brisanje artikel_sifra_parameter
        $sql = "DELETE FROM artikel_sifra_parameter  WHERE artikel_id = :artikel_id";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':artikel_id', $revizija_data->artikel_id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'Napaka pri potrditvi artikla'];
        }

        // Vstavljanje artikel_sifra_parameter
        if ($parameter_list) {
            foreach ($parameter_list as $parameter) {
                // Parameter barva
                if($parameter != 0) {
                    $sql = "INSERT INTO artikel_sifra_parameter (parameter_id, artikel_id, created_at) 
                            VALUES (:parameter_id, :artikel_id, NOW())";
                    $stmt = $this->db->link_id->prepare($sql);
                    $stmt->bindParam(':parameter_id', $parameter, PDO::PARAM_INT);
                    $stmt->bindParam(':artikel_id', $revizija_data->artikel_id, PDO::PARAM_INT);
        
                    if (!$stmt->execute()) {
                        return ['success' => false, 'message' => 'Napaka pri vstavljanju barva_id parametra za artikel'];
                    }
                }
            }
        }

        // Brisanje sifra_parameter
        $sql = "DELETE FROM sifra_parameter  WHERE artikel_id = :artikel_id";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':artikel_id', $revizija_data->artikel_id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'Napaka pri potrditvi artikla'];
        }

        // Vstavljanje v sifra_parameter
        foreach($parameters as $parameter) {
            if(isset($parameter->barva_id) && $parameter->barva_id) {
                $sql = "INSERT INTO sifra_parameter (sifra_id, parameter_id, artikel_id, created_at) VALUES (:sifra_id,:parameter_id, :artikel_id, NOW())";
                $stmt = $this->db->link_id->prepare($sql);
                $stmt->bindParam(':sifra_id', $parameter->sifra_id, PDO::PARAM_INT);
                $stmt->bindParam(':parameter_id', $parameter->barva_id, PDO::PARAM_INT);
                $stmt->bindParam(':artikel_id', $revizija_data->artikel_id, PDO::PARAM_INT);
                $stmt->execute();
            }
            if(isset($parameter->velikost_id) && $parameter->velikost_id) {
                $sql = "INSERT INTO sifra_parameter (sifra_id, parameter_id, artikel_id, created_at) VALUES (:sifra_id, :parameter_id, :artikel_id, NOW())";
                $stmt = $this->db->link_id->prepare($sql);
                $stmt->bindParam(':sifra_id', $parameter->sifra_id, PDO::PARAM_INT);
                $stmt->bindParam(':parameter_id', $parameter->velikost_id, PDO::PARAM_INT);
                $stmt->bindParam(':artikel_id', $revizija_data->artikel_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
        return ['success' => true, 'message' => 'Artikel je uspešno potrjen'];
    }    
}