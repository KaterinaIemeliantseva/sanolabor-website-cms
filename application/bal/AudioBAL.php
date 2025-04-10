<?php
        class AudioBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'audio';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'audio';
            public $audit_message_single = 'audio';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        