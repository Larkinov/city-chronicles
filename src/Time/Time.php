<?php

namespace CityChronicles\Time;

use vkbot_conversation\classes\conversation\Conversation;

class Time
{
    private int $birthTime;
    private int $timeLive;

    public function __construct(private Conversation $conversation)
    {
        $birthTime = $this->conversation->getSavedInfo("birthTime");
        if (empty($birthTime)) {
            $birthTime = time();
            $this->conversation->saveInfo("birthTime", strval($birthTime));
            $this->conversation->saveInfo("timeLive", strval(0));
        }
        $this->birthTime = intval($birthTime);
        $this->timeLive = time() - $this->birthTime;
        $this->conversation->saveInfo("timeLive", $this->timeLive);
    }

    public function getBirthTime(): int
    {
        return $this->birthTime;
    }
    public function getTimeLive(): int
    {
        return $this->timeLive;
    }
}
