<?php
    define('DS', DIRECTORY_SEPARATOR);
    define('SITE_ROOT', dirname(__DIR__, 1));
    define('IMAGES_ROOT', dirname(__DIR__, 1) . "/images");

    require_once("Database.php");
    require_once("Model.php");
    require_once("File.php");
    require_once("Character.php");
    require_once("Weapon.php");
    require_once("Artifact.php");
    require_once("Nation.php");
    require_once("Consumable.php");