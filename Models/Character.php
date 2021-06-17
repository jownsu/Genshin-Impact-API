<?php
    class Character extends Model{
        use File;
        static function fetch($name){
           $name = str_replace('-', ' ', $name);
           $character = self::where(["name = {$name}"])->get_single();

           if(empty($character)) return false;

           $character->skillTalents   = json_decode($character->skillTalents);
           $character->passiveTalents = json_decode($character->passiveTalents);
           $character->constellations = json_decode($character->constellations);
           unset($character->id);
           return json_encode($character);
        }

        static function add($data){
            if(empty($data) || empty($data->name)) return false;
            if(self::where(["name = $data->name"])->get_single()) return "$data->name already exists";
            $character = new Character();

            $character->name            = $data->name ?? "";
            $character->vision          = $data->vision ?? "";
            $character->weapon          = $data->weapon ?? "";
            $character->nation          = $data->nation ?? "";
            $character->affiliation     = $data->affiliation ?? "";
            $character->rarity          = $data->rarity ?? "";
            $character->constellation   = $data->constellation ?? "";
            $character->birthday        = $data->birthday ?? "";
            $character->description     = $data->description ?? "";
            $character->skillTalents    = json_encode($data->skillTalents ?? null);
            $character->passiveTalents  = json_encode($data->passiveTalents ?? null);
            $character->constellations  = json_encode($data->constellations ?? null);

            return $character->create() ? "Character $character->name added" : false;
        }

        static function edit($character, $input){

            $character->name            = $input->name ?? $character->name;
            $character->vision          = $input->vision ?? $character->vision;
            $character->weapon          = $input->weapon ?? $character->weapon;
            $character->nation          = $input->nation ?? $character->nation;
            $character->affiliation     = $input->affiliation ?? $character->affiliation;
            $character->rarity          = $input->rarity ?? $character->rarity;
            $character->constellation   = $input->constellation ?? $character->constellation;
            $character->birthday        = $input->birthday ?? $character->birthday;
            $character->description     = $input->description ?? $character->description;
            $character->skillTalents    = json_encode($input->skillTalents ?? json_decode($character->skillTalents));
            $character->passiveTalents  = json_encode($input->passiveTalents ?? json_decode($character->passiveTalents));
            $character->constellations  = json_encode($input->constellations ?? json_decode($character->constellations));

            return $character->update() ? $character->name : false;
         }

        public function image_path(){
            //code here
            $path = IMAGES_ROOT . "characters" . DS;
            return $path;
         }

         public function upload($file, $filename = "image"){
            //code here
            $path = "../images/characters/";
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

?>