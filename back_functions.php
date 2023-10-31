<?php
// получиьт данные из коллекции по фильтру
function get_items($collection, $params){
    $result = $collection->find($params);
    $c = 0;

    foreach ($result as $document){
        $c++;
        echo json_encode($document);
    }
    if($c === 0){except_empty_result();}
}


// получить параметры из ссылки
function query_from_url($url){
    $parsed_url = parse_url($url, PHP_URL_QUERY);

    $query_len_syms = strlen($parsed_url ?? '');                    // проверки на корректные параметры
    if ($query_len_syms === 0){                                     // проверки на корректные параметры
        return false;                                               // проверки на корректные параметры
    }                                                               // проверки на корректные параметры
    else {                                                          // проверки на корректные параметры
        $query = explode('&', $parsed_url);                         // проверки на корректные параметры
        $query_len_parts = count($query);                           // проверки на корректные параметры
        if ($query_len_parts === 0){                                // проверки на корректные параметры
            return false;                                           // проверки на корректные параметры
        }                                                           // проверки на корректные параметры
        else {                                                      // проверки на корректные параметры
            foreach($query as $part){                               // проверки на корректные параметры
                if (mb_strpos($part, '=') === false){return false;} // проверки на корректные параметры
            }
            return $query;
        }
    }
}


// создать словарь параметров
function params_from_query($query){
    $params = array();
    foreach($query as $part){
        $splited = explode("=", $part);
        $key = $splited[0];
        $value = $splited[1];
        if (mb_strpos($value, ':') === false){
            $converted = floatval($value);
            if ($converted == $value){
                $params[$key] = $converted;
            } else {
                $params[$key] = $value;
            }
        }
    }
    return $params;
}