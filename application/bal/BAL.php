<?php
        class BAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'statistika';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'statistika';
            public $audit_message_single = 'statistika';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        