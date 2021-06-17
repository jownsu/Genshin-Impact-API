<?php

    class Weapon extends Model{
        use File;

        static function fetch($name){
            $name = str_replace('-', ' ', $name);
            $weapon = self::where(["name = {$name}"])->get_single();

            if(empty($weapon)) return false;

            unset($weapon->id);
            return json_encode($weapon);
         }
 
         static function add($data){
            if(empty($data) || empty($data->name)) return false;
            if(self::where(["name = $data->name"])->get_single()) return "$data->name already exists";

             $weapon = new Weapon();

             foreach(array_keys(get_object_vars($weapon)) as $key){
                $weapon->$key = $data->$key ?? "";
            }
 
             return $weapon->create() ? "Weapon $weapon->name added" : false;
         }

         static function edit($weapon, $input){
            foreach(array_keys(get_object_vars($input)) as $key){
                $weapon->$key = $input->$key;
            }

            return $weapon->update() ? $weapon->name : false;
         }

         public function upload($file, $filename = "image"){
            //code here
            $path = "../images/weapons/";
            $name = strtolower(str_replace(' ', '-', $this->name));

             if(!file_exists($path)){
                mkdir($path);
             }

             if(!file_exists($path . $name)){
                mkdir($path . $name);
             }

             if($this->check_files($file)){
               // $this->rename_if_exists();
               move_uploaded_file($file['tmp_name'], $path . $name . DS . $filename);
               return true;

             }else{
                return json_encode($this->errors);
             }
         }


    }


