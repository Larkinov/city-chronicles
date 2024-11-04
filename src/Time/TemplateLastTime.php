<?php

namespace CityChronicles\Time;

use CityChronicles\Settings\Settings;
use vkbot_conversation\classes\conversation\Conversation;
use vkbot_conversation\classes\profile\Profile;

class TemplateLastTime
{
    private int $lastTime;

    public function __construct(
        private Conversation|Profile $storageType,
        private string $nameTime
    ) {
        $lastTime = $this->storageType->getSavedInfo($this->nameTime);
        if (empty($lastTime)) {
            $lastTime = 0;
            $this->storageType->saveInfo($this->nameTime, strval(0));
        }
        if ($lastTime === "null")
            $lastTime = 0;
        $this->lastTime = strval($lastTime);
    }

    public function getLastTime(): int
    {
        return $this->lastTime;
    }

    public function updateLastTime()
    {
        $this->lastTime = time();
        $this->storageType->saveInfo($this->nameTime, $this->lastTime);
    }

    public function getWaitingHoursTime(): int
    {
        if ($this->lastTime === 0)
            return 24;
        else
            return floor((abs(time() - $this->lastTime)) / Settings::CITY_WAITING_MULTIPLIER);
    }
}
