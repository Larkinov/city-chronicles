<?php

namespace CityChronicles\God;

use vkbot_conversation\classes\profile\Profile;

class God
{
    public array $commands = [];
 
    private Profile $profile;

    public function __construct(
        private string $id,
        private string $peer_id,
        private string $storagePath,
        private string $name = "bot",
        private string $lastName = "bot",
        private bool $isMan = false,
        private bool $isAdmin = false,

    ) {
        $this->profile = new Profile($id,$peer_id,$storagePath);
        $this->setId($id);
        $this->setPeerId($peer_id);
        $this->setName($name);
        $this->setLastName($lastName);
        $this->setIsMan($isMan);
        $this->setIsAdmin($isAdmin);
    }

    public function getName():string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name=$name;
        $this->profile->saveInfo("name",$name);
    }

    public function getLastName():string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName)
    {
        $this->lastName=$lastName;
        $this->profile->saveInfo("lastName",$lastName);
    }

    public function getIsMan():bool
    {
        return $this->isMan;
    }

    public function setIsMan(bool $isMan)
    {
        $this->isMan=$isMan;
        $this->profile->saveInfo("isMan",$isMan);
    }
    public function getIsAdmin():bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin)
    {
        $this->isAdmin=$isAdmin;
        $this->profile->saveInfo("isAdmin",$isAdmin);
    }

    public function getId():string
    {
        return $this->id;
    }

    public function setId(string $id)
    {
        $this->id=$id;
        $this->profile->saveInfo("id",$id);
    }
    public function getPeerId():string
    {
        return $this->peer_id;
    }

    public function setPeerId(string $peer_id)
    {
        $this->peer_id=$peer_id;
        $this->profile->saveInfo("peer_id",$peer_id);
    }

}
