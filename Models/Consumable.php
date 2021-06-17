<?php

    class Consumable extends Model{

        static function fetch($name){
            // $name = str_replace('-', ' ', $name);
            $name = str_replace('%20', ' ', $name);
            $consumable = self::where(["name = {$name}"])->get_single();

            if(empty($consumable)) return false;

            unset($consumable->id);
            return json_encode($consumable);
         }
 
         static function add($data){
            if(empty($data) || empty($data->name)) return false;
            if(self::where(["name = $data->name"])->get_single()) return "$data->name already exists";

             $consumable = new Consumable();

             foreach(array_keys(get_object_vars($consumable)) as $key){
                $consumable->$key = $data->$key ?? "";
            }
 
             return $consumable->create() ? "Consumable $consumable->name added" : false;
         }

         static function edit($consumable, $input){
            foreach(array_keys(get_object_vars($input)) as $key){
                $consumable->$key = $input->$key;
            }

            return $consumable->update() ? "Consumable $consumable->name updated" : false;
         }

    }

?>