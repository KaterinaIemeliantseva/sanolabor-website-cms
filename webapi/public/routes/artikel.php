<?php
$app->group('/artikel', function () use ($app) {
    $app->group('/s2', function () use ($app) {
        $app->get('/kategorije', function () use ($app) {
            $json = new \library\Json($app);

            $req = $app->request();
            $_type = $req->get('_type');

            if(!$json->checkAjaxToken($req->get('token')))
            {
                $json->deny();
                return;
            }

            $search = (!empty($req->get('search')) ? $req->get('search') : '');

            $request = new artikelKategorijaBAL();
            $result = $request->getKategorijeSelect2($search);

            array_unshift($result, array('id' => 0, 'text' => '-'));
            $json->plain(array('results' => $result));
        });
    });

    
});
