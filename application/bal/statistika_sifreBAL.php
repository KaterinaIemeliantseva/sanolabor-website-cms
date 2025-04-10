<?php
        class statistika_sifreBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'statistika_sifre';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'statistika_sifre';
            public $audit_message_single = 'statistika_sifre';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        