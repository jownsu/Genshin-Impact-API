<?php
    require_once("../Models/init.php");

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    $type = ucfirst(substr($_GET['type'], 0, -1)) ?? "";
    $name = $_GET['name'];
    $data = $type::fetch($name);

    echo $data ?? json_encode(['message' => "No data found"]);



    // if(isset($_GET['name'])){
    //     $character = Character::get_character($_GET['name']);
    //     echo $character;
    // }else{
    //     echo Character::character_names();
    // }

