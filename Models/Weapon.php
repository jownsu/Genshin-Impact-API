<?php

    class Weapon extends Model{

        static function get_weapon_names(){
            $weapons = self::all('name');

            $weap_names = array();
    
            foreach($weapons as $weapon){
                $weap_names[] = $weapon->name;
            }

            return json_encode($weap_names);
        }

        static function fetch($name){
            $name = str_replace('-', ' ', $name);
            $weapon = self::where(["name = {$name}"])->get();

            if(empty($weapon)) return false;

            $weapon = array_shift($weapon);
            unset($weapon->id);
            return json_encode($weapon);
         }
 
         static function add($data){
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


