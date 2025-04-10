<?php
include (ROOT . DS . 'application' . DS . 'bal' . DS . 'BaseBAL.php');

class PrijavaBAL extends BaseBAL
{
    function __construct()
    {

    }

    function preveri($post)
    {
        global $user;

        if ( isset($post['uname']) && isset($post['pwd']))
        {
            if(isset($post['remember'])) $rem = $post['remember']; else $rem = '';

            if (!$user->login($post['uname'],$post['pwd'], $rem ))
            {
                echo 'false';
            }
            else {
                echo 'true';
            }
        }
    }
}
