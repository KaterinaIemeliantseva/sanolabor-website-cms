<?php
$app->group('/proizvajalci', function () use ($app) {

    $app->post('/get', function () use ($app) {
        $json = new \library\Json($app);
        parse_str($app->request->getBody(), $post_data);
        $token = (!empty($post_data['token'])) ? $post_data['token'] : '';
        if(!$json->checkAjaxToken($token) && !$json->checkToken($token))
        {
            $json->deny();
            return;
        }

        $request = new ProizvajalciBAL();
        $seznam = $request->getSingle($post_data['id']);

        $json->success($seznam);
    });

    $app->group('/s2', function () use ($app) {
        $app->get('/all', function () use ($app) {
            $json = new \library\Json($app);

            $req = $app->request();
            $_type = $req->get('_type');

            if(!$json->checkAjaxToken($req->get('token')))
            {
                $json->deny();
                return;
            }

            $search = (!empty($req->get('search')) ? $req->get('search') : '');

            $request = new ProizvajalciBAL();
            $result = $request->getAllSelect($search);

            array_unshift($result, array('id' => 0, 'text' => '-'));
            $json->plain(array('results' => $result));
        });
    });
});
