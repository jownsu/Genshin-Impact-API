<?php



require_once("../Models/init.php");

if(!isset($_SERVER['PHP_AUTH_USER']) || (($_SERVER['PHP_AUTH_USER'] != USER_AUTH) || ($_SERVER['PHP_AUTH_PW'] != PASS_AUTH))){
    header("WWW-Authenticate: Basic realm=\"Private Area \"");
    header("HTTP/1.0 401 Unauthorized");
    exit("Sorry, you need proper credentials");
}

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
