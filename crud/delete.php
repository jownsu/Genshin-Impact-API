<?php



require_once("../Models/init.php");

if(!isset($_SERVER['PHP_AUTH_USER']) || (($_SERVER['PHP_AUTH_USER'] != USER_AUTH) || ($_SERVER['PHP_AUTH_PW'] != PASS_AUTH))){
    header("WWW-Authenticate: Basic realm=\"Private Area \"");
    header("HTTP/1.0 401 Unauthorized");
    exit("Sorry, you need proper credentials");
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

$type = ucfirst(substr($_GET['type'], 0, -1)) ?? "";
$message = array();

if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
        // $input = json_decode(file_get_contents("php://input"));
        // $id = $input->id;
        // $data = $type::find($id);

        $name = str_replace('%20', ' ', $_GET['name']);
        $name = $_GET['name'];
        $data = $type::where(["name = $name"])->get_single();

        if(!empty($data)){

            if($data->delete()){
                $message[] = "$type $data->name deleted";
            }else{
                $message[] = "$type $data->name not deleted";
            }

        }else{
                $message[] = "$type $name not found";
        }
}else{
    $message[] = "Not DELETE Request";

}

echo json_encode(['message' => $message]);
