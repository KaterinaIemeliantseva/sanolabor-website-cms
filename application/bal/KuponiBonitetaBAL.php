<?php
        class KuponiBonitetaBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'kuponi_boniteta';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'kuponi_boniteta';
            public $audit_message_single = 'kuponi_boniteta';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

            function getKuponiBoniteta($data = []){
                $data['array_search'] = [];
                $data['query'] = "SELECT *
                FROM 
                kuponi_boniteta                 
                ";

                $data['orderby'] = 'order by vrstni_red asc';

                $result = parent::getKuponiBoniteta($this->table, $data);
    
                return $result;
            }

            function getKuponiStatistika($datum_od, $datum_do)
            {
                $data['query'] = "SELECT 
                    kuponi_boniteta.sifra_kupona, 
                    kuponi_boniteta.naziv, 
                    count(koriscen_kupon.id) as kolicina 
                FROM 
                koriscen_kupon 
                inner join kuponi_boniteta on koriscen_kupon.sifra_kupona = kuponi_boniteta.sifra_kupona
                
                ";

                $data['where'] = " and koriscen_kupon.ws_response is not null
                and st_kz != 13 and st_kz != 1 and st_kz != 10 and st_kz != 12 and st_kz != 11";
                $data['where'] .= " and (DATE(koriscen_kupon.created_at) between '".$datum_od."' and '".$datum_do."') ";
                $data['orderby'] = ' order by count(koriscen_kupon.id) DESC ';
                $data['groupBy'] = ' koriscen_kupon.sifra_kupona ';

                return parent::getList('koriscen_kupon', $data);

            }

            function getSifreStatistika($datum_od, $datum_do)
            {
                $data['query'] = "SELECT 
                    narocilo_postavke.sifra,
                    narocilo_postavke.naziv,
                    narocilo.nacin_dostave_opis,
                    sum(narocilo_postavke.kolicina) as kolicina
                FROM
                narocilo
                INNER JOIN
                    narocilo_postavke ON narocilo.id = narocilo_postavke.narocilo_id
                ";

                $data['where'] = " and narocilo.status = 3 
                and narocilo.stevilka_narocila is not null";
                $data['where'] .= " and (DATE(narocilo.created_at) between '".$datum_od."' and '".$datum_do."') ";
                $data['orderby'] = ' order by kolicina DESC ';
                $data['groupBy'] = ' narocilo_postavke.sifra, narocilo.nacin_dostave_opis ';

                return parent::getList('sifra_statistika', $data);
            }

        }  
        