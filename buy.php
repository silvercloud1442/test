<?php
function main_buy($db, $url){
    $items = $db->items;
    $users = $db->users;
    check_url($url);
    $urlParts = parse_url($url);
    parse_str($urlParts['query'], $queryParams);

    if (!(isset($queryParams['item']) && isset($queryParams['user']))){
        except_invalid_args();
    }

    $item = $items->find(['_id' => new MongoDB\BSON\ObjectID($queryParams['item'])])->toArray()[0];
    $buyer = $users->find(['_id' => new MongoDB\BSON\ObjectID($queryParams['user'])])->toArray()[0];
    $seller = $users->find(['_id' => new MongoDB\BSON\ObjectID($item['user'])])->toArray()[0];
    if ((isset($item['price'])) && ($buyer['_id'] != $seller['_id'])) {
        if ($buyer['money'] >= $item['price']){
            $items->updateOne(
                ['_id' => $item['_id']],
                ['$set' => ['user' => $buyer['_id']]]
            );
            $items->updateOne(
                ['_id' => $item['_id']],
                ['$unset' => ['price' => true]]
            );
            $users->updateOne(
                ['_id' => $seller['_id']],
                ['$set' => ['money' => $seller['money'] + $item['price']]]
            );
            $users->updateOne(
                ['_id' => $buyer['_id']],
                ['$set' => ['money' => $buyer['money'] - $item['price']]]
            );
        }
        else{
            echo 'net denyak';
            die();
        }
    }
    else {
        echo 'ne normalno';
        die();
    }
    echo 'normalno';
}