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
        // $name = str_replace('-', ' ', $_GET['name']);
        $name = str_replace('%20', ' ', $_GET['name']);
        $name = $_GET['name'];
        $data = $type::where(["name = $name"])->get_single();

        if(!empty($data)){

            $message[] = ($response = $type::edit($data, $input)) ? $response : "$type $name not added";

        }else{
            $message[] = "$type $name not found";
        }
}else{
        $message[] = 'Not PUT Request';
}

echo json_encode(['message' => $message]);
