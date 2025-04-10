<?php
        class TestimonialsBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'testimonials';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'testimonials';
            public $audit_message_single = 'testimonials';

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
                $data['query'] = "SELECT * FROM testimonials";
                $data['orderby'] = ' order by testimonials.status desc, created_at desc';
                $result = parent::getList($this->table, $data);
                return $result;
            }
        }  
        