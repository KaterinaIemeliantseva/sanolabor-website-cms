<?php
        class SvetujemoKategorijaBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'svetujemo_kategorija';
            var $user;

            public $audit = false; 
            public $audit_message_list = 'svetujemo_kategorija';
            public $audit_message_single = 'svetujemo_kategorija';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        