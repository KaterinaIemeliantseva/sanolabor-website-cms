<?php
        class AttributeTypeBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'artikel_parameter_tip';
            var $user;

            public $audit = false; 
            public $audit_message_list = 'artikel_parameter_tip';
            public $audit_message_single = 'artikel_parameter_tip_single';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        