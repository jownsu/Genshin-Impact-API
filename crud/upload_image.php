<?php

require_once("../Models/init.php");

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

$message = array();
$type = ucfirst(substr($_GET['type'], 0, -1)) ?? "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
//code here
    $name = $_GET['name'];
    $obj = $type::where(["name = {$name}"])->get_single();

   if($obj){
        if(isset($_FILES['icon']) && is_uploaded_file($_FILES['icon']['tmp_name'])){
            //code here
            if($obj->upload_icon($_FILES['icon'])){
                $message[] = 'Icon Uploaded';
            }else{
                $message[] = $obj->errors;
            }
        }

        if(isset($_FILES['portrait']) && is_uploaded_file($_FILES['portrait']['tmp_name'])){
            //code here
            if($obj->upload_portrait($_FILES['portrait'])){
                $message[] = 'Portrait Uploaded';
            }else{
                $message[] = $obj->errors;
            }
        }

   }else{
       $message[] = "$type not found";
   }

}else{
    $message[] = "Not POST request";
}

    echo json_encode(['message' => $message]);
