<?php

namespace CityChronicles\Event;

class NeutralEvent extends ModelEvent
{

    public function __construct(
        string $id,
        string $type,
        string $rankEvent,
        int $countCharacter,
        string $nextEventId,
        string $text,
        string $time,
        private string $effNameCharacter,
        private string $effLastNameCharacter,
        private string $effProfession,
        private string $effNameCity,
        private int $effRich,
        private string $effPlace,
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
            $effPlace
        );
    }

    public function getEffNameCharacter(): string
    {
        return $this->effNameCharacter;
    }
    public function setEffNameCharacter(string $effNameCharacter)
    {
        $this->effNameCharacter = $effNameCharacter;
    }
    public function getEffLastNameCharacter(): string
    {
        return $this->effLastNameCharacter;
    }
    public function setEffLastNameCharacter(string $effLastNameCharacter)
    {
        $this->effLastNameCharacter = $effLastNameCharacter;
    }
    public function getEffProfession(): string
    {
        return $this->effProfession;
    }
    public function setEffProfession(string $effProfession)
    {
        $this->effProfession = $effProfession;
    }
    public function getEffNameCity(): string
    {
        return $this->effNameCity;
    }
    public function setEffNameCity(string $effNameCity)
    {
        $this->effNameCity = $effNameCity;
    }

    public function getEffPlace(): string
    {
        return $this->effPlace;
    }
    public function setEffPlace(string $effPlace)
    {
        $this->effPlace = $effPlace;
    }
}
