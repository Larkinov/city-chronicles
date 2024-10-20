<?php

namespace CityChronicles\Character;

use vkbot_conversation\classes\profile\Profile;

class Character
{

    private Profile $profile;
    private string $lastSendEventTime = "";

    public function __construct(
        private string $id,
        private string $peer_id,
        private string $storagePath,
        private string $name,
        private string $lastName,
        private int $isMan,
        private int $faith,
        private int $age,
        private string $health,
        private int $money,
        private string $profession,
        private int $isBot,
        private int $luckyCount,
        private int $unluckyCount,
        // private int $location,
    ) {
        $this->profile = new Profile($id, $peer_id, $storagePath);
        $this->setId($id);
        $this->setPeerId($peer_id);
        $this->setName($name);
        $this->setLastName($lastName);
        $this->setIsMan($isMan);
        $this->setFaith($faith);
        $this->setAge($age);
        $this->setHealth($health);
        $this->setMoney($money);
        $this->setProfession($profession);
        $this->setLuckyCount($luckyCount);
        $this->setUnluckyCount($unluckyCount);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
        $this->profile->saveInfo("name", $name);
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
        $this->profile->saveInfo("lastName", $lastName);
    }

    public function getIsMan(): int
    {
        return $this->isMan;
    }

    public function setIsMan(int $isMan)
    {
        $this->isMan = $isMan;
        $this->profile->saveInfo("isMan", $isMan);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id)
    {
        $this->id = $id;
        $this->profile->saveInfo("id", $id);
    }
    public function getPeerId(): string
    {
        return $this->peer_id;
    }

    public function setPeerId(string $peer_id)
    {
        $this->peer_id = $peer_id;
        $this->profile->saveInfo("peer_id", $peer_id);
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age)
    {
        $this->age = $age;
        $this->profile->saveInfo("age", $age);
    }
    public function getHealth(): string
    {
        return $this->health;
    }

    public function setHealth(string $health)
    {
        $this->health = $health;
        $this->profile->saveInfo("health", $health);
    }
    public function getMoney(): int
    {
        return $this->money;
    }

    public function setMoney(int $money)
    {
        $this->money = $money;
        $this->profile->saveInfo("money", $money);
    }
    public function getFaith(): int
    {
        return $this->faith;
    }

    public function setFaith(int $faith)
    {
        $this->faith = $faith;
        $this->profile->saveInfo("faith", $faith);
    }
    public function getProfession(): string
    {
        return $this->profession;
    }

    public function setProfession(string $profession)
    {
        $this->profession = $profession;
        $this->profile->saveInfo("profession", $profession);
    }

    public function getIsBot(): int
    {
        return $this->isBot;
    }

    public function setIsBot(int $isBot)
    {
        $this->isBot = $isBot;
        $this->profile->saveInfo("isBot", $isBot);
    }

    public function getLuckyCount(): int
    {
        return $this->luckyCount;
    }
    public function setLuckyCount(int $lucky)
    {
        $this->luckyCount = $lucky;
        $this->profile->saveInfo("luckyCount", $lucky);
    }

    public function getUnluckyCount(): int
    {
        return $this->unluckyCount;
    }
    public function setUnluckyCount(int $unlucky)
    {
        $this->unluckyCount = $unlucky;
        $this->profile->saveInfo("unluckyCount", $unlucky);
    }

    public function getLastSendEventTime(): string
    {
        return $this->lastSendEventTime;
    }

    public function setLastSendEventTime(string $lastSendEventTime)
    {
        $this->lastSendEventTime = $lastSendEventTime;
        $this->profile->saveInfo("lastSendEventTime", $lastSendEventTime);
    }

    public function getElapsedHoursEvent(string $lastSendEventTime): int
    {
        if ($lastSendEventTime !== "") {
            return floor((abs(intval(time()) - intval($lastSendEventTime))));
        }else{
            return 24;
        }
    }
}