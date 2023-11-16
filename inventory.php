<?php
function get_user_items($db, $url){
    
    check_url($url);
    $urlParts = parse_url($url);
    parse_str($urlParts['query'], $queryParams);

    if(!isset($queryParams['user']))
    {
        except_invalid_args();
    }
    $user_id = $queryParams['user'];

    $res = $db->items->find(['user' => new MongoDB\BSON\ObjectID($user_id)]);
    foreach($res as $doc){
        echo json_encode($doc);
    }
}