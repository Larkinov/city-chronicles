<?php

namespace CityChronicles\Event;

class DrinkTime extends ModelEvent
{

    public function __construct(
        string $id,
        int $type,
        string $rankEvent,
        int $countCharacter,
        string $nextEventId,
        string $text,
        string $time,
        private int $effMoney,
        private int $effRich,
        private string $place,
    ) {

        parent::__construct(
            $id,
            $rankEvent,
            $countCharacter,
            $nextEventId,
            $text,
            $type,
            $time,
            $effRich,
        );
    }

    public function getPlace(): string
    {
        return $this->place;
    }
    public function setPlace(string $place)
    {
        $this->place = $place;
    }
    
    public function getEffMoney(): string
    {
        return $this->effMoney;
    }
    public function setEffMoney(string $effMoney)
    {
        $this->effMoney = $effMoney;
    }
}
