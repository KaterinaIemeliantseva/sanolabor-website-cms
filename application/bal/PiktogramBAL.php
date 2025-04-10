<?php
        class PiktogramBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'piktogram';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'piktogram';
            public $audit_message_single = 'piktogram';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        