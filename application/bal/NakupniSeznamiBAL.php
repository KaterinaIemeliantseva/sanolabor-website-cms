<?php
        class NakupniSeznamiBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'nakupni_seznami';
            var $user;

            function __construct()
            {
                global $user;

                $this->user = $user;
                $this->handler = new SuperClass();
                $this->base = new BaseBAL();
            }
            /**************************************************************************/
            function getList($data = [])
            {
                $data['orderby'] = ' order by nakupni_seznami.status desc, created_at desc ';
                $result = $this->base->getList($this->table, $data);
            
                return $result;
            }
            function getSingle($id)
            {
                return $this->base->getSingle($this->table, $id);
            }
            function delete($data)
            {
                $result =  $this->base->delete($this->table, $data['id']);
                $result = $this->base->query("delete from artikel_povezani where tip = 12 and id_item = ".$data['id'].";");
                return $result;
            }
            function update($data)
            {    
                $povezani_artikli = false;
                if(isset($data['povezani_artikli']))
                {
                    $povezani_artikli = $data['povezani_artikli'];
                    unset($data['povezani_artikli']);
                }
                
                $result = $this->base->update($this->table,$data);
                $id = (!empty($data['id'])) ? $data['id'] : $result;
                
                if($povezani_artikli)
                {
                    $this->base->query("delete from artikel_povezani where tip = 12 and id_item = ".$id.";");
                    foreach($povezani_artikli as $key => $value)
                    {
                        if($value != 0)
                        {                        
                            $this->base->potrdiSpremembeCMS('artikel_povezani',['id_item' => $id, 'id_artikel'=> $value, 'tip'=> 12], false);
                        }
                    }
                }

                return $result;
            }
        }  
        