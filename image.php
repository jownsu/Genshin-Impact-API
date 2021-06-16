<?php

header('Access-Control-Allow-Origin: *');


require_once('Models/init.php');


$type = ucfirst(substr($_GET['type'], 0, -1)) ?? "";
$data = $type::fetch($_GET['name']);

if(!empty($data)){
    header('Content-Type: image/jpg');
    $path = "images/" . $_GET['type'] . "/" . $_GET['name'] . "/" . $_GET['img'];
    $image = file_get_contents($path);

    echo $image;
}else{
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Character not found']);
}

?>