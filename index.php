<?php
    require 'vendor/autoload.php';
    require 'back_functions.php';
    require 'out_functions.php';
    header('Content-Type: application/json');

    /*НАДО
    
    пользователи + хрень
    владельцы у предметов
    цены + смены

    ЕСЛИ МОЖНО

    токены доступа

    */
    die(bin2hex(random_bytes(32)));




    // получить параметры query из url
    $q = $_GET['q'];
    $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // получить бд
    $client = new MongoDB\Client('mongodb+srv://user:Tunehemah1@cluster0.ghsbmhw.mongodb.net/?retryWrites=true&w=majority');

    $db = $client->trade;
    
    switch ($q){
        case 'items':
            page_items($db, $url);
            break;
        case 'properties':
            page_properties($db);
            break;

    }

    function page_items($db, $url){
        $collection = $db->items;

        $filter = filter_from_url($url);
        get_items($collection, $filter);
    }

    function page_properties($db){
        $collection = $db->properties;
        $result = $collection->find([], ['projection' => ['property' => 1]]);

        foreach ($result as $document){
            echo json_encode($document);
        }
    }
?>