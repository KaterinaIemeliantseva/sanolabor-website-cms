<?php
        class NoviceBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'novice';
            var $user;

            public $audit = false; 
            public $audit_message_list = 'novice';
            public $audit_message_single = 'novice';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        