<?php

class KuponiBAL 
{
    public $dal;
    private $handler;
    private $table = 'kupon';
    var $user;

    function __construct()
    {
        global $user;

        $this->user = $user;
        $this->handler = new SuperClass();
        $this->base = new BaseBAL();
        $this->artikel = new ArtikelBAL();

    }

  /**************************************************************************/
  function getList($data = [])
  {
      $data['where'] = ' and omejitev_koriscenje_enkrat = 0 ';
      $data['orderby'] = 'order by id desc';
      return $this->base->getList($this->table, $data);
  }

  function getSingle($id)
  {
      return $this->base->getSingle($this->table, $id);
  }

  function update($data)
  {
      return $this->base->potrdiSpremembeCMS($this->table, $data, (!empty($data['id'])) ? $data['id'] : false);
  }

  function delete($data)
  {
      return $this->base->delete($this->table, $data['id']);
  }
  /**************************************************************************/

  function save($data)
    {

        $kategorija = [];
        if(isset($data['kategorija']))
        {
            $kategorija = $data['kategorija'];
            unset ($data['kategorija']);
        }
        //povezani artikli
        $povezani_artikli = false;
        if(isset($data['povezani_artikli']))
        {
            $povezani_artikli = $data['povezani_artikli'];
            unset($data['povezani_artikli']);
        }

        //povezani artikli2
        $povezani_artikli2 = false;
        if(isset($data['povezani_artikli2']))
        {
            $povezani_artikli2 = $data['povezani_artikli2'];
            unset($data['povezani_artikli2']);
        }

        //vstavi artikel
        $result = $this->update($data);
        $id = (!empty($data['id'])) ? $data['id'] : $result;

        /**/
        if($povezani_artikli)
        {
            $this->base->query("delete from artikel_povezani where tip = 2 and id_item = ".$id.";");
            foreach ($povezani_artikli as $key => $value)
            {
                $this->base->potrdiSpremembeCMS('artikel_povezani', ['id_item' => $id, 'id_artikel' => $value, 'tip' => 2], false);
            }
        }

        if($povezani_artikli2)
        {
            $this->base->query("delete from artikel_povezani where tip = 10 and id_item = ".$id.";");
            foreach ($povezani_artikli2 as $key => $value)
            {
                $this->base->potrdiSpremembeCMS('artikel_povezani', ['id_item' => $id, 'id_artikel' => $value, 'tip' => 10], false);
            }
        }
        /**/

        $this->shraniRavrstitev(['kategorija' => $kategorija, 'id' => $id]);

        return $result;
    }

    function getKategorijeRazvrsti($parent)
	{
        $data = [];
        $data['query'] = " SELECT * FROM artikel_kategorija ";
        $data['where'] = " and parent = ".$parent."  ";
        $data['orderby'] = " order by sort";

        return $this->base->getList('artikel_kategorija', $data);

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

                
            }

            
        }
    }

    function getArtikelKategorija($kat_id, $art_id)
	{
        $data = [];
        $data['where'] = " and id_kategorija = ".$kat_id." and id_kupon = ".$art_id." ";
        //if(DE) print_r($data);
        $result = $this->base->getList('kupon_kategorija_mm', $data);
        if(!empty($result[0]))
        {
            return $result;
        }

        return null;
        //return $this->dal->getArtikelKategorija($kat_id, $art_id);
    }

    function shraniRavrstitev($post)
	{
        $this->base->query("delete from kupon_kategorija_mm where id_kupon = ".$post['id'].";");
    	$checkbox=$post['kategorija'];

	    //izdelki_kategorije
	    if(is_array($checkbox))
	    {
			foreach ($checkbox as $key => $check)
			{
                if($check == 1)
                {
    		        $data['id_kupon'] = $post['id'];
    		        $data['id_kategorija'] = $key;
                    //print_r($data);

    				$insert = $this->base->update('kupon_kategorija_mm', $data);
                    if(!$insert)
                    {
                        return false;
                    }
                }
			}
	    }


        return true;
	}

}
