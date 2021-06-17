<?php

    class Nation extends Model{

        static function fetch($name){
            $name = str_replace('-', ' ', $name);
            $nation = self::where(["name = {$name}"])->get_single();

            if(empty($nation)) return false;

            unset($nation->id);
            return json_encode($nation);
         }
 
         static function add($data){
            if(empty($data) || empty($data->name)) return false;
            if(self::where(["name = $data->name"])->get_single()) return "$data->name already exists";

             $nation = new Nation();

             foreach(array_keys(get_object_vars($nation)) as $key){
                $nation->$key = $data->$key ?? "";
            }
 
             return $nation->create() ? "Nation $nation->name added" : false;
         }

         static function edit($nation, $input){
            foreach(array_keys(get_object_vars($input)) as $key){
                $nation->$key = $input->$key;
            }

            return $nation->update() ? $nation->name : false;
         }

    }

?>