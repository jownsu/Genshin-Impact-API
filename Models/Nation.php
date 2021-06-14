<?php

    class Nation extends Model{

        static function fetch($name){
            $name = str_replace('-', ' ', $name);
            $nation = self::where(["name = {$name}"])->get();

            if(empty($nation)) return false;

            $nation = array_shift($nation);
            unset($nation->id);
            return json_encode($nation);
         }
 
         static function add($data){
            if(empty($data) || empty($data->name)) return false;

             $nation = new Nation();

             foreach(array_keys(get_object_vars($nation)) as $key){
                $nation->$key = $data->$key ?? "";
            }
 
             return $nation->create() ? $nation->name : false;
         }

         static function edit($nation, $input){
            foreach(array_keys(get_object_vars($input)) as $key){
                $nation->$key = $input->$key;
            }

            return $nation->update() ? true : false;
         }

    }

?>