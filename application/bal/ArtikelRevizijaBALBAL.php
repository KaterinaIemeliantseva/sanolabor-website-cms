<?php
        class ArtikelRevizijaBALBAL extends BaseBAL
        {
            public $dal;
            public $handler;
            public $table = 'artikel_revizija';
            var $user;

            public $audit = true; 
            public $audit_message_list = 'artikel_revizija';
            public $audit_message_single = 'artikel_revizija';

            function __construct()
            {
                global $user;

                $this->dal = new BaseDAL();
                $this->user = $user;
                $this->handler = new SuperClass();
            }
            /**************************************************************************/

        }  
        