<?php
class ArtikelKategorijaBAL extends BaseBAL
{
	public $dal;
    public $handler;
    public $table = 'artikel_kategorija';
    var $user;
    public $response_select = [];

    public $audit = false; 
    public $audit_message_list = 'Artikel kategorije'; 
    public $audit_message_single = 'Artikel kategorija'; 

    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
        $this->db = Database::obtain();
    }

    /**************************************************************************/
	function getList($data = [])
	{
		$data['array_search'] = ['artikel_kategorija.naziv'];
        $data['array_search'] = [];
		
		$data['query'] = "SELECT artikel_kategorija.id, artikel_kategorija.naziv, artikel_kategorija.parent, artikel_kategorija.id_old,
                artikel_kategorija.status, artikel_kategorija.menu, artikel_kategorija.filter, artikel_kategorija.sort, artikel_kategorija.nicename
                , if(parent.naziv is not null, parent.naziv, '-') as '_parent', artikel_kategorija.izpostavljeno
                FROM ". $this->table ."
            left join artikel_kategorija as parent on parent.id = artikel_kategorija.parent
         ";

		
        if(!empty($data['parent0']))
        {
            $sql .= " and artikel_kategorija.parent = 1 ";
		}

		$data['groupBy'] = ' artikel_kategorija.id ';
        $data['orderby'] = ' order by parent, sort asc ';

        // if(DE)
        // {
        //     print_r($data);
        // }

		return parent::getList($this->table,$data);
    }
    
    function update($data)
    {
        
        $this->handler->apiCallAsync(MAIN_URL.'/api/update-cache', ['auth' => $_SESSION['auth_key'] , 'type' => 'artikel_kategorija', 'nosignal' => true]);

        return parent::update($this->table, $data);
    }


    /**************************************************************************/


    function getKategorijeSelect2($search)
    {
        $this->response_select = [];
        $this->prepareCategoriesSelect(0, '');
        $tags = $this->response_select;

        if(!empty($search))
        {
            $tags = parent::select2Search($tags, $search);
        }

        //$tags =parent::getList($this->table, ['search' => $search, 'parent0' => true]);
		return parent::getKategorijeSelect($tags);
    }


    function prepareCategoriesSelect($parent, $title)
    {
        //$rows = parent::getList($this->table, ['where' => ' and vsebina.parent = '. $parent]);
        $input = [];
        //$input['search'] = $search;
        // if(!$parent)
        // {
        //     $input['parent0'] = true;
        // }
        // else
        // {
            $input['where'] = ' and parent = '. $parent;
        // }

        $rows =parent::getList($this->table, $input);
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

                $children = parent::getList($this->table, ['where' => ' and parent = '. $parent]);

                if($children)
                {
                    $this->prepareCategoriesSelect($value['id'], $item['naziv']);
                }
            }
        }
    }

    function getCategoryCardsList($category_id) {
        $sql = "SELECT * FROM promocija_ploscica WHERE  kategorija_TK = :category_id";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return($result);
    }

    function getCategorysList() {
        $sql = "SELECT * FROM artikel_kategorija WHERE status = 1 ORDER BY naziv ASC";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return($result);
    }

    function sanitizeNicename($string) {
        // Replace special characters
        $string = strtr($string, [
            'š' => 's', 'Š' => 's',
            'č' => 'c', 'Č' => 'c',
            'ž' => 'z', 'Ž' => 'z',
            'đ' => 'd', 'Đ' => 'd',
            'ć' => 'c', 'Ć' => 'c'
        ]);
        // Replace spaces with '-' and convert to lowercase
        $string = mb_strtolower($string, 'UTF-8');
        $string = str_replace(' ', '-', $string);
    
        return $string;
    }

    function createCategoryCard($data) {

        $sql = "SELECT nicename FROM artikel_kategorija WHERE id = :id";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $nicename = $result['nicename'] . '/' . $this->sanitizeNicename($data['naziv']);
            $nicename_exists = true;
            $counter = 1;

            while ($nicename_exists) {
                $sql = "SELECT COUNT(*) FROM promocija_ploscica WHERE nicename = :nicename";
                $stmt = $this->db->link_id->prepare($sql);
                $stmt->bindParam(':nicename', $nicename, PDO::PARAM_STR);
                $stmt->execute();
                $rowCount = $stmt->fetchColumn();

                if ($rowCount > 0) {
                    $nicename = $result['nicename'] . '/' . $this->sanitizeNicename($data['naziv']) . '-' . $counter;
                    $counter++;
                } else {
                    $nicename_exists = false;
                }
            }
        }

        $sql = "INSERT INTO promocija_ploscica (naziv, nicename, path, tip, `order`, vsebina, kategorija_id, kategorija_TK)
                VALUES (:naziv, :nicename, :path, 1, :order, :vsebina, :kategorija_id, :kategorija_TK)";

        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':naziv', $data['naziv'], PDO::PARAM_STR);
        $stmt->bindParam(':nicename', $nicename, PDO::PARAM_STR);
        $stmt->bindParam(':path', $data['path'], PDO::PARAM_STR);
        $stmt->bindParam(':order', $data['order'], PDO::PARAM_INT);
        $stmt->bindParam(':vsebina', $data['vsebina'], PDO::PARAM_STR);
        $stmt->bindParam(':kategorija_id', $data['kategorija_id'], PDO::PARAM_INT);
        $stmt->bindParam(':kategorija_TK', $data['id'], PDO::PARAM_INT);
        

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Ploščica je uspešno ustvarjena'];
        }else{
            return ['success' => false, 'message' => 'Napaka pri ustvarjanju nove ploščice'];
        }
    }

    function editCategoryCard($data) {

        $sql = "SELECT nicename FROM artikel_kategorija WHERE id = :id";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {

            $nicename = $result['nicename'] . '/' . $this->sanitizeNicename($data['naziv']);
            $nicename_exists = true;
            $counter = 1;

            while ($nicename_exists) {
                $sql = "SELECT COUNT(*) FROM promocija_ploscica WHERE nicename = :nicename";
                $stmt = $this->db->link_id->prepare($sql);
                $stmt->bindParam(':nicename', $nicename, PDO::PARAM_STR);
                $stmt->execute();
                $rowCount = $stmt->fetchColumn();

                if ($rowCount > 0) {
                    $nicename = $result['nicename'] . '/' . $this->sanitizeNicename($data['naziv']) . '-' . $counter;
                    $counter++;
                } else {
                    $nicename_exists = false;
                }
            }
        }
    
        $sql = "UPDATE promocija_ploscica 
                SET naziv = :naziv, 
                    nicename = :nicename, 
                    path = :path,
                    `order` = :order,
                    vsebina = :vsebina,
                    kategorija_id = :kategorija_id, 
                    kategorija_TK = :kategorija_TK
                WHERE id = :id";
    
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':naziv', $data['naziv'], PDO::PARAM_STR);
        $stmt->bindParam(':nicename', $nicename, PDO::PARAM_STR);
        $stmt->bindParam(':path', $data['path'], PDO::PARAM_STR);
        $stmt->bindParam(':order', $data['order'], PDO::PARAM_INT);
        $stmt->bindParam(':vsebina', $data['vsebina'], PDO::PARAM_STR);
        $stmt->bindParam(':kategorija_id', $data['kategorija_id'], PDO::PARAM_INT);
        $stmt->bindParam(':kategorija_TK', $data['id'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $data['card_id'], PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Ploščica je uspešno posodobljena'];
        } else {
            return ['success' => false, 'message' => 'Napaka pri posodabljanju ploščice'];
        }
    }
    

    function getCard($data) {
        $sql = "SELECT * FROM promocija_ploscica WHERE id = :id";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if($result->tip == 2){
                $sql = "SELECT * FROM artikel_povezani WHERE id_item = :id AND tip = 13";
                $stmt = $this->db->link_id->prepare($sql);
                $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
                $stmt->execute();
                $result->artikli = $stmt->fetch(PDO::FETCH_OBJ);
        }

        return($result);
    }

    function deleteCategoryCard($data) {    

        $sql = "DELETE FROM promocija_ploscica WHERE id = :id";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':id', $data['card_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Ploščica je uspešno odstranjena'];
        } else {
            return ['success' => false, 'message' => 'Napaka pri odstranjevanju ploščice'];
        }
    }





    // custom ploščice
    public function getModulesAndTilesByCategory($categoryId)
    {
        // Fetch modules for the category
        $sqlModules = "SELECT * FROM promocija_modul_iskanje WHERE kategorija_TK = :categoryId ORDER BY `order` ASC";
        $stmtModules = $this->db->link_id->prepare($sqlModules);
        $stmtModules->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmtModules->execute();
        $modules = $stmtModules->fetchAll(PDO::FETCH_ASSOC);

        // For each module, fetch its custom tiles
        foreach ($modules as &$module) {
            $moduleId = $module['id'];
            $sqlTiles = "SELECT * FROM promocija_ploscica WHERE promocija_modul_iskanje_TK = :moduleId ORDER BY `order` ASC";
            $stmtTiles = $this->db->link_id->prepare($sqlTiles);
            $stmtTiles->bindParam(':moduleId', $moduleId, PDO::PARAM_INT);
            $stmtTiles->execute();
            $tiles = $stmtTiles->fetchAll(PDO::FETCH_ASSOC);
            $module['tiles'] = $tiles;
        }

        return $modules;
    }

    public function createModule($moduleTitle, $categoryId, $order = 0)
    {
        $sql = "INSERT INTO promocija_modul_iskanje (naziv, kategorija_TK, `order`)
            VALUES (:moduleTitle, :categoryId, :order)";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':moduleTitle', $moduleTitle, PDO::PARAM_STR);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':order', $order, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Return the ID of the newly created module
            return $this->db->link_id->lastInsertId();
        } else {
            return false;
        }
    }

    public function getModuleById($moduleId)
    {
        $sql = "SELECT * FROM promocija_modul_iskanje WHERE id = :moduleId";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':moduleId', $moduleId, PDO::PARAM_INT);
        $stmt->execute();
        $module = $stmt->fetch(PDO::FETCH_ASSOC);
        return $module;
    }

    public function updateModule($moduleId, $moduleTitle, $order)
    {
        $sql = "UPDATE promocija_modul_iskanje SET naziv = :moduleTitle, `order` = :order WHERE id = :moduleId";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':moduleTitle', $moduleTitle, PDO::PARAM_STR);
        $stmt->bindParam(':order', $order, PDO::PARAM_INT);
        $stmt->bindParam(':moduleId', $moduleId, PDO::PARAM_INT);

        return $stmt->execute();
    }


    public function deleteModule($moduleId)
    {
        // Begin transaction
        $this->db->link_id->beginTransaction();

        try {
            // Delete associated tiles
            $sqlTiles = "DELETE FROM promocija_ploscica WHERE promocija_modul_iskanje_TK = :moduleId";
            $stmtTiles = $this->db->link_id->prepare($sqlTiles);
            $stmtTiles->bindParam(':moduleId', $moduleId, PDO::PARAM_INT);
            $stmtTiles->execute();

            // Delete the module
            $sqlModule = "DELETE FROM promocija_modul_iskanje WHERE id = :moduleId";
            $stmtModule = $this->db->link_id->prepare($sqlModule);
            $stmtModule->bindParam(':moduleId', $moduleId, PDO::PARAM_INT);
            $stmtModule->execute();

            // Commit transaction
            $this->db->link_id->commit();
            return true;
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->link_id->rollBack();
            return false;
        }
    }

    public function createTile($data) {
        $nicename = $this->generateNicename($data['moduleId'], $data['naziv']);

        // Start transaction
        $this->db->link_id->beginTransaction();

        try {
            $sql = "INSERT INTO promocija_ploscica (naziv, nicename, vsebina, path, promocija_modul_iskanje_TK, tip, `order`)
            VALUES (:naziv, :nicename, :vsebina, :path, :moduleId, :tip, :order)";
            $stmt = $this->db->link_id->prepare($sql);
            $stmt->bindParam(':naziv', $data['naziv'], PDO::PARAM_STR);
            $stmt->bindParam(':nicename', $nicename, PDO::PARAM_STR);
            $stmt->bindParam(':vsebina', $data['vsebina'], PDO::PARAM_STR);
            $stmt->bindParam(':path', $data['path'], PDO::PARAM_STR);
            $stmt->bindParam(':moduleId', $data['moduleId'], PDO::PARAM_INT);
            $tip = 2; // Custom tiles
            $stmt->bindParam(':tip', $tip, PDO::PARAM_INT);
            $stmt->bindParam(':order', $data['order'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                $tileId = $this->db->link_id->lastInsertId();

                // Save associated articles
                if (isset($data['selectedArticles']) && !empty($data['selectedArticles'])) {
                    $this->saveTileArticles($tileId, $data['selectedArticles']);
                }

                // Commit transaction
                $this->db->link_id->commit();

                return ['success' => true, 'tile' => $this->getTileById($tileId)];
            } else {
                $this->db->link_id->rollBack();
                return ['success' => false, 'message' => 'Error creating tile'];
            }
        } catch (Exception $e) {
            $this->db->link_id->rollBack();
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
    }





    public function getTileById($tileId) {
        $sql = "SELECT * FROM promocija_ploscica WHERE id = :tileId";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':tileId', $tileId, PDO::PARAM_INT);
        $stmt->execute();
        $tile = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($tile) {
            // Get associated articles
            $sqlArticles = "SELECT a.id, a.naziv 
                        FROM artikel_povezani ap
                        JOIN artikel a ON ap.id_artikel = a.id
                        WHERE ap.id_item = :tileId AND ap.tip = 13";
            $stmtArticles = $this->db->link_id->prepare($sqlArticles);
            $stmtArticles->bindParam(':tileId', $tileId, PDO::PARAM_INT);
            $stmtArticles->execute();
            $articles = $stmtArticles->fetchAll(PDO::FETCH_ASSOC);

            $tile['selectedArticles'] = $articles;
        }

        return $tile;
    }

    private function saveTileArticles($tileId, $articleIds) {
        // First, delete existing associations for this tile
        $sqlDelete = "DELETE FROM artikel_povezani WHERE id_item = :tileId AND tip = 13";
        $stmtDelete = $this->db->link_id->prepare($sqlDelete);
        $stmtDelete->bindParam(':tileId', $tileId, PDO::PARAM_INT);
        $stmtDelete->execute();

        // Check if there are articles to insert
        if (!empty($articleIds) && is_array($articleIds)) {
            // Now, insert new associations
            $sqlInsert = "INSERT INTO artikel_povezani (id_item, id_artikel, tip) VALUES (:tileId, :articleId, 13)";
            $stmtInsert = $this->db->link_id->prepare($sqlInsert);

            foreach ($articleIds as $articleId) {
                $stmtInsert->bindParam(':tileId', $tileId, PDO::PARAM_INT);
                $stmtInsert->bindParam(':articleId', $articleId, PDO::PARAM_INT);
                $stmtInsert->execute();
            }
        }
    }




    public function updateTile($data) {
        $nicename = $this->generateNicename($data['moduleId'], $data['naziv'], $data['tileId']);

        // Start transaction
        $this->db->link_id->beginTransaction();

        try {
            $sql = "UPDATE promocija_ploscica 
                SET naziv = :naziv, nicename = :nicename, vsebina = :vsebina, path = :path, promocija_modul_iskanje_TK = :moduleId, `order` = :order
                WHERE id = :tileId";
            $stmt = $this->db->link_id->prepare($sql);
            $stmt->bindParam(':naziv', $data['naziv'], PDO::PARAM_STR);
            $stmt->bindParam(':nicename', $nicename, PDO::PARAM_STR);
            $stmt->bindParam(':vsebina', $data['vsebina'], PDO::PARAM_STR);
            $stmt->bindParam(':path', $data['path'], PDO::PARAM_STR);
            $stmt->bindParam(':moduleId', $data['moduleId'], PDO::PARAM_INT);
            $stmt->bindParam(':tileId', $data['tileId'], PDO::PARAM_INT);
            $stmt->bindParam(':order', $data['order'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Update associated articles
                if (isset($data['selectedArticles'])) {
                    $this->saveTileArticles($data['tileId'], $data['selectedArticles']);
                }

                // Commit transaction
                $this->db->link_id->commit();

                return ['success' => true, 'tile' => $this->getTileById($data['tileId'])];
            } else {
                $this->db->link_id->rollBack();
                return ['success' => false, 'message' => 'Error updating tile'];
            }
        } catch (Exception $e) {
            $this->db->link_id->rollBack();
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
    }



    public function deleteTile($tileId) {
        $sql = "DELETE FROM promocija_ploscica WHERE id = :tileId";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':tileId', $tileId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    private function generateNicename($moduleId, $naziv, $tileId = null) {
        // Retrieve the category's nicename based on the module ID
        $sqlCategory = "SELECT k.nicename AS category_nicename
                    FROM promocija_modul_iskanje m
                    JOIN artikel_kategorija k ON m.kategorija_TK = k.id
                    WHERE m.id = :moduleId";
        $stmtCategory = $this->db->link_id->prepare($sqlCategory);
        $stmtCategory->bindParam(':moduleId', $moduleId, PDO::PARAM_INT);
        $stmtCategory->execute();
        $result = $stmtCategory->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new Exception("Module or Category not found for module ID: $moduleId");
        }
        $categoryNicename = $result['category_nicename'];

        // Retrieve the module's naziv and generate nicename
        $sqlModule = "SELECT naziv FROM promocija_modul_iskanje WHERE id = :moduleId";
        $stmtModule = $this->db->link_id->prepare($sqlModule);
        $stmtModule->bindParam(':moduleId', $moduleId, PDO::PARAM_INT);
        $stmtModule->execute();
        $module = $stmtModule->fetch(PDO::FETCH_ASSOC);

        if (!$module) {
            throw new Exception("Module not found for module ID: $moduleId");
        }

        $moduleNicename = $this->sanitizeNicename($module['naziv']);

        // Generate tile nicename from $naziv
        $tileNicename = $this->sanitizeNicename($naziv);

        // Concatenate all nicenames
        $nicename = $categoryNicename . '-' . $moduleNicename . '-' . $tileNicename;

        return $nicename;
    }

    public function saveIzbranoZaVas($categoryId, $selectedArticles) {
        try {
            // Start transaction
            $this->db->link_id->beginTransaction();

            $tip = 14;

            // Delete existing relationships for this category and tip
            $sqlDelete = "DELETE FROM artikel_povezani WHERE id_item = :categoryId AND tip = :tip";
            $stmtDelete = $this->db->link_id->prepare($sqlDelete);
            $stmtDelete->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
            $stmtDelete->bindParam(':tip', $tip, PDO::PARAM_INT);
            $stmtDelete->execute();

            // Insert new relationships
            $sqlInsert = "INSERT INTO artikel_povezani (id_artikel, id_item, tip) VALUES (:id_artikel, :id_item, :tip)";
            $stmtInsert = $this->db->link_id->prepare($sqlInsert);
            foreach ($selectedArticles as $articleId) {
                $stmtInsert->bindParam(':id_artikel', $articleId, PDO::PARAM_INT);
                $stmtInsert->bindParam(':id_item', $categoryId, PDO::PARAM_INT);
                $stmtInsert->bindParam(':tip', $tip, PDO::PARAM_INT);
                $stmtInsert->execute();
            }

            // Commit transaction
            $this->db->link_id->commit();

            return ['success' => true];
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->link_id->rollBack();
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
    }

    public function getBannersByCategory($categoryId) {
        $sql = "SELECT * FROM promocija_banner WHERE kategorija_TK = :categoryId ORDER BY `order` ASC";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        $banners = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $banners;
    }

    public function getBannerById($bannerId) {
        $sql = "SELECT * FROM promocija_banner WHERE id = :bannerId";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->bindParam(':bannerId', $bannerId, PDO::PARAM_INT);
        $stmt->execute();
        $banner = $stmt->fetch(PDO::FETCH_ASSOC);
        return $banner;
    }

    public function createBanner($data) {
        try {
            // Start transaction
            $this->db->link_id->beginTransaction();

            // Check if a Tip 2 banner already exists for this category
            // if ($data['tip'] == 2) {
            //     $sqlCheck = "SELECT COUNT(*) FROM promocija_banner WHERE kategorija_TK = :categoryId AND tip = 2";
            //     $stmtCheck = $this->db->link_id->prepare($sqlCheck);
            //     $stmtCheck->bindParam(':categoryId', $data['categoryId'], PDO::PARAM_INT);
            //     $stmtCheck->execute();
            //     $count = $stmtCheck->fetchColumn();
            //     if ($count > 0) {
            //         $this->db->link_id->rollBack();
            //         return ['success' => false, 'message' => 'Za to kategorijo že obstaja manjši banner.'];
            //     }
            // }

            if(!empty($data['datum_od']) && !empty($data['datum_do']) && $data['datum_od'] > $data['datum_do']) {
                $data['datum_od'] = date('Y-m-d H:i:s', strtotime($data['datum_od']));
                $data['datum_do'] = date('Y-m-d H:i:s', strtotime($data['datum_do']));
            } else {
                $data['datum_od'] = null;
                $data['datum_do'] = null;
            }

            $sql = "INSERT INTO promocija_banner (kategorija_TK, tip, `order`, path, datum_od, datum_do, status_od_do)
                    VALUES (:categoryId, :tip, :order, :path, :datum_od, :datum_do, :status_od_do)";
            $stmt = $this->db->link_id->prepare($sql);
            $stmt->bindParam(':categoryId', $data['categoryId'], PDO::PARAM_INT);
            $stmt->bindParam(':tip', $data['tip'], PDO::PARAM_INT);
            $stmt->bindParam(':order', $data['order'], PDO::PARAM_INT);
            $stmt->bindParam(':path', $data['path'], PDO::PARAM_STR);
            $stmt->bindParam(':datum_od', $data['datum_od'], PDO::PARAM_STR);
            $stmt->bindParam(':datum_do', $data['datum_do'], PDO::PARAM_STR);
            $stmt->bindParam(':status_od_do', $data['status_od_do'], PDO::PARAM_INT);
            $stmt->execute();

            $bannerId = $this->db->link_id->lastInsertId();
            // Commit transaction
            $this->db->link_id->commit();
            return ['success' => true, 'bannerId' => $bannerId];
        } catch (Exception $e) {
            $this->db->link_id->rollBack();
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
    }

    public function updateBanner($data) {
        try {
            // Start transaction
            $this->db->link_id->beginTransaction();

            // Check if changing to Tip 2 and another Tip 2 banner exists
            // if ($data['tip'] == 2) {
            //     $sqlCheck = "SELECT COUNT(*) FROM promocija_banner WHERE kategorija_TK = :categoryId AND tip = 2 AND id != :bannerId";
            //     $stmtCheck = $this->db->link_id->prepare($sqlCheck);
            //     $stmtCheck->bindParam(':categoryId', $data['categoryId'], PDO::PARAM_INT);
            //     $stmtCheck->bindParam(':bannerId', $data['bannerId'], PDO::PARAM_INT);
            //     $stmtCheck->execute();
            //     $count = $stmtCheck->fetchColumn();
            //     if ($count > 0) {
            //         $this->db->link_id->rollBack();
            //         return ['success' => false, 'message' => 'Za to kategorijo že obstaja manjši banner.'];
            //     }
            // }

            if(!empty($data['datum_od']) && !empty($data['datum_do']) && $data['datum_od'] < $data['datum_do']) {
                $data['datum_od'] = date('Y-m-d H:i:s', strtotime($data['datum_od']));
                $data['datum_do'] = date('Y-m-d H:i:s', strtotime($data['datum_do']));
            } else {
                $data['datum_od'] = null;
                $data['datum_do'] = null;
            }

            $sql = "UPDATE promocija_banner SET tip = :tip, `order` = :order, path = :path, datum_od = :datum_od, datum_do = :datum_do, status_od_do = :status_od_do WHERE id = :bannerId";
            $stmt = $this->db->link_id->prepare($sql);
            $stmt->bindParam(':tip', $data['tip'], PDO::PARAM_INT);
            $stmt->bindParam(':order', $data['order'], PDO::PARAM_INT);
            $stmt->bindParam(':path', $data['path'], PDO::PARAM_STR);
            $stmt->bindParam(':bannerId', $data['bannerId'], PDO::PARAM_INT);
            $stmt->bindParam(':datum_od', $data['datum_od'], PDO::PARAM_STR);
            $stmt->bindParam(':datum_do', $data['datum_do'], PDO::PARAM_STR);
            $stmt->bindParam(':status_od_do', $data['status_od_do'], PDO::PARAM_INT);
            $stmt->execute();

            // Commit transaction
            $this->db->link_id->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->db->link_id->rollBack();
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
    }

    public function deleteBanner($bannerId) {
        try {
            $sql = "DELETE FROM promocija_banner WHERE id = :bannerId";
            $stmt = $this->db->link_id->prepare($sql);
            $stmt->bindParam(':bannerId', $bannerId, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
    }
    
    public function getMaxCategoryId() {
        $sql = "SELECT MAX(id) AS max_id FROM artikel_kategorija";
        $stmt = $this->db->link_id->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['max_id'];
    }  

    //
}
