<?php

namespace CityChronicles\Event;

class InfluentialEvent extends ModelEvent
{

    public function __construct(
        string $id,
        int $type,
        string $rankEvent,
        int $countCharacter,
        string $nextEventId,
        string $text,
        string $time,
        private int $effMana,
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

    public function getEffMana(): int
    {
        return $this->effMana;
    }
    public function setEffMana(int $effMana)
    {
        $this->effMana = $effMana;
    }

    public function getPlace(): string
    {
        return $this->place;
    }
    public function setPlace(string $place)
    {
        $this->place = $place;
    }
}
