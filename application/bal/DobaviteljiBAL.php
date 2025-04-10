<?php
    class DobaviteljiBAL extends BaseBAL
    {
        public $dal;
        public $handler;
        public $table = 'dobavitelj_users';
        var $user;

        public $audit = false; 
        public $audit_message_list = 'dobavitelj_users';
        public $audit_message_single = 'dobavitelj_users';

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
            $data['array_search'] = ['dobavitelj_users.name', 'dobavitelj_users.username'];
          
            return parent::getList($this->table,$data);
        }

        function update($data)
        {
            if(!empty($data['password']))
            {
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            }
            else
            {
                unset($data['password']);
            }

            if($this->checkIfExist($data['id']) == false || empty($data['id']))
            {
                $data['dobavitelj_id'] = $this->getMaxId()['max_id'] + 1;
            } else {
                if($this->check_dobavitelj_id($data['id']) == false)
                {
                    $data['dobavitelj_id'] = $this->getMaxId()['max_id'] + 1;
                }
                
            }

            return parent::update($this->table, $data);
        }

        function getMaxId()
        {
            $sql = "SELECT MAX(dobavitelj_id) as max_id FROM dobavitelj_users";
            $stmt = $this->db->link_id->prepare($sql);
            $stmt->execute();
            return $stmt->fetch();
        }

        function checkIfExist($id)
        {
            $sql = "SELECT COUNT(*) FROM dobavitelj_users WHERE id = :id";
            $stmt = $this->db->link_id->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->fetch();
            
            if($count >= 1) {
                return true;
            } else {
                return false;
            }
        }

        function check_dobavitelj_id($id)
        {
            $sql = "SELECT dobavitelj_id FROM dobavitelj_users WHERE id = :id";
            $stmt = $this->db->link_id->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $dobavitelj_id = $stmt->fetch();

            if($dobavitelj_id['dobavitelj_id'] == null || empty($dobavitelj_id['dobavitelj_id'])) {
                return false;
            } else {
                return true;
            }
        }

    }  
        