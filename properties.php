<?php
function main_properties($db){
        $collection = $db->properties;
        $result = $collection->find([], ['projection' => ['property' => 1]]);
        echo '[';
        foreach ($result as $document){
            echo  json_encode($document) ;
            echo ',';
        }
        echo ']';
    }