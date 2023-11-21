<?php

function main_items($db, $url){
    $collection = $db->items;

    $filter = filter_from_url($url);
    get_items($collection, $filter);
}

// получиьт данные из коллекции по фильтру
function get_items($collection, $filter){
    $result = $collection->find($filter);
    $c = 0;
    echo '[';
    foreach ($result as $document){
        echo json_encode($document);
        echo ',';
        $c++;
    }
    echo ']';
    if($c === 0){except_empty_result();}
}

// создать словарь параметров
function filter_from_url($url){

    // Разбиваем URL на части, чтобы получить параметры
    check_url($url);
    $urlParts = parse_url($url);
    parse_str($urlParts['query'], $queryParams);
    
    // Инициализируем пустой фильтр
    $filter = [];

    foreach(array_keys($queryParams) as $param){
        if($param != 'properties' && $param != 'price'){
            $value = $queryParams[$param];
            $converted = floatval($value);
            if ($converted == $value){
                $filter[$param] = ['$gte' => $converted];
            } else {
                $filter[$param] = $value;
            }
        }
    }
    if(!isset($queryParams['price'])) {
        $filter['price'] = ['$exists' => true];
    }
    else{
        $f = false;
        $price = substr($queryParams['price'], 1 ,-1);
        $subparts = explode(',', $price);
        $conditions = [];
        foreach ($subparts as $condition) {
            list($key, $value) = explode(':', $condition);
            $conditions[$key] = $value;
        }
        if (isset($conditions['gte'])) {
            $elemMatch['$gte'] = (int)$conditions['gte'];
            $f = true;
        }
    
        if (isset($conditions['lte'])) {
            $elemMatch['$lte'] = (int)$conditions['lte'];
            $f = true;
        }
        if(!$f)
        {
            $filter['price'] = ['$exists' => true];
        }
        else{
            $filter['price'] = $elemMatch;
        }
        var_dump($filter['price']);
    }

    if (isset($queryParams['properties'])) {
        if($queryParams['properties'] != "()"){
            $properties = substr($queryParams['properties'], 1 ,-1);
            $parts = explode(';', $properties);
        
            foreach ($parts as $part) {
                // Разбиваем каждую часть на ID и условия
                $subparts = explode(',', $part);
                $id = $subparts[0];
            
                $conditions = [];
                foreach (array_slice($subparts, 1) as $condition) {
                    list($key, $value) = explode(':', $condition);
                    $conditions[$key] = $value;
                }
                
                $objectId = new MongoDB\BSON\ObjectID($id);
                $elemMatch = [
                    'id' => $objectId,
                ];
            
                if (isset($conditions['gte'])) {
                    $elemMatch['values']['$gte'] = (int)$conditions['gte'];
                }
            
                if (isset($conditions['lte'])) {
                    $elemMatch['values']['$lte'] = (int)$conditions['lte'];
                }
            
                $filter['$and'][] = ['properties' => ['$elemMatch' => $elemMatch]];
            }
        }
    }
    return $filter;
}