<?php
        class VprasanjaBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'vprasanja';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'vprasanja';
            public $audit_message_single = 'vprasanja';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        