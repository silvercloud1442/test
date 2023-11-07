<?php
function main_login($db, $url){
    $collection = $db->users;
    $user_id = get_user($collection, $url);
    if($user_id){
        var_dump($user_id);
    }
    else{
        except_wrong_login();
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
           return $res[0];
        }
    }
}