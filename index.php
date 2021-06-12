<?php

require_once("Models/init.php");

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');



        if(isset($_GET['type'])){

            switch ($_GET['type']){
                case 'characters':
                    echo Character::character_names();
                    break;
                case 'weapons':
                    echo Weapon::get_weapon_names();
                    break;
            }

        }else{
            $types = ["types" => ["artifacts", "characters" , "domains", "elements", "nations", "weapons"]];
            echo json_encode($types);
        }
