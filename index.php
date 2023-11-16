<?php
    require 'vendor/autoload.php';
    require 'items.php';
    require 'properties.php';
    require 'users.php';
    require 'utils.php';
    require 'buy.php';
    require 'sell.php';
    require 'inventory.php';
    header('Content-Type: application/json');
    
    /*НАДО
    
    пользователи + хрень

    владельцы у предметов
    цены + смены

    ЕСЛИ МОЖНО

    токены доступа

    */

    // получить параметры query из url
    $q = $_GET['q'];
    $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // получить бд
    $client = new MongoDB\Client('mongodb+srv://user:Tunehemah1@cluster0.ghsbmhw.mongodb.net/?retryWrites=true&w=majority');

    $db = $client->trade;
    try{
        switch ($q){
            case 'inventory':
                get_user_items($db, $url);
                break;
            case 'error':
                http_response_code(400);
                break;
            case 'test':
                echo 200;
                break;
            case 'sell':
                main_sell($db, $url);
                break;
            case 'buy':
                main_buy($db, $url);
                break;
            case 'items':
                main_items($db, $url);
                break;
            case 'properties':
                main_properties($db);
                break;
            case 'login':
                main_login($db, $url);
                break;
            case 'register':
                main_register($db, $url);
                break;


        }
    }
    catch (Exception $e) {
        except_main_error($e);
    }