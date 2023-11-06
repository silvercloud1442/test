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