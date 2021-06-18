<?php


class Model{

    protected static $sql;
    protected static $bind = array();
    protected static $fields = array();

    public static $select;

    function __construct(){
        $this->set_properties();
    }

    /*****Pre-defined Select Query Methods********************/

    public static function all($tables = "*"){
        $result = self::find_query(self::selectSQL($tables));
        return !empty($result) ? $result : false;
    }

    public static function find($id, $tables = "*"){
        $sql = self::selectSQL($tables) . " WHERE " . self::id() . " = :id LIMIT 1";

        $result = self::find_query($sql, [':id' => $id]);
        return !empty($result) ? array_shift($result) : false;
    }

    public static function where($sqlWhere = array(), $tables = "*"){
        $self = new static;
        $where = array();

        foreach($sqlWhere as $val){

            $arrVal        = explode(" ", $val);
            $whereTable    = $arrVal[0];
            $whereOperator = $arrVal[1];
            $whereValue    = explode(" {$whereOperator} ", $val)[1];
        

            $where[] = "{$whereTable} {$whereOperator} :{$whereTable}";
            self::$bind = array_merge(self::$bind, [":{$whereTable}" => "{$whereValue}"]);
        }

        if(!isset(self::$sql)){
            self::$sql .= self::selectSQL($tables);    
        }

        self::$sql .= " WHERE ( ". implode( " AND ", $where) . " ) ";
        
        return $self;
    }

    public static function orWhere($sqlWhere = array()){
        $self = new static;
        $where = array();

        foreach($sqlWhere as $val){
            
            $arrVal        = explode(" ", $val);
            $whereTable    = $arrVal[0];
            $whereOperator = $arrVal[1];
            $whereValue    = explode(" {$whereOperator} ", $val)[1];

            $where[] = "{$whereTable} {$whereOperator} :OR{$whereTable}";
            self::$bind = array_merge(self::$bind, [":OR{$whereTable}" => "{$whereValue}"]);
        }

        self::$sql .= " OR ( ". implode( " AND ", $where) . " ) ";    

        return $self;
    }

    public static function orderBy($table, $order = "ASC", $tables = "*"){
        $self = new static;
        if(!isset(self::$sql)){
            self::$sql = self::selectSQL($tables) . " ORDER BY " . $table . " " . $order;
        }else{
            self::$sql .= " ORDER BY " . $table . " " . $order;
        }

        return $self;
    }

    public static function paginate($items_per_page = 5){
        $self = new static;
        $page = isset($_GET['page']) && $_GET['page'] >= 1 ? (int)$_GET['page'] : 1;
        $offset = (($page - 1) * $items_per_page);

        $paginateSQL = " LIMIT " . $items_per_page . " OFFSET " . $offset;

        if(!isset(self::$sql)){
            self::$sql = self::selectSQL() . $paginateSQL;
        }else{
            self::$sql .= $paginateSQL;
        }
        
        return $self;
    }

    /*****End of pre-defined Select Query Methods********************/


    /******Pre-defined Count Methods**************/

    public static function count(){
        $self = new static;

        self::$sql .= self::countSQL();

        return $self;
    }

    public static function count_all(){
        $result = self::count_query(self::countSQL());
        return $result;
    }

    public function total_page($items_per_page){
        $total_count = $this->get();
        return ceil($total_count / $items_per_page);
    }

    /****End of pre-defined Count Methods*********************/

    /*****************Methods******************/

    public function get(){
        if(strpos(self::$sql,'SQL_CALC_FOUND_ROWS')){
            $result = self::count_query(self::$sql, self::$bind);
        }else{
            $result = self::find_query(self::$sql, self::$bind);
        }

        self::$sql = null;
        self::$bind = array();
        return !empty($result) ? $result : false;

        // return self::$sql;
    }

    public function get_single(){
        $result = self::find_query(self::$sql . " LIMIT 1", self::$bind);
        
        self::$sql = null;
        self::$bind = array();

        return !empty($result) ? array_shift($result) : false;
    }

