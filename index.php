<?php
    require 'vendor/autoload.php';
    require 'functions.php';

    header('Content-Type: application/json');
    $client = new MongoDB\Client('mongodb+srv://user:Tunehemah1@cluster0.ghsbmhw.mongodb.net/?retryWrites=true&w=majority');
    $collection = $client->trade->items;

    $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $parsed_url = parse_url($url, PHP_URL_QUERY);

    $query_len_syms = strlen($parsed_url ?? '');
    if ($query_len_syms === 0){
        invalid_args();
    } else {
        $query = explode('&', $parsed_url);
        $query_len_parts = count($query);
        if ($query_len_parts === 0){
            invalid_args();
        } else {

            $params = array();
            foreach($query as $part){
                $splited = explode("=", $part);
                $key = $splited[0];
                $value = $splited[1];
                $converted = floatval($value);
                if ($converted == $value){
                    $params[$key] = $converted;
                } else {
                    $params[$key] = $value;
                }
            }

            $result = $collection->find($params);
            $c = 0;

            foreach ($result as $document){
                $c++;
                echo json_encode($document);
            }
            if($c === 0){empty_result();}
        }
    }
?>