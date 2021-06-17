<?php


require_once("../Models/init.php");

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

$message = array();
$type = ucfirst(substr($_GET['type'], 0, -1)) ?? "";
$data = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_FILES['data'])){
        $files = $_FILES['data']['tmp_name'];

        foreach($files as $file){
            $data = json_decode(file_get_contents($file));

            if(property_exists($data, "name") && !is_object($data->name)){

                    $message[] = ($response = $type::add($data)) ? $response : $message[] = "{$type} not added";
                
            }else{
                foreach($data as $key => $val){

                    $message[] = ($response = $type::add($data)) ? $response : $message[] = "{$type} not added";
                }
            }
        }
    }

    $data = json_decode(file_get_contents("php://input"));

    if(isset($data)){
        $message[] = ($response = $type::add($data)) ? $response : $message[] = "{$type} not added";
    }

}else{
    $message[] = "Not POST request";
}

    echo json_encode(['message' => $message]);
