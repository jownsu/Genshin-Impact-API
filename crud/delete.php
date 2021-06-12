<?php


require_once("../Models/init.php");

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

$type = ucfirst(substr($_GET['type'], 0, -1)) ?? "";

if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
        $input = json_decode(file_get_contents("php://input"));
        $id = $input->id;
        $data = $type::find($id);

        if(!empty($data)){

            if($data->delete()){
                echo json_encode(['message' => "{$type}" . " deleted"]);
            }else{
                echo json_encode(['message' => "{$type}" . " not deleted"]);
            }

        }else{
            echo json_encode(['message' => "{$type}" . " not found"]);
        }
}else{
    echo json_encode(['message' => "Not DELETE Request"]);
}