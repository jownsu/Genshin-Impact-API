<?php

require_once("Models/init.php");

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');



        if(isset($_GET['type'])){
            $type = ucfirst(substr($_GET['type'], 0, -1)) ?? "";

            $names = $type::orderBy('name', 'asc', 'name')->get();

            $arrNames = array();
            
            if(empty($names)){
                echo json_encode(['message' => 'No Informations yet']);
            }else{
                foreach($names as $obj){
                    $arrNames[] = $obj->name;
                }
    
                echo json_encode($arrNames);
            }
            


        }else{
            $types = ["types" => ["characters", "weapons" , "artifacts", "consumables", "nations"]];
            echo json_encode($types);
        }
