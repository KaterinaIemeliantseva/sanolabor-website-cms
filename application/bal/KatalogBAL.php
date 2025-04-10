<?php
        class KatalogBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'katalog';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'katalog';
            public $audit_message_single = 'katalog';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        