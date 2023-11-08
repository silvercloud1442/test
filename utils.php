<?php

function except_invalid_args($die=true){
    $res = [
        "status" => false,
        "message" => "Incorrect args. Check url"
    ];
    if($die){
        die(json_encode($res));
    }
    return false;
}

function except_empty_result($die=true){
    $res = [
        "status" => false,
        "message" => "Empty result"
    ];
    if($die){
        die(json_encode($res));
    }
    return false;
}

function except_main_error($die=true){
    $res = [
        "status" => false,
        "message" => "Undefiend error. Check url"
    ];
    if($die){
        die(json_encode($res));
    }
    return false;
}

function except_wrong_login($die=true){
    $res = [
    "status" => false,
    "message" => "Wrong login or pasword"
    ];
    if($die){
        die(json_encode($res));
    }
    return false;
}

function check_url($url){
    $parsed_url = parse_url($url, PHP_URL_QUERY);
    
    $query_len_syms = strlen($parsed_url ?? '');                    // проверки на корректные параметры
    if ($query_len_syms === 0){                                     // проверки на корректные параметры
        except_invalid_args();                                             // проверки на корректные параметры
    }                                                               // проверки на корректные параметры
    else {                                                          // проверки на корректные параметры
        $query = explode('&', $parsed_url);                         // проверки на корректные параметры
        $query_len_parts = count($query);                           // проверки на корректные параметры
        if ($query_len_parts === 0){                                // проверки на корректные параметры
            except_invalid_args();                                      // проверки на корректные параметры
        }
        foreach($query as $part){
            if (mb_strpos($part, '=') === false){
                except_invalid_args();
            }
        }
    }
}