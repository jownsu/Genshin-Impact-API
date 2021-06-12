<?php

    class Character extends Model{

        static function character_names(){
            $characters = self::all('name');

            $char_names = array();
    
            foreach($characters as $character){
                $char_names[] = $character->name;
            }

            return json_encode($char_names);
        }

        static function fetch($name){
           $name = str_replace('-', ' ', $name);
           $character = self::where(["name = {$name}"])->get();

           if(empty($character)) return false;

           $character = array_shift($character);
           $character->skillTalents   = json_decode($character->skillTalents);
           $character->passiveTalents = json_decode($character->passiveTalents);
           $character->constellations = json_decode($character->constellations);
           unset($character->id);
           return json_encode($character);
        }

        static function add($data){
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

            return $character->create() ? $character->name : false;
        }

        static function edit($character, $input){

            $character->name            = $input->name ?? "";
            $character->vision          = $input->vision ?? "";
            $character->weapon          = $input->weapon ?? "";
            $character->nation          = $input->nation ?? "";
            $character->affiliation     = $input->affiliation ?? "";
            $character->rarity          = $input->rarity ?? "";
            $character->constellation   = $input->constellation ?? "";
            $character->birthday        = $input->birthday ?? "";
            $character->description     = $input->description ?? "";
            $character->skillTalents    = json_encode($input->skillTalents ?? null);
            $character->passiveTalents  = json_encode($input->passiveTalents ?? null);
            $character->constellations  = json_encode($input->constellations ?? null);

            return $character->update() ? true : false;
         }

    }

?>