<?php
function main_login($db, $url){
    $collection = $db->users;
    $user_id = get_user($collection, $url);
    if($user_id){
        echo($user_id);
    }
    else{
        echo 0;
    }
    
}

function main_register($db, $url){
    $collection = $db->users;
    $user_id = set_user($collection, $url);
    if ($user_id != false){
        echo($user_id);
    }
    else{
        echo("Логин занят");
    }
}

function get_money($db, $url){
    check_url($url);
    $urlParts = parse_url($url);
    parse_str($urlParts['query'], $queryParams);

    if (isset($queryParams['user'])){
        $collection = $db->users->find(['_id' => new MongoDB\BSON\ObjectID($queryParams['user'])], ['projection' => ['money' => 1]])->toArray()[0];
        echo json_encode($collection);
    }
    else{
        except_invalid_args();
    }
}

function get_user($collection, $url){
    check_url($url);
    $urlParts = parse_url($url);
    parse_str($urlParts['query'], $queryParams);

    if (isset($queryParams['login']) && isset($queryParams['password'])){
        $login = $queryParams['login'];
        $password = $queryParams['password'];
        
        $filter = ['login' => $login,'password'=> $password];
        $res = $collection->find($filter)->toArray();
        $count = count($res);
        if($count !== 1){
            return false;
        }
        else {
           foreach($res as $doc){
            return (string)$doc['_id'];;
           }
        }
    }
}

function set_user($collection, $url){
    check_url($url);
    $urlParts = parse_url($url);
    parse_str($urlParts['query'], $queryParams);

    if (isset($queryParams['login']) && isset($queryParams['password'])){
        $login = $queryParams['login'];
        $password = $queryParams['password'];

        $filter = ['login' => $login];
        $res = $collection->find($filter)->toArray();
        if(!empty($res)){
            return false;
        }
        else {
            $insertOneResult = $collection->insertOne([
                'login' => $login,
                'password' => $password,
                'money' => 100
            ]);
            foreach($insertOneResult as $doc){
                return (string)$doc['_id'];;
               }
            return true;        
        }
    }
}
