<?php
namespace library;

class Json
{
    private $app;

    function __construct($app)
    {
        $this->app = $app;
    }

    function hash_equals($str1, $str2)
    {
        if(strlen($str1) != strlen($str2))
        {
            return false;
        }
        else
        {
            $res = $str1 ^ $str2;
            $ret = 0;
            for($i = strlen($res) - 1; $i >= 0; $i--)
            {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }

    function plain($data)
    {
        $response = $this->app->response();
        $response['Content-Type'] = 'application/json; charset=utf-8';
        $response->status(200);

        $response->body(json_encode($data));
    }

    function success($data)
    {
        $response = $this->app->response();
        $response['Content-Type'] = 'application/json; charset=utf-8';
        $response->status(200);

        $response->body(json_encode(['success' => true,'data' => $data]));
    }

    function error($data)
    {
        $response = $this->app->response();
        $response['Content-Type'] = 'application/json; charset=utf-8';
        $response->status(400);
        $response->body(json_encode(['success' => false,'data' => $data]));
    }

    function deny()
    {
        $response = $this->app->response();
        return $response->status(403);
    }

    function checkAjaxToken($token)
    {
        if (!isset($_SESSION['token']) ||  (isset($_SESSION['token']) && !$this->hash_equals($_SESSION['token'], $token)))
        {
           // return false;
        }

        return true;
    }

    function checkAdmin()
    {
        if (empty($_SESSION['userSessionValue']))
        {
            $this->deny();
            return false;
        }

        return true;
    }

    function denyBrowser()
    {
        $isRunningFromBrowser = !isset($GLOBALS['argv']);
        if($isRunningFromBrowser)
        {
            $this->deny();
            return false;
        }

        return true;
    }

    function checkToken($token)
    {
        // $uuid = API_SEC_KEY;
        // if($token != $uuid)
        // {
        //     $this->deny();
        //     return false;
        // }

        return true;
    }
}
