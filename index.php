<?php

    $q = $_GET['q'];
    $params = explode('/', $q);
    require 'vendor/autoload.php';
    header('Content-Type: application/json');
    $client = new MongoDB\Client('mongodb+srv://user:Tunehemah1@cluster0.ghsbmhw.mongodb.net/?retryWrites=true&w=majority');

    $collection = $client->trade->items;

    $result = $collection->find(["base_type" => $params[0]]);
    $cc = $result;
    echo count($cc -> toArray());
    if ($cc === 0)
    {
        $res = [
            "status" => false,
            "message" => "Items not found"
        ];
        echo json_encode($res);
    }
    $cc -> close();
    foreach ($result as $document){
        echo json_encode($document);
    }



?>