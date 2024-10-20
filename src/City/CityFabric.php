<?php

namespace CityChronicles\City;

use CityChronicles\Character\CharacterFabric;
use CityChronicles\Text\TextCharacter;
use CityChronicles\Text\TextCity;
use CityChronicles\God\GodFabric;
use DateTime;
use vkbot_conversation\classes\conversation\Conversation;
use vkbot_conversation\classes\profile\Profile;

class CityFabric
{
    public static function getCity(
        string $peer_id,
        string $storagePath,
        string $storagePathProfile,
        string $storagePathCharacters
    ): City {
        $conversation = new Conversation($peer_id, $storagePath, $storagePathProfile);
        $city = new City($peer_id, $storagePath, $storagePathProfile, $storagePathCharacters);
        if (empty($conversation->getSavedInfo("initConversation"))) {
            $city->setRich(0);
            $city->setRank(TextCity::getRank($city->getRich()));
            $city->setName(TextCity::getName($city->getRank()));
            $city->setEffPlace([]);
            // $city->setInitConversation("firstLoading");
            $city->setSpeedTime(TextCity::SPEED_TIME_1);
            $city->updateTimeLive();
            $profiles = $conversation->getProfiles();

            foreach ($profiles as $value) {
                $godID = self::createGod($value, $peer_id, $storagePathProfile);
                $city->setGodsID($godID);
            }

            foreach ($profiles as $value) {
                self::createCharacter($value, $peer_id, $storagePathCharacters);
            }
        } else {
            $data = $conversation->getSavedFullInfo();

            property_exists($data, "rich") && $city->setRich($data->rich);
            property_exists($data, "rank") && $city->setRank($data->rank);
            property_exists($data, "name") && $city->setName($data->name);
            property_exists($data, "effPlace") && $city->setEffPlace($data->effPlace);
            property_exists($data, "initConversation") && $city->setInitConversation("init");
            property_exists($data, "speedTime") && $city->setSpeedTime($data->speedTime);
            property_exists($data, "timeLive") && $city->setTimeLive($data->timeLive);

            property_exists($data, "godsID") && $city->setGodsID($data->godsID);

            property_exists($city, "lastTimeEvent") && $city->setLastTimeEvent($data->lastTimeEvent);

            self::addNewProfiles($conversation->getProfiles(), $peer_id, $storagePathProfile, $storagePathCharacters, $city);

            
            $city->updateTimeLive();
        }
        return $city;
    }

    private static function converterDateVK(string $dateDMYYYY)
    {
        // Преобразуем строку даты в формат Y-m-d
        $date = DateTime::createFromFormat('d.m.Y', $dateDMYYYY);

        if (!$date)
            throw new \Exception("Ошибка: некорректный формат даты");


        $currentDate = new DateTime();

        $difference = $currentDate->diff($date);

        return $difference->y;
    }

    private static function addNewProfiles(array $profiles, string $peer_id, string $storagePathProfile, string $storagePathCharacters, City $city)
    {
        $newProfiles = [];
        foreach ($profiles as $key => $value) {
            if (!in_array($value->getId(), $city->getGodsID())) {
                $newProfiles[$key] = $value;
            }
        }
        foreach ($newProfiles as $value) {
            $godID = self::createGod($value, $peer_id, $storagePathProfile);
            $city->setGodsID($godID);
            self::createCharacter($value, $peer_id, $storagePathCharacters);
        }
    }

    private static function createCharacter(Profile $profile, string $peer_id, string $storagePathCharacters)
    {
        $sex = $profile->getSex();
        $age = $profile->getBirthDate();
        if (empty($age))
            $age =  TextCharacter::getRandomAge();
        elseif (substr_count($age, ".") < 2)
            $age =  TextCharacter::getRandomAge();
        elseif (substr_count($age, ".") >= 2) {
            $age = self::converterDateVK($age);
        }

        $character = CharacterFabric::createNewCharacter(
            $profile->getId(),
            $peer_id,
            $storagePathCharacters,
            $profile->getFirstName(),
            $profile->getLastName(),
            $sex,
            0,
            $age,
            TextCharacter::HEALTH[0],
            0,
            TextCharacter::PROFESSION_NO,
            0,
            0,
            0,
        );
    }

    private static function createGod(Profile $profile, string $peer_id, string $storagePathProfile): string
    {
        $sex = $profile->getSex();
        $god = GodFabric::getGod(
            $profile->getId(),
            $peer_id,
            $storagePathProfile,
            $profile->getFirstName(),
            $profile->getLastName(),
            $sex,
            $profile->getIsAdmin(),
            0,
            1,
        );
        return $god->getId();
    }
}
