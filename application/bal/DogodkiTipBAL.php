<?php
        class DogodkiTipBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'dogodki_tip';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'dogodki_tip';
            public $audit_message_single = 'dogodki_tip';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        