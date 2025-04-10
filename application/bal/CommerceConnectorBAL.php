<?php
        class CommerceConnectorBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'commerce_connector';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'commerce_connector';
            public $audit_message_single = 'commerce_connector';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        