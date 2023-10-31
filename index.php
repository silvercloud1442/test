<?php
    require 'vendor/autoload.php';
    require 'back_functions.php';
    require 'out_functions.php';
    header('Content-Type: application/json');

    // получить коллекции из бд
    $client = new MongoDB\Client('mongodb+srv://user:Tunehemah1@cluster0.ghsbmhw.mongodb.net/?retryWrites=true&w=majority');
    $collection = $client->trade->items;

    // получить параметры query из url
    $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $query = query_from_url($url);
    if ($query === false){
        except_invalid_args();
    }
    // создать словарь параметров
    $params = params_from_query($query);

    // получиьт данные из коллекции по параметрам
    get_items($collection, $params);
?>