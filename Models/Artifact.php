<?php

    class Artifact extends Model{

        static function fetch($name){
            // $name = str_replace('-', ' ', $name);
            $name = str_replace('%20', ' ', $name);
            $artifact = self::where(["name = {$name}"])->get_single();

            if(empty($artifact)) return false;

            unset($artifact->id);
            return json_encode($artifact);
         }
 
         static function add($data){
            if(empty($data) || empty($data->name)) return false;
            if(self::where(["name = $data->name"])->get_single()) return "$data->name already exists";

             $artifact = new Artifact();

             foreach(array_keys(get_object_vars($artifact)) as $key){
                $artifact->$key = $data->$key ?? "";
            }
 
             return $artifact->create() ? "Artifact $artifact->name added" : false;
         }

         static function edit($artifact, $input){
            foreach(array_keys(get_object_vars($input)) as $key){
                $artifact->$key = $input->$key;
            }

            return $artifact->update() ? "Artifact $artifact->name updated" : false;
         }

    }

?>