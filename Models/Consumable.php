<?php

    class Consumable extends Model{

        static function fetch($name){
            $name = str_replace('-', ' ', $name);
            $consumable = self::where(["name = {$name}"])->get();

            if(empty($consumable)) return false;

            $consumable = array_shift($consumable);
            unset($consumable->id);
            return json_encode($consumable);
         }
 
         static function add($data){
            if(empty($data) || empty($data->name)) return false;

             $consumable = new Consumable();

             foreach(array_keys(get_object_vars($consumable)) as $key){
                $consumable->$key = $data->$key ?? "";
            }
 
             return $consumable->create() ? $consumable->name : false;
         }

         static function edit($consumable, $input){
            foreach(array_keys(get_object_vars($input)) as $key){
                $consumable->$key = $input->$key;
            }

            return $consumable->update() ? true : false;
         }

    }

?>