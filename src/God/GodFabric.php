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
        }
        return $god;
    }
}
