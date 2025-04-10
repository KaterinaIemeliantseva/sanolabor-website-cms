<?php
        class statistika_kuponiBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'statistika_kuponi';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'statistika_kuponi';
            public $audit_message_single = 'statistika_kuponi';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        