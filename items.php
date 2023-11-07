<?php
function main_items($db, $url){
    $collection = $db->items;

    $filter = filter_from_url($url);
    get_items($collection, $filter);
}

// получиьт данные из коллекции по фильтру
function get_items($collection, $filter){
    $result = $collection->find($filter);
    foreach ($result as $document){
        echo json_encode($document);
    }
    $count = count(iterator_to_array($result));
    if($count === 0){except_empty_result();}
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
        if($param != 'properties'){
            $value = $queryParams[$param];
            $converted = floatval($value);
            if ($converted == $value){
                $filter[$param] = ['$gte' => $converted];
            } else {
                $filter[$param] = $value;
            }
        }
    }

    if (isset($queryParams['properties'])) {
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
        
            $elemMatch = [
                'id' => $id,
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
    return $filter;
}
