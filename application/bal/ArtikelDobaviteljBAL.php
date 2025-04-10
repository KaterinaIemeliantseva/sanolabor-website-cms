<?php
class ArtikelDobaviteljBAL extends BaseBAL
{
    public $dal;
    public $bal;
    public $handler;
    public $table = 'artikel_predogled';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'artikel_dobavitelj';
    public $audit_message_single = 'artikel_dobavitelj';

    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
    }
    /**************************************************************************/

    // Old version
    // function getList($data = [])
    // {
    //     $data['array_search'] = ['p1.podatki_json', 'dobavitelj.naziv', 'dobavitelj.username'];

    //     $data['query'] = "SELECT p1.id, p1.artikel_id, p1.podatki_json, dobavitelj.naziv as dobavitelj_naziv, dobavitelj.username as dobavitelj_username
    //     ,  p1.podatki_json->>'$.naziv' AS naziv, p1.cas_spremembe as cas_spremembe
    //     FROM artikel_predogled p1 
    //     LEFT JOIN artikel_predogled p2
    //     ON (p1.artikel_id = p2.artikel_id AND p1.id < p2.id)
    //     inner join dobavitelj on dobavitelj.id = p1.dobavitelj_id
    //     inner join artikel on artikel.id = p1.artikel_id
    //     ";
   
    //     $data['where'] = " and p2.id IS NULL and p1.aktivno = 1   and p1.podatki_json is not null ";
    //     $data['orderby'] = ' ORDER BY p1.cas_spremembe asc ';
    //     //$data['groupBy'] = ' artikel.id ';
  
    //     return parent::getList($this->table, $data);
    // }

    // New version
    function getList($data = [])
    {
        $data['array_search'] = ['p1.podatki_json', 'dobavitelj_users.name', 'dobavitelj_users.username'];

        $data['query'] = "SELECT p1.id, p1.artikel_id, p1.podatki_json, dobavitelj_users.name as dobavitelj_naziv, dobavitelj_users.username as dobavitelj_username
        ,  p1.podatki_json->>'$.naziv' AS naziv, p1.cas_spremembe as cas_spremembe
        FROM artikel_predogled p1 
        LEFT JOIN artikel_predogled p2
        ON (p1.artikel_id = p2.artikel_id AND p1.id < p2.id)
        inner join dobavitelj_users on dobavitelj_users.dobavitelj_id = p1.dobavitelj_id
        inner join artikel on artikel.id = p1.artikel_id
        ";
   
        $data['where'] = " and p2.id IS NULL and p1.aktivno = 1   and p1.podatki_json is not null ";
        $data['orderby'] = ' ORDER BY p1.cas_spremembe asc ';
        //$data['groupBy'] = ' artikel.id ';
  
        return parent::getList($this->table, $data);
    }

    function getArtikel($id)
    {
        return parent::getSingle('artikel', $id);
    }

    function getBZ($id)
    {
        return parent::getSingle('blagovna_znamka', $id);
    }

    function getParameter($id)
    {
        return parent::getSingle('artikel_parameter', $id);
    }

    function getArtikelSifra($id)
    {
        return parent::getList('artikel_sifra', ['where' => ' and artikel_id = '.$id.' ']);
    }

    function getKategorija($id)
    {
        return parent::getArtikelKategorijaa($id);
    }

    function getKategorijaNaziv($array)
    {
        return parent::getArtikelKategorijaNaziv($array);
    }

    function updateItemCode($data)
    {
        // require_once (ROOT . DS . 'application' . DS . 'bal' . DS . 'ArtikelBAL.php');
        // $artikelBal = new ArtikelBAL();

        //die('test');

        // $artikelBal->updateItemCode($data);
        //return parent::getSingle('artikel_parameter', $id);
    }

    function updateSpremembe($data)
    {
        //echo 'updateSpremembe';
        //print_r($data);
        $item = parent::getSingle('artikel_predogled', $data['id']);

        $artikel_id = $item->artikel_id;
		$artikel_predogled_id = $item->id;
		$polje = $data['field'];
        //$check_count = $post['check_count'];
        
        //aktiviraj artikel
        //parent::update('artikel', ['id' => $item->artikel_id, 'active' => 1]);

        // if(DE)
        // {
        //     print_r($data); die();
        // }
        // print_r($data);

        //potrdi spremembe
        if($polje == 'navodilo')
        {
            $polje = 'navodila';
        }
        
        if($data['type'] == 5)
        {
            $data['id'] = $item->artikel_id;

            include (ROOT . DS . 'application' . DS . 'bal' . DS . 'ArtikelBAL.php');
            $artikelBal = new ArtikelBAL();
            $artikelBal->updateItemCode($data);

    
            //print_r($param);
            parent::query("delete from artikel_sifra_parameter where artikel_id = ".$item->artikel_id.";");
            if(!empty($data['param_attributes']))
            {
                $param_array = [];
                foreach ($data['param_attributes'] as $key => $value) 
                {
                    $param_array = array_merge($param_array, $value);
                }

                $param_array = array_unique($param_array);
                if($param_array)
                {
                    foreach ($param_array as $key => $value) 
                    {
                        parent::potrdiSpremembeCMS('artikel_sifra_parameter', ['artikel_id' => $item->artikel_id, 'parameter_id' => $value], false);
                    }
                }
            }

            
            return true;

        }
        elseif($data['type'] == 3)
        {
            $predogled_artikel = json_decode($item->podatki_json, true);

            $datoteke = $this->getFiles(['type' => $data['file'] , 'item_id' => $artikel_id]);
            $datoteke_org_array = array();
            if($datoteke)
            {
                foreach ($datoteke as $key => $value)
                {
                    $datoteke_org_array[] = $value['path'];
                }
            }
            
            //potrdi spremembe
            parent::query("delete from files where type = ".$data['file']." and item_id = ".$artikel_id.";");

			if($predogled_artikel[$polje])
			{
				$i = 1;
				foreach ($predogled_artikel[$polje] as $key => $value)
				{                    
                    parent::saveFile([
                        'guid' => trim(com_create_guid(), '{}'), 
                        'type' => $data['file'], 
                        'item_id' => $artikel_id,
                        'sort' => $key,
                        'url' => $value
                    ]);
                }
                
                return true;
            }
            
        }
        elseif($data['type'] == 6){
            $predogled_artikel = json_decode($item->podatki_json, true);

            parent::query("delete from artikel_kategorija_mm where id_artikel = ".$artikel_id.";");
            $checkbox=$predogled_artikel['kategorija'];

            // print_r($checkbox); die();

            //izdelki_kategorije
            if(is_array($checkbox) && !empty($checkbox))
            {
                foreach ($checkbox as $key => $check)
                {   
                    $datax = [];
                    $datax['id_artikel'] = $artikel_id;
                    $datax['id_kategorija'] = $check;
                    //print_r($data);

                    $insert = parent::update('artikel_kategorija_mm', $datax);
                    if(!$insert)
                    {
                        return false;
                    }
                }
            }
            
            return true;

        }
        else
        {
            $polje1 = $polje;
            $polje2 = $polje;

            if($polje == 'navodila')
            {
                $polje1 = 'navodila';
                $polje2 = 'navodilo';
            }

            $update = [];
            $update['id'] = $item->artikel_id;
            $update[$polje1] = $data[$polje2.'sprememba'];
            

            //$this->handler->apiCallAsync(MAIN_URL.'/api/update-cache', ['auth' => $_SESSION['auth_key'] , 'type' => 'artikel', 'nosignal' => true]);
            return parent::update('artikel', $update);
        }
        
    }

    function getEanData($predogled_artikel_json, $artikel_id)
    {

        $predogled_artikel_json['velikost_artikla_id'] = [];
        $predogled_artikel_json['barva_artikla_id'] = [];
        $predogled_artikel_json['velikost'] = [];
        $predogled_artikel_json['barva'] = [];
        $predogled_artikel_json['ean_dobavitelj'] = [];

        $combinations = $this->parameterList(['artikel_id' => $artikel_id]); 
        if(!empty($combinations[0]))
        {
            // new optimized code
            if (true) {      
                $api_res = $this->handler->apiCall(MAIN_URL.'/api/artikel-params', ['auth' => $_SESSION['auth_key'] , 'artikel_id' => $artikel_id, 'attributes' => [], 'combinations' => $combinations ]);
                  
                foreach ($api_res as $key => $item) 
                {
                    $velikost = '0';
                    $barva = '0';
                    foreach ($combinations[$key] as $combo_key => $value) 
                    {
                        $param = $this->getParameter($value);
                    // print_r($param); die();

                        if($param->tip_id == 1)
                        {
                            $predogled_artikel_json['velikost'][] = $param->id;
                            $velikost = $param->id;

                            $predogled_artikel_json['velikost_artikla_id'][$item->ean] = $param->id;
                        }

                        if($param->tip_id == 2)
                        {
                            $predogled_artikel_json['barva'][] = $param->naziv;
                            $barva = $param->id;

                            $predogled_artikel_json['barva_artikla_id'][$item->ean] = $param->id;
                        }
                    }

                    $predogled_artikel_json['ean_dobavitelj'][$velikost.'#'.$barva] =  $item->ean;
                }
            } 
            // old code
            else {   
                foreach ($combinations as $key => $combo) 
                {
                    $api_res = $this->handler->apiCall(MAIN_URL.'/api/artikel-params', ['auth' => $_SESSION['auth_key'] , 'artikel_id' => $artikel_id, 'attributes' => $combo ]);

                    if($combo)
                    {
                        $velikost = '0';
                        $barva = '0';
                        foreach ($combo as $combo_key => $value) 
                        {
                            $param = $this->getParameter($value);
                        // print_r($param); die();

                            if($param->tip_id == 1)
                            {
                                $predogled_artikel_json['velikost'][] = $param->id;
                                $velikost = $param->id;

                                $predogled_artikel_json['velikost_artikla_id'][$api_res->ean] = $param->id;
                            }

                            if($param->tip_id == 2)
                            {
                                $predogled_artikel_json['barva'][] = $param->naziv;
                                $barva = $param->id;

                                $predogled_artikel_json['barva_artikla_id'][$api_res->ean] = $param->id;
                            }
                        }

                        $predogled_artikel_json['ean_dobavitelj'][$velikost.'#'.$barva] =  $api_res->ean;
                    } 
                }
            }
        }
        else
        {
            $artikel_sifra = null;
            $artikel_sifra_list = $this->getArtikelSifra($artikel_id);
            if(!empty($artikel_sifra_list[0]))
            {
                $artikel_sifra = $artikel_sifra_list[0];
            }

            $predogled_artikel_json['ean_dobavitelj'] = ['0#0' => $artikel_sifra['ean']];
            
        }

        $predogled_artikel_json['velikost'] = array_unique($predogled_artikel_json['velikost']);
        $predogled_artikel_json['barva'] = array_unique($predogled_artikel_json['barva']);

        return $predogled_artikel_json;
    }

    function discardChanges($data)
    {
        $item = parent::getSingle('artikel_predogled', $data['id']);

        $artikel_id = $item->artikel_id;
		$artikel_predogled_id = $item->id;
		$polje = $data['field'];
        //echo 'discardChanges';
        //print_r($data);
        $predogled_artikel_json = json_decode($item->podatki_json, true);

        $artikel = (array)$this->getArtikel($artikel_id);

        if($data['type'] == 5)
        {
            $predogled_artikel_json = $this->getEanData($predogled_artikel_json, $artikel_id);
            //echo json_encode($predogled_artikel_json);            die();
        }
        elseif($data['type'] == 3)
        {
            $datoteke = $this->getFiles(['type' => $data['file'] , 'item_id' => $artikel_id]);
            $datoteke_org_array = array();
            if($datoteke)
            {
                foreach ($datoteke as $key => $value)
                {
                    $datoteke_org_array[] = $value['path'];
                }
            }

            $predogled_artikel_json[$polje] = $datoteke_org_array;

        }
        elseif($data['type'] == 6){
            $predogled_artikel_json[$polje]; //shranjene nove kategorije

            $value = parent::getArtikelKategorijaa($artikel_id); //trenutne kategorije
            $ids = [];
            if($value){
                foreach($value as $key => $item){
                    $ids[$key] = $item->id;
                }
            }

            print_r($ids);
            $predogled_artikel_json[$polje] = $ids;
        }
        else
        {
            $predogled_artikel_json[$polje] = $artikel[$polje];
        }
		


        $update = [];
        $update['id'] = $item->id;
        $update['podatki_json'] = json_encode($predogled_artikel_json);
        return parent::update('artikel_predogled', $update);
    }

    function predogledSpremembeDeaktiviraj($id)
    {
        $item = parent::getSingle('artikel_predogled', $id);

        parent::update('artikel', ['id' => $item->artikel_id, 'dobavitelj_id' => $item->dobavitelj_id, 'active' => 1]);
        return parent::update('artikel_predogled', ['id' => $id, 'aktivno' => 2]);
    }

    function getFiles($data)
    {
        
        return parent::getFiles($data);
    }

    function parameterList($data)
    {
        ob_start();

        $data['query'] = "SELECT artikel_parameter.id, artikel_parameter.tip_id, artikel_parameter.naziv 
        FROM artikel_sifra_parameter
        inner join artikel_parameter on artikel_parameter.id = artikel_sifra_parameter.parameter_id
        ";

        $data['where'] = " and artikel_id = '".$data['artikel_id']."'";

        $combinations = [];
        $parameters = parent::getList('', $data);
        if($parameters)
        {
            foreach ($parameters as $key => $param) 
            {
                $combinations[$param['tip_id']][] = $param['id'];
            }
        }

        $combinations = $this->handler->get_combinations($combinations);

        return $combinations;
    }
}  
        