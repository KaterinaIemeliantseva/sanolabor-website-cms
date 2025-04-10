<?php
        class commerce_conn_pixelBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'commerce_conn_pixel';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'commerce_conn_pixel';
            public $audit_message_single = 'commerce_conn_pixel';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        