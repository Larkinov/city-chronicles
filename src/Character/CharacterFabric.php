<?php

namespace CityChronicles\Character;

use CityChronicles\Character\Character;
use CityChronicles\Text\TextCharacter;
use vkbot_conversation\classes\profile\Profile;

class CharacterFabric
{

    public static function getCharacter(
        string $id,
        string $peer_id,
        string $storagePath,
    ): Character {
        $profile = new Profile($id, $peer_id, $storagePath);
        $data = $profile->getSavedFullInfo();

        $fake = [];
        property_exists($data, "name") ? $fake['name'] = $data->name : $fake['name'] = "fake_name";
        property_exists($data, "lastName") ? $fake['lastName'] = $data->lastName : $fake['lastName'] = "fake_last_name";
        property_exists($data, "isMan") ? $fake['isMan'] = $data->isMan : $fake['isMan'] = 0;
        property_exists($data, "age") ? $fake['age'] = $data->age : $fake['age'] = 0;

        //дополнительные свойства
        property_exists($data, "health") ? $fake['health'] = $data->health : $fake['health'] = TextCharacter::getRandomHealth();
        property_exists($data, "money") ? $fake['money'] = $data->money : $fake['money'] = 0;
        property_exists($data, "profession") ? $fake['profession'] = $data->profession : $fake['profession'] = TextCharacter::getRandomProfession();
        property_exists($data, "luckyCount") ? $fake['luckyCount'] = $data->luckyCount : $fake['luckyCount'] = 0;
        property_exists($data, "unluckyCount") ? $fake['unluckyCount'] = $data->unluckyCount : $fake['unluckyCount'] = 0;
        property_exists($data, "lastSendEventTime") ? $fake['lastSendEventTime'] = $data->lastSendEventTime : $fake['lastSendEventTime'] = "";
        $character = new Character(
            $id,
            $peer_id,
            $storagePath,
            $fake['name'],
            $fake['lastName'],
            $fake['isMan'],
            $fake['age'],
            $fake['health'],
            $fake['money'],
            $fake['profession'],
            $fake['luckyCount'],
            $fake['unluckyCount'],
        );

        $character->setLastSendEventTime($fake['lastSendEventTime']);
        return $character;
    }


    public static function createNewCharacter(
        string $id,
        string $peer_id,
        string $storagePath,
        string $name,
        string $lastName,
        bool $isMan,
        int $age,
    ): Character {
        $profile = new Profile($id, $peer_id, $storagePath);

        $character = new Character(
            $id,
            $peer_id,
            $storagePath,
            $name,
            $lastName,
            $isMan,
            $age,
        );
        return $character;
    }
}
