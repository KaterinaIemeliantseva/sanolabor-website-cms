<?php
        class ZaposlitevBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'zaposlitev';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'zaposlitev';
            public $audit_message_single = 'zaposlitev';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        