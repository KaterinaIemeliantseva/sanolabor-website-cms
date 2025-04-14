<?php
class AdministracijaBAL extends BaseBAL
{
	public $dal;
	public $handler;
	var $user;
    public $scale;
    public $table;
    public $audit = true; 
    public $audit_message_list = 'Seznam administratorjev'; 
    public $audit_message_single = 'Administrator'; 

	function __construct()
	{
		global $user;

        $this->table = 'cms_user';

        $this->dal = new BaseDAL();
        $this->user = $user;
        $this->handler = new SuperClass();
    }
    
    function getList($data = [])
    {
 
        $data['array_search'] = ['username', 'ime_priimek'];
        $data['orderby'] = 'order by ime_priimek asc';
        
        return parent::getList($this->table, $data);
    }


    function save($data)
	{
        if(!empty($data['password']))
        {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        else
        {
            unset($data['password']);
        }

        return parent::update($this->table, $data);
	}

    function getKategorijeRazvrsti($parent)
	{
        return parent::getList('cms_menu', ['where' => ' and parent = '.(int)$parent.' and status = 1']);
    }

    function getKategorijeRazvrstiIzpis($parent, $nivo, $id)
    {
        $rows = $this->getKategorijeRazvrsti($parent);
        if($rows)
        {
            foreach ($rows as $key => $value)
            {
                if($nivo == 1 && $key > 0)
                {
                    ?><hr /><?php
                }
                $value = (object) $value;
                ?><div class="nastavitev_kategorije_box_main<?php echo $nivo; ?>"><?php
                $this->handler->html_checkbox(['label' => $value->naziv, 'name' => 'kategorija['.$value->id.']', 'status' => ($this->getuporabnikDostopMenu($value->id, $id))]);
                ?></div><?php

                $children = $this->getKategorijeRazvrsti($value->id);
                if($children)
                {
                    $this->getKategorijeRazvrstiIzpis($value->id, $nivo+1, $id);
                }
            }
        }
    }

    function getuporabnikDostopMenu($kat_id, $art_id)
	{
        return parent::getList('cms_user_dostop', ['where' => ' and id_kategorija = '.(int)$kat_id.' and id_item = '.(int)$art_id]);
    }

	function shraniRavrstitev($post)
	{
       // print_r($post);
        $t = parent::query("delete from cms_user_dostop where id_item = ".$post['id'].";");
    	$checkbox=$post['kategorija'];

	    //izdelki_kategorije
	    if(is_array($checkbox))
	    {
			foreach ($checkbox as $key => $check)
			{
                if($check == 1)
                {
                    $data = [];
    		        $data['id_item'] = $post['id'];
                    $data['id_kategorija'] = $key;
                    //print_r($data);
    				$insert = parent::potrdiSpremembeCMS('cms_user_dostop', $data, false); 
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
