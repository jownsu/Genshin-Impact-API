<?php


require_once("../Models/init.php");

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

$message = array();
$type = ucfirst(substr($_GET['type'], 0, -1)) ?? "";

if($_SERVER['REQUEST_METHOD'] == 'PUT'){
        $input = json_decode(file_get_contents("php://input"));

        // $id = $input->id ?? null;
        $name = str_replace('-', ' ', $_GET['name']);
        $data = $type::where(["name = $name"])->get_single();

        if(!empty($data)){

            echo $type::edit($data, $input) ? json_encode(['message' => "$type $name updated"]) : json_encode(['message' => "$type $name not updated"]);

        }else{
            echo json_encode(['message' => "$type $name not found"]);
        }
}else{
    echo json_encode(['message' => 'Not PUT Request']);
}