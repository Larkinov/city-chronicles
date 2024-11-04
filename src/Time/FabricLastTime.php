<?php

namespace CityChronicles\Time;

use CityChronicles\Time\TemplateLastTime;
use vkbot_conversation\classes\conversation\Conversation;
use vkbot_conversation\classes\profile\Profile;

class FabricLastTime
{

    public function __construct(
        private Conversation|Profile $storageType,
    ) {}

    public function getInstance(string $nameTime)
    {
        return new TemplateLastTime($this->storageType, $nameTime);
    }
}
