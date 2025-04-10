<?php
class NarocilaBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'narocilo';
    var $user;

    public $audit = true; 
    public $audit_message_list = 'Narocila'; 
    public $audit_message_single = 'Narocilo'; 

    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
    }

    /**************************************************************************/
    function getList($data = [])
    {

        $data['array_search'] = ['narocilo.id','narocilo.st_narocila', 'narocilo.e_posta', 'narocilo.nacin_placila_opis','narocilo.opombe', 'narocilo.ime', 'narocilo.priimek',  'narocilo.st_kz', 'narocilo.vd', 'narocilo.znesek_placila', 'narocilo.znesek_dostave', 'narocilo.podjetje_naziv', 'narocilo.payment_reference_id'];

        //iskanje artiklov
        $data['array_search'][] = " (select concat(GROUP_CONCAT(narocilo_postavke.naziv SEPARATOR '; '), '; ', GROUP_CONCAT(narocilo_postavke.sifra SEPARATOR '; ')) as postavke
                 from narocilo_postavke 
                 where narocilo_postavke.narocilo_id = narocilo.id and narocilo_postavke.sifra != '790985' limit 1) "; 
      
        $data['array_search'][] = " concat(narocilo.ime, ' ', narocilo.priimek) "; 
        $data['array_search'][] = " concat(narocilo.dostava_ime, ' ', narocilo.dostava_priimek) "; 

