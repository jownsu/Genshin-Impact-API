<?php

    class Artifact extends Model{

        static function fetch($name){
            $name = str_replace('-', ' ', $name);
            $artifact = self::where(["name = {$name}"])->get();

            if(empty($artifact)) return false;

            $artifact = array_shift($artifact);
            unset($artifact->id);
            return json_encode($artifact);
         }
 
         static function add($data){
            if(empty($data) || empty($data->name)) return false;

             $artifact = new Artifact();

             foreach(array_keys(get_object_vars($artifact)) as $key){
                $artifact->$key = $data->$key ?? "";
            }
 
             return $artifact->create() ? $artifact->name : false;
         }

         static function edit($artifact, $input){
            foreach(array_keys(get_object_vars($input)) as $key){
                $artifact->$key = $input->$key;
            }

            return $artifact->update() ? true : false;
         }

    }

?>