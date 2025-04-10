<?php
        class CsvDokumentBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'espremnica';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'espremnica';
            public $audit_message_single = 'espremnica';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        