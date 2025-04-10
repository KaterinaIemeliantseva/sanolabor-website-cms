<?php
        class PopupBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'popup';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'popup';
            public $audit_message_single = 'popup';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        