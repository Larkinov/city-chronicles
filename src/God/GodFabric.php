<?php

namespace CityChronicles\God;

use CityChronicles\God\God;
use vkbot_conversation\classes\profile\Profile;

class GodFabric
{
    public static function getGod(
        string $id,
        string $peer_id,
        string $storagePath,
        string $name = "bot",
        string $lastName = "bot",
        bool $isMan = false,
        bool $isAdmin = false,
        int $power = 0,
        int $faith = 1,
    ): God {
        $profile = new Profile($id, $peer_id, $storagePath);
        if (empty($profile->getSavedInfo("id"))) {
            $god = new God(
                $id,
                $peer_id,
                $storagePath,
                $name,
                $lastName,
                $isMan,
                $isAdmin,
                $power,
                $faith,
            );
        } else {
            $data = $profile->getSavedFullInfo();
            $god = new God(
                $id,
                $peer_id,
                $storagePath,
            );
            property_exists($data, "name") && $god->setName($data->name);
            property_exists($data, "lastName") && $god->setLastName($data->lastName);
            property_exists($data, "isMan") && $god->setIsMan($data->isMan);
            property_exists($data, "isAdmin") && $god->setIsAdmin($data->isAdmin);
            property_exists($data, "power") && $god->setPower($data->power);
            property_exists($data, "faith") && $god->setFaith($data->faith);
        }
        return $god;
    }
}
