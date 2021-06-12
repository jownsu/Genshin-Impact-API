<?php

require_once("config.php");

class Database{


    private $connection;
    private $stmt;

    function __construct(){
        $this->open_db(); 
    }

    function open_db(){
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;

        try{
            $this->connection = new PDO($dsn, DB_USER, DB_PASS);
        }catch(PDOexception $e){
            die("Database Connection Error! " . $e->getMessage());
        }

        return $this->connection;
    }

    function query($sql){
        $this->stmt = $this->connection->prepare($sql);
    }

    function execute(){
        if($this->stmt->execute()){
            return $this->stmt;
        }else{
            die("Query Error!");
        }
    }

    function fetch(){
        $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    function bind($param, $value, $type = null){
        if(is_null($type)){
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                case is_string($value):
                    $type = PDO::PARAM_STR;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
            $this->stmt->bindValue($param, $value, $type);
    }

    public function fetchColumn(){
		$this->execute();
        return $this->stmt->fetchColumn();
    }

    function confirm_query($result){
        if(!$result){
            die("Query Error! " . $this->connect_error);
        }
    }

    function escape_string($str){
        return $this->connection->quote($str);
    }

    function get_columns($table){
        $this->query("SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "'");
        $result = $this->execute();
        $columns = $result->fetchAll();
        return $columns;
    }

    function insert_id(){
        return $this->connection->insert_id;
    }

}

$db = new Database();