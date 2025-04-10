<?php
class ProgramBAL extends BaseBAL
{
    public $dal;
    public $handler;
    public $table = 'program';
    var $user;

    public $audit = false; 
    public $audit_message_list = 'programi';
    public $audit_message_single = 'program';

    function __construct()
    {
        global $user;

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
    }
    /**************************************************************************/

    function update($data)
    {
        $kontakti = false;
        if(isset($data['kontakti']))
        {
            $kontakti = $data['kontakti'];
            unset($data['kontakti']);
        }

        $result = parent::update($data);
        $id_item = (!empty($data['id'])) ? $data['id'] : $result;

        /**/
        if($kontakti)
        {
            parent::query("delete from program_kontakt_mm where id_program = ".$id_item.";");
            foreach ($kontakti as $key => $value)
            {
                if($value > 0)
                {
                    parent::potrdiSpremembeCMS('program_kontakt_mm', ['id_program' => $id_item, 'id_kontakt' => $value], false);
                }
            }
        }
        /**/

        return $result;
    }

    /**/
    function getKategorijeRazvrsti($parent, $telo = 0)
	{
        $data = [];
        $data['query'] = " SELECT * FROM program_kategorija ";
        $data['where'] = " and parent = ".$parent." ";
        $data['orderby'] = " order by sort";

        return parent::getList('program_kategorija', $data);

        //return $this->dal->getKategorijeRazvrsti($parent, $telo);
    }

    function getKategorijeRazvrstiIzpis($parent, $nivo, $id, $telo = 0)
    {
        $rows = $this->getKategorijeRazvrsti($parent, $telo);
        if($rows)
        {
            foreach ($rows as $key => $value)
            {
                $value = (object) $value;
                //print_r($value);
                if($nivo == 1 && $key > 0)
                {
                    ?><hr /><?php
                }
                ?><div class="nastavitev_kategorije_box_main<?php echo $nivo; ?>"><?php
                $this->handler->html_checkbox(['label' => $value->naziv, 'name' => 'kategorija['.$value->id.']', 'status' => ($this->getArtikelKategorija($value->id, $id))]);
                ?></div><?php

                $children = $this->getKategorijeRazvrsti($value->id, $telo);
                if($children)
                {
                    $this->getKategorijeRazvrstiIzpis($value->id, $nivo+1, $id, $telo);
                }
            }
        }
    }

    function getArtikelKategorija($kat_id, $art_id)
	{
        $data = [];
        $data['where'] = " and id_kategorija = ".$kat_id." and id_program = ".$art_id." ";
        //if(DE) print_r($data);
        $result = parent::getList('program_kategorija_mm', $data);
        if(!empty($result[0]))
        {
            return $result;
        }

        return null;
        //return $this->dal->getArtikelKategorija($kat_id, $art_id);
    }

	function shraniRavrstitev($post)
	{
        parent::query("delete from program_kategorija_mm where id_program = ".$post['id'].";");
    	$checkbox=$post['kategorija'];

	    //izdelki_kategorije
	    if(is_array($checkbox))
	    {
			foreach ($checkbox as $key => $check)
			{
                if($check == 1)
                {
    		        $data['id_program'] = $post['id'];
    		        $data['id_kategorija'] = $key;
                    //print_r($data);

    				$insert = parent::update('program_kategorija_mm', $data);
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
}  
        