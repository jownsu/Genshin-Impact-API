<?php
    require_once("../Models/init.php");

    // if(!isset($_SERVER['PHP_AUTH_USER']) || (($_SERVER['PHP_AUTH_USER'] != USER_AUTH) || ($_SERVER['PHP_AUTH_PW'] != PASS_AUTH))){
    //     header("WWW-Authenticate: Basic realm=\"Private Area \"");
    //     header("HTTP/1.0 401 Unauthorized");
    //     exit("Sorry, you need proper credentials");
    // }


    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    $type = ucfirst(substr($_GET['type'], 0, -1)) ?? "";
    $name = $_GET['name'];
    $data = $type::fetch($name);

    echo $data ? $data : json_encode(['message' => $name . " not found"]);



    // if(isset($_GET['name'])){
    //     $character = Character::get_character($_GET['name']);
    //     echo $character;
    // }else{
    //     echo Character::character_names();
    // }

