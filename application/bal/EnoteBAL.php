<?php
class EnoteBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'enota';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'enote'; 
    public $audit_message_single = 'enota'; 

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
        $data['array_search'] = ['enota.naziv', 'enota.naslov', 'enota.sifra'];

        // $data['query'] = "SELECT artikel.id, artikel.naziv, artikel.status, artikel.sifra, artikel.blagovna_znamka, artikel.izdelki_skupine
        // , if(proizvajalci.naziv is not null, proizvajalci.naziv, '-') as '_blagovna_znamka'
        // , if(artikel_cena.zaloga is not null, artikel_cena.zaloga, '0') as '_zaloga'
        // FROM ". $this->table ."
        // left join proizvajalci on artikel.blagovna_znamka = proizvajalci.id
        // left join artikel_cena on artikel_cena.sifra_artikla = artikel.sifra 
        // ";
   
        $data['orderby'] = ' ORDER BY enota.naziv asc ';
        //$data['groupBy'] = ' artikel.id ';
  
        return parent::getList($this->table, $data);
    }

}  
        