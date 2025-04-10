<?php
$app->get('/s2', function () use ($app) {
    $json = new \library\Json($app);

    $req = $app->request();
    $_type = $req->get('_type');

    if(!$json->checkAjaxToken($req->get('token')))
    {
        $json->deny();
        return;
    }

    $data['search'] = (!empty($req->get('search')) ? $req->get('search') : '');

    //$request = new artikelKategorijaBAL();
    //$result = $request->getKategorijeSelect($search);

    $classname = $req->get('c').'BAL';
    $request = new $classname;
    $methodname = $req->get('m');
    $result = $request->$methodname($data);

    array_unshift($result, array('id' => 0, 'text' => '-'));
    $json->plain(array('results' => $result));
});

$app->get('/select2', function () use ($app) {
    $json = new \library\Json($app);

    $req = $app->request();
    $_type = $req->get('_type');

    if(!$json->checkAjaxToken($req->get('token')))
    {
        $json->deny();
        return;
    }

    $data['search'] = (!empty($req->get('search')) ? $req->get('search') : '');
    $data['table'] = (!empty($req->get('table')) ? $req->get('table') : '');
    $data['where'] = (!empty($req->get('where')) ? $req->get('where') : '');



    //print_r($data);

    $request = new BaseBAL();
    $result = $request->select2prepare($data);

    array_unshift($result, array('id' => 0, 'text' => '-'));
    $json->plain(array('results' => $result));
});

$app->post('/select2Single', function () use ($app) {
    $json = new \library\Json($app);

    $req = $app->request();
    parse_str($app->request->getBody(), $post_data);

    // if(!$json->checkAjaxToken($req->get('token')))
    // {
    //     $json->deny();
    //     return;
    // }

    $id = (!empty($post_data['id']) ? $post_data['id'] : '');
    $table = (!empty($post_data['table']) ? $post_data['table'] : '');

 

    //$data['table'] = (!empty($req->get('table')) ? $req->get('table') : '');

    $request = new BaseBAL();
    $result = $request->getSingle($table, $id);

    $json->success($result);
});


$app->post('/select2List', function () use ($app) {
    $json = new \library\Json($app);
    parse_str($app->request->getBody(), $post_data);

    $req = $app->request();
    //$_type = $post_data['_type'];


    // if(!$json->checkAjaxToken($post_data['token']))
    // {
    //     $json->deny();
    //     return;
    // }

    $id = (!empty($post_data['id']) ? $post_data['id'] : '');
    $table1 = (!empty($post_data['table1']) ? $post_data['table1'] : '');
    $table2 = (!empty($post_data['table2']) ? $post_data['table2'] : '');
    $field1 = (!empty($post_data['field1']) ? $post_data['field1'] : '');
    $field2 = (!empty($post_data['field2']) ? $post_data['field2'] : '');
    $where = (!empty($post_data['where']) ? $post_data['where'] : '');

    $request = new BaseBAL();
    
    $tags = $request->getList($table1, [
        'query' => ' SELECT '.$table2.'.id, '.$table2.'.naziv FROM '.$table1.'
        inner join '.$table2.' on '.$table2.'.id = '.$table1.'.'.$field1.' and '.$table1.'.'.$field2.' = '.$id.' ',
        'where' => $where,
        'orderby' => 'order by id asc']);
       
    $result =  $request->getKategorijeSelect($tags);

    $json->success($result);
});