//  (narocilo.znesek_placila - narocilo.popust_zaposleni_skupaj - narocilo.bon_vrednost) as znesek_placila, 
        $data['query'] = "SELECT DISTINCTROW narocilo.id,narocilo.st_narocila, narocilo.created_at, narocilo.updated_at, narocilo.e_posta, narocilo.nacin_placila_opis, 
        (narocilo.znesek_placila - narocilo.popust_zaposleni_skupaj) as znesek_placila, 
        narocilo.znesek_dostave, narocilo.opombe, narocilo.nacin_placila, narocilo.pregledano, 
        narocilo.payment_reference_id, narocilo.kartica_potrdilo_cas
        , narocilo.stevilka_narocila, narocilo.racun_podjetje, narocilo.st_kz, narocilo.zakljuceno, enota.naziv as enota_naziv_1
        ,   CASE
                WHEN LOCATE(',', narocilo.osebni_prevzem_naslov) > 0
                THEN SUBSTRING(narocilo.osebni_prevzem_naslov, 1, LOCATE(',', narocilo.osebni_prevzem_naslov) - 1)
                ELSE enota.naziv
            END as enota_naziv
        , narocilo.kartica_type
        , narocilo.furs_stevilka
        , if(narocilo.racun_podjetje = 1, narocilo.podjetje_naziv, concat(narocilo.ime, ' ', narocilo.priimek)) as ime_priimek
        , if(narocilo.vd = 1007, 'Medis', '') as partner_opis
        , if(narocilo.status = 3 and narocilo.stevilka_narocila != '', 'Da', 'Ne') as narocilo_zakljuceno
        , narocilo.nacin_placila as _nacin_placila
        , narocilo.nacin_dostave as nacin_dostave
        , narocilo.cas_poslano_v_enoto as _cas_poslano_v_enoto
        , narocilo.kupon_brezplacna_dostava as _kupon_brezplacna_dostava
        , (select GROUP_CONCAT(narocilo_postavke.naziv  SEPARATOR '; ')  from narocilo_postavke where narocilo_postavke.narocilo_id = narocilo.id and  narocilo_postavke.sifra != '790985') as '_artikli'
        , if(narocilo.bon_sifra != '', narocilo.bon_sifra,(select GROUP_CONCAT(DISTINCT  narocilo_postavke.kupon_koda  SEPARATOR '; ')  from narocilo_postavke where narocilo_postavke.narocilo_id = narocilo.id and  kupon_koda is not null  and kupon_koda != '0' )) as '_kupon_koda'
        , concat('',narocilo.interna_st_racuna, '') as interna_st_racuna
        , narocilo.furs_stevilka as furs_stevilka
        FROM narocilo
        left join enota on enota.sifra = narocilo.skladisce 

        -- left join nacin_placila on nacin_placila.sifra = narocilo.nacin_placila
        ";

        if(strpos($data['where'], 'status') === false)
        {
            $data['where'] .= " and narocilo.status = 3  ";
        }
   
        
        
 
        $data['orderby'] = ' order by narocilo.created_at desc ';
        $data['groupBy'] = ' narocilo.id ';
        $data['limit'] = ' 6000 ';

       // if(DE) print_r($data);
        $result = parent::getList($this->table, $data);
    
        return $result;
    }

    function update($data)
    {
        $narocilo = parent::getSingle($this->table, $data['id']);
        // if($narocilo->stevilka_narocila != '')
        // {
        //     $update = ['id' => $data['id'], 'e_posta' => $data['e_posta']];   
        //     if(!empty($data['podjetje_davcna']))
        //     {
        //         $update['podjetje_davcna'] = $data['podjetje_davcna'];
        //     }
           
        //     return parent::update($this->table, $update);
        // }

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

        //način plačila
        if(!empty($data['nacin_placila']) )
        {
            $nacin_placila = parent::getSingle('nacin_placila', $data['nacin_placila']);
            if(!$nacin_placila)
            {
                return false;
            }
            $data['nacin_placila'] = $nacin_placila->sifra;
            $data['nacin_placila_opis'] = $nacin_placila->naziv;
        }

        //dostava
        if(!empty($data['nacin_dostave']) )
        {
            $nacin_dostave = parent::getSingle('dostava', $data['nacin_dostave']);
            if(!$nacin_dostave)
            {
                return false;
            }
            $data['nacin_dostave'] = $nacin_dostave->sifra;
            $data['nacin_dostave_opis'] = $nacin_dostave->naziv;
        }

        //sprememba zneska dostave
        if(!empty($data['znesek_dostave']) )
        {
            $placilo = parent::getList('narocilo_postavke', [
                'query' => " select sum(placilo) as placilo_skupaj FROM narocilo_postavke ",
                'where' => " and narocilo_id = ".$data['id']
            ]); //print_r($placilo);

            $data['znesek_placila'] = $placilo[0]['placilo_skupaj'] +  $data['znesek_dostave'];

           // echo $data['znesek_placila']; die();
        }

        return parent::update($this->table, $data);
    }

    function delete($data)
    {
        return parent::potrdiSpremembeCMS($this->table, ['status' => 0], $data['id']);
        //return $this->base->delete($this->table, $data['id']);
    }
    /**************************************************************************/

    function getNacinDostaveSelect($data)
    {
        $search = (isset($data['search'])) ? $data['search'] : $data;
        $tags = parent::getList('dostava', ['search' => $search, 'query' => " SELECT sifra as id, if(id = 3, 'Osebni prevzem', naziv) as naziv FROM dostava", 'where' => ' and status = 1 and id != 1 and id != 2 ']);
        $tags[] = ['id' => '102346', 'naziv' => 'Navadna dostava (0 € - 24,99 €)'];
        $tags[] = ['id' => '102347', 'naziv' => 'Navadna dostava  (25 € - 49,99 €)'];
        $tags[] = ['id' => '102348', 'naziv' => 'Navadna dostava  (50 € in več)'];
        return parent::getKategorijeSelect($tags, []);
    }

    function getNacinDostave($data)
    {
        $data['where'] = " and sifra = ".$data['sifra'];
        $result = parent::getList('dostava', $data);
        return (!empty($result['0'])) ? $result[0] : null;
    }

    function getNacinPlacila($data)
    {
        $data['where'] = " and sifra = ".$data['sifra'];
        $result = parent::getList('nacin_placila', $data);
        return (!empty($result['0'])) ? $result[0] : null;
    }

    function getEnote($data)
    {
        $data['where'] = " and sifra = ".$data['sifra'];
        // if($data['naziv'])
        //     $data['where'] = " and (naziv like '".$data['naziv'] . "' or naziv like '%".str_replace(' -', '', $data['naziv'])."')";
        $result = parent::getList('enota', $data);
        return (!empty($result['0'])) ? $result[0] : null;
    }

    function getNacinPlacilaSelect($data)
    {
        $search = (isset($data['search'])) ? $data['search'] : $data;
        $tags = parent::getList('nacin_placila', ['search' => $search]);
        return parent::getKategorijeSelect($tags, ['id_name' => 'sifra']);
    }

    function getPostavke($id_narocilo)
    {
        $data['where'] = " and narocilo_id = ".(int)$id_narocilo;
        $data['orderby'] = ' order by id desc ';
        return parent::getList('narocilo_postavke', $data);
    }




    function getNarociloPoNacinuPlacila($nacin_placila, $datum_od, $datum_do)
    {
        $data['query'] = " select count(*) as stevilo_nakupov, sum(znesek_placila) as znesek_skupaj from narocilo ";
        $data['where'] = " and (narocilo.stevilka_narocila != '' or stevilka_narocila is not null) and narocilo.status = 3 

        and date(created_at) >= '".$datum_od."' and date(created_at) <= '".$datum_do."' ";

        if($nacin_placila == 39)
        {
            $data['where'] .= " and (nacin_placila = 39 || nacin_placila = 100) ";
        }
        else
        {
            $data['where'] .= " and nacin_placila = ".(int)$nacin_placila." ";
        }

        //$nacin_placila



        // if(DE)
        // {
        //     print_r($data);
        // }

        $result = parent::getList('narocilo', $data);
        if($result)
        {
            return $result[0];
        }
    }

    function getNarociloPoNacinuNakupa($tip, $datum_od, $datum_do)
    {
        $data['query'] = " select count(*) as stevilo_nakupov, sum(znesek_placila) as znesek_skupaj from narocilo
        left join users on users.id = narocilo.user_id ";
        $data['where'] = " and (narocilo.stevilka_narocila != '' or stevilka_narocila is not null) and narocilo.status = 3 ";

        if($tip == 1)
        {
            $data['where'] .= " and vd = 1007 "; 
        }
        elseif($tip == 2)
        {
            $data['where'] .= " and (user_id is not null and user_id > 0)  and vd = 1001 and (users.facebook_id is null or users.facebook_id = 0) "; 
        }
        elseif($tip == 3)
        {
            $data['where'] .= " and (user_id is null or user_id = 0) and vd = 1001 "; 
        }
        elseif($tip == 4)
        {
            $data['where'] .= " and (users.facebook_id is not null and users.facebook_id > 0) and vd = 1001 "; 
        } 
        elseif($tip == 5)
        {
            $data['where'] .= " and nacin_dostave = '148969' "; 
        }
        
       // $data['where'] .= " and date(narocilo.created_at) >= '".$datum_od."' and date(narocilo.created_at) <= '".$datum_do."' ";
        $data['where'] .= " and (DATE(narocilo.created_at) between '".$datum_od."' and '".$datum_do."') ";
       // $data['groupBy'] = ' narocilo.id ';
        $data['orderby'] = ' order by narocilo.id desc ';
        $result = parent::getList('narocilo', $data);
        if($result)
        {
            return $result[0];
        }
    }

    function getNarocilaPostavke( $datum_od, $datum_do, $dostava = '')
    {
        $data['query'] = " SELECT 
        narocilo_postavke.sifra,
           narocilo_postavke.naziv,
           artikel_kategorija.naziv as kategorija_naziv,
           narocilo.nacin_dostave_opis as nacin_dostave,
           SUM(narocilo_postavke.placilo) AS placilo,
           SUM(narocilo_postavke.kolicina ) AS kolicina
        FROM
        narocilo_postavke 
        inner JOIN  narocilo ON narocilo_postavke.narocilo_id = narocilo.id
		left join artikel_sifra ON  artikel_sifra.id = (select id from artikel_sifra where narocilo_postavke.sifra = artikel_sifra.sifra limit  1) 
        left join artikel_kategorija_mm ON artikel_kategorija_mm.id = (select id from artikel_kategorija_mm where artikel_kategorija_mm.id_artikel = artikel_sifra.artikel_id limit 1)
        left join artikel_kategorija  on artikel_kategorija.id = (select id from artikel_kategorija where artikel_kategorija_mm.id_kategorija = artikel_kategorija.id AND artikel_kategorija.status = 1 AND artikel_kategorija.parent = 0 limit 1)
        
         ";

        $data['where'] = " and (narocilo.stevilka_narocila != '' OR stevilka_narocila IS NOT NULL)
        AND narocilo.status = 3
        and narocilo_postavke.sifra != '790985' ";
        $data['where'] .= " and (DATE(narocilo.created_at) between '".$datum_od."' and '".$datum_do."') ";
        $data['orderby'] = ' order by SUM(narocilo_postavke.kolicina ) desc ';
        // $data['groupBy'] = ' narocilo_postavke.sifra '.$dostava;

        if(!empty($_POST['name']))
        {
            $data['groupBy'] = ' narocilo_postavke.sifra '.$dostava;
        }else{
            $data['groupBy'] = ' narocilo_postavke.sifra ';
        }

        return parent::getList('narocilo_postavke', $data);

    }

    function statusMail($id, $status)
    {
        $narocilo = parent::getList('narocilo_status', ['where' => ' and status = '.$status.' and narocilo_id = '.$id.' ']);
        if(!empty($narocilo[0]))
        {
            return $narocilo[0];
        }
        
        return false;
    }

    function exportNarociloPostavke($data)
    {
        $date_from = date("Y-m-d", strtotime($data['date_from']));   
        $date_to = date("Y-m-d", strtotime($data['date_to'])); 

        $data['query'] = "
        SELECT 
        narocilo.created_at, narocilo.id, narocilo_postavke.naziv, narocilo_postavke.kolicina, narocilo_postavke.cena, narocilo_postavke.ddv_vrednost, if(narocilo_postavke.ddv_sifra = 31, '22,00', '9,5') as ddv_odstotek
        FROM narocilo_postavke
        inner join narocilo on narocilo.id = narocilo_postavke.narocilo_id
        ";

        //$data['where'] = " and (stevilka_narocila != '' and stevilka_narocila is not null) and status = 3  ";
        $data['where'] = " and status = 3  ";
        $data['where'] .= " and (DATE(narocilo.created_at) between '".$date_from."' and '".$date_to."') ";
        $data['orderby'] = ' order by id desc ';
        $result = parent::getList($this->table, $data);

    
        require_once 'E:\www\\' . DS . 'lib' . DS . 'PHPExcel-1.8' . DS . 'Classes' . DS . 'PHPExcel.php';
		$objPHPExcel = new PHPExcel();

		if(!$result)
		{
			exit();
		}

		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Čas naročila')
            ->setCellValue('B1', 'Št. naročila')
            ->setCellValue('C1', 'Naziv')
            ->setCellValue('D1', 'Količina')
            ->setCellValue('E1', 'Cena')
            ->setCellValue('F1', 'DDV vrednost')
            ->setCellValue('G1', 'DDV odstotek')
        ;

        foreach ($result as $key => $item)
		{
			//print_r($item);
			$key += 2;
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$key, $item['created_at'])
            ->setCellValue('B'.$key, $item['id'])
            ->setCellValue('C'.$key, $item['naziv'])
            ->setCellValue('D'.$key, $item['kolicina'])
            ->setCellValue('E'.$key, number_format($item['cena'], 2, ',', ''))
            ->setCellValue('F'.$key, number_format($item['ddv_vrednost'], 2, ',', ''))
            ->setCellValue('G'.$key, $item['ddv_odstotek'])
            ;
		}

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

       // $f = fopen('php://memory', 'w');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="seznam.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
        //fpassthru($f);
    }

    function exportAsw($data)
    {
        //$data['query'] = " DATE_FORMAT(kartica_potrdilo_cas, \"%d.%m.%Y\") as kartica_potrdilo_cas, replace(znesek_placila, '.', ',') as znesek_placila
       // FROM narocilo
        //";

        $date_from = date("Y-m-d", strtotime($data['date_from']));   
        $date_to = date("Y-m-d", strtotime($data['date_to']));   

        $data['where'] = " and (stevilka_narocila != '' and stevilka_narocila is not null)
        and status = 3 and nacin_placila = 45 ";
        $data['where'] .= " and (DATE(narocilo.kartica_potrdilo_cas) between '".$date_from."' and '".$date_to."') ";
        $data['orderby'] = ' order by id desc ';
   

       // if(DE) print_r($data);
        $result = parent::getList($this->table, $data);
        $array = [];
		
        if($result )
        {
            foreach ($result as $key => $value)
            {
                $kartica_potrdilo_cas = date("d.m.Y", strtotime($value['kartica_potrdilo_cas']));
                $znesek_placila = number_format($value['znesek_placila'] - $value['popust_zaposleni_skupaj'], 2, ',', ''); 
                

                $item = [];
                $item[] = '165830'; 
                $item[] = 'K';
                $item[] = '25550';
                $item[] = $kartica_potrdilo_cas;
                $item[] = $value['ime'].' '.$value['priimek'];
                $item[] = '0';
                $item[] = $znesek_placila;
                $item[] = '0';
                $item[] = $kartica_potrdilo_cas;
                $item[] = 'EUR';
                $item[] = '1';
                $item[] = $value['id'];
                $item[] = $value['ime'].' '.$value['priimek'];
                $item[] = '0';
                $item[] = $znesek_placila;
                $item[] = '0';

                $array[] = $item;
            }
		}
		
		//print_r($array); die();

        // open raw memory as file so no temp files needed, you might run out of memory though
        $f = fopen('php://memory', 'w');
		// loop over the input array
		
		

        foreach ($array as $line)
        {
            // generate csv lines from the inner arrays
			//fputcsv($f, $line, $delimiter, ' ');

            fwrite($f, iconv("UTF-8", "Windows-1250//IGNORE", implode('|', $line)) . "\n");
            //fwrite($f, iconv("UTF-8", "Windows-1252//IGNORE", implode(';', $line)) . "\n");
        }
        // reset the file pointer to the start of the file
        fseek($f, 0);
        // tell the browser it's going to be a csv file
        //header('Content-Type: application/excel');
        header('Content-Encoding: windows-1252');
        header('Content-Type: application/csv; charset=windows-1252');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="'.$data['filename'].'";');
        // make php send the generated csv lines to the browser
        fpassthru($f);
    }
}
