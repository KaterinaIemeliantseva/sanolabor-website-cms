<?php

$app->get('/', function () use ($app) {
    $json = new \library\Json($app);
    $json->deny();
});

$app->group('/webapp', function () use ($app) {
    $app->get('/', function () use ($app) {
        $json = new \library\Json($app);
        $json->deny();
    });

    $app->get('/test1', function () use ($app) {
        $json = new \library\Json($app);
        $data = [];
        $data['fds'] = 2313;
        $json->error($data);
    });


    

    require 'routes/select2.php';
    require 'routes/base.php';
    require 'routes/nastavitve.php';
    require 'routes/artikel.php';
    require 'routes/vsebina.php';
    require 'routes/enote.php';
    require 'routes/proizvajalci.php';
    require 'routes/svetovanje.php';
    require 'routes/kontakt.php';
});
