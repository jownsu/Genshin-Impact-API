<?php

    class Weapon extends Model{

        static function fetch($name){
            $name = str_replace('-', ' ', $name);
            $weapon = self::where(["name = {$name}"])->get_single();

            if(empty($weapon)) return false;

            unset($weapon->id);
            return json_encode($weapon);
         }
 
         static function add($data){
            if(empty($data) || empty($data->name)) return false;

             $weapon = new Weapon();

             foreach(array_keys(get_object_vars($weapon)) as $key){
                $weapon->$key = $data->$key ?? "";
            }
 
             return $weapon->create() ? $weapon->name : false;
         }

         static function edit($weapon, $input){
            foreach(array_keys(get_object_vars($input)) as $key){
                $weapon->$key = $input->$key;
            }

            return $weapon->update() ? true : false;
         }


    }


