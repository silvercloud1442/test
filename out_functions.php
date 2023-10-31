<?php

function except_invalid_args(){
    $res = [
        "status" => false,
        "message" => "Incorrect args"
    ];
    die(json_encode($res));
}


function except_empty_result(){
    $res = [
        "status" => false,
        "message" => "Empty result"
    ];
    die(json_encode($res));
}