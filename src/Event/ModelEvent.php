<?php

namespace CityChronicles\Event;

abstract class ModelEvent
{
    public function __construct(
        private string $id,
        private string $rankEvent,
        private int $countCharacter,
        private string $nextEventId,
        private string $text,
        private int $type,
        private string $time,
        private int $effRich,
    ) {}


    public function getId(): string
    {
        return $this->id;
    }
    public function setId(string $id)
    {
        $this->id = $id;
    }
    public function getType(): int
    {
        return $this->type;
    }
    public function setType(int $type)
    {
        $this->type = $type;
    }
    public function getRankEvent(): string
    {
        return $this->rankEvent;
    }
    public function setRankEvent(string $rankEvent)
    {
        $this->rankEvent = $rankEvent;
    }
    public function getCountCharacter(): int
    {
        return $this->countCharacter;
    }
    public function setCountCharacter(int $countCharacter)
    {
        $this->countCharacter = $countCharacter;
    }
    public function getNextEventId(): string
    {
        return $this->nextEventId;
    }
    public function setNextEventId(string $nextEventId)
    {
        $this->nextEventId = $nextEventId;
    }
    public function getTime(): string
    {
        return $this->time;
    }
    public function setTime(string $time)
    {
        $this->time = $time;
    }
    public function getText(): string
    {
        return $this->text;
    }
    public function setText(string $text)
    {
        $this->text = $text;
    }

    public function getEffRich(): int
    {
        return $this->effRich;
    }
    public function setEffRich(int $effRich)
    {
        $this->effRich = $effRich;
    }
    
}
