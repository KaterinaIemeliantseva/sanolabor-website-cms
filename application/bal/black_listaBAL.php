<?php
        class black_listaBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'black_lista';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'black_lista';
            public $audit_message_single = 'black_lista';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        