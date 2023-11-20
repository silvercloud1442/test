<?php
function main_sell($db, $url){
    $items = $db->items;
    check_url($url);
    $urlParts = parse_url($url);
    parse_str($urlParts['query'], $queryParams);

    if (!(isset($queryParams['item']) && isset($queryParams['price']))){
        except_invalid_args();
    }
    if ((int)($queryParams['price']) >= 1) {
        $items->updateOne(
            ['_id' => new MongoDB\BSON\ObjectID($queryParams['item'])],
            ['$set' => ['price' => (int)$queryParams['price']]]
        );
    }
    else if ((int)($queryParams['price']) == 0){
        $items->updateOne(
            ['_id' => new MongoDB\BSON\ObjectID($queryParams['item'])],
            ['$unset' => ['price' => true]]
        );
    }
    else {
        echo 'ne normalno';
        die();
    }
    echo 'normalno';
}