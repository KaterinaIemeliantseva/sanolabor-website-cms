<?php
        class DokumentBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'dokument';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'dokument';
            public $audit_message_single = 'dokument';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        