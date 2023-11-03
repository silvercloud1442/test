<?php
// получиьт данные из коллекции по фильтру
function get_items($collection, $filter){
    $result = $collection->find($filter);
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
        echo $value;
        // if (mb_strpos($value, ':') === false){}
        if (mb_strpos($value, '(') == false){
            $converted = floatval($value);
            if ($converted == $value){
                $params[$key] = $converted;
            } else {
                $params[$key] = $value;
            }
        }
        // пример свойств properties=(property, gte123, lte432;porperty,gte111)
        else {
            $sub = substr($value, 1, -1);
            echo($sub);
            $value = explode(';', $sub);
            var_dump($value);
            foreach($value as $property_with_values){
                
                $property_with_values = explode(',', $property_with_values);
                $property = $property_with_values[0];
                $values = array_slice($property_with_values, 1);
            }
            
        }
        
    }
    return $params;
}

function pivo($collection){
    $filter = [
        '$and' => [
            [
                'sockets' => 5
            ],
            [
                'base_type' => 'body_armour'
            ],
            [
                'properties' => [
                    '$elemMatch' => [
                        'property' => '+#% to Fire Resistance',
                        'values' => ['$gte' => 10, '$lte' => 12]
                    ]
                ]
            ],
            [
                'properties' => [
                    '$elemMatch' => [
                        'property' => '#% increased Stun and Block Recovery',
                        'values' => ['$gte' => 10]
                    ]
                ]
            ]
            // Добавьте дополнительные условия для других полей "property", если необходимо
        ]
    ];

    $res = $collection->find($filter);
    foreach ($res as $document){
        echo json_encode($document);
    }
    die();
}

function pivo2($url){
/*$url = "http://test?sockets=5&base_type=body_armour&properties=(property1,gte123,lte432;property2,gte111)";*/

    // Разбиваем URL на части, чтобы получить параметры
    $urlParts = parse_url($url);
    parse_str($urlParts['query'], $queryParams);
    
    // Инициализируем пустой фильтр
    $filter = ['$and' => []];
/*
var_dump($queryParams);
die();
// Обрабатываем параметры
if (isset($queryParams['sockets'])) {
    $filter['sockets'] = (int)$queryParams['sockets'];
}

if (isset($queryParams['base_type'])) {
    $filter['base_type'] = $queryParams['base_type'];
}*/

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
        $properties = $queryParams['properties'];
        $parts = explode(';', $properties);
    
        foreach ($parts as $part) {
            // Разбиваем каждую часть на id и значение с помощью разделителя ,
            list($id, $value) = explode(',', $part);
            
            // Создаем элемент фильтра для каждой части
            $element = [
                'properties' => [
                    '$elemMatch' => [
                        'id' => $id,
                        'values' => ['$gte' => intval($value)]
                    ]
                ]
            ];
            
            // Добавляем элемент в массив $and
            $filter['$and'][] = $element;
        }
    }
    var_dump($filter);
    return $filter;
}