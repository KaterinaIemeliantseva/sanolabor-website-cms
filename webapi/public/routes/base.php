<?php
$app->group('/base', function () use ($app) {
    $app->get('/test2', function () use ($app) {
        $json = new \library\Json($app);
        $data = [];
        $data['fds'] = 2313;
        $json->error($data);
    });

    $app->post('/get-list', function () use ($app) {
        $json = new \library\Json($app);
        parse_str($app->request->getBody(), $post_data);
        $token = (!empty($post_data['token'])) ? $post_data['token'] : '';
        if(!$json->checkAjaxToken($token) && !$json->checkToken($token))
        {
            $json->deny();
            return;
        }

        $classname = $post_data['c'].'BAL';
        $request = new $classname;

        $query = [];
        if(!empty($post_data['search']['value']))
        {
            $query['search'] = $post_data['search']['value'];
        }

        if(!empty($post_data['query_params']))
        {
            $where = '';
            foreach ($post_data['query_params'] as $key => $item) 
            {
                if(empty($item['name']) || empty($item['value']))
                {
                    continue;
                }

                if(strpos($item['name'], 'custom_') !== false)
                {
                    $query[$item['name']] = $item['value'];
                    continue;
                }

                $operator = (!empty($item['operator'])) ?  $item['operator'] : ' = ';
                $where .= " and ".$item['name']." ".$operator." '".$item['value']."' ";
            }

            $query['where'] = $where;
        }

        //echo $query['where'];
        // 
        $all_data = $request->getList($query);
        $total = count($all_data);
        // if(DE) $post_data['length'] = 50;
        if($post_data['length'] == -1)
        {
            $post_data['length'] = null;
        }
        
        $sliced_data = array_slice($all_data, $post_data['start'], $post_data['length']);

        $result = array(
			"draw"  => $post_data['draw'],
			"recordsTotal"    => intval( $total ),
			"recordsFiltered" => intval( $total ),
			"data"            => $sliced_data
        );

        
       
        
        echo json_encode($result);

        //audit
        if(!empty($post_data['audit']) || !empty($request->audit))
        {
            //$time_start = microtime(true);

            if($sliced_data)
            {
                $audit_entries['Entries'] = [];

                $i = 0;
                foreach ($sliced_data as $key => $item) 
                {
                    if($item)
                    {
                        foreach ($item as $field_name => $value_item) 
                        {
                            $audit_entries['Entries'][] = ['FieldName' => $field_name, 'TrailType' => 2, 'OldValue' => $value_item, 'NewValue' => '', 'OldDescription' => '', 'NewDescription' => '', 'TableName' => $request->table];
                        }
                    }

                    
                    if($i > 10)
                    {
                        break;
                    }

                    $i++;
                }

                $GLOBALS['audit_config']['UserLoginID'] = $_SESSION['userSessionValue'];
                $GLOBALS['audit_config']['UserLoginName'] = (!empty($_SESSION['userSessionName'])) ? $_SESSION['userSessionName'] : 'anon';
                $GLOBALS['audit_config']['Message'] = (!empty($post_data['audit_message'])) ? $post_data['audit_message'] : $request->audit_message_list;
                $GLOBALS['audit_config']['Code'] = 'LIST';
                $result = Audit::add($GLOBALS['audit_config'], $audit_entries);
            }

            // if(DE)
            // {
            //     $time_end_ws = microtime(true); echo  number_format($time_end_ws-$time_start,3);
            // }
        }

    });

    $app->post('/get', function () use ($app) {
        $json = new \library\Json($app);
        parse_str($app->request->getBody(), $post_data);
        $token = (!empty($post_data['token'])) ? $post_data['token'] : '';
        if(!$json->checkAjaxToken($token) && !$json->checkToken($token))
        {
            $json->deny();
            return;
        }

        $classname = $post_data['c'].'BAL';
        $request = new $classname;
        $seznam = array();
        if(!empty($post_data['id']))
        {
            $seznam = $request->getSingle($post_data['id']);
        }
        else
        {
            $seznam = $request->getList();
        }

        $json->success($seznam);
    });

    $app->post('/update', function () use ($app) {
        $json = new \library\Json($app);
        //parse_str($app->request->getBody(), $post_data);
        $post_data = $app->request->post();
        $token = (!empty($post_data['token'])) ? $post_data['token'] : '';


        //$json->success($post_data); return;


        if(!$json->checkAjaxToken($token) && !$json->checkToken($token))
        {
            $json->deny();
            return;
        }

        $classname = $post_data['c'].'BAL';
        $methodname = false;
        $request = new $classname;

        if(isset($post_data['token'])) unset($post_data['token']);
        if(isset($post_data['c'])) unset($post_data['c']);
        if(isset($post_data['m']))
        {
            $methodname = $post_data['m'];
            unset($post_data['m']);
        }

        $data = (isset($post_data['data'])) ? $post_data['data'] : $post_data;

        $result = ($methodname) ? $request->$methodname($data) : $request->update($data);
        if($result)
        {
            $json->success($result);
        }
        else
        {
            $json->error('Napaka!');
        }
    });

    $app->post('/delete', function () use ($app) {
        $json = new \library\Json($app);
        parse_str($app->request->getBody(), $post_data);
        $token = (!empty($post_data['token'])) ? $post_data['token'] : '';

        if(!$json->checkAjaxToken($token) && !$json->checkToken($token))
        {
            $json->deny();
            return;
        }

        $classname = $post_data['c'].'BAL';
        $request = new $classname;

        if(isset($post_data['token'])) unset($post_data['token']);
        if(isset($post_data['c'])) unset($post_data['c']);
        if(isset($post_data['m'])) unset($post_data['m']);

        $data = (isset($post_data['data'])) ? $post_data['data'] : $post_data;

        $result = $request->delete($data);
        if($result)
        {
            $json->success(true);
        }
        else
        {
            $json->error('Napaka!');
        }
    });


    $app->post('/call', function () use ($app) {
        $json = new \library\Json($app);
        parse_str($app->request->getBody(), $post_data);
        $token = (!empty($post_data['token'])) ? $post_data['token'] : '';
        if(!$json->checkAjaxToken($token) && !$json->checkToken($token))
        {
            $json->deny();
            return;
        }

        $classname = $post_data['c'].'BAL';
        $methodname = $post_data['m'];
        $request = new $classname;

        if(isset($post_data['token'])) unset($post_data['token']);
        if(isset($post_data['c'])) unset($post_data['c']);
        if(isset($post_data['m'])) unset($post_data['m']);

        $s2 = false;
        if(isset($post_data['s2']))
        {
            $s2 = true;
            unset($post_data['s2']);
        }

        $data = (isset($post_data['data'])) ? $post_data['data'] : $post_data;

        $result = $request->$methodname($data);

        if(isset($post_data['export_data']))
        {
            return $result;
        }
        
        if(($result || ($result === array())) )
        {
            if($s2)
            {
                $json->plain($result);
            }
            else
            {
                $json->success($result);
            }
        }
        else
        {
            $json->error('Napaka!');
        }
    });



    $app->post('/uploadImage', function () use ($app) {
        $json = new \library\Json($app);

        $req = $app->request();
        $dir = $req->get('dir');

        $options = array('upload_dir'=>ROOT.DS.'files'.DS.$dir.DS, 'upload_url'=>'/files/'.$dir.'/');
        // if(DE)
        // {
        //     print_r($options); die();
        // }
        //
        $upload_handler = new UploadHandler($options);
    });

    $app->post('/product-revison', function () use ($app) {
        $json = new \library\Json($app);

        $req = $app->request();
        $post_data = $req->post();
        $data = $post_data['data'];

        $bal = new ArtikelRevizijaBAL();
        if(!$json->checkAjaxToken($req->get('token')))
        {
            $json->deny();
            return;
        }

        if($data['action'] == 'reject'){
            $result = $bal->rejectProduct($data['id'], $data['comment']);
            echo json_encode($result);
        }
        if($data['action'] == "approve"){
            $result = $bal->approveProduct($data['id'], $data['parameters']);
            echo json_encode($result);
        }
    });


    $app->post('/save-category-card', function () use ($app) {
        $json = new \library\Json($app);

        $req = $app->request();
        $post_data = $req->post();
        $data = $post_data['data'];

        $bal = new ArtikelKategorijaBAL();
        if(!$json->checkAjaxToken($req->get('token')))
        {
            $json->deny();
            return;
        }

        if($data['action'] == 'create') {
            $result = $bal->createCategoryCard($data);
        }
        if($data['action'] == 'edit') {
            $result = $bal->editCategoryCard($data);
        }
        if($data['action'] == 'delete') {
            $result = $bal->deleteCategoryCard($data);
        }
        echo json_encode($result);
        

    });

    $app->post('/get-category-card', function () use ($app) {
        $json = new \library\Json($app);

        $req = $app->request();
        $post_data = $req->post();
        $data = $post_data['data'];

        $bal = new ArtikelKategorijaBAL();
        if(!$json->checkAjaxToken($req->get('token')))
        {
            $json->deny();
            return;
        }

        $result = $bal->getCard($data);
        echo json_encode($result);
    });

    $app->post('/saveModule', function () use ($app) {
        $json = new \library\Json($app);
        $post_data = $app->request->post();
        $token = (!empty($post_data['token'])) ? $post_data['token'] : '';

        if(!$json->checkAjaxToken($token) && !$json->checkToken($token)) {
            $json->deny();
            return;
        }

        $moduleTitle = $post_data['moduleTitle'];
        $categoryId = $post_data['categoryId'];
        $moduleOrder = $post_data['moduleOrder'];

        $bal = new ArtikelKategorijaBAL();
        $result = $bal->createModule($moduleTitle, $categoryId, $moduleOrder);

        if($result) {
            // Fetch the newly created module
            $newModule = $bal->getModuleById($result);
            $json->success(['module' => $newModule]);
        } else {
            $json->error('Error saving module.');
        }
    });

    $app->post('/getModule', function () use ($app) {
        $json = new \library\Json($app);
        $post_data = $app->request->post();
        $token = (!empty($post_data['token'])) ? $post_data['token'] : '';

        if(!$json->checkAjaxToken($token) && !$json->checkToken($token)) {
            $json->deny();
            return;
        }

        $moduleId = $post_data['moduleId'];

        $bal = new ArtikelKategorijaBAL();
        $module = $bal->getModuleById($moduleId);

        if($module) {
            $json->success(['module' => $module]);
        } else {
            $json->error('Modul ni najden.');
        }
    });

    $app->post('/updateModule', function () use ($app) {
        $json = new \library\Json($app);
        $post_data = $app->request->post();
        $token = (!empty($post_data['token'])) ? $post_data['token'] : '';

        if(!$json->checkAjaxToken($token) && !$json->checkToken($token)) {
            $json->deny();
            return;
        }

        $moduleId = $post_data['moduleId'];
        $moduleTitle = $post_data['moduleTitle'];
        $moduleOrder = $post_data['moduleOrder'];

        $bal = new ArtikelKategorijaBAL();
        $result = $bal->updateModule($moduleId, $moduleTitle, $moduleOrder);

        if($result) {
            $module = $bal->getModuleById($moduleId);
            $json->success(['module' => $module]);
        } else {
            $json->error('Napaka pri posodabljanju modula.');
        }
    });

    $app->post('/deleteModule', function () use ($app) {
        $json = new \library\Json($app);
        $post_data = $app->request->post();
        $token = (!empty($post_data['token'])) ? $post_data['token'] : '';

        if(!$json->checkAjaxToken($token) && !$json->checkToken($token)) {
            $json->deny();
            return;
        }

        $moduleId = $post_data['moduleId'];

        $bal = new ArtikelKategorijaBAL();
        $result = $bal->deleteModule($moduleId);

        if($result) {
            $json->success(true);
        } else {
            $json->error('Napaka pri brisanju modula.');
        }
    });

    $app->post('/saveTile', function () use ($app) {
        $json = new \library\Json($app);

        $req = $app->request();
        $post_data = $req->post();
        $data = json_decode($post_data['data'], true);

        $bal = new ArtikelKategorijaBAL();
        if(!$json->checkAjaxToken($data['token']))
        {
            $json->deny();
            return;
        }

        $result = $bal->createTile($data);

        echo json_encode($result);
    });


    $app->post('/getTile', function () use ($app) {
        $json = new \library\Json($app);
        $post_data = $app->request->post();
        $token = isset($post_data['token']) ? $post_data['token'] : '';

        if (!$json->checkAjaxToken($token) && !$json->checkToken($token)) {
            $json->deny();
            return;
        }

        $tileId = isset($post_data['tileId']) ? $post_data['tileId'] : '';

        $bal = new ArtikelKategorijaBAL();
        $tile = $bal->getTileById($tileId);

        if ($tile) {
            $json->success(['tile' => $tile]);
        } else {
            $json->error('Napaka pri pridobivanju ploščice.');
        }
    });

    $app->post('/updateTile', function () use ($app) {
        $json = new \library\Json($app);

        $req = $app->request();
        $post_data = $req->post();
        $data = json_decode($post_data['data'], true); // Decode the JSON data

        $bal = new ArtikelKategorijaBAL();
        if(!$json->checkAjaxToken($data['token']))
        {
            $json->deny();
            return;
        }

        $result = $bal->updateTile($data);

        echo json_encode($result);
    });


    $app->post('/deleteTile', function () use ($app) {
        $json = new \library\Json($app);
        $post_data = $app->request->post();
        $token = isset($post_data['token']) ? $post_data['token'] : '';

        if (!$json->checkAjaxToken($token) && !$json->checkToken($token)) {
            $json->deny();
            return;
        }

        $tileId = isset($post_data['tileId']) ? $post_data['tileId'] : '';

        $bal = new ArtikelKategorijaBAL();
        $result = $bal->deleteTile($tileId);

        if ($result) {
            $json->success([]);
        } else {
            $json->error('Napaka pri brisanju ploščice.');
        }
    });


    $app->post('/saveIzbranoZaVas', function () use ($app) {
        $json = new \library\Json($app);

        $req = $app->request();
        $post_data = $req->post();
        $data = json_decode($post_data['data'], true);

        $bal = new ArtikelKategorijaBAL();
        if(!$json->checkAjaxToken($data['token'])) {
            $json->deny();
            return;
        }

        $result = $bal->saveIzbranoZaVas($data['categoryId'], $data['selectedArticles']);

        if ($result['success']) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
    });

    $app->get('/getGraphicItem', function () use ($app) {
        $bannerId = $app->request->get('graphicItemId');
        $bal = new ArtikelKategorijaBAL();
        $banner = $bal->getBannerById($bannerId);
        if ($banner) {
            echo json_encode(['success' => true, 'banner' => $banner]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Banner not found']);
        }
    });

    $app->post('/saveBanner', function () use ($app) {
        $json = new \library\Json($app);
        $req = $app->request();
        $post_data = $req->post();
        $data = json_decode($post_data['data'], true);
        $bal = new ArtikelKategorijaBAL();

        // Validate the Ajax token
        if(!$json->checkAjaxToken($data['token'])) {
            $json->deny();
            return;
        }

        // Create the new banner
        $res = $bal->createBanner($data);
        $newBannerId = $res['bannerId'];

        if ($newBannerId) {
            // Fetch the newly created banner data
            $banner = $bal->getBannerById($newBannerId);

            // Return the success response with banner data
            echo json_encode(['success' => true, 'banner' => $banner]);
        } else {
            // Return an error response
            echo json_encode(['success' => false, 'message' => 'Napaka pri shranjevanju bannerja.']);
        }
    });


    $app->post('/updateBanner', function () use ($app) {
        $json = new \library\Json($app);
        $req = $app->request();
        $post_data = $req->post();
        $data = json_decode($post_data['data'], true);
        $bal = new ArtikelKategorijaBAL();
        if(!$json->checkAjaxToken($data['token'])) {
            $json->deny();
            return;
        }

        // Perform the update
        $updateSuccess = $bal->updateBanner($data);

        if ($updateSuccess) {
            // Fetch the updated banner
            $banner = $bal->getBannerById($data['bannerId']);
            echo json_encode(['success' => true, 'banner' => $banner]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Napaka pri posodabljanju bannerja.']);
        }
    });


    $app->post('/deleteBanner', function () use ($app) {
        $json = new \library\Json($app);
        $req = $app->request();
        $post_data = $req->post();
        $data = json_decode($post_data['data'], true);
        $bal = new ArtikelKategorijaBAL();
        if(!$json->checkAjaxToken($data['token'])) {
            $json->deny();
            return;
        }
        $result = $bal->deleteBanner($data['bannerId']);
        echo json_encode($result);
    });

    $app->get('/get-category-cards', function () use ($app) {
        $categoryId = $app->request->get('categoryId');

        $bal = new ArtikelKategorijaBAL();
        $_category_cards = $bal->getCategoryCardsList($categoryId);

        // Generate the HTML table
        $htmlContent = '';
        $htmlContent .= "<table class='main_table' style='margin-top: 10px;'>";
        $htmlContent .= "<thead>";
        $htmlContent .= "<tr>";
        $htmlContent .= "<th>ID</th>";
        $htmlContent .= "<th>Vrstni red</th>";
        $htmlContent .= "<th>Naziv</th>";
        $htmlContent .= "<th>Vsebina ploščice</th>";
        $htmlContent .= "<th>Slika</th>";
        $htmlContent .= "<th>Uredi</th>";
        $htmlContent .= "</tr>";
        $htmlContent .= "</thead>";
        $htmlContent .= "<tbody>";
        foreach($_category_cards as $card) {
            $htmlContent .= "<tr class='table_row'>";
            $htmlContent .= "<td>" . $card->id . "</td>";
            $htmlContent .= "<td>" . htmlspecialchars($card->order) . "</td>";
            $htmlContent .= "<td>" . htmlspecialchars($card->naziv) . "</td>";
            $htmlContent .= "<td>" . htmlspecialchars($card->vsebina) . "</td>";

            $htmlContent .= "<td>";
            if (!empty($card->path)) {
                $htmlContent .= "<img src='/files" . htmlspecialchars($card->path) . "' alt='Card Image' style='max-width: 100px;'>";
            } else {
                $htmlContent .= "Ni slike";
            }
            echo("</td>");

            $htmlContent .= "<td>";
            $htmlContent .= "<button class='btn btn-primary margin-right-3' onclick='editCategoryCard(" . $card->id . ")'>Uredi</button>";
            $htmlContent .= "<button class='btn btn-danger' onclick='deleteCategoryCard(" . $card->id . ")'>Izbriši</button>";
            $htmlContent .= "</td>";
            $htmlContent .= "</tr>";
        }
        $htmlContent .= "</tbody>";
        $htmlContent .= "</table>";

        echo $htmlContent;
    });


});