    public function create(){
        global $db;

        $properties = $this->get_property_values();

        $sql = "INSERT INTO " . static::table() . " (". implode(", ", array_keys($properties)) .") ";
        $sql .= "VALUES (:". implode(", :", array_keys($properties)) .") ";

        $db->query($sql);

        foreach($properties as $key => $value){
            $db->bind(":{$key}", "{$value}");
        }

        return $db->execute() ? true : false;

    }

    public function update(){
        global $db;

        $properties = $this->get_property_values();
        $update_set_pair = array();

        foreach(array_keys($properties) as $key){
            $update_set_pair[] = "{$key} = :{$key}";
        }

        $sql = "UPDATE " . static::table() . " SET ";
        $sql .= implode(', ', $update_set_pair) . " WHERE " . static::id() . " = " . $this->{static::id()};
        
        $db->query($sql);

        foreach($properties as $key => $value){
            $db->bind(":{$key}", "{$value}");
        }

        return $db->execute() ? true : false;
    }

    public function delete(){
        global $db;

        $sql = "DELETE FROM " . static::table() . " WHERE " . static::id() . " = " . $this->{static::id()};

        $db->query($sql);
        return $db->execute() ? true : false;
    }
    
    /**************End of Methods***************/

    /********Static Properties*******************/
    public static function table(){
        $calling_class = get_called_class();
        $default_table = strtolower($calling_class) . 's';
        
        $table = isset(static::$table) ? static::$table : $default_table;
        return $table;
    }

    public static function id(){
        return isset(static::$primary_key) ? static::$primary_key : 'id';
    }

    public static function countSQL(){
        return "SELECT SQL_CALC_FOUND_ROWS * FROM " . self::table();
    }

    public static function selectSQL($selectTables = "*"){
        return "SELECT " . $selectTables ." FROM " . self::table();
    }

    /********End of static properties*************/

    /*********Custom Query**************************/

    public static function count_query($sql, $binds = array()){
        global $db;

        $db->query($sql);

        if(isset($binds)){
            foreach($binds as $key => $value){
                $db->bind("{$key}", "{$value}");
            }
        }

        $db->execute();
        $db->query("SELECT FOUND_ROWS()");

        $result = $db->execute();
        return $result->fetchColumn(); 
    }

    public static function find_query($sql, $binds = array()){
        global $db;

        $db->query($sql);
        
        if(isset($binds)){
            foreach($binds as $key => $value){
                $db->bind("{$key}", "{$value}");
            }
        }
        
        $result = $db->execute();

        $obj_arr = array();
            while($row = $result->fetch()){

                $obj_arr[] = self::instantiate($row);
            }
        return $obj_arr;
    }

    /************End of Custom Query***************/


    /*******Instantation of Properties**************/

    public static function instantiate($record){
        $calling_class = get_called_class();
        $obj = new $calling_class;

        foreach($record as $key => $value){
            if($obj->has_attribute($key)){
                $obj->$key = $value;
            }
        }

        return $obj;
    }

    private function has_attribute($attribute){
        return property_exists($this, $attribute);
    }

    private function set_properties(){
        global $db;

        $sql = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` ";
        $sql.=  "WHERE `TABLE_SCHEMA`='" . DB_NAME ."' AND `TABLE_NAME`='" . self::table() . "'";

        $db->query($sql);
        $result = $db->execute();
        $columns = $result->fetchAll();

        $fields = array();

        foreach($columns as $column){
            $this->createProperty($column['COLUMN_NAME'], null);
            static::$fields[] = $column['COLUMN_NAME'];
        }
        
    }

    protected function createProperty($name, $value){
        $this->{$name} = $value;
    }

    protected function get_properties(){
        $properties = self::$fields;

        if(($key = array_search(self::id(), $properties)) !== false){
            unset($properties[$key]);
        }

        return $properties;
    }

    protected function get_property_values(){
        $property_values = array();
        $properties = $this->get_properties();

        foreach($properties as $field){
            if($this->has_attribute($field)){
                $property_values[$field] = $this->$field;
            }
        }

        return $property_values;
    }

    /*******End of Instantation of Properties**************/


}