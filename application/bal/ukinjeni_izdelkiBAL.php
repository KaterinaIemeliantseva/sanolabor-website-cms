<?php
        class ukinjeni_izdelkiBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'ukinjeni_artikli';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'ukinjeni_artikli';
            public $audit_message_single = 'ukinjeni_artikli';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        