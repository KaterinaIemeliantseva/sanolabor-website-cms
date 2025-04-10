<?php
require_once (ROOT . DS . 'application' . DS . 'bal' . DS . 'ArtikelDobaviteljBAL.php');
        // $artikelBal = new ArtikelBAL();

class ArtikelBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'artikel';
    public $user;
    public $artikel_dobavitelj;

    public $audit = true; 
    public $audit_message_list = 'artikli';
    public $audit_message_single = 'artikel';

    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
        $this->artikel_dobavitelj = new ArtikelDobaviteljBAL();
        $this->db = Database::obtain();
    }
    /**************************************************************************/

    function getList($data = [])
    {
        // , (select GROUP_CONCAT(artikel_sifra.sifra  SEPARATOR '; ')  from artikel_sifra where artikel_sifra.artikel_id = artikel.id) as '_sifre'

        $data['array_search'] = ['artikel.naziv', 'artikel_sifra.sifra'];

        $data['query'] = "SELECT artikel.id, artikel.naziv, artikel.status, artikel.updated_at, artikel.arhiv
        , (select artikel_sifra.sifra  from artikel_sifra where artikel_sifra.artikel_id = artikel.id limit 1) as '_sifre'
        , (SELECT GROUP_CONCAT(artikel_kategorija.naziv  SEPARATOR '; ') FROM artikel_kategorija
            inner join artikel_kategorija_mm on artikel_kategorija_mm.id_kategorija = artikel_kategorija.id and parent = 0
            where artikel_kategorija_mm.id_artikel = artikel.id) as '_kategorija'
      
        FROM ". $this->table ."
        left join artikel_sifra on artikel_sifra.artikel_id = artikel.id
        left join artikel_cena on artikel_sifra.sifra = artikel_cena.sifra_artikla

        ";
   
        $where = ' and artikel.active = 1 ';
        if(!empty($data['where']))
        {
            $where .= $data['where'];
        }

        $data['where'] = $where;

        $data['orderby'] = ' ORDER BY artikel.id desc ';
        $data['groupBy'] = ' artikel.id ';
  
        return parent::getList($this->table, $data);
    }

        function getArtikelSifra($id)
	{
        $data = [];
  
        $data['where'] = " and artikel_id = '".$id."' ";
        $result = parent::getList('artikel_sifra', $data);
        if($result)
        {
            return $result;
        }

        //return $this->dal->getKategorijeRazvrsti($parent, $telo);
    }

        function getArtikelCena($sifra)
	{
        $data = [];
  
        $data['where'] = " and sifra_artikla = '".$sifra."' ";
        $result = parent::getList('artikel_cena', $data);
        if($result)
        {
            return $result[0];
        }

        //return $this->dal->getKategorijeRazvrsti($parent, $telo);
    }

    function save($data)
    {
                //odstrani popust
        // $ni_popusta = false;
        if(isset($data['ni_popusta']))
        {
            $ni_popusta = $data['ni_popusta'];
            unset($data['ni_popusta']);
        }

        //pokaži artikle tudi, če nimajo zaloge
        if(isset($data['zaloga_izjema']))
        {
            $zaloga_izjema = $data['zaloga_izjema'];
            unset($data['zaloga_izjema']);
        }

        //enota
        if(!empty($data['skladisce']) )
        {
            $skladisce = parent::getSingle('enota', $data['skladisce']);
            if(!$skladisce)
            {
                return false;
            }
            $data['skladisce'] = $skladisce->sifra;
        }

        //povezani artikli
        $povezani_artikli = false;
        if(isset($data['povezani_artikli']))
        {
            $povezani_artikli = $data['povezani_artikli'];
            unset($data['povezani_artikli']);
        }

        //certifikati
        $certifikati = false;
        if(isset($data['certifikati']))
        {
            $certifikati = $data['certifikati'];
            unset($data['certifikati']);
        }

        //vstavi artikel
        $result = parent::update($data);
        $id_artikel = (!empty($data['id'])) ? $data['id'] : $result;


        if(isset($ni_popusta))
        {
            $artikel_sifra = $this->getArtikelSifra($data['id']);
            foreach ($artikel_sifra as $key => $value)
            {
                parent::potrdiSpremembeCMS('artikel_cena', ['ni_popusta' => $ni_popusta], false, "sifra_artikla ='". $value['sifra']."'");
            }
        }

        if(isset($zaloga_izjema))
        {
            $artikel_sifra = $this->getArtikelSifra($data['id']);
            foreach ($artikel_sifra as $key => $value)
            {
                parent::potrdiSpremembeCMS('artikel_cena', ['zaloga_izjema' => $zaloga_izjema], false, "sifra_artikla ='". $value['sifra']."'");
            }
        }
        /**/
        if($povezani_artikli)
        {
            parent::query("delete from artikel_povezani where tip = 1 and id_item = ".$id_artikel.";");
            foreach ($povezani_artikli as $key => $value)
            {
                parent::potrdiSpremembeCMS('artikel_povezani', ['id_item' => $id_artikel, 'id_artikel' => $value, 'tip' => 1], false);
            }
        }

        if($certifikati)
        {
            parent::query("delete from artikel_certifikat where id_artikel = ".$id_artikel.";");
            foreach ($certifikati as $key => $value)
            {
                parent::potrdiSpremembeCMS('artikel_certifikat', ['id_artikel' => $id_artikel, 'id_certifikat' => $value], false);
            }
        }
        /**/

        
        $this->insertArtikelPredogled($id_artikel);
        

        //$this->handler->apiCallAsync(MAIN_URL.'/api/update-cache', ['auth' => $_SESSION['auth_key'] , 'type' => 'artikel', 'nosignal' => true]);


        return $result;
    }

    function insertArtikelPredogled($id)
    {
        $value = (array)parent::getSingle('artikel', $id);
        if(!$value && !empty($value['dobavitelj_id']))
        {
            return;
        }

        $artikel_predogled = array();
		$artikel_predogled['artikel_id'] = $value['id'];
		$artikel_predogled['cas_spremembe'] = date('Y-m-d - H:i:s');
		$artikel_predogled['aktivno'] = 0;
		$artikel_predogled['dobavitelj_id'] = $value['dobavitelj_id'];

		$podatki = array();
        $podatki['naziv'] = $value['naziv'];
        
        $bz = parent::getSingle('blagovna_znamka', $value['blagovna_znamka']);

		$podatki['blagovna_znamka_label'] = ($bz && !empty($bz->naziv)) ? $bz->naziv :  '';
		$podatki['blagovna_znamka'] = ($bz && !empty($bz->id)) ? $bz->id : 0;

		$podatki['sifra'] = '';

		$podatki['kratki_opis'] = $value['kratki_opis'];
		$podatki['vsebina'] = $value['vsebina'];
		$podatki['navodilo'] = $value['navodila'];
		$podatki['opozorilo'] = $value['opozorilo'];
        $podatki['tehnicna_dokumentacija'] = $value['tehnicna_dokumentacija'];
        


		$podatki['path_slika'] =  $this->getFiles(['type' => 1 , 'item_id' => $id]);
		$podatki['path_dokument'] = $this->getFiles(['type' => 16 , 'item_id' => $id]);
		$podatki['path_dokument_deklaracija'] = $this->getFiles(['type' => 15 , 'item_id' => $id]);

		$podatki['textarea_komentar_spremembe'] = '';
		$podatki['action'] = 'edit';
		$podatki['seznamSlikZaIzbris'] = '';
		$podatki['seznamDokumetovZaIzbris'] = '';
		$podatki['izbrisiVideoStatus'] = '';
		$podatki['komentar_uporabnika'] = '';

        $podatki = $this->artikel_dobavitelj->getEanData($podatki, $id);


		//print_r($podatki); die();

		$artikel_predogled['podatki'] = $podatki;

        $insert_data = array();
        $insert_data['artikel_id'] = $artikel_predogled['artikel_id'];
        $insert_data['cas_spremembe'] = $artikel_predogled['cas_spremembe'];
        //$insert_data['podatki'] = serialize($artikel_predogled["podatki"]);
        $insert_data['podatki_json'] = json_encode($artikel_predogled["podatki"]);
        $insert_data['aktivno'] = 2;
        $insert_data['dobavitelj_id'] = $artikel_predogled['dobavitelj_id'];

        // print_r($insert_data); die();
        parent::potrdiSpremembeCMS('artikel_predogled', $insert_data, false);
    }

    function getFiles($data)
    {
        $datoteke = parent::getFiles($data);
        $datoteke_org_array = array();
        if($datoteke)
        {
            foreach ($datoteke as $key => $value)
            {
                $datoteke_org_array[] = $value['path'];
            }
        }

        return $datoteke_org_array;
    }

    function updateItemCode($data)
    {
        $logFile = 'c:\temp\cms_log.txt';
                $logMessage = date('Y-m-d h:i:s'). ' -updateItemCode ' ."\r\n";
                file_put_contents($logFile, $logMessage, FILE_APPEND);
        // print_r($data); die();
        $id_artikel = $data['id'];
        $prepare = [];

        if($data['param_sifra'])
        {
            foreach ($data['param_sifra'] as $key => $value) 
            {
                $prepare[$key]['sifra'] = $value;
            }
        }
        
        if(!empty($data['param_ean']))
        {
            foreach ($data['param_ean'] as $key => $value) 
            {
                $prepare[$key]['ean'] = $value;
            }
        }

        if(!empty($data['param_attributes']))
        {
            foreach ($data['param_attributes'] as $key => $value) 
            {
                $prepare[$key]['attributes'] = $value;
            }
        }
    
        // if(DE)
        // {
        //     echo $id_artikel;
        //     print_r($prepare); die();
        // }
        

        if($prepare)
        {
            parent::query("delete from artikel_sifra where artikel_id = ".$id_artikel.";");
            parent::query("delete from sifra_parameter where artikel_id = ".$id_artikel.";");

            foreach ($prepare as $key => $value) 
            {
                //artikel_sifra
                //print_r(['artikel_id' => $id_artikel, 'sifra' => $value['sifra'], 'ean' => $value['ean']]);
                $artikel_sifra_id =  parent::update('artikel_sifra', ['artikel_id' => $id_artikel, 'sifra' => $value['sifra'], 'ean' => $value['ean']]);
                
                //sifra_parameter
                if(!empty($value['attributes']))
                {
                    foreach ($value['attributes'] as $key_attr => $value_attr) 
                    {
                        parent::update('sifra_parameter', ['sifra_id' => $artikel_sifra_id, 'parameter_id' => $value_attr, 'artikel_id' => $id_artikel]);
                    }
                }
                
            }
        }

        return true;
    }

    function getParameterTypes()
    {
        return parent::getList('artikel_parameter_tip', []);
    }
    
    function parameterListView($data)
    {
        // $logFile = 'c:\temp\cms_log.txt';
        //         $logMessage = date('Y-m-d h:i:s'). ' -parameterListView ' ."\r\n";
        //         file_put_contents($logFile, $logMessage, FILE_APPEND);
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
        // file_put_contents($logFile, json_encode($combinations), FILE_APPEND);
        

        // if(DE)
        // {
        //     print_r($combinations); die();
        // } 
        //$param_types
        if(!empty($combinations[0]))
        {
            
            // new optimized code
            /*
            // if (true) {
            //     $logFile2 = 'c:\temp\cms_log.txt';
            //     $logMessage = date('Y-m-d h:i:s'). ' -parameterListView optimized code' ."\r\n";
            //     file_put_contents($logFile2, $logMessage, FILE_APPEND);
            //     $api_res = $this->handler->apiCall(MAIN_URL.'/api/artikel-params', ['auth' => $_SESSION['auth_key'] , 'artikel_id' => $data['artikel_id'], 'attributes' => [], 'combinations' => $combinations ]);
            //     file_put_contents($logFile2, json_encode($api_res), FILE_APPEND);
            //         foreach ($api_res as $key => $item) 
            //         {
            //             ?>
            //             <tr>
            //                 <td><?php $this->handler->html_input(['name' => 'param_sifra['.$key.']', 'maxlength' => 6, 'type' => 'number', 'value' => (!empty($item->sifra)) ? $item->sifra : '', 'required' => false]); ?><div class="sifra-opozorilo el<?php echo $key; ?> hide">Šifra je že povezana z artiklom  1 <?php echo $key; ?> <?php echo $item->sifra; ?>(<span class="povezani-art"></span>).</div></td>
            //                 <td><?php $this->handler->html_input(['name' => 'param_ean['.$key.']', 'type' => 'number', 'value' => (!empty($item->ean)) ? trim($item->ean) : '', 'required' => false]); ?></td>
                        
            //                 <td style="vertical-align: middle;"><label>
            //                 <?php 
            //                 if($combinations[$key])
            //                 {
            //                     foreach ($combinations[$key] as $combo_key => $value) 
            //                     {
            //                         ?><?php 
            //                         $param = parent::getSingle('artikel_parameter', $value);
            //                         echo $param->naziv ?>; <input type="hidden" name="param_attributes[<?php echo $key; ?>][<?php echo $combo_key; ?>]" value="<?php echo $value ?>" ><?php
            //                     }
            //                 } ?>
            //                 </label></td>
            //                 <td></td>
            //             </tr>
            //             <?php
            //         }
                
              
            // }  
            */
            // // // old code
            // else {                
                
                foreach ($combinations as $key => $combo) 
                {
                    // if(DE)
                    // {
                    //     print_r($combo); die();
                    // }
                    $api_res = $this->handler->apiCall(MAIN_URL.'/api/artikel-params', ['auth' => $_SESSION['auth_key'] , 'artikel_id' => $data['artikel_id'], 'attributes' => $combo ]);
                    //print_r([1190, 2203]); 
                    
                    //  
                    //  //
                    // print_r($api_res);  continue;
                    ?>
                    <tr>
                        <td><?php $this->handler->html_input(['name' => 'param_sifra['.$key.']', 'maxlength' => 6, 'type' => 'number', 'value' => (!empty($api_res->sifra)) ? $api_res->sifra : '', 'required' => false]); ?><div class="sifra-opozorilo el<?php echo $key; ?> hide">Šifra je že povezana z artiklom druga (<span class="povezani-art"></span>).</div></td>
                        <td><?php $this->handler->html_input(['name' => 'param_ean['.$key.']', 'type' => 'number', 'value' => (!empty($api_res->ean)) ? trim($api_res->ean) : '', 'required' => false]); ?></td>
                    
                        <td style="vertical-align: middle;"><label>
                        <?php 
                        if($combo)
                        {
                            foreach ($combo as $combo_key => $value) 
                            {
                                ?><?php 
                                $param = parent::getSingle('artikel_parameter', $value);
                                echo $param->naziv ?>; <input type="hidden" name="param_attributes[<?php echo $key; ?>][<?php echo $combo_key; ?>]" value="<?php echo $value ?>" ><?php
                            }
                        } ?>
                        </label></td>
                        <td></td>
                    </tr>
                    <?php
                }
            // }
        }
        else
        {
            $artikel_sifra = null;
            $artikel_sifra_list = parent::getList('artikel_sifra', ['where' => ' and artikel_id = '.$data['artikel_id'].' ']);
            if(!empty($artikel_sifra_list[0]))
            {
                $artikel_sifra = $artikel_sifra_list[0];
            }
            ?>
            <tr>
                <td><?php $this->handler->html_input(['name' => 'param_sifra[0]', 'maxlength' => 6, 'minlength' => 6, 'type' => 'number', 'class' => ' param_sifra ', 'value' => (!empty($artikel_sifra['sifra'])) ? $artikel_sifra['sifra'] : '', 'required' => false]); ?><div class="sifra-opozorilo el0 hide">Šifra je že povezana z artiklom tretja (<span class="povezani-art"></span>).</div></td>
                <td><?php $this->handler->html_input(['name' => 'param_ean[0]', 'type' => 'number', 'value' => (!empty($artikel_sifra['ean'])) ? $artikel_sifra['ean'] : '', 'required' => false]); ?></td>
                <td></td>
                <td></td>
            </tr>
        <?php
        }
        //
        $contents = ob_get_contents();
        ob_end_clean();
        
        $contents = trim(preg_replace('/\s+/', ' ', $contents));
		return  $contents;
		//return 'test122'; // $contents;
    }

    /**/
    function getPovezaniSelect($data)
    {
        $tags = parent::getList(['search' => $data['search'], 'orderby' => 'order by naziv asc', 'array_search' => ['artikel.naziv', 'artikel.sifra']]);
        return parent::getKategorijeSelect($tags);
    }

    function getPovezaniSelected($data)
    {
        $tags = parent::getList('artikel_povezani', [
            'query' => ' SELECT artikel.id, artikel.naziv, artikel.sifra FROM artikel_povezani
            inner join artikel on artikel.id = artikel_povezani.id_artikel and artikel_povezani.tip = '.$data['tip'].' and artikel_povezani.id_item = '.$data['id'].' ',
            'orderby' => 'order by id asc']);

        return parent::getKategorijeSelect($tags);
    }
    /**/


    /**/
    function getKategorijeRazvrsti($parent)
	{
        $data = [];
        $data['query'] = " SELECT * FROM artikel_kategorija ";
        $data['where'] = " and parent = ".$parent."  ";
        $data['orderby'] = " order by sort";

        return parent::getList('artikel_kategorija', $data);

        //return $this->dal->getKategorijeRazvrsti($parent, $telo);
    }

    function getKategorijeRazvrstiIzpis($parent, $nivo, $id)
    {
        $rows = $this->getKategorijeRazvrsti($parent);
        if($rows)
        {
            foreach ($rows as $key => $value)
            {
                $value = (object) $value;
                //print_r($value);
                if($nivo == 1 && $key > 0 )
                {
                    ?></div><hr /><div class="nastavitev_kategorije_wrapper"><?php
                }
                // for($i=0; $i < $nivo; $i++)
                // {
                //     echo ';';
                // } echo $value->naziv.' ('.$value->id.')<br />';
                // if(1 == 2):
                ?><div class="nastavitev_kategorije_box_main<?php echo $nivo; ?> <?php if($value->filter) echo ' filter '; ?>"><?php
                $this->handler->html_checkbox(['label' => $value->naziv, 'name' => 'kategorija['.$value->id.']', 'status' => ($this->getArtikelKategorija($value->id, $id))]);
                ?></div><?php
                // endif;

                $children = $this->getKategorijeRazvrsti($value->id);
                if($children)
                {
                    $this->getKategorijeRazvrstiIzpis($value->id, $nivo+1, $id);
                }
            }
        }
    }

    function getArtikelKategorija($kat_id, $art_id)
	{
        $data = [];
        $data['where'] = " and id_kategorija = ".$kat_id." and id_artikel = ".$art_id." ";
        //if(DE) print_r($data);
        $result = parent::getList('artikel_kategorija_mm', $data);
        if(!empty($result[0]))
        {
            return $result;
        }

        return null;
        //return $this->dal->getArtikelKategorija($kat_id, $art_id);
    }

    

	function shraniRavrstitev($post)
	{
        parent::query("delete from artikel_kategorija_mm where id_artikel = ".$post['id'].";");
    	$checkbox=$post['kategorija'];

	    //izdelki_kategorije
	    if(is_array($checkbox))
	    {
			foreach ($checkbox as $key => $check)
			{
                if($check == 1)
                {
    		        $data['id_artikel'] = $post['id'];
    		        $data['id_kategorija'] = $key;
                    //print_r($data);

    				$insert = parent::update('artikel_kategorija_mm', $data);
                    if(!$insert)
                    {
                        return false;
                    }
                }
			}
	    }


        return true;
	}
    /**/

    function preveriSifra($data)
    {
        $result['obstaja'] = 'false';

        if(isset($data['sifra']) && strlen($data['sifra']) > 0)
		{

            $query = parent::getList('artikel_sifra', [
                'query' => "SELECT s.artikel_id, a.naziv
                FROM artikel_sifra s
                inner join artikel a on a.id = s.artikel_id ",
                'where' => " and s.sifra='".$data['sifra']."' and a.id != '".$data['artikel_id']."' and a.arhiv != 1 ",
                'orderby' => 'order by a.id'
            ]);

            if(!empty($query[0]))
            {
                $r = $query[0];

                $result['obstaja'] = 'true';
				$result['id'] = $r['artikel_id'];
				$result['naziv'] = $r['naziv'];
            }
		}

        return $result;
    }

    function orderGaleryItems($post)
	{
        if($post)
        {
            foreach ($post as $key => $value) 
            {
                $item = parent::getList('files', ['where' => ' and guid = "'.$value['id'].'" and type = 1 ']);
               // print_R(['where' => ' and guid = "'.$value['id'].'" and type = 1 ']);

                if($item)
                {
                    $data = [];
                    $data['sort'] = $value['order'];
                    $data['id'] = $item[0]['id'];
                    //print_r($data);
                    parent::update('files', $data);
                }
            }
        }
    

        return true;
	}

     function getEnote($data)
    {
        if (empty($data['sifra'])) return null;

        $data['where'] = " and sifra = ".$data['sifra'];
        $result = parent::getList('enota', $data);
        return (!empty($result['0'])) ? $result[0] : null;
    }

    function getDobaviteljName($dobavitelj_id)
    {
        $sql = "SELECT name FROM dobavitelj_users WHERE dobavitelj_id = :dobavitelj_id LIMIT 1";
        $stmp = $this->db->link_id->prepare($sql);
        $stmp->bindParam(':dobavitelj_id', $dobavitelj_id, PDO::PARAM_INT);
        $stmp->execute();
        return $stmp->fetchColumn();
    }
}  
        