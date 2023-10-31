<?php

function invalid_args(){
    $res = [
        "status" => false,
        "message" => "Incorrect args"
    ];
    echo json_encode($res);
}

function empty_result(){
    $res = [
        "status" => false,
        "message" => "Empty result"
    ];
    echo json_encode($res);
}