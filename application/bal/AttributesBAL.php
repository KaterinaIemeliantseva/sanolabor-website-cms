<?php
        class AttributesBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'artikel_parameter';
            var $user;

            public $audit = false; 
            public $audit_message_list = 'artikel_parameter';
            public $audit_message_single = 'artikel_parameter_single';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